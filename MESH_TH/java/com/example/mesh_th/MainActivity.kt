package com.example.mesh_th


import android.Manifest
import android.app.AlertDialog
import android.app.DatePickerDialog
import android.app.NotificationChannel
import android.app.NotificationManager
import android.content.*
import android.content.pm.PackageManager
import android.icu.util.Calendar
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.os.Environment
import android.util.Log
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import android.widget.Toast
import androidx.core.app.ActivityCompat
import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.lifecycleScope
import androidx.localbroadcastmanager.content.LocalBroadcastManager
import androidx.recyclerview.widget.LinearLayoutManager
import com.example.mesh_th.databinding.ActivityMainBinding
import kotlinx.coroutines.launch
import java.io.File
import java.text.SimpleDateFormat
import java.util.*

class MainActivity : AppCompatActivity() {

    private val TAG = "MESH_LOG"
    private val CHANNEL_ID = "mesh_th_service"
    private val PREFS_NAME = "mesh_prefs"

    private lateinit var editBlock: EditText
    private lateinit var editSerialNo: EditText
    private lateinit var editInterval: EditText
    private lateinit var textStatus: TextView
    private lateinit var textBattery: TextView
    private lateinit var btnStart: Button
    private lateinit var btnStop: Button

    private lateinit var binding: ActivityMainBinding
    private lateinit var adapter: MeshThDataAdapter
    private lateinit var viewModel: MeshThDataViewModel

    private var fromDate: Long = System.currentTimeMillis()
    private var toDate: Long = System.currentTimeMillis()


    // ステータスの表示変更
    private val statusReceiver = object : BroadcastReceiver() {
        override fun onReceive(context: Context?, intent: Intent?) {
            val status = intent?.getStringExtra("status")
            textStatus.text = "状態: $status"

            // SharedPreferences に保管
            val prefs = getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
            if (status == "接続") {
                prefs.edit().putBoolean("isRunning", true).apply()
            } else {
                prefs.edit().putBoolean("isRunning", false).apply()
            }
        }
    }

    // バッテリーの表示変更
    private val batteryReceiver = object : BroadcastReceiver() {
        override fun onReceive(context: Context?, intent: Intent?) {
            val battery = intent?.getStringExtra("battery")
            textBattery.text = "バッテリー: $battery%"
        }
    }


    // Activity 生成時
    override fun onCreate(savedInstanceState: Bundle?) {
        Log.d(TAG, "MainActivity#onCreate()")
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // 権限チェック
        checkPermissions()

        // 通知チャネルを作成
        createNotificationChannel()


        // 画面アイテム
        editBlock = findViewById(R.id.editBlock)
        editSerialNo = findViewById(R.id.editSerialNo)
        editInterval = findViewById(R.id.editInterval)
        textStatus = findViewById(R.id.textStatus)
        textBattery = findViewById(R.id.textBattery)
        btnStart = findViewById(R.id.btnStart)
        btnStop = findViewById(R.id.btnStop)


        // SharedPreferences から復元
        val prefs = getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
        val block = prefs.getString("block", editBlock.text.toString())
        val serialNo = prefs.getString("serialNo", editSerialNo.text.toString())
        val interval = prefs.getString("interval", editInterval.text.toString())
        val isRunning = prefs.getBoolean("isRunning", false)

        Log.d(TAG, "block=$block, serialNo=$serialNo, interval=$interval")
        Log.d(TAG, "isRunnning=$isRunning")

        editBlock.setText(block)
        editSerialNo.setText(serialNo)
        editInterval.setText(interval)

        // 状態の変化
        LocalBroadcastManager.getInstance(this)
            .registerReceiver(statusReceiver, IntentFilter("MESH_STATUS"))
        // バッテリーの変化
        LocalBroadcastManager.getInstance(this)
            .registerReceiver(batteryReceiver, IntentFilter("MESH_BATTERY"))

        if (isRunning) {
            btnStart.isEnabled = false
            btnStop.isEnabled = true
            // フォアグラウンドサービス開始
            startServiceWithParams()
        } else {
            btnStart.isEnabled = true
            btnStop.isEnabled = false
        }

        // スタートボタン
        binding.btnStart.setOnClickListener {
            btnStart.isEnabled = false
            btnStop.isEnabled = true
            // フォアグラウンドサービス開始
            startServiceWithParams()
        }

        // ストップボタン
        binding.btnStop.setOnClickListener {
            val intent = Intent(this, MeshBleService::class.java)
            // フォアグラウンドサービス停止
            stopService(intent)
            btnStart.isEnabled = true
            btnStop.isEnabled = false
        }

        // クリアボタン
        binding.btnDelete.setOnClickListener {
            val dialogView = layoutInflater.inflate(R.layout.dialog_buttons, null)

            val dialog = AlertDialog.Builder(this)
                .setTitle("クリア")
                .setMessage("保存されているデータを削除します。")
                .setView(dialogView)
                .create()

            // DBテーブル削除
            dialogView.findViewById<Button>(R.id.btnDelDb).setOnClickListener {
                lifecycleScope.launch {
                    val db = AppDatabase.getInstance(applicationContext)
                    db.meshThDataDao().deleteAll()
                }
                Toast.makeText(this, "DBテーブルからデータを削除しました", Toast.LENGTH_SHORT).show()
                dialog.dismiss()
            }

            // ファイル削除
            dialogView.findViewById<Button>(R.id.btnDelFile).setOnClickListener {
                // ユーザーの Documents フォルダ
                val documentsDir = Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOCUMENTS)

                // data フォルダ、ファイルが存在する場合はファイルを削除
                val dataDir = File(documentsDir, "data")
                if (dataDir.exists()) {
                    val file = File(dataDir, "temphum.txt")
                    if (file.exists()) {
                        file.delete()
                    }
                }
                Toast.makeText(this, "ファイルを削除しました", Toast.LENGTH_SHORT).show()
                dialog.dismiss()
            }

            // キャンセル
            dialogView.findViewById<Button>(R.id.btnDelCancel).setOnClickListener {
                dialog.dismiss()
            }

            dialog.show()
        }

