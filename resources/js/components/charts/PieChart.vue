<template>
  <div class="relative">
    <canvas ref="canvasRef"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const props = defineProps({
  labels: { type: Array, default: () => [] },
  data: { type: Array, default: () => [] },
  colors: { type: Array, default: () => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'] },
  title: { type: String, default: '' },
})

const canvasRef = ref(null)
let chartInstance = null

function render() {
  if (chartInstance) chartInstance.destroy()
  if (!canvasRef.value) return
  chartInstance = new Chart(canvasRef.value, {
    type: 'doughnut',
    data: {
      labels: props.labels,
      datasets: [{ data: props.data, backgroundColor: props.colors.slice(0, props.labels.length), borderWidth: 1 }],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' },
        title: props.title ? { display: true, text: props.title } : undefined,
      },
    },
  })
}

onMounted(render)
watch(() => [props.labels, props.data], render, { deep: true })
</script>
