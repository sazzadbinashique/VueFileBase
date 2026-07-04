<template>
  <Layout>
    <div class="max-w-6xl mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ $t('my_dashboard') }}</h1>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="rounded-lg p-5 text-center" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <p class="text-3xl font-bold" :style="{ color: 'var(--primary)' }">${{ Number(stats.total_donated).toLocaleString() }}</p>
          <p class="text-xs mt-1 font-mono uppercase tracking-wide" :style="{ color: 'var(--ink-soft)' }">{{ $t('total_donated') }}</p>
        </div>
        <div class="rounded-lg p-5 text-center" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <p class="text-3xl font-bold" :style="{ color: 'var(--accent2)' }">{{ stats.donation_count }}</p>
          <p class="text-xs mt-1 font-mono uppercase tracking-wide" :style="{ color: 'var(--ink-soft)' }">{{ $t('donations') }}</p>
        </div>
        <div class="rounded-lg p-5 text-center" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <p class="text-3xl font-bold" :style="{ color: 'var(--accent)' }">{{ Object.keys(yearlyStats).length }}</p>
          <p class="text-xs mt-1 font-mono uppercase tracking-wide" :style="{ color: 'var(--ink-soft)' }">{{ $t('years_active') }}</p>
        </div>
        <div class="rounded-lg p-5 text-center" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <p class="text-3xl font-bold" style="color: var(--primary)">{{ uniqueProjects }}</p>
          <p class="text-xs mt-1 font-mono uppercase tracking-wide" :style="{ color: 'var(--ink-soft)' }">{{ $t('projects') }}</p>
        </div>
      </div>

      <div class="rounded-lg p-6 mb-8" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h2 class="text-lg font-semibold mb-4">{{ $t('yearly_summary') }}</h2>
        <div v-if="yearKeys.length">
          <BarChart :labels="yearKeys" :datasets="yearDatasets" />
          <div class="mt-4 overflow-x-auto">
            <table class="w-full text-xs">
              <thead><tr class="border-b" :style="{ borderColor: 'var(--border)' }">
                <th class="py-2 px-3 text-left">{{ $t('year') }}</th>
                <th class="py-2 px-3 text-left">{{ $t('project') }}</th>
                <th class="py-2 px-3 text-right">{{ $t('count') }}</th>
                <th class="py-2 px-3 text-right">{{ $t('total') }}</th>
              </tr></thead>
              <tbody>
                <template v-for="(items, year) in yearlyStats" :key="year">
                  <tr v-for="(item, i) in items" :key="item.project_id" class="border-b" :class="{ 'border-t-2': i === 0 }" :style="{ borderColor: 'var(--border)' }">
                    <td class="py-2 px-3 font-medium" v-if="i === 0" :rowspan="items.length">{{ year }}</td>
                    <td class="py-2 px-3">{{ item.project_title }}</td>
                    <td class="py-2 px-3 text-right">{{ item.count }}</td>
                    <td class="py-2 px-3 text-right">${{ Number(item.total).toLocaleString() }}</td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
        <p v-else class="text-sm" :style="{ color: 'var(--ink-soft)' }">{{ $t('no_donation_data') }}</p>
      </div>

      <div class="rounded-lg p-6 mb-8" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h2 class="text-lg font-semibold mb-4">{{ $t('donation_history') }}</h2>
        <div v-if="loadingHistory" class="text-center py-8">
          <span class="inline-block w-6 h-6 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></span>
        </div>
        <template v-else>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead><tr class="border-b text-left" :style="{ borderColor: 'var(--border)' }">
                <th class="py-3 px-3">{{ $t('date') }}</th>
                <th class="py-3 px-3">{{ $t('project') }}</th>
                <th class="py-3 px-3 text-right">{{ $t('amount') }}</th>
                <th class="py-3 px-3">{{ $t('transaction') }}</th>
                <th class="py-3 px-3">{{ $t('status') }}</th>
                <th class="py-3 px-3">{{ $t('payment') }}</th>
              </tr></thead>
              <tbody>
                <tr v-for="d in donations" :key="d.id" class="border-b" :style="{ borderColor: 'var(--border)' }">
                  <td class="py-3 px-3 text-xs">{{ new Date(d.created_at).toLocaleDateString() }}</td>
                  <td class="py-3 px-3 font-medium">{{ d.project?.title }}</td>
                  <td class="py-3 px-3 text-right font-mono">${{ Number(d.amount).toLocaleString() }}</td>
                  <td class="py-3 px-3 text-xs font-mono" :style="{ color: 'var(--ink-soft)' }">{{ d.transaction_id }}</td>
                  <td class="py-3 px-3"><StatusBadge :status="d.status" /></td>
                  <td class="py-3 px-3 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ d.card_type || '-' }}</td>
                </tr>
                <tr v-if="!donations.length"><td colspan="6" class="text-center py-8" :style="{ color: 'var(--ink-soft)' }">{{ $t('no_donations') }}</td></tr>
              </tbody>
            </table>
          </div>
          <div v-if="historyPages > 1" class="flex items-center justify-center gap-2 mt-4">
            <button @click="goHistoryPage(historyPage - 1)" :disabled="historyPage <= 1" class="px-3 py-1 rounded text-sm border hover:opacity-80 disabled:opacity-40" :style="{ borderColor: 'var(--border)' }">‹</button>
            <span class="text-sm" :style="{ color: 'var(--ink-soft)' }">{{ historyPage }} / {{ historyPages }}</span>
            <button @click="goHistoryPage(historyPage + 1)" :disabled="historyPage >= historyPages" class="px-3 py-1 rounded text-sm border hover:opacity-80 disabled:opacity-40" :style="{ borderColor: 'var(--border)' }">›</button>
          </div>
        </template>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useLangStore } from '@/stores/lang'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Layout from '@/layouts/Layout.vue'
