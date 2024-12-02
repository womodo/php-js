package com.example.test2application

import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.SavedStateHandle
import androidx.lifecycle.ViewModel

class AppStateViewModel(private val savedStateHandle: SavedStateHandle) : ViewModel() {

    val sharedText: MutableLiveData<String> = MutableLiveData("")

    companion object {
        private const val KEY_LAST_ACTIVITY = "key_last_activity"
        private const val KEY_DISPLAY_TEXT = "key_display_text"
    }

    fun setLastActivity(activity_name: String) {
        savedStateHandle[KEY_LAST_ACTIVITY] = activity_name
    }

    fun getLastActivity(): String {
        return savedStateHandle[KEY_LAST_ACTIVITY] ?: ""
    }

    fun setDisplayText(text: String) {
        savedStateHandle[KEY_DISPLAY_TEXT] = text
    }

    fun getDisplayText(): String {
        return savedStateHandle[KEY_DISPLAY_TEXT] ?: ""
    }
}