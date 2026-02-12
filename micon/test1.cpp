/************************************************************
  ESP32 工場運用 完全安定版
  Arduino-ESP32 v3.3.7 対応

  ■ 機能
  ・固定IP
  ・WiFiフリーズ自動復帰
  ・HTTP固まり防止
  ・WDT安全化（v3 API）
  ・NTP 3サーバ冗長
  ・DNS 2系統
  ・送信失敗→NVS保存→再送
  ・電源断でもデータ保持
  ・Web監視ページ
  ・Web設定ページ
************************************************************/

#include <WiFi.h>          // WiFi制御
#include <HTTPClient.h>    // HTTP通信
#include <WebServer.h>     // Webサーバ
#include <Preferences.h>   // 不揮発メモリ(NVS)
#include <time.h>          // NTP
#include "esp_task_wdt.h"  // WDT

/************ WiFi設定（初期値） ************/
String ssid = "SSID";       // WiFi SSID
String pass = "PASS";   // WiFi パスワード

/************ 固定IP設定 ************/
IPAddress local_IP(192,168,1,55);
IPAddress gateway(192,168,1,1);
IPAddress subnet(255,255,255,0);
IPAddress dns1(8,8,8,8);
IPAddress dns2(8,8,4,4);

/************ HTTP送信先 ************/
String postURL = "http://192.168.1.100/api/data";

/************ NTP（3重冗長） ************/
const char* ntp1 = "ntp.nict.jp";
const char* ntp2 = "ntp.jst.mfeed.ad.jp";
const char* ntp3 = "pool.ntp.org";

/************ 動作設定 ************/
#define WDT_TIMEOUT   30        // WDTタイムアウト秒
#define POST_INTERVAL 5000      // POST周期(ms)
#define WIFI_TIMEOUT  15000     // WiFi接続タイムアウト(ms)
#define MAX_HTTP_FAIL 5         // HTTP連続失敗→WiFi再接続
#define QUEUE_MAX     50        // 保存キュー最大数

/************ グローバル ************/
WebServer server(80);       // Webサーバ
Preferences prefs;          // NVS

unsigned long lastPost = 0; // POSTタイマ
int httpFail = 0;           // HTTP失敗カウンタ
bool wifiConnected = false; // WiFi状態

/************************************************************
  WDT初期化（Arduino-ESP32 v3.x用安全版）
************************************************************/
void initWDT() {

  // 既に登録済みなら何もしない
  if (esp_task_wdt_status(NULL) == ESP_OK) {
    return;
  }

  // 現在のタスクを監視対象へ追加
  esp_task_wdt_add(NULL);
}

/************************************************************
  ログ保存（電源断でも保持）
************************************************************/
void addLog(String msg) {

  prefs.begin("log", false);

  int idx = prefs.getInt("idx", 0);
  prefs.putString(String(idx).c_str(), msg);

  idx = (idx + 1) % 200;   // リングバッファ
  prefs.putInt("idx", idx);

  prefs.end();
}

/************************************************************
  WiFi接続（フリーズ対策込み）
************************************************************/
void connectWiFi() {

  WiFi.disconnect(true);      // 完全リセット
  WiFi.mode(WIFI_STA);
  WiFi.setSleep(false);       // 省電力OFF → 安定化
  WiFi.setAutoReconnect(true);
  WiFi.config(local_IP, gateway, subnet, dns1, dns2);

  WiFi.begin(ssid.c_str(), pass.c_str());

  unsigned long start = millis();

  // 接続待機（タイムアウト付き）
  while (WiFi.status() != WL_CONNECTED) {
    delay(300);

    if (millis() - start > WIFI_TIMEOUT) {
      addLog("WiFi Timeout → Restart");
      ESP.restart();
    }
  }

  wifiConnected = true;
  addLog("WiFi Connected");
}

