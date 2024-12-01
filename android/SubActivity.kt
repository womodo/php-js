package com.example.testapplication

import android.annotation.SuppressLint
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.Button
import android.widget.TextView
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import org.w3c.dom.Text
import java.util.Date

class SubActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        Log.i("SubActivity", "onCreate()")

        enableEdgeToEdge()
        setContentView(R.layout.activity_sub)
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // UI 要素
        val messageTextView: TextView = findViewById(R.id.messageTextView)
        val messageTextView2: TextView = findViewById(R.id.messageTextView2)

        val sharedPreferences = getSharedPreferences("AppState", MODE_PRIVATE)

        // データの復元
        var value = sharedPreferences.getString("messageText", "No message")
        messageTextView.text = value

        // 状態を保存
        sharedPreferences.edit().putString("lastScreen", "SubActivity").apply()

        //　データの復元
        value = savedInstanceState?.getString("MESSAGE_TEXT") ?: "No message 2"
        messageTextView2.text = value

        // メッセージを更新
        val updateMessageButton: Button = findViewById(R.id.updateMessageButton)
        updateMessageButton.setOnClickListener {
            val date = Date()
            messageTextView.text = "update - $date"
            messageTextView2.text = "update - $date"
            sharedPreferences.edit().putString("messageText", messageTextView.text.toString()).apply()
        }

        // メイン画面へボタン
        val buttonToMainActivity = findViewById<Button>(R.id.button_to_main)
        buttonToMainActivity.setOnClickListener {
            sharedPreferences.edit().remove("lastScreen").apply()
            val intent = Intent(this, MainActivity::class.java)
            startActivity(intent)
        }
    }

    // データの保存
    override fun onSaveInstanceState(outState: Bundle) {
        super.onSaveInstanceState(outState)
        val messageTextView2: TextView = findViewById(R.id.messageTextView2)

        outState.putString("MESSAGE_TEXT", messageTextView2.text.toString())
        Log.i("SubActivity", "onSaveInstanceState() - ${messageTextView2.text}")
    }

    override fun onRestoreInstanceState(savedInstanceState: Bundle) {
        super.onRestoreInstanceState(savedInstanceState)
        val value = savedInstanceState.getString("MESSAGE_TEXT")
        Log.i("SubActivity", "onRestoreInstanceState() - $value")

        val messageTextView2: TextView = findViewById(R.id.messageTextView2)
        messageTextView2.text = value
    }

    override fun onStart() {
        super.onStart()
        Log.i("SubActivity", "onStart()")
    }

    override fun onResume() {
        super.onResume()
        Log.i("SubActivity", "onResume()")
    }

    override fun onPause() {
        super.onPause()
        Log.i("SubActivity", "onPause()")
    }

    override fun onStop() {
        super.onStop()
        Log.i("SubActivity", "onStop()")
    }

    override fun onRestart() {
        super.onRestart()
        Log.i("SubActivity", "onRestart()")
    }

    override fun onDestroy() {
        super.onDestroy()
        Log.i("SubActivity", "onDestroy()")
    }
}