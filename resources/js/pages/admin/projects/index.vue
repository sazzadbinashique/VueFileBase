<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-6xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ $t('projects') }}</h1>
        <RouterLink to="/admin/projects/create" class="px-4 py-2 rounded font-semibold hover:opacity-90 whitespace-nowrap inline-block"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">+ {{ $t('new_project') }}</RouterLink>
      </div>

      <div class="flex flex-wrap gap-3 mb-4">
        <input v-model="filters.search" @input="debouncedLoad" :placeholder="$t('search')"
          class="px-3 py-2 rounded border text-sm outline-none flex-1 min-w-[200px]"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }" />
        <select v-model="filters.status" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }">
          <option value="">{{ $t('all_status') }}</option>
          <option value="active">Active</option>
          <option value="draft">Draft</option>
          <option value="completed">Completed</option>
        </select>
      </div>

      <div class="rounded-lg overflow-hidden" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <div v-if="loading" class="text-center py-12">
          <span class="inline-block w-6 h-6 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></span>
        </div>
        <table v-else class="w-full text-sm">
          <thead><tr class="border-b text-left" :style="{ borderColor: 'var(--border)' }">
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('title')">{{ $t('title') }} <span v-if="filters.sort_by==='title'">{{ filters.sort_dir==='asc' ? '▲' : '▼' }}</span></th>
            <th class="py-3 px-4">{{ $t('goal') }}</th>
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('status')">{{ $t('status') }} <span v-if="filters.sort_by==='status'">{{ filters.sort_dir==='asc' ? '▲' : '▼' }}</span></th>
            <th class="py-3 px-4">{{ $t('created') }}</th>
            <th class="py-3 px-4">{{ $t('actions') }}</th>
          </tr></thead>
          <tbody>
            <tr v-for="p in projects" :key="p.id" class="border-b hover:opacity-80" :style="{ borderColor: 'var(--border)' }">
              <td class="py-3 px-4 font-medium">{{ p.title }}</td>
              <td class="py-3 px-4">${{ Number(p.goal_amount).toLocaleString() }}</td>
              <td class="py-3 px-4"><StatusBadge :status="p.status" /></td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ new Date(p.created_at).toLocaleDateString() }}</td>
              <td class="py-3 px-4">
                <div class="flex gap-2">
                  <button @click="viewProject(p)" class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> {{ $t('view') }}</button>
                  <RouterLink :to="'/admin/projects/edit/' + p.id" class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> {{ $t('edit') }}</RouterLink>
                  <button @click="deleteProject(p.id)" class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition" :style="{ background: 'var(--accent2)', color: 'var(--accent2-ink)' }"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg> {{ $t('delete') }}</button>
                </div>
              </td>
            </tr>
            <tr v-if="!projects.length"><td colspan="5" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">{{ $t('no_projects_admin') }}</td></tr>
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
        <div class="relative rounded-xl p-6 max-w-2xl w-full overflow-y-auto" :style="{ background: 'var(--surface)', color: 'var(--ink)', maxHeight: '80vh' }">
          <button @click="viewing=null" class="absolute top-3 right-4 text-2xl hover:opacity-70">&times;</button>
          <h2 class="text-2xl font-bold mb-2">{{ viewing.title }}</h2>
          <p v-if="viewing.title_bn" class="mb-4" :style="{ color: 'var(--ink-soft)' }">{{ viewing.title_bn }}</p>
          <img v-if="viewing.featured_image" :src="viewing.featured_image" class="w-full h-48 object-cover rounded mb-4" />
          <p class="mb-2">{{ viewing.description }}</p>
          <p class="text-sm mt-4" :style="{ color: 'var(--ink-soft)' }">{{ $t('goal') }}: ${{ Number(viewing.goal_amount).toLocaleString() }} | {{ $t('status') }}: {{ viewing.status }}</p>
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

const breadcrumbs = [{ label: 'Projects', labelBn: 'প্রকল্প' }]
const admin = useAdminStore()
const router = useRouter()
const projects = ref([])
const loading = ref(true)
const viewing = ref(null)
const currentPage = ref(1)
const lastResponse = ref(null)

const filters = reactive({ search: '', status: '', sort_by: 'created_at', sort_dir: 'desc' })
const totalPages = computed(() => lastResponse.value?.last_page || 1)

let debounceTimer = null
function debouncedLoad() { clearTimeout(debounceTimer); debounceTimer = setTimeout(load, 300) }

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  loading.value = true
  try {
    const params = { ...filters, page: currentPage.value, per_page: 15 }
    const data = await admin.fetchProjects(params)
    projects.value = data.data || []
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

function viewProject(p) { viewing.value = p }

async function deleteProject(id) {
  if (confirm($t('delete_project'))) { await admin.deleteProject(id); await load() }
}

onMounted(load)
</script>
