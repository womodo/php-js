package com.example.wifimonitor

import android.app.Application
import android.util.Log
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData

class WifiLogViewModel(application: Application) : AndroidViewModel(application) {

    private val database = AppDatabase.getDatabase(application)
    private val wifiLogDao: WifiLogDao = database.wifiLogDao()

    // 検索条件
    private val searchParams = MutableLiveData<SearchParams>()

    // 検索結果
    fun searchResults(): LiveData<List<WifiLog>> {
        val startDate = searchParams.value?.startDate
        val endDate = searchParams.value?.endDate?.plus((24 * 60 * 60 * 1000) - 1)
        val ssid = searchParams.value?.ssid
        val state = searchParams.value?.state
        Log.d("WifiLogViewModel", "searchResults() - $ssid")
        return wifiLogDao.searchLogs(startDate, endDate, ssid, state)
    }

    // 検索条件を更新
    fun updateSearchParams(startDate: Long?, endDate: Long?, ssid: String?, state: String?) {
        Log.d("WifiLogViewModel", "updateSearchParams() - $ssid")
        searchParams.value = SearchParams(startDate, endDate, ssid, state)
    }

    // SSIDリスト
    fun getDistinctSsids() : LiveData<List<String>> {
        return wifiLogDao.getDistinctSsids()
    }

    // 状態リスト
    fun getDistinctStates() : LiveData<List<String>> {
        return wifiLogDao.getDistinctStates()
    }
}

// 検索条件
data class SearchParams(
    val startDate: Long?,
    val endDate: Long?,
    val ssid: String?,
    val state: String?
)