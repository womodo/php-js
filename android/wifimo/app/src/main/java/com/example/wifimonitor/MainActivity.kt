package com.example.wifimonitor

import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.os.Build
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat

class MainActivity : AppCompatActivity() {

    private val REQUEST_PERMISSIONS = 100
    private var isServiceRunning = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        // 必要な権限の配列
        val permissions = arrayOf(
            android.Manifest.permission.ACCESS_FINE_LOCATION,
            android.Manifest.permission.POST_NOTIFICATIONS,
            android.Manifest.permission.BLUETOOTH_CONNECT,
        )
        // すべての権限が許可されているかチェック
        if (hasPermissions(permissions)) {
            // すべての権限が許可されている場合
            startWifiMonitorService(this)
        } else {
            // どれか一つでも権限が許可されていない場合
            ActivityCompat.requestPermissions(this, permissions, REQUEST_PERMISSIONS)
        }

        // モニタリング開始・停止ボタン
        val toggleButton: Button = findViewById(R.id.toggleButton)
        toggleButton.setOnClickListener {
            if (hasPermissions(permissions)) {
                if (isServiceRunning) {
                    stopWifiMonitorService(this)
                } else {
                    startWifiMonitorService(this)
                }
            } else {
                ActivityCompat.requestPermissions(this, permissions, REQUEST_PERMISSIONS)
            }
        }

        // ログ表示ボタン
        val openLogButton: Button = findViewById(R.id.openLogButton)
        openLogButton.setOnClickListener {
            val intent = Intent(this, WifiLogViewActivity::class.java)
            startActivity(intent)
        }
    }

    // すべての権限が許可されているかチェックする
    private fun hasPermissions(permissions: Array<out String>): Boolean {
        for (permission in permissions) {
            if (ContextCompat.checkSelfPermission(this, permission) != PackageManager.PERMISSION_GRANTED) {
                return false
            }
        }
        return true
    }

    // 権限許可/拒否を選択した後のコールバック
    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<out String>,
        grantResults: IntArray,
        deviceId: Int
    ) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults, deviceId)
        if (requestCode == REQUEST_PERMISSIONS) {
            if (hasPermissions(permissions)) {
                startWifiMonitorService(this)
            }
        }
    }

    // サービス開始
    private fun startWifiMonitorService(context: Context) {
        val serviceIntent = Intent(context, NetworkMonitorService::class.java)
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            context.startForegroundService(serviceIntent)
        } else {
            context.startService(serviceIntent)
        }

        isServiceRunning = true
        findViewById<TextView>(R.id.serviceStateText).text = "Wi-Fi モニタリング中"
        findViewById<Button>(R.id.toggleButton).text = "モニタリング停止する"
    }

    // サービス停止
    fun stopWifiMonitorService(context: Context) {
        val serviceIntent = Intent(context, NetworkMonitorService::class.java)
        context.stopService(serviceIntent)

        isServiceRunning = false
        findViewById<TextView>(R.id.serviceStateText).text = "Wi-Fi モニタリング停止"
        findViewById<Button>(R.id.toggleButton).text = "モニタリング開始する"
    }

    override fun onDestroy() {
        super.onDestroy()
        if (isServiceRunning) {
            stopWifiMonitorService(this)
        }
    }
}