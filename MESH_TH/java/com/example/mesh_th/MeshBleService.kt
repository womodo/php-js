package com.example.mesh_th

import android.annotation.SuppressLint
import android.app.PendingIntent
import android.app.Service
import android.bluetooth.BluetoothDevice
import android.bluetooth.BluetoothGatt
import android.bluetooth.BluetoothGattCallback
import android.bluetooth.BluetoothGattCharacteristic
import android.bluetooth.BluetoothGattDescriptor
import android.bluetooth.BluetoothGattService
import android.bluetooth.BluetoothManager
import android.bluetooth.BluetoothProfile
import android.bluetooth.le.BluetoothLeScanner
import android.bluetooth.le.ScanCallback
import android.bluetooth.le.ScanResult
import android.content.Intent
import android.os.Environment
import android.os.Handler
import android.os.IBinder
import android.os.Looper
import android.util.Log
import androidx.core.app.NotificationCompat
import androidx.localbroadcastmanager.content.LocalBroadcastManager
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.SupervisorJob
import kotlinx.coroutines.launch
import java.io.File
import java.text.SimpleDateFormat
import java.util.*
import kotlin.math.atan
import kotlin.math.pow
import kotlin.math.round
import kotlin.math.sqrt

@SuppressLint("MissingPermission")
class MeshBleService : Service() {

    private val TAG = "MESH_LOG"
    private val CHANNEL_ID = "mesh_th_service"

    private var latestStatus: String = ""
    private var latestBattery: String = ""

    private val CORE_SERVICE_UUID = UUID.fromString("72c90001-57a9-4d40-b746-534e22ec9f9e")
    private val CORE_INDICATE_UUID = UUID.fromString("72c90005-57a9-4d40-b746-534e22ec9f9e")
    private val CORE_NOTIFY_UUID = UUID.fromString("72c90003-57a9-4d40-b746-534e22ec9f9e")
    private val CORE_WRITE_UUID = UUID.fromString("72c90004-57a9-4d40-b746-534e22ec9f9e")
    private val DESCRIPTOR_UUID = UUID.fromString("00002902-0000-1000-8000-00805f9b34fb")

    private var deviceName = "MESH-100TH"
    private var interval = 60

    private var step = 0
    private var bluetoothGatt: BluetoothGatt? = null
    private var scanner: BluetoothLeScanner? = null

    private val handler = Handler(Looper.getMainLooper())
    private var isConnected = false

    private val serviceScope = CoroutineScope(Dispatchers.IO + SupervisorJob())
    private lateinit var db: AppDatabase


    // BLE デバイスの検索
    private val scanCallback = object : ScanCallback() {
        override fun onScanResult(callbackType: Int, result: ScanResult) {
            super.onScanResult(callbackType, result)
            val device = result.device
            val name = result.scanRecord?.deviceName ?: device.name ?: null

            Log.d(TAG, "スキャン... ${device.address} - $name")
            updateStatus("接続試行中")

            if (name == deviceName) {
                Log.d(TAG, "デバイス発見: ${device.address} - $name")
                scanner?.stopScan(this)
                connectToDevice(device)
            }
        }

        override fun onScanFailed(errorCode: Int) {
            super.onScanFailed(errorCode)
            Log.d(TAG, "スキャン失敗: $errorCode")
        }
    }

