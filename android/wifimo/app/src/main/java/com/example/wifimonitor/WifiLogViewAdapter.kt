package com.example.wifimonitor

import android.graphics.Color
import android.icu.text.SimpleDateFormat
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import java.util.Date
import java.util.Locale

class WifiLogViewAdapter :RecyclerView.Adapter<WifiLogViewAdapter.ViewHolder>() {

    private var wifiLogs: List<WifiLog> = emptyList()

    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val timestamp: TextView = itemView.findViewById(R.id.timestampTextView)
        val ssid: TextView = itemView.findViewById(R.id.ssidTextView)
        val bssid: TextView = itemView.findViewById(R.id.bssidTextView)
        val rssi: TextView = itemView.findViewById(R.id.rssiTextView)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_wifi_log, parent,false)
        return ViewHolder(view)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val wifiLog = wifiLogs[position]

        // Timestamp
        val date = Date(wifiLog.timestamp)
        val format = SimpleDateFormat("yyyy-MM-dd HH:mm:ss.SSS", Locale.getDefault())
        holder.timestamp.text = format.format(date)

        // SSID + 周波数
        if (wifiLog.ssid == "") {
            holder.ssid.text = ""
        } else {
            holder.ssid.text = "${wifiLog.ssid}\n${wifiLog.frequency}"
        }

        // BSSID + 状態
        if (wifiLog.bssid == "") {
            holder.bssid.text = "\n${wifiLog.state}"
        } else {
            holder.bssid.text = "${wifiLog.bssid}\n${wifiLog.state}"
        }
        when (wifiLog.state) {
            "DISCONNECTED" -> holder.bssid.setTextColor(Color.RED)
            "CONNECTED" -> holder.bssid.setTextColor(Color.parseColor("#34a352"))
            else -> {
                if (wifiLogs.size > position + 1) {
                    val preWifiLog = wifiLogs[position + 1]
                    if (preWifiLog.bssid != "" && wifiLog.bssid != preWifiLog.bssid) {
                        holder.bssid.setTextColor(Color.MAGENTA)
                    } else {
                        holder.bssid.setTextColor(Color.BLACK)
                    }
                } else {
                    holder.bssid.setTextColor(Color.BLACK)
                }
            }
        }

        // RSSI
        if (wifiLog.rssi == 0) {
            holder.rssi.text = ""
        } else {
            val rssiCategory = getRssiCategory(wifiLog.rssi, wifiLog.frequency)
            holder.rssi.text = "${wifiLog.rssi}\n${rssiCategory}"
            when (rssiCategory) {
                "強い" -> holder.rssi.setTextColor(Color.BLUE)
                "普通" -> holder.rssi.setTextColor(Color.BLACK)
                "弱い" -> holder.rssi.setTextColor(Color.parseColor("#FFA500"))
            }
        }
    }

    // 周波数に応じてRSSIから強い・普通・弱いを取得
    private fun getRssiCategory(rssi: Int, frequency: String): String {
        return when(frequency) {
            "2.4GHz" -> when {
                rssi >= -50 -> "強い"
                rssi >= -70 -> "普通"
                else -> "弱い"
            }
            "5GHz" -> when {
                rssi >= -50 -> "強い"
                rssi >= -65 -> "普通"
                else -> "弱い"
            }
            else -> when {
                rssi >= -50 -> "強い"
                rssi >= -70 -> "普通"
                else -> "弱い"
            }
        }
    }

    // 件数
    override fun getItemCount() = wifiLogs.size

    fun setLogs(newLogs: List<WifiLog>) {
        Log.d("WifiLogViewAdapter", "setLogs() - ${newLogs}")
        wifiLogs = newLogs
        notifyDataSetChanged()
    }
}