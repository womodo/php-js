package com.example.mesh_th

import androidx.lifecycle.LiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.asLiveData
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.combine
import kotlinx.coroutines.flow.flatMapLatest

class MeshThDataViewModel(private val dao: MeshThDataDao) : ViewModel() {

    private val _fromDate = MutableStateFlow(System.currentTimeMillis())
    private val _toDate = MutableStateFlow(System.currentTimeMillis())

    // 日付範囲を Flow で監視して DB クエリ
    val filteredData: LiveData<List<MeshThData>> = combine(_fromDate, _toDate) { from, to ->
        from to to
    }.flatMapLatest { (from, to) ->
        dao.getByDateRange(from, to)
    }.asLiveData()

    fun setDateRange(from: Long, to: Long) {
        _fromDate.value = from
        _toDate.value = to
    }
}