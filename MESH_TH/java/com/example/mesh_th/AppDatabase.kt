package com.example.mesh_th

import android.content.Context
import androidx.room.Database
import androidx.room.Room
import androidx.room.RoomDatabase

@Database(
    entities = [MeshThData::class],
    version = 1,
    exportSchema = false // 開発中は false
)
abstract class AppDatabase : RoomDatabase() {

    abstract fun meshThDataDao(): MeshThDataDao

    companion object {
        @Volatile private var instance: AppDatabase? = null

        fun getInstance(context: Context): AppDatabase =
            instance ?: synchronized(this) {
                instance ?: Room.databaseBuilder(
                    context.applicationContext,
                    AppDatabase::class.java,
                    "app_db"
                ).build().also { instance = it }
            }
    }
}