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
  datasets: { type: Array, default: () => [] },
  title: { type: String, default: '' },
  horizontal: { type: Boolean, default: false },
})

const canvasRef = ref(null)
let chartInstance = null

function render() {
  if (chartInstance) chartInstance.destroy()
  if (!canvasRef.value) return
  chartInstance = new Chart(canvasRef.value, {
    type: 'bar',
    data: {
      labels: props.labels,
      datasets: props.datasets.map(ds => ({
        ...ds,
        backgroundColor: ds.backgroundColor || '#3b82f6',
        borderColor: ds.borderColor || '#2563eb',
        borderWidth: 1,
      })),
    },
    options: {
      indexAxis: props.horizontal ? 'y' : 'x',
      responsive: true,
      plugins: {
        legend: { display: props.datasets.length > 1, position: 'bottom' },
        title: props.title ? { display: true, text: props.title } : undefined,
      },
      scales: { y: { beginAtZero: true }, x: { beginAtZero: true } },
    },
  })
}

onMounted(render)
watch(() => [props.labels, props.datasets], render, { deep: true })
</script>