    // BLE デバイス処理
    private val gattCallback = object : BluetoothGattCallback() {
        // 接続状態変更
        override fun onConnectionStateChange(gatt: BluetoothGatt, status: Int, newState: Int) {
            super.onConnectionStateChange(gatt, status, newState)

            when (newState) {
                // 接続時
                BluetoothProfile.STATE_CONNECTED -> {
                    isConnected = true
                    updateStatus("接続")
                    step = 0
                    gatt.discoverServices()
                }

                // 切断時
                BluetoothProfile.STATE_DISCONNECTED -> {
                    isConnected = false
                    updateStatus("切断")
                    updateBattery("--")

                    Log.d(TAG, "接続切断、再接続を試みます...")

                    // 解放
                    bluetoothGatt?.disconnect()
                    bluetoothGatt?.close()
                    bluetoothGatt = null

                    // 5秒後に再接続を試みる
                    handler.postDelayed({
                        scanner?.stopScan(scanCallback)
                        startBleScan()
                    }, 5000)
                }
            }
        }

        //　サービス発見
        override fun onServicesDiscovered(gatt: BluetoothGatt, status: Int) {
            super.onServicesDiscovered(gatt, status)
            val service = gatt.getService(CORE_SERVICE_UUID)

            // Indicate 有効化
            step = 1
            val indicateChar = service.getCharacteristic(CORE_INDICATE_UUID)
            gatt.setCharacteristicNotification(indicateChar, true)
            val indicateDescriptor = indicateChar.getDescriptor(DESCRIPTOR_UUID)
            gatt.writeDescriptor(indicateDescriptor, BluetoothGattDescriptor.ENABLE_INDICATION_VALUE)
        }

        // Descriptor に書き込まれた
        override fun onDescriptorWrite(
            gatt: BluetoothGatt,
            descriptor: BluetoothGattDescriptor,
            status: Int
        ) {
            super.onDescriptorWrite(gatt, descriptor, status)
            if (status == BluetoothGatt.GATT_SUCCESS) {
                Log.d(TAG, "Descriptor write success: ${descriptor.uuid}")
                val service = gatt.getService(CORE_SERVICE_UUID)

                when (step) {
                    1 -> {
                        // Notify 有効化
                        step = 2
                        val notifyChar = service.getCharacteristic(CORE_NOTIFY_UUID)
                        gatt.setCharacteristicNotification(notifyChar, true)
                        val notifyDescriptor = notifyChar.getDescriptor(DESCRIPTOR_UUID)
                        gatt.writeDescriptor(notifyDescriptor, BluetoothGattDescriptor.ENABLE_NOTIFICATION_VALUE)
                    }

                    2 -> {
                        // ブロック機能有効化
                        step = 3
                        val writeChar = service.getCharacteristic(CORE_WRITE_UUID)
                        gatt.writeCharacteristic(writeChar,
                            byteArrayOf(0x00, 0x02, 0x01, 0x03),
                            BluetoothGattCharacteristic.WRITE_TYPE_DEFAULT
                        )

                        // 定期リクエスト開始
                        startPeriodicRequest(gatt, service)
                    }
                }
            } else {
                Log.d(TAG, "Descriptor write failed: $status")
            }
        }

        // 通知を受け取った
        override fun onCharacteristicChanged(
            gatt: BluetoothGatt,
            characteristic: BluetoothGattCharacteristic,
            value: ByteArray
        ) {
            super.onCharacteristicChanged(gatt, characteristic, value)

            // WBGT を計算
            fun calculateWBGT(T: Double, RH: Double): Double {
                // T: 気温(℃), RH: 相対湿度(%)
                val Tw = T * atan(0.151977 * sqrt(RH + 8.313659)) +
                        atan(T + RH) -
                        atan(RH - 1.676331) +
                        0.00391838 * RH.pow(1.5) * atan(0.023101 * RH) -
                        4.686035
                // 屋内・簡易版WBGT
                return round((0.7 * Tw + 0.3 * T) * 10) / 10.0
            }

            // 基本情報通知
            if (value[0].toInt() == 0x00 && value[1].toInt() == 0x02) {
                val battery = value[14].toInt() * 10
                updateBattery(battery.toString())
                Log.d(TAG, "ブロックの種類: ${value[2]}, バッテリーレベル: $battery%")
            }

            // 定時通信
            else if (value[0].toInt() == 0x00 && value[1].toInt() == 0x00) {
                val battery = value[2].toInt() * 10
                updateBattery(battery.toString())
                Log.d(TAG, "バッテリーレベル: $battery%")
            }

            // 温度湿度
            else if (value[0].toInt() == 0x01 && value[1].toInt() == 0x00) {
                val temp_lsb: Byte = value[4]
                val temp_msb: Byte = value[5]
                val temp: Int = (temp_msb.toInt() and 0xFF shl 8) or (temp_lsb.toInt() and 0xFF)

                val hum_lsb: Byte = value[6]
                val hum_msb: Byte = value[7]
                val hum: Int = (hum_msb.toInt() and 0xFF shl 8) or (hum_lsb.toInt() and 0xFF)

                val wbgt = calculateWBGT(temp / 10.0, hum.toDouble())

                val datetime = System.currentTimeMillis()

                // DB に保存
                serviceScope.launch {
                    db.meshThDataDao().insert(
                        MeshThData(
                            datetime = datetime,
                            temperature = temp / 10.0,
                            humidity = hum,
                            wbgt = wbgt
                        )
                    )
                }

                // ファイルに保存
                saveToFile(datetime, temp / 10.0, hum)

                Log.d(TAG, "温度:${temp / 10.0}℃, 湿度:${hum}%, WBGT:${wbgt}")
            }

            // その他
            else {
                val hex = value.joinToString(separator = ",") {
                    "0x%02X".format(it.toInt() and 0xFF)
                }
                Log.d(TAG, "Notfication received: ${characteristic.uuid}, value=$hex")
            }
        }
    }


    // Service 生成時
    override fun onCreate() {
        super.onCreate()

        db = AppDatabase.getInstance(applicationContext)

        startForegroundService()
        startBleScan()
    }

    // フォアグラウンドサービス開始
    private fun startForegroundService() {
        // 通知に PendingIntent を設定
        val intent = Intent(this, MainActivity::class.java).apply {
            flags = Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_SINGLE_TOP
        }
        val pendingIntent = PendingIntent.getActivity(
            this,
            0,
            intent,
            PendingIntent.FLAG_UPDATE_CURRENT or PendingIntent.FLAG_IMMUTABLE
        )

        // 通知
        val notification = NotificationCompat.Builder(this, CHANNEL_ID)
            .setContentTitle("MESH TH Service")
            .setContentText("MESH 接続中")
            .setSmallIcon(R.drawable.ic_launcher_foreground)
            .setContentIntent(pendingIntent)
            .build()

        startForeground(1, notification)
    }

