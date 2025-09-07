package com.example.mesh_th

import androidx.room.Entity
import androidx.room.PrimaryKey

@Entity(tableName = "mesh_th_data")
data class MeshThData(
    @PrimaryKey(autoGenerate = true) val id: Int = 0,
    val datetime: Long,
    val temperature: Double,
    val humidity: Int,
    val wbgt: Double
)
