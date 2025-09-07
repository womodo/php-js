package com.example.mesh_th

import androidx.room.Dao
import androidx.room.Insert
import androidx.room.Query
import kotlinx.coroutines.flow.Flow

@Dao
interface MeshThDataDao {
    @Insert
    suspend fun insert(data: MeshThData)

    @Query("SELECT * FROM mesh_th_data ORDER BY datetime DESC")
    fun getAll(): Flow<List<MeshThData>>

    @Query("""
        SELECT * FROM mesh_th_data
        WHERE datetime BETWEEN :from AND :to
        ORDER BY datetime DESC
    """)
    fun getByDateRange(from: Long, to: Long): Flow<List<MeshThData>>

    @Query("DELETE FROM mesh_th_data")
    suspend fun deleteAll()
}