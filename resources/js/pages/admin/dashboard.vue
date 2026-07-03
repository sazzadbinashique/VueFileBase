<template>
  <AdminLayout>
    <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
    <div v-if="loading" class="text-center py-12">Loading dashboard...</div>
    <template v-else>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 text-center"><p class="text-3xl font-bold text-blue-600">${{ Number(data.stats.total_donations).toLocaleString() }}</p><p class="text-gray-600 text-sm">Total Donations</p></div>
        <div class="bg-white rounded-lg shadow p-6 text-center"><p class="text-3xl font-bold text-green-600">{{ data.stats.donation_count }}</p><p class="text-gray-600 text-sm">Donation Count</p></div>
        <div class="bg-white rounded-lg shadow p-6 text-center"><p class="text-3xl font-bold text-purple-600">{{ data.stats.active_projects }}</p><p class="text-gray-600 text-sm">Active Projects</p></div>
        <div class="bg-white rounded-lg shadow p-6 text-center"><p class="text-3xl font-bold text-orange-600">{{ data.stats.total_users }}</p><p class="text-gray-600 text-sm">Total Users</p></div>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6"><h2 class="text-xl font-semibold mb-4">Donations by Project</h2><div class="h-72"><PieChart v-if="data.project_wise_donations?.length" :labels="pieLabels" :data="pieData" /><p v-else class="text-gray-500 text-center py-12">No data yet.</p></div></div>
        <div class="bg-white rounded-lg shadow p-6"><h2 class="text-xl font-semibold mb-4">Monthly Donations</h2><div class="h-72"><BarChart v-if="data.monthly_donations?.length" :labels="monthlyLabels" :datasets="monthlyDatasets" /><p v-else class="text-gray-500 text-center py-12">No data yet.</p></div></div>
      </div>
      <div class="bg-white rounded-lg shadow p-6 mb-8"><h2 class="text-xl font-semibold mb-4">Project-wise Collection</h2><div class="h-72"><BarChart v-if="data.project_wise_donations?.length" :labels="projectLabels" :datasets="projectDatasets" :horizontal="true" /><p v-else class="text-gray-500 text-center py-12">No data yet.</p></div></div>
      <div class="bg-white rounded-lg shadow p-6"><h2 class="text-xl font-semibold mb-4">Recent Donations</h2>
        <table class="w-full text-sm">
          <thead><tr class="bg-gray-50 border-b text-left"><th class="py-2 px-3">Date</th><th class="py-2 px-3">Donor</th><th class="py-2 px-3">Project</th><th class="py-2 px-3">Amount</th></tr></thead>
          <tbody>
            <tr v-for="d in data.recent_donations" :key="d.id" class="border-b hover:bg-gray-50">
              <td class="py-2 px-3">{{ new Date(d.created_at).toLocaleDateString() }}</td>
              <td class="py-2 px-3">{{ d.user?.name || d.donor_name }}</td>
              <td class="py-2 px-3">{{ d.project?.title }}</td>
              <td class="py-2 px-3 font-medium">${{ Number(d.amount).toLocaleString() }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import PieChart from '@/components/charts/PieChart.vue'
import BarChart from '@/components/charts/BarChart.vue'

const admin = useAdminStore()
const router = useRouter()
const data = ref({ stats: {}, project_wise_donations: [], monthly_donations: [], recent_donations: [] })
const loading = ref(true)

const pieLabels = computed(() => data.value.project_wise_donations?.map(p => p.project_title) || [])
const pieData = computed(() => data.value.project_wise_donations?.map(p => p.total_donated) || [])
const monthlyLabels = computed(() => data.value.monthly_donations?.map(m => m.month) || [])
const monthlyDatasets = computed(() => [{ label: 'Donations', backgroundColor: '#3b82f6', data: data.value.monthly_donations?.map(m => m.total_amount) || [] }])
const projectLabels = computed(() => data.value.project_wise_donations?.map(p => p.project_title) || [])
const projectDatasets = computed(() => [{ label: 'Total Donated', backgroundColor: '#10b981', data: data.value.project_wise_donations?.map(p => p.total_donated) || [] }])

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  try { data.value = await admin.fetchDashboard() }
  finally { loading.value = false }
})
</script>
