package com.example.wifimonitor

import androidx.lifecycle.LiveData
import androidx.room.Dao
import androidx.room.Insert
import androidx.room.Query
import androidx.room.Transaction

@Dao
interface WifiLogDao {
    @Insert
    suspend fun insertLog(log: WifiLog)

    @Query("""
        SELECT * FROM wifi_logs
        WHERE (:startDate IS NULL OR timestamp >= :startDate)
          AND (:endDate IS NULL OR timestamp <= :endDate)
          AND (:ssid IS NULL OR ssid = :ssid)
          AND (:state IS NULL OR state = :state)
        ORDER BY timestamp DESC
    """)
    fun searchLogs(
        startDate: Long?,
        endDate: Long?,
        ssid: String?,
        state: String?
    ): LiveData<List<WifiLog>>

    @Query("DELETE FROM wifi_logs")
    fun deleteAll()

    @Query("DELETE FROM sqlite_sequence WHERE name = :tableName")
    fun resetAutoIncrement(tableName: String)

    @Transaction
    suspend fun clearTable() {
        deleteAll()
        resetAutoIncrement("wifi_logs")
    }

    @Query("SELECT DISTINCT ifnull(ssid, '-') FROM wifi_logs ORDER BY ssid ASC")
    fun getDistinctSsids(): LiveData<List<String>>

    @Query("SELECT DISTINCT ifnull(state, '-') FROM wifi_logs ORDER BY ssid ASC")
    fun getDistinctStates(): LiveData<List<String>>
}