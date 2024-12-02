package com.example.test2application

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.Button
import androidx.activity.enableEdgeToEdge
import androidx.activity.viewModels
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import androidx.lifecycle.SavedStateHandle
import androidx.lifecycle.ViewModel

class MainActivity : AppCompatActivity() {

    private val appStateViewModel: AppStateViewModel by viewModels()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        val lastActivity = appStateViewModel.getLastActivity()
        Log.i("MainActivity", lastActivity)

        // 状態を確認してSub画面を再表示するか判断
        when (lastActivity) {
            "SubActivity" -> {
                val intent = Intent(this, SubActivity::class.java)
                startActivity(intent)
                finish()
            }
            else -> {
                enableEdgeToEdge()
                setContentView(R.layout.activity_main)
                ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
                    val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
                    v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
                    insets
                }

                // Sub画面への遷移ボタン
                findViewById<Button>(R.id.btnGoToSubActivity).setOnClickListener {
                    val intent = Intent(this, SubActivity::class.java)
                    startActivity(intent)
                    finish()
                }
            }
        }
    }
}