        // 検索日付
        binding.editFromDate.setText(formatDate(fromDate))
        binding.editToDate.setText(formatDate(toDate))
        binding.editFromDate.setOnClickListener {
            showDatePicker { dateMillis ->
                fromDate = dateMillis ?: System.currentTimeMillis()
                binding.editFromDate.setText(formatDate(fromDate))
                filterAndObserveData()
            }
        }
        binding.editToDate.setOnClickListener {
            showDatePicker { dateMillis ->
                toDate = dateMillis ?: System.currentTimeMillis()
                binding.editToDate.setText(formatDate(toDate))
                filterAndObserveData()
            }
        }

        // DB
        val dao = AppDatabase.getInstance(this).meshThDataDao()
        viewModel = ViewModelProvider(this, object : ViewModelProvider.Factory {
            override fun <T : ViewModel> create(modelClass: Class<T>): T {
                return MeshThDataViewModel(dao) as T
            }
        })[MeshThDataViewModel::class.java]

        adapter = MeshThDataAdapter()
        binding.recyclerView.adapter = adapter
        binding.recyclerView.layoutManager = LinearLayoutManager(this)
        binding.recyclerView.setHasFixedSize(true)
        filterAndObserveData()
    }

    // 権限チェック
    private fun checkPermissions() {
        val permissions = arrayOf(
            Manifest.permission.BLUETOOTH_SCAN,
            Manifest.permission.BLUETOOTH_CONNECT,
            Manifest.permission.ACCESS_FINE_LOCATION,
            Manifest.permission.POST_NOTIFICATIONS
        )
        if (permissions.any {
                ActivityCompat.checkSelfPermission(this, it) != PackageManager.PERMISSION_GRANTED
            }) {
            ActivityCompat.requestPermissions(this, permissions, 1000)
        }
    }

    // 通知チャネルを作成
    private fun createNotificationChannel() {
        val channel = NotificationChannel(
            CHANNEL_ID,
            "MESH TH Service",
            NotificationManager.IMPORTANCE_LOW
        ).apply {
            description = "MESH 温度湿度計測サービス"
            setSound(null, null)
            enableVibration(false)
        }

        val manager = getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager
        manager.createNotificationChannel(channel)
    }

    // フォアグラウンドサービス開始
    private fun startServiceWithParams() {
        val block = editBlock.text.toString().trim().uppercase()
        val serialNo = editSerialNo.text.toString().trim().uppercase()
        val interval = editInterval.text.toString().toIntOrNull() ?: 60

        // SharedPreferences に保管
        val prefs = getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
        prefs.edit()
            .putString("block", block)
            .putString("serialNo", serialNo)
            .putString("interval", interval.toString())
            .apply()

        val intent = Intent(this, MeshBleService::class.java).apply {
            putExtra("deviceName", block + serialNo)
            putExtra("interval", interval)
        }
        startForegroundService(intent)
    }


    // 日付ピッカー
    private fun showDatePicker(onDateSelected: (Long?) -> Unit) {
        val cal = Calendar.getInstance().apply {
            timeInMillis = System.currentTimeMillis()
        }

        val dialog = DatePickerDialog(
            this,
            { _, year, month, dayOfMonth ->
                val selectedCal = Calendar.getInstance().apply {
                    set(year, month, dayOfMonth, 0, 0, 0)
                    set(Calendar.MILLISECOND, 0)
                }
                onDateSelected(selectedCal.timeInMillis)
            },
            cal.get(Calendar.YEAR),
            cal.get(Calendar.MONTH),
            cal.get(Calendar.DAY_OF_MONTH)
        )
        dialog.show()
    }

    // 日付フォーマット
    private fun formatDate(time: Long): String {
        return SimpleDateFormat("yyyy/MM/dd", Locale.getDefault()).format(Date(time))
    }

    /** 日付を 0:00 にセット */
    private fun getStartOfDay(time: Long): Long {
        return Calendar.getInstance().apply {
            timeInMillis = time
            set(Calendar.HOUR_OF_DAY, 0)
            set(Calendar.MINUTE, 0)
            set(Calendar.SECOND, 0)
            set(Calendar.MILLISECOND, 0)
        }.timeInMillis
    }

    /** 日付を 23:59:59 にセット */
    private fun getEndOfDay(time: Long): Long {
        return Calendar.getInstance().apply {
            timeInMillis = time
            set(Calendar.HOUR_OF_DAY, 23)
            set(Calendar.MINUTE, 59)
            set(Calendar.SECOND, 59)
            set(Calendar.MILLISECOND, 999)
        }.timeInMillis
    }

    // 表示データ
    private fun filterAndObserveData() {
        viewModel.setDateRange(getStartOfDay(fromDate), getEndOfDay(toDate))

        viewModel.filteredData.observe(this) { list ->
            adapter.submitList(list) {
                binding.recyclerView.post {
                    val layoutManager = binding.recyclerView.layoutManager as LinearLayoutManager
                    if (layoutManager.findFirstVisibleItemPosition() == 0) {
                        binding.recyclerView.scrollToPosition(0)
                    }
                }
            }
        }
    }

    // Activity が破棄される直前
    override fun onDestroy() {
        Log.d(TAG, "MainActivity#onDestroy()")
        super.onDestroy()
        LocalBroadcastManager.getInstance(this).unregisterReceiver(statusReceiver)
        LocalBroadcastManager.getInstance(this).unregisterReceiver(batteryReceiver)
    }
}