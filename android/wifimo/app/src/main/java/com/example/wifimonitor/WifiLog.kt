package com.example.wifimonitor

import androidx.room.Entity
import androidx.room.PrimaryKey

@Entity(tableName = "wifi_logs")
data class WifiLog(
    @PrimaryKey(autoGenerate = true) val id: Int = 0,
    val timestamp: Long,
    val ssid: String,
    val bssid: String,
    val rssi: Int,
    val frequency: String,
    val state: String
)
