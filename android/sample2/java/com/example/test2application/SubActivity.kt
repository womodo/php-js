package com.example.test2application

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.Button
import android.widget.TextView
import androidx.activity.enableEdgeToEdge
import androidx.activity.viewModels
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import org.w3c.dom.Text
import java.text.SimpleDateFormat
import java.util.Date
import java.util.Locale

class SubActivity : AppCompatActivity() {

    private val appStateViewModel: AppStateViewModel by viewModels()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_sub)
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // 最終画面を設定
        appStateViewModel.setLastActivity("SubActivity")

        val textView1: TextView = findViewById(R.id.textView1)
        textView1.text = appStateViewModel.getDisplayText()

        // 日付を表示
        findViewById<Button>(R.id.button).setOnClickListener {
            val currentDate = SimpleDateFormat(
                "yyyy/MM/dd HH:mm:ss", Locale.getDefault()).format(Date())
            textView1.text = currentDate
            appStateViewModel.setDisplayText(currentDate)
        }

        // Main画面へボタン
        findViewById<Button>(R.id.btnGoToMainActivity).setOnClickListener {
            appStateViewModel.setLastActivity("xxx")
            val intent = Intent(this, MainActivity::class.java)
            startActivity(intent)
            finish()
        }
    }
}