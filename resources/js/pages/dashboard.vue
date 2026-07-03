<template>
  <Layout>
    <div class="max-w-6xl mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6">My Dashboard</h1>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 rounded-lg p-6 text-center">
          <p class="text-4xl font-bold text-blue-600">${{ Number(stats.total_donated).toLocaleString() }}</p>
          <p class="text-gray-600 mt-2">Total Donated</p>
        </div>
        <div class="bg-green-50 rounded-lg p-6 text-center">
          <p class="text-4xl font-bold text-green-600">{{ stats.donation_count }}</p>
          <p class="text-gray-600 mt-2">Donations Made</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-6 text-center">
          <p class="text-4xl font-bold text-purple-600">{{ auth.user?.name?.charAt(0) }}</p>
          <p class="text-gray-600 mt-2">{{ auth.user?.name }}</p>
        </div>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Donations by Project</h2>
          <div class="h-64">
            <PieChart v-if="stats.project_wise?.length" :labels="pieLabels" :data="pieData" />
            <p v-else class="text-gray-500 text-center py-12">No donations yet.</p>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Monthly Donations</h2>
          <div class="h-64">
            <BarChart v-if="stats.monthly?.length" :labels="barLabels" :datasets="barDatasets" />
            <p v-else class="text-gray-500 text-center py-12">No donation history yet.</p>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Donation History</h2>
        <div v-if="donations.length" class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead><tr class="border-b text-left"><th class="py-2 pr-4">Date</th><th class="py-2 pr-4">Project</th><th class="py-2 pr-4">Amount</th><th class="py-2 pr-4">Status</th></tr></thead>
            <tbody>
              <tr v-for="d in donations" :key="d.id" class="border-b hover:bg-gray-50">
                <td class="py-2 pr-4">{{ new Date(d.created_at).toLocaleDateString() }}</td>
                <td class="py-2 pr-4">{{ d.project?.title || 'N/A' }}</td>
                <td class="py-2 pr-4 font-medium">${{ Number(d.amount).toLocaleString() }}</td>
                <td class="py-2 pr-4"><span :class="d.status === 'completed' ? 'text-green-600' : 'text-yellow-600'">{{ d.status }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <p v-else class="text-gray-500 text-center py-8">No donations yet. <RouterLink to="/projects" class="text-blue-600 hover:underline">Browse projects</RouterLink></p>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Layout from '@/layouts/Layout.vue'
import PieChart from '@/components/charts/PieChart.vue'
import BarChart from '@/components/charts/BarChart.vue'

const auth = useAuthStore()
const router = useRouter()
const stats = ref({ total_donated: 0, donation_count: 0, project_wise: [], monthly: [] })
const donations = ref([])

const pieLabels = computed(() => stats.value.project_wise?.map(p => p.project_title) || [])
const pieData = computed(() => stats.value.project_wise?.map(p => p.total_amount) || [])
const barLabels = computed(() => stats.value.monthly?.map(m => m.month) || [])
const barDatasets = computed(() => [{ label: 'Donations', backgroundColor: '#3b82f6', data: stats.value.monthly?.map(m => m.total_amount) || [] }])

onMounted(async () => {
  if (!auth.isLoggedIn) { router.push('/login'); return }
  try {
    const [statsRes, donationsRes] = await Promise.all([axios.get('/api/donations/stats'), axios.get('/api/donations')])
    stats.value = statsRes.data
    donations.value = donationsRes.data.data || []
  } catch (e) {}
})
</script>
