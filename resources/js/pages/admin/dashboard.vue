<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ lang.t('Admin Dashboard', 'অ্যাডমিন ড্যাশবোর্ড') }}</h1>

    <div v-if="loading" class="text-center py-12">
      <span class="inline-block w-8 h-8 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></span>
    </div>

    <template v-else>
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="rounded-lg p-5 text-center" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <p class="text-2xl md:text-3xl font-bold" :style="{ color: 'var(--primary)' }">${{ Number(dashboard.stats?.total_donations || 0).toLocaleString() }}</p>
          <p class="text-xs mt-1 font-mono uppercase tracking-wide" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Donations', 'দান') }}</p>
        </div>
        <div class="rounded-lg p-5 text-center" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <p class="text-2xl md:text-3xl font-bold" :style="{ color: 'var(--accent2)' }">{{ dashboard.stats?.total_projects || 0 }}</p>
          <p class="text-xs mt-1 font-mono uppercase tracking-wide" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Projects', 'প্রকল্প') }}</p>
        </div>
        <div class="rounded-lg p-5 text-center" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <p class="text-2xl md:text-3xl font-bold" :style="{ color: 'var(--accent)' }">{{ dashboard.stats?.total_users || 0 }}</p>
          <p class="text-xs mt-1 font-mono uppercase tracking-wide" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Users', 'ব্যবহারকারী') }}</p>
        </div>
        <div class="rounded-lg p-5 text-center" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <p class="text-2xl md:text-3xl font-bold" :style="{ color: 'var(--primary)' }">{{ dashboard.stats?.active_projects || 0 }}</p>
          <p class="text-xs mt-1 font-mono uppercase tracking-wide" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Active', 'সক্রিয়') }}</p>
        </div>
        <div class="rounded-lg p-5 text-center" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <p class="text-2xl md:text-3xl font-bold" style="color: var(--primary)">{{ dashboard.stats?.donation_count || 0 }}</p>
          <p class="text-xs mt-1 font-mono uppercase tracking-wide" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Transactions', 'লেনদেন') }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <h2 class="text-lg font-semibold mb-4">{{ lang.t('Monthly Donations', 'মাসিক দান') }}</h2>
          <BarChart v-if="barLabels.length" :labels="barLabels" :datasets="barDatasets" />
          <p v-else class="text-sm" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('No data yet', 'কোনো ডেটা নেই') }}</p>
        </div>
        <div class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <h2 class="text-lg font-semibold mb-4">{{ lang.t('Project-wise Donations', 'প্রকল্প অনুযায়ী দান') }}</h2>
          <PieChart v-if="pieLabels.length" :labels="pieLabels" :data="pieData" />
          <p v-else class="text-sm" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('No data yet', 'কোনো ডেটা নেই') }}</p>
        </div>
      </div>

      <div class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h2 class="text-lg font-semibold mb-4">{{ lang.t('Recent Donations', 'সাম্প্রতিক দান') }}</h2>
        <table class="w-full text-sm">
          <thead><tr class="border-b text-left" :style="{ borderColor: 'var(--border)' }">
            <th class="py-2 px-3">{{ lang.t('Date', 'তারিখ') }}</th><th class="py-2 px-3">{{ lang.t('Donor', 'দাতা') }}</th><th class="py-2 px-3">{{ lang.t('Project', 'প্রকল্প') }}</th><th class="py-2 px-3">{{ lang.t('Amount', 'পরিমাণ') }}</th><th class="py-2 px-3">{{ lang.t('Status', 'স্ট্যাটাস') }}</th>
          </tr></thead>
          <tbody>
            <tr v-for="d in dashboard.recent_donations || []" :key="d.id" class="border-b" :style="{ borderColor: 'var(--border)' }">
              <td class="py-2 px-3 text-xs">{{ new Date(d.created_at).toLocaleDateString() }}</td>
              <td class="py-2 px-3">{{ d.user?.name || d.donor_name }}</td>
              <td class="py-2 px-3">{{ d.project?.title }}</td>
              <td class="py-2 px-3 font-medium">${{ Number(d.amount).toLocaleString() }}</td>
              <td class="py-2 px-3"><StatusBadge :status="d.status" /></td>
            </tr>
            <tr v-if="!(dashboard.recent_donations || []).length"><td colspan="5" class="text-center py-8" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('No donations yet.', 'এখনো কোনো দান নেই।') }}</td></tr>
          </tbody>
        </table>
      </div>

      <div class="flex flex-wrap gap-3 mt-6">
        <RouterLink to="/admin/projects" class="px-4 py-2 rounded font-semibold text-sm hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">+ {{ lang.t('New Project', 'নতুন প্রকল্প') }}</RouterLink>
        <RouterLink to="/admin/gallery" class="px-4 py-2 rounded font-semibold text-sm hover:opacity-90" :style="{ background: 'var(--accent)', color: 'var(--accent-ink)' }">+ {{ lang.t('Add Image', 'ছবি যোগ') }}</RouterLink>
        <RouterLink to="/admin/videos" class="px-4 py-2 rounded font-semibold text-sm hover:opacity-90" :style="{ background: 'var(--accent2)', color: 'var(--accent2-ink)' }">+ {{ lang.t('Add Video', 'ভিডিও যোগ') }}</RouterLink>
      </div>
    </template>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useLangStore } from '@/stores/lang'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'
import StatusBadge from '@/components/admin/StatusBadge.vue'
import PieChart from '@/components/charts/PieChart.vue'
import BarChart from '@/components/charts/BarChart.vue'

const admin = useAdminStore()
const lang = useLangStore()
const router = useRouter()
const breadcrumbs = [{ label: 'Dashboard', labelBn: 'ড্যাশবোর্ড' }]
const dashboard = ref({ stats: {} })
const loading = ref(true)

const pieLabels = computed(() => (dashboard.value.project_wise_donations || []).map(p => p.project_title || 'Unknown'))
const pieData = computed(() => (dashboard.value.project_wise_donations || []).map(p => p.total_donated || 0))
const barLabels = computed(() => (dashboard.value.monthly_donations || []).map(m => m.month))
const barDatasets = computed(() => [{
  label: lang.t('Donations', 'দান'),
  backgroundColor: 'var(--primary)',
  data: (dashboard.value.monthly_donations || []).map(m => m.total_amount || 0),
}])

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  try {
    const res = await admin.fetchDashboard()
    dashboard.value = res
  } finally { loading.value = false }
})
</script>