/************************************************************
  NTP同期（3サーバ冗長）
************************************************************/
void syncTime() {

  configTime(9 * 3600, 0, ntp1, ntp2, ntp3);

  struct tm t;

  for (int i=0; i<10; i++) {
    if (getLocalTime(&t)) {
      addLog("NTP OK");
      return;
    }
    delay(500);
  }

  addLog("NTP FAIL");
}

/************************************************************
  HTTP POST（固まり防止）
************************************************************/
bool sendPOST(String data) {

  HTTPClient http;
  http.setTimeout(3000);  // フリーズ防止

  http.begin(postURL);
  http.addHeader("Content-Type", "application/json");

  int code = http.POST(data);
  http.end();

  if (code > 0) {
    httpFail = 0;
    return true;
  } else {
    httpFail++;
    return false;
  }
}

/************************************************************
  送信失敗データ保存（電源断保持）
************************************************************/
void saveQueue(String data) {

  prefs.begin("queue", false);

  int n = prefs.getInt("n", 0);

  if (n < QUEUE_MAX) {
    prefs.putString(String(n).c_str(), data);
    prefs.putInt("n", n + 1);
  }

  prefs.end();
}

/************************************************************
  保存データ再送
************************************************************/
void resendQueue() {

  prefs.begin("queue", false);

  int n = prefs.getInt("n", 0);

  for (int i=0; i<n; i++) {
    String d = prefs.getString(String(i).c_str(), "");
    if (d != "") sendPOST(d);
  }

  prefs.putInt("n", 0);
  prefs.end();

  addLog("Queue Resent");
}

/************************************************************
  Web監視ページ
************************************************************/
void handleRoot() {

  String html = "<h1>ESP32 Monitor</h1>";
  html += "IP: " + WiFi.localIP().toString() + "<br>";
  html += "Heap: " + String(ESP.getFreeHeap()) + "<br>";
  html += "WiFi: " + String(WiFi.status()==WL_CONNECTED?"OK":"NG") + "<br>";

  server.send(200, "text/html", html);
}

/************************************************************
  Web設定ページ（現在値表示）
************************************************************/
void handleConfig() {

  // 保存処理
  if (server.method() == HTTP_POST) {

    ssid = server.arg("ssid");
    pass = server.arg("pass");

    prefs.begin("cfg", false);
    prefs.putString("ssid", ssid);
    prefs.putString("pass", pass);
    prefs.end();

    server.send(200, "text/plain", "Saved → Reboot");
    delay(1000);
    ESP.restart();
    return;
  }

  // 表示ページ
  String html = "<h1>Config</h1><form method=POST>";
  html += "SSID:<input name=ssid value='"+ssid+"'><br>";
  html += "PASS:<input name=pass value='"+pass+"'><br>";
  html += "<input type=submit></form>";

  server.send(200, "text/html", html);
}

/************************************************************
  Setup
************************************************************/
void setup() {

  Serial.begin(115200);
  delay(1000);

  initWDT();                 // WDT初期化

  // 保存設定読み込み
  prefs.begin("cfg", true);
  ssid = prefs.getString("ssid", ssid);
  pass = prefs.getString("pass", pass);
  prefs.end();

  connectWiFi();             // WiFi接続
  syncTime();                // NTP同期

  server.on("/", handleRoot);
  server.on("/config", handleConfig);
  server.begin();

  addLog("System Start");
}

/************************************************************
  Loop
************************************************************/
void loop() {

  esp_task_wdt_reset();   // WDTリセット

  server.handleClient();  // Web処理

  // WiFi切断時 → 自動復帰 + キュー再送
  if (WiFi.status() != WL_CONNECTED) {
    wifiConnected = false;
    connectWiFi();
    resendQueue();
  }

  // 定期POST
  if (millis() - lastPost > POST_INTERVAL) {

    String data = "{\"value\":123}";

    if (!sendPOST(data)) {
      saveQueue(data);

      if (httpFail >= MAX_HTTP_FAIL) {
        addLog("HTTP Fail → WiFi Reset");
        WiFi.disconnect(true);
      }
    }

    lastPost = millis();
  }
}