    // BLE デバイスの検索
    private fun startBleScan() {
        val bluetoothAdapter = (getSystemService(BLUETOOTH_SERVICE) as BluetoothManager).adapter
        scanner = bluetoothAdapter.bluetoothLeScanner
        Log.d(TAG, "スキャン開始...")
        scanner?.startScan(scanCallback)
    }

    // BLE デバイスに接続
    private fun connectToDevice(device: BluetoothDevice) {
        bluetoothGatt?.close() // 前の接続があれば閉じる
        bluetoothGatt = device.connectGatt(this, false, gattCallback)
    }

    // 定期リクエスト開始
    private fun startPeriodicRequest(gatt: BluetoothGatt, service: BluetoothGattService) {
        val writeChar = service.getCharacteristic(CORE_WRITE_UUID)

        // 現在の値を取得
        var bytes = byteArrayOf(
            0x01.toByte(), //  [0] Message Type ID
            0x00.toByte(), //  [1] Event Type ID
            0x00.toByte(), //  [2] リクエスト ID（任意のID）
            0xF4.toByte(), //  [3] 温度 上限値(LSB)　50℃
            0x01.toByte(), //  [4] 温度 上限値(MSB)
            0x9C.toByte(), //  [5] 温度 下限値(LSB) -10℃
            0xFF.toByte(), //  [6] 温度 下限値(MSB)
            0x64.toByte(), //  [7] 湿度 上限値(LSB)  100%
            0x00.toByte(), //  [8] 湿度 上限値(MSB)
            0x00.toByte(), //  [9] 湿度 下限値(LSB)    0%
            0x00.toByte(), // [10] 湿度 下限値(MSB)
            0x00.toByte(), // [11] 温度通知イベントの通知条件
            0x00.toByte(), // [12] 湿度通知イベントの通知条件
            0x10.toByte()  // [13] 通知モード
        )

        // チェックサムを計算(データの総和の下位 1 バイトの値)
        val sum = bytes.fold(0) { acc, b -> acc + (b.toInt() and 0xFF) }
        val lowerByte = (sum and 0xFF).toByte()
        bytes = bytes + lowerByte

        // 1秒待ってから書き込み
        handler.postDelayed({
            gatt.writeCharacteristic(
                writeChar,
                bytes,
                BluetoothGattCharacteristic.WRITE_TYPE_DEFAULT
            )
        }, 1000)

        // 計測時間の間隔で書き込み(実行)
        val runnable = object : Runnable {
            override fun run() {
                if (isConnected) {
                    Log.d(TAG, "温度・湿度を計測")
                    gatt.writeCharacteristic(
                        writeChar,
                        bytes,
                        BluetoothGattCharacteristic.WRITE_TYPE_DEFAULT
                    )
                    handler.postDelayed(this, interval * 1000L)
                }
            }
        }
        handler.post(runnable)
    }

    // 接続状態を更新
    private fun updateStatus(status: String) {
        latestStatus = status
        val intent = Intent("MESH_STATUS").apply {
            putExtra("status", latestStatus)
        }
        LocalBroadcastManager.getInstance(this).sendBroadcast(intent)
    }

    // バッテリー状態を更新
    private fun updateBattery(battery: String) {
        latestBattery = battery
        val intent = Intent("MESH_BATTERY").apply {
            putExtra("battery", latestBattery)
        }
        LocalBroadcastManager.getInstance(this).sendBroadcast(intent)
    }

    // ファイルに保存
    private fun saveToFile(datetime: Long, temp: Double, hum: Int) {
        try {
            // ユーザーの Documents フォルダ
            val documentsDir = Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOCUMENTS)

            // data フォルダを作成（存在しなければ）
            val dataDir = File(documentsDir, "data")
            if (!dataDir.exists()) {
                dataDir.mkdir()
            }

            // 保存ファイル
            val file = File(dataDir, "temphum.txt")

            // 日付フォーマット
            val sdf = SimpleDateFormat("yyyy/MM/dd HH:mm:ss", Locale.getDefault())
            val datetimeStr = sdf.format(Date(datetime))

            // 追記モードで書き込み
            file.appendText("$datetimeStr,$temp,$hum\n")

        } catch (e: Exception) {
            Log.e(TAG, "ファイル保存エラー", e)
        }
    }


    // バインド型サービスとしては使わないので null
    override fun onBind(intent: Intent): IBinder? = null

    // Service の処理を開始
    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        // Activity からパラメータを受け取る
        deviceName = intent?.getStringExtra("deviceName") ?: deviceName
        interval = intent?.getIntExtra("interval", 60) ?: interval

        Log.d(TAG, "サービス開始 deviceName=$deviceName, interval=$interval")

        return START_STICKY // OS にプロセスが killed されても再起動してくれる可能性がある
    }

    // Service が破棄される直前
    override fun onDestroy() {
        super.onDestroy()

        isConnected = false
        bluetoothGatt?.disconnect()
        bluetoothGatt?.close()
        scanner?.stopScan(scanCallback)

        updateStatus("未接続")
        updateBattery("--")

        Log.d(TAG, "MeshBleService#onDestroy() - サービス停止")
    }
}