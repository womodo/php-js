package com.example.wifimonitor

import android.app.AlertDialog
import android.app.DatePickerDialog
import android.content.Intent
import android.icu.text.SimpleDateFormat
import android.icu.util.Calendar
import android.os.Bundle
import android.util.Log
import android.widget.ArrayAdapter
import android.widget.Button
import android.widget.EditText
import android.widget.Spinner
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.FileProvider
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.distinctUntilChanged
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import java.io.File
import java.util.Date
import java.util.Locale

class WifiLogViewActivity : AppCompatActivity() {

    private lateinit var wifiLogViewAdapter: WifiLogViewAdapter
    private lateinit var viewModel: WifiLogViewModel
    private lateinit var swipeRefreshLayout: SwipeRefreshLayout
    private lateinit var recyclerView: RecyclerView

    private lateinit var startDateEditText: EditText
    private lateinit var endDateEditText: EditText
    private lateinit var ssidSpinner: Spinner
    private lateinit var stateSpinner: Spinner
    private lateinit var searchButton: Button
    private lateinit var csvButton: Button
    private lateinit var clearButton: Button

    private lateinit var searchCount: TextView

    private val dateEditTextMap = mutableMapOf<EditText, Calendar?>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_wifi_log_view)

        startDateEditText = findViewById(R.id.start_date_input)
        endDateEditText = findViewById(R.id.end_date)
        ssidSpinner = findViewById(R.id.ssid_spinner)
        stateSpinner = findViewById(R.id.state_spinner)
        searchButton = findViewById(R.id.search_button)
        csvButton = findViewById(R.id.csv_button)
        clearButton = findViewById(R.id.clear_button)
        searchCount = findViewById(R.id.search_count)

        wifiLogViewAdapter = WifiLogViewAdapter()
        viewModel = ViewModelProvider(this)[WifiLogViewModel::class.java]

        swipeRefreshLayout = findViewById(R.id.swipe_refresh_layout)
        swipeRefreshLayout.setOnRefreshListener {
//            refreshLogs()
            swipeRefreshLayout.isRefreshing = false
        }

        recyclerView = findViewById(R.id.recycler_view)
        recyclerView.layoutManager = LinearLayoutManager(this)
        recyclerView.adapter = wifiLogViewAdapter

        // 日付選択ダイアログ
        startDateEditText.setOnClickListener {
            showDatePickerDialog(startDateEditText)
        }
        endDateEditText.setOnClickListener {
            showDatePickerDialog(endDateEditText)
        }

        // 実行日を設定
        val today = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault()).format(Date())
        startDateEditText.setText(today)

        // SSID Spinner設定
        viewModel.getDistinctSsids()
            .distinctUntilChanged() // データが同じなら再通知しない
            .observe(this) {
                populateSpinner(ssidSpinner, it)
            }
        // 状態 Spinner設定
        viewModel.getDistinctStates()
            .distinctUntilChanged() // データが同じなら再通知しない
            .observe(this) {
                populateSpinner(stateSpinner, it)
            }

        // 検索ボタン
        searchButton.setOnClickListener {
            refreshLogs()
        }

        // CSV出力ボタン
        csvButton.setOnClickListener {
            val startDate = startDateEditText.text.toString().toTimestamp()
            val endDate = endDateEditText.text.toString().toTimestamp()
            val ssid = if (ssidSpinner.selectedItem == "全て") null else ssidSpinner.selectedItem as String?
            val state = if (stateSpinner.selectedItem == "全て") null else stateSpinner.selectedItem as String?

            exportFilteredLogsToCsv(startDate, endDate, ssid, state, "wifi_log.csv")
        }

        // ログクリアボタン
        clearButton.setOnClickListener {
            AlertDialog.Builder(this)
                .setMessage("ログをすべて削除してよろしいですか？")
                .setPositiveButton("OK", { dialog, which ->
                    val database = AppDatabase.getDatabase(application)
                    val wifiLogDao: WifiLogDao = database.wifiLogDao()
                    lifecycleScope.launch(Dispatchers.IO) {
                        wifiLogDao.clearTable()
                    }
                })
                .setNegativeButton("No", { dialog, which -> })
                .show()
        }
    }

    // CSVファイルに出力
    private fun exportFilteredLogsToCsv(
        startDate: Long?,
        endDate: Long?,
        ssid: String?,
        state: String?,
        fileName: String
    ) {
        val database = AppDatabase.getDatabase(application)
        val wifiLogDao: WifiLogDao = database.wifiLogDao()
        val format = SimpleDateFormat("yyyy-MM-dd HH:mm:ss.SSS", Locale.getDefault())

        val liveData = wifiLogDao.searchLogs(startDate, endDate, ssid, state)
        liveData.observe(this) { wifiLogs ->
            if (wifiLogs != null && wifiLogs.size > 0) {
                lifecycleScope.launch(Dispatchers.IO) {
                    // CSVファイル作成
                    val csvFile = File(getExternalFilesDir(null), fileName)
                    csvFile.printWriter().use { writer ->
                        // ヘッダー行
                        writer.println("\"id\",\"timestamp\",\"ssid\",\"frequency\",\"bssid\",\"state\",\"rssi\"")
                        // データ行
                        wifiLogs.forEach { log ->
                            val line = listOf(
                                log.id.toString(),
                                format.format(Date(log.timestamp)),
                                log.ssid,
                                log.frequency,
                                log.bssid,
                                log.state,
                                log.rssi.toString()
                            ).joinToString(",") { escapeCsvValue(it) }
                            writer.println(line)
                        }
                    }
                    Log.d("WifiLogViewActivity", "${csvFile.absoluteFile}")

                    // CSVファイルをBluetoothで送信
                    withContext(Dispatchers.Main) {
                        sendFileViaBluetooth(csvFile)
                    }
                }
                // 一度だけ処理を行いたいので Observer を解除する
                liveData.removeObservers(this)
            }
        }
    }

    // CSVファイルをBluetooth共有
    private fun sendFileViaBluetooth(csvFile: File) {
        Log.d("WifiLogViewActivity", "sendFileViaBluetooth(${csvFile.absoluteFile})")

        val fileUri = FileProvider.getUriForFile(
            this,
            "${applicationContext.packageName}.fileprovider",
            csvFile
        )
        val intent = Intent(Intent.ACTION_SEND)
        intent.type = "*/*"
        intent.putExtra(Intent.EXTRA_STREAM, fileUri)
        startActivity(Intent.createChooser(intent, "共有"))
    }

    // ログ表示更新
    private fun refreshLogs() {
        val startDate = startDateEditText.text.toString().toTimestamp()
        val endDate = endDateEditText.text.toString().toTimestamp()
        val ssid = if (ssidSpinner.selectedItem == "全て") null else ssidSpinner.selectedItem as String?
        val state = if (stateSpinner.selectedItem == "全て") null else stateSpinner.selectedItem as String?

        viewModel.updateSearchParams(startDate, endDate, ssid, state)

        viewModel.searchResults().observe(this) { logs ->
            wifiLogViewAdapter.setLogs(logs)
            searchCount.text = "${wifiLogViewAdapter.itemCount}件"
        }
    }

    // 日付ダイアログを表示
    private fun showDatePickerDialog(editText: EditText) {
        val calendar = dateEditTextMap[editText] ?: Calendar.getInstance()

        val year = calendar.get(Calendar.YEAR)
        val month = calendar.get(Calendar.MONTH)
        val dayOfMonth = calendar.get(Calendar.DAY_OF_MONTH)

        val datePickerDialog = DatePickerDialog(
            this,
            { _, year, month, dayOfMonth ->
                val selectedCalendar = Calendar.getInstance().apply {
                    set(Calendar.YEAR, year)
                    set(Calendar.MONTH, month)
                    set(Calendar.DAY_OF_MONTH, dayOfMonth)
                }
                dateEditTextMap[editText] = selectedCalendar
                val dateFormat = SimpleDateFormat("yyyy-MM-dd", Locale.JAPAN)
                editText.setText(dateFormat.format(selectedCalendar.time))
            },
            year,
            month,
            dayOfMonth
        )

        // クリアボタンの実装
        datePickerDialog.setButton(DatePickerDialog.BUTTON_NEUTRAL, "クリア") { _, _ ->
            dateEditTextMap[editText] = null
            editText.text = null
        }

        datePickerDialog.show()
    }

    // Spinnerのリストを設定
    private fun populateSpinner(spinner: Spinner, items: List<String>) {
        // 現在の選択を保持
        val selectedItem = spinner.selectedItem?.toString()

        // 新しいAdapterを作成してセット
        val adapter = ArrayAdapter(
            this,
            android.R.layout.simple_spinner_item,
            listOf("全て") + items)
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
        spinner.adapter = adapter

        // 保持した選択を復元
        val position = adapter.getPosition(selectedItem)
        if (position >= 0) {
            spinner.setSelection(position)
        }
    }

    // 日付を変換
    private fun String.toTimestamp(): Long? {
        return if (this.isEmpty()) null else SimpleDateFormat("yyyy-MM-dd", Locale.getDefault()).parse(this)?.time
    }

    // エスケープ関数
    private fun escapeCsvValue(value: String): String {
        val escapedValue = value.replace("\"", "\"\"")
        return "\"$escapedValue\""
    }
}