<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-6xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ $t('donations') }}</h1>
        <a :href="`/api/admin/donations/export/csv?${exportParams}`"
          class="px-4 py-2 rounded font-semibold hover:opacity-90 whitespace-nowrap text-sm"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
          {{ $t('export_csv') }}
        </a>
      </div>

      <div class="flex flex-wrap gap-3 mb-4">
        <input v-model="filters.search" @input="debouncedLoad" :placeholder="$t('search_donor')"
          class="px-3 py-2 rounded border text-sm outline-none flex-1 min-w-[200px]"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }" />
        <select v-model="filters.status" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }">
          <option value="">{{ $t('all_status') }}</option>
          <option value="completed">Completed</option>
          <option value="pending">Pending</option>
          <option value="failed">Failed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <select v-model="filters.project_id" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }">
          <option value="">{{ $t('all_projects') }}</option>
          <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option>
        </select>
      </div>

      <div class="rounded-lg overflow-hidden" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <div v-if="loading" class="text-center py-12">
          <span class="inline-block w-6 h-6 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></span>
        </div>
        <table v-else class="w-full text-sm">
          <thead><tr class="border-b text-left" :style="{ borderColor: 'var(--border)' }">
            <th class="py-3 px-4">{{ $t('date') }}</th>
            <th class="py-3 px-4">{{ $t('donor') }}</th>
            <th class="py-3 px-4">{{ $t('project') }}</th>
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('amount')">{{ $t('amount') }}</th>
            <th class="py-3 px-4">{{ $t('transaction') }}</th>
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('status')">{{ $t('status') }}</th>
            <th class="py-3 px-4">{{ $t('actions') }}</th>
          </tr></thead>
          <tbody>
            <tr v-for="d in donations" :key="d.id" class="border-b hover:opacity-80" :style="{ borderColor: 'var(--border)' }">
              <td class="py-3 px-4 text-xs">{{ new Date(d.created_at).toLocaleDateString() }}</td>
              <td class="py-3 px-4">{{ d.user?.name || d.donor_name }}</td>
              <td class="py-3 px-4">{{ d.project?.title }}</td>
              <td class="py-3 px-4 font-medium">${{ Number(d.amount).toLocaleString() }}</td>
              <td class="py-3 px-4 text-xs font-mono" :style="{ color: 'var(--ink-soft)' }">{{ d.transaction_id }}</td>
              <td class="py-3 px-4"><StatusBadge :status="d.status" /></td>
              <td class="py-3 px-4">
                <button @click="viewDonation(d)" class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> {{ $t('view') }}</button>
              </td>
            </tr>
            <tr v-if="!donations.length"><td colspan="7" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">{{ $t('no_donations_found') }}</td></tr>
          </tbody>
        </table>
      </div>

      <div v-if="totalPages > 1" class="flex items-center justify-center gap-2 mt-4">
        <button @click="goPage(currentPage - 1)" :disabled="currentPage <= 1" class="px-3 py-1 rounded text-sm border hover:opacity-80 disabled:opacity-40" :style="{ borderColor: 'var(--border)' }">‹</button>
        <span class="text-sm" :style="{ color: 'var(--ink-soft)' }">{{ currentPage }} / {{ totalPages }}</span>
        <button @click="goPage(currentPage + 1)" :disabled="currentPage >= totalPages" class="px-3 py-1 rounded text-sm border hover:opacity-80 disabled:opacity-40" :style="{ borderColor: 'var(--border)' }">›</button>
      </div>

      <div v-if="viewing" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="viewing=null">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.5)"></div>
        <div class="relative rounded-xl p-6 max-w-lg w-full overflow-y-auto" :style="{ background: 'var(--surface)', color: 'var(--ink)', maxHeight: '80vh' }">
          <button @click="viewing=null" class="absolute top-3 right-4 text-2xl hover:opacity-70">&times;</button>
          <h2 class="text-xl font-bold mb-4">{{ $t('donation_details') }}</h2>
          <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt>{{ $t('donor') }}:</dt><dd>{{ viewing.user?.name || viewing.donor_name }}</dd></div>
            <div class="flex justify-between"><dt>{{ $t('email') }}:</dt><dd>{{ viewing.user?.email || viewing.donor_email }}</dd></div>
            <div class="flex justify-between"><dt>{{ $t('project') }}:</dt><dd>{{ viewing.project?.title }}</dd></div>
            <div class="flex justify-between"><dt>{{ $t('amount') }}:</dt><dd>${{ Number(viewing.amount).toLocaleString() }}</dd></div>
            <div class="flex justify-between"><dt>{{ $t('transaction_id') }}:</dt><dd class="font-mono text-xs">{{ viewing.transaction_id }}</dd></div>
            <div class="flex justify-between" v-if="viewing.bank_tran_id"><dt>Bank Tran ID:</dt><dd class="font-mono text-xs">{{ viewing.bank_tran_id }}</dd></div>
            <div class="flex justify-between" v-if="viewing.card_type"><dt>{{ $t('card') }}:</dt><dd>{{ viewing.card_type }}</dd></div>
            <div class="flex justify-between"><dt>{{ $t('status') }}:</dt><dd><StatusBadge :status="viewing.status" /></dd></div>
            <div class="flex justify-between"><dt>{{ $t('date') }}:</dt><dd>{{ new Date(viewing.created_at).toLocaleString() }}</dd></div>
            <div class="flex justify-between" v-if="viewing.message"><dt>{{ $t('message') }}:</dt><dd>{{ viewing.message }}</dd></div>
          </dl>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'
import StatusBadge from '@/components/admin/StatusBadge.vue'

const breadcrumbs = [{ label: 'Donations', labelBn: 'দান' }]
const admin = useAdminStore()
const router = useRouter()
const donations = ref([])
const projects = ref([])
const loading = ref(true)
const viewing = ref(null)
const currentPage = ref(1)
const lastResponse = ref(null)

const filters = reactive({
  search: '', status: '', project_id: '', sort_by: 'created_at', sort_dir: 'desc',
})

const totalPages = computed(() => lastResponse.value?.last_page || 1)

const exportParams = computed(() => {
  const params = new URLSearchParams()
  if (filters.status) params.set('status', filters.status)
  if (filters.project_id) params.set('project_id', filters.project_id)
  return params.toString()
})
let debounceTimer = null
function debouncedLoad() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(load, 300)
}

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  loading.value = true
  try {
    const params = { ...filters, page: currentPage.value, per_page: 15 }
    const data = await admin.fetchDonations(params)
    donations.value = data.data || []
    lastResponse.value = data
  } finally { loading.value = false }
}

function sortBy(col) {
  if (filters.sort_by === col) filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  else { filters.sort_by = col; filters.sort_dir = 'asc' }
  load()
}

function goPage(p) {
  if (p < 1 || p > totalPages.value) return
  currentPage.value = p; load()
}

function viewDonation(d) { viewing.value = d }

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const projRes = await admin.fetchProjects({ per_page: 100 })
  projects.value = projRes.data || []
  await load()
})
</script>
