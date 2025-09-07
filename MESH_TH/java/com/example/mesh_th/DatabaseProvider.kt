package com.example.mesh_th

import android.content.Context
import androidx.room.Room

object DatabaseProvider {
    private var db: AppDatabase? = null

    fun getDatabase(context: Context): AppDatabase {
//        if (db == null) {
//            db = Room.databaseBuilder(
//                context.applicationContext,
//                AppDatabase::class.java,
//                "mesh_th_db"
//            ).build()
//        }
//        return db!!

        return db ?: synchronized(this) {
            val instance = Room.databaseBuilder(
                context.applicationContext,
                AppDatabase::class.java,
                "mesh_th_db"
            ).build()
            db = instance
            instance
        }
    }
}