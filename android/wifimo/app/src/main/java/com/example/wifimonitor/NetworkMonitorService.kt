package com.example.wifimonitor

import android.app.Notification
import android.app.NotificationChannel
import android.app.NotificationManager
import android.app.PendingIntent
import android.app.Service
import android.content.Context
import android.content.Intent
import android.net.ConnectivityManager
import android.net.Network
import android.net.NetworkCapabilities
import android.net.wifi.WifiInfo
import android.os.Build
import android.os.IBinder
import androidx.annotation.RequiresApi
import androidx.core.app.NotificationCompat
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

class NetworkMonitorService : Service() {

    private lateinit var connectivityManager: ConnectivityManager
    private lateinit var networkCallback: ConnectivityManager.NetworkCallback

    @RequiresApi(Build.VERSION_CODES.S)
    override fun onCreate() {
        super.onCreate()

        // Foreground Notification
        startForeground(NOTIFICATION_ID, createNotification())

        // ConnectivityManager Setup
        connectivityManager = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        networkCallback = object : ConnectivityManager.NetworkCallback(FLAG_INCLUDE_LOCATION_INFO) {
            // 状態変化時
            override fun onCapabilitiesChanged(
                network: Network,
                networkCapabilities: NetworkCapabilities
            ) {
                super.onCapabilitiesChanged(network, networkCapabilities)

                // WiFiかつインターネットに接続中
                if (networkCapabilities.hasTransport(NetworkCapabilities.TRANSPORT_WIFI)) {

                    // WiFi接続情報
                    val wifiInfo = networkCapabilities.transportInfo as WifiInfo
                    val ssid = wifiInfo.ssid.replace("\"", "")
                    val bssid = wifiInfo.bssid
                    val rssi = wifiInfo.rssi
                    val frequency = when (wifiInfo.frequency) {
                        in 2400 .. 2500 -> "2.4GHz"
                        in 4900 .. 5900 -> "5GHz"
                        else -> "Unknown"
                    }
                    val state = if (networkCapabilities.hasCapability(NetworkCapabilities.NET_CAPABILITY_INTERNET)) {
                        wifiInfo.supplicantState.toString()
                    } else {
                        "NO_INTERNET"
                    }

                    // データベースにWiFi接続情報を保存
                    val log = WifiLog(
                        timestamp = System.currentTimeMillis(),
                        ssid = ssid,
                        bssid = bssid,
                        rssi = rssi,
                        frequency = frequency,
                        state = state
                    )

                    val database = AppDatabase.getDatabase(applicationContext)
                    CoroutineScope(Dispatchers.IO).launch {
                        database.wifiLogDao().insertLog(log)
                    }
                }
            }

            // 接続時
            override fun onAvailable(network: Network) {
                super.onAvailable(network)

                val networkCapabilities = connectivityManager.getNetworkCapabilities(network)
                if (networkCapabilities != null) {
                    if (networkCapabilities.hasTransport(NetworkCapabilities.TRANSPORT_WIFI)) {
                        // データベースに接続したことを保存
                        val log = WifiLog(
                            timestamp = System.currentTimeMillis(),
                            ssid = "-",
                            bssid = "",
                            rssi = 0,
                            frequency = "",
                            state = "CONNECTED"
                        )

                        val database = AppDatabase.getDatabase(applicationContext)
                        CoroutineScope(Dispatchers.IO).launch {
                            database.wifiLogDao().insertLog(log)
                        }
                    }
                }
            }

            // 切断時
            override fun onLost(network: Network) {
                super.onLost(network)

                val networkCapabilities = connectivityManager.getNetworkCapabilities(network)
                if (networkCapabilities != null) {
                    if (networkCapabilities.hasTransport(NetworkCapabilities.TRANSPORT_WIFI)) {
                        // データベースに切断したことを保存
                        val log = WifiLog(
                            timestamp = System.currentTimeMillis(),
                            ssid = "-",
                            bssid = "",
                            rssi = 0,
                            frequency = "",
                            state = "DISCONNECTED"
                        )

                        val database = AppDatabase.getDatabase(applicationContext)
                        CoroutineScope(Dispatchers.IO).launch {
                            database.wifiLogDao().insertLog(log)
                        }
                    }
                }
            }

            //
            override fun onUnavailable() {
                super.onUnavailable()

                val log = WifiLog(
                    timestamp = System.currentTimeMillis(),
                    ssid = "-",
                    bssid = "",
                    rssi = 0,
                    frequency = "",
                    state = "UNAVAILABLE"
                )

                val database = AppDatabase.getDatabase(applicationContext)
                CoroutineScope(Dispatchers.IO).launch {
                    database.wifiLogDao().insertLog(log)
                }
            }

            // ブロック時
            override fun onBlockedStatusChanged(network: Network, blocked: Boolean) {
                super.onBlockedStatusChanged(network, blocked)

                if (blocked) {
                    val log = WifiLog(
                        timestamp = System.currentTimeMillis(),
                        ssid = "-",
                        bssid = "",
                        rssi = 0,
                        frequency = "",
                        state = "BLOCKED"
                    )

                    val database = AppDatabase.getDatabase(applicationContext)
                    CoroutineScope(Dispatchers.IO).launch {
                        database.wifiLogDao().insertLog(log)
                    }
                }
            }
        }

        connectivityManager.registerDefaultNetworkCallback(networkCallback)
    }

    override fun onDestroy() {
        super.onDestroy()
        connectivityManager.unregisterNetworkCallback(networkCallback)
    }

    override fun onBind(p0: Intent?): IBinder? {
        return null
    }

    private fun createNotification(): Notification {
        // 通知チャンネルの作成
        val channel = NotificationChannel(
            CHANNEL_ID,
            CHANNEL_NAME,
            NotificationManager.IMPORTANCE_LOW  // 重要度：低（通知音なし）
        ).apply {
            description = "Notification for monitoring network changes."
        }

        // NotificationManagerを取得してチャンネルを登録
        val notificationManager = getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager
        notificationManager.createNotificationChannel(channel)

        // MainActivity起動用
        val intent = Intent(applicationContext, MainActivity::class.java).apply {
            flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
        }
        val pendingIntent: PendingIntent = PendingIntent.getActivity(
            applicationContext, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT or PendingIntent.FLAG_IMMUTABLE
        )

        // 通知の生成
        return NotificationCompat.Builder(this, CHANNEL_ID)
            .setContentTitle("Wi-Fi モニタリング")
            .setContentText("バックグラウンドでネットワーク接続を監視中")
            .setSmallIcon(R.drawable.ic_wifi)
            .setPriority(NotificationCompat.PRIORITY_LOW)
            .setContentIntent(pendingIntent)    // 通知タップ時のIntent
            .build()
    }

    companion object {
        private const val CHANNEL_ID = "network_monitor_channel"
        private const val CHANNEL_NAME = "Network Monitor"
        private const val NOTIFICATION_ID = 1
    }
}