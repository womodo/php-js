package com.example.mesh_th

import android.app.Activity
import android.app.Application
import android.content.Intent
import android.os.Bundle
import android.util.Log

class MeshThApp : Application(), Application.ActivityLifecycleCallbacks {

    private val TAG = "MESH_LOG"

    private var activityCount = 0
    private var isChangingConfig = false

    override fun onCreate() {
        Log.d(TAG, "MeshThApp#onCreate()")
        super.onCreate()
        registerActivityLifecycleCallbacks(this)
    }

    override fun onActivityStarted(activity: Activity) {
        if (++activityCount == 1 && !isChangingConfig) {
            // アプリがフォアグラウンドに戻ったとき
            Log.d(TAG, "MeshThApp#onActivityStarted()")
        }
    }

    override fun onActivityStopped(activity: Activity) {
        isChangingConfig = activity.isChangingConfigurations
        if (--activityCount == 0 && !isChangingConfig) {
            // アプリが完全にバックグラウンドになったとき
            Log.d(TAG, "MeshThApp#onActivityStopped()")
        }
    }

    override fun onActivityDestroyed(activity: Activity) {
        // 最後の Activity が破棄されたときにサービス停止
        if (activityCount == 0) {
            Log.d(TAG, "MeshThApp#onActivityDestroyed()")
            stopMeshService()
        }
    }

    private fun stopMeshService() {
        val intent = Intent(this, MeshBleService::class.java)
        stopService(intent)
    }

    override fun onActivityCreated(activity: Activity, savedInstanceState: Bundle?) {}
    override fun onActivityResumed(activity: Activity) {}
    override fun onActivityPaused(activity: Activity) {}
    override fun onActivitySaveInstanceState(activity: Activity, outState: Bundle) {}
}