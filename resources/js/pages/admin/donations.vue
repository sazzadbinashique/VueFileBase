<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-6xl">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ lang.t('Donations', 'দান') }}</h1>

      <div class="flex flex-wrap gap-3 mb-4">
        <input v-model="filters.search" @input="debouncedLoad" :placeholder="lang.t('Search donor/transaction...', 'দাতা/লেনদেন অনুসন্ধান...')"
          class="px-3 py-2 rounded border text-sm outline-none flex-1 min-w-[200px]"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }" />
        <select v-model="filters.status" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }">
          <option value="">{{ lang.t('All Status', 'সব স্ট্যাটাস') }}</option>
          <option value="completed">Completed</option>
          <option value="pending">Pending</option>
          <option value="failed">Failed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <select v-model="filters.project_id" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }">
          <option value="">{{ lang.t('All Projects', 'সব প্রকল্প') }}</option>
          <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option>
        </select>
      </div>

      <div class="rounded-lg overflow-hidden" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <div v-if="loading" class="text-center py-12">
          <span class="inline-block w-6 h-6 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></span>
        </div>
        <table v-else class="w-full text-sm">
          <thead><tr class="border-b text-left" :style="{ borderColor: 'var(--border)' }">
            <th class="py-3 px-4">{{ lang.t('Date', 'তারিখ') }}</th>
            <th class="py-3 px-4">{{ lang.t('Donor', 'দাতা') }}</th>
            <th class="py-3 px-4">{{ lang.t('Project', 'প্রকল্প') }}</th>
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('amount')">{{ lang.t('Amount', 'পরিমাণ') }}</th>
            <th class="py-3 px-4">{{ lang.t('Transaction', 'লেনদেন') }}</th>
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('status')">{{ lang.t('Status', 'স্ট্যাটাস') }}</th>
            <th class="py-3 px-4">{{ lang.t('Actions', 'কর্ম') }}</th>
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
                <button @click="viewDonation(d)" class="text-xs hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ lang.t('View', 'দেখুন') }}</button>
              </td>
            </tr>
            <tr v-if="!donations.length"><td colspan="7" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('No donations found.', 'কোনো দান পাওয়া যায়নি।') }}</td></tr>
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
        <div class="relative rounded-xl p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto" :style="{ background: 'var(--surface)', color: 'var(--ink)' }">
          <button @click="viewing=null" class="absolute top-3 right-4 text-2xl hover:opacity-70">&times;</button>
          <h2 class="text-xl font-bold mb-4">{{ lang.t('Donation Details', 'দানের বিবরণ') }}</h2>
          <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt>{{ lang.t('Donor', 'দাতা') }}:</dt><dd>{{ viewing.user?.name || viewing.donor_name }}</dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Email', 'ইমেইল') }}:</dt><dd>{{ viewing.user?.email || viewing.donor_email }}</dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Project', 'প্রকল্প') }}:</dt><dd>{{ viewing.project?.title }}</dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Amount', 'পরিমাণ') }}:</dt><dd>${{ Number(viewing.amount).toLocaleString() }}</dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Transaction ID', 'লেনদেন আইডি') }}:</dt><dd class="font-mono text-xs">{{ viewing.transaction_id }}</dd></div>
            <div class="flex justify-between" v-if="viewing.bank_tran_id"><dt>Bank Tran ID:</dt><dd class="font-mono text-xs">{{ viewing.bank_tran_id }}</dd></div>
            <div class="flex justify-between" v-if="viewing.card_type"><dt>{{ lang.t('Card', 'কার্ড') }}:</dt><dd>{{ viewing.card_type }}</dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Status', 'স্ট্যাটাস') }}:</dt><dd><StatusBadge :status="viewing.status" /></dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Date', 'তারিখ') }}:</dt><dd>{{ new Date(viewing.created_at).toLocaleString() }}</dd></div>
            <div class="flex justify-between" v-if="viewing.message"><dt>{{ lang.t('Message', 'বার্তা') }}:</dt><dd>{{ viewing.message }}</dd></div>
          </dl>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useLangStore } from '@/stores/lang'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'
import StatusBadge from '@/components/admin/StatusBadge.vue'

const breadcrumbs = [{ label: 'Donations', labelBn: 'দান' }]
const admin = useAdminStore()
const lang = useLangStore()
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
