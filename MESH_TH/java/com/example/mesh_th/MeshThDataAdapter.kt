package com.example.mesh_th

import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.example.mesh_th.databinding.TableRowItemBinding
import java.text.SimpleDateFormat
import java.util.*

class MeshThDataAdapter :
    ListAdapter<MeshThData, MeshThDataAdapter.MeshDataViewHolder>(DiffCallback()) {

    class MeshDataViewHolder(val binding: TableRowItemBinding) : RecyclerView.ViewHolder(binding.root)

    class DiffCallback : DiffUtil.ItemCallback<MeshThData>() {
        override fun areItemsTheSame(oldItem: MeshThData, newItem: MeshThData) =
            oldItem.id == newItem.id

        override fun areContentsTheSame(oldItem: MeshThData, newItem: MeshThData) =
            oldItem == newItem
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): MeshDataViewHolder {
        val binding = TableRowItemBinding.inflate(
            LayoutInflater.from(parent.context), parent, false)
        return MeshDataViewHolder(binding)
    }

    override fun onBindViewHolder(holder: MeshDataViewHolder, position: Int) {
        val meshThData = getItem(position)
        holder.binding.textDate.text = SimpleDateFormat("yyyy/MM/dd HH:mm:ss", Locale.JAPAN).format(Date(meshThData.datetime))
        holder.binding.textTemp.text = meshThData.temperature.toString()
        holder.binding.textHum.text = meshThData.humidity.toString()
        holder.binding.textWbgt.text = meshThData.wbgt.toString()
    }
}