import StatusBadge from '@/components/admin/StatusBadge.vue'
import BarChart from '@/components/charts/BarChart.vue'

const auth = useAuthStore()
const lang = useLangStore()
const router = useRouter()
const stats = ref({ total_donated: 0, donation_count: 0, project_wise: [], monthly: [] })
const donations = ref([])
const loadingHistory = ref(true)
const historyPage = ref(1)
const historyPages = ref(1)
const yearlyStats = ref({})
const chartColors = ['#0E6B5C', '#E8A93B', '#C43D3D', '#4FBFA6', '#F0BE5E', '#E5766B', '#71B8A5', '#D4942A']

const uniqueProjects = computed(() => {
  const p = new Set()
  donations.value.forEach(d => d.project?.title && p.add(d.project.title))
  return p.size
})

const yearKeys = computed(() => Object.keys(yearlyStats.value).sort())
const yearDatasets = computed(() => {
  const years = yearKeys.value
  // Get all project names across all years
  const allProjects = new Set()
  years.forEach(y => (yearlyStats.value[y] || []).forEach(i => allProjects.add(i.project_title)))
  const projectList = [...allProjects]

  return projectList.map((proj, idx) => ({
    label: proj,
    backgroundColor: chartColors[idx % chartColors.length],
    data: years.map(y => {
      const items = yearlyStats.value[y] || []
      const match = items.find(i => i.project_title === proj)
      return match ? match.total : 0
    }),
  }))
})

async function loadHistory() {
  loadingHistory.value = true
  try {
    const { data } = await axios.get('/api/donations', { params: { page: historyPage.value, per_page: 15 } })
    donations.value = data.data || []
    historyPages.value = data.last_page || 1
  } finally { loadingHistory.value = false }
}

function goHistoryPage(p) {
  if (p < 1 || p > historyPages.value) return
  historyPage.value = p
  loadHistory()
}

onMounted(async () => {
  if (!auth.isLoggedIn) { router.push('/login'); return }
  try {
    const [statsRes, yearlyRes] = await Promise.all([
      axios.get('/api/donations/stats'),
      axios.get('/api/donations/yearly-stats'),
    ])
    stats.value = statsRes.data
    yearlyStats.value = yearlyRes.data || {}
  } catch (e) {}
  await loadHistory()
})
</script>
