package com.example.testapplication

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity

class LauncherActivity :AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // 保存された情報を取得
        val sharedPreferences = getSharedPreferences("AppState", MODE_PRIVATE)
        val lastScreen = sharedPreferences.getString("lastScreen", "")

        // 表示する画面を選択
        val intent = when (lastScreen) {
            "SubActivity" ->Intent(this, SubActivity::class.java)
            else -> Intent(this, MainActivity::class.java)
        }

        // 状態をリセットする
        sharedPreferences.edit().remove("lastScreen").apply()

        // 遷移先画面を起動
        startActivity(intent)
        finish() // LauncherActivity は不要なので終了
    }
}