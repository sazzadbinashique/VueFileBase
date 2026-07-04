<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-6xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ $t('users') }}</h1>
        <RouterLink to="/admin/users/create" class="px-4 py-2 rounded font-semibold hover:opacity-90 whitespace-nowrap inline-block"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">+ {{ $t('new_user') }}</RouterLink>
      </div>

      <div class="rounded-lg p-4 mb-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <div class="flex flex-wrap gap-3">
          <input v-model="filters.search" @input="debouncedLoad" :placeholder="$t('search_name_email')"
            class="px-3 py-2 rounded border text-sm outline-none flex-1 min-w-[200px]"
            :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" />
          <select v-model="filters.role" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
            :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }">
            <option value="">{{ $t('all_roles') }}</option>
            <option v-for="r in roles" :key="r.id" :value="r.name">{{ r.label || r.name }}</option>
          </select>
          <select v-model="filters.status" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
            :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }">
            <option value="">{{ $t('all_status') }}</option>
            <option value="1">{{ $t('active') }}</option>
            <option value="0">{{ $t('inactive') }}</option>
          </select>
          <button @click="filters = { search: '', role: '', status: '', sort_by: 'created_at', sort_dir: 'desc' }; load()"
            class="px-3 py-2 rounded text-sm border hover:opacity-80"
            :style="{ borderColor: 'var(--border)', color: 'var(--ink-soft)' }">{{ $t('clear') }}</button>
        </div>
      </div>

      <div class="rounded-lg" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <div class="flex items-center justify-between px-4 py-3" :style="{ borderBottom: '1px solid var(--border)' }">
          <span class="text-sm font-medium" :style="{ color: 'var(--ink-soft)' }">
            {{ $t('total') }}: {{ lastResponse?.total || 0 }} {{ $t('users_count') }}
          </span>
          <span class="text-xs" :style="{ color: 'var(--ink-soft)' }">
            {{ $t('sorted_by') }} {{ filters.sort_by }} ({{ filters.sort_dir }})
          </span>
        </div>
        <div v-if="loading" class="text-center py-12">
          <span class="inline-block w-6 h-6 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></span>
        </div>
        <table v-else class="w-full text-sm border-collapse">
          <thead>
            <tr :style="{ borderBottom: '2px solid var(--border)' }">
              <th class="py-3 px-4 text-left cursor-pointer select-none hover:opacity-70" @click="sortBy('name')">
                {{ $t('name') }} <span v-if="filters.sort_by === 'name'" class="text-xs">{{ filters.sort_dir === 'asc' ? '▲' : '▼' }}</span>
              </th>
              <th class="py-3 px-4 text-left">{{ $t('email') }}</th>
              <th class="py-3 px-4 text-left">{{ $t('role') }}</th>
              <th class="py-3 px-4 text-left">{{ $t('donations') }}</th>
              <th class="py-3 px-4 text-left">{{ $t('status') }}</th>
              <th class="py-3 px-4 text-left cursor-pointer select-none hover:opacity-70" @click="sortBy('created_at')">
                {{ $t('joined') }} <span v-if="filters.sort_by === 'created_at'" class="text-xs">{{ filters.sort_dir === 'asc' ? '▲' : '▼' }}</span>
              </th>
              <th class="py-3 px-4 text-left">{{ $t('actions') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(u, idx) in users"
              :key="u.id"
              class="transition-colors hover:opacity-90"
              :style="{ borderBottom: idx < users.length - 1 ? '1px solid var(--border)' : 'none', background: idx % 2 === 0 ? 'transparent' : 'var(--surface-2)' }"
            >
              <td class="py-3 px-4 font-medium">{{ u.name }}</td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ u.email }}</td>
              <td class="py-3 px-4">
                <span
                  class="inline-block px-2 py-0.5 rounded text-xs font-mono uppercase"
                  :style="{
                    background: u.role === 'admin' ? 'var(--accent2)' : u.role === 'manager' ? 'var(--accent)' : 'var(--primary)',
                    color: u.role === 'admin' ? 'var(--accent2-ink)' : u.role === 'manager' ? 'var(--accent-ink)' : 'var(--primary-ink)',
                  }"
                >{{ u.role }}</span>
              </td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ u.donations_count || 0 }}</td>
              <td class="py-3 px-4">
                <span class="inline-flex items-center gap-1 text-xs" :style="{ color: u.status ? 'var(--primary)' : 'var(--accent2)' }">
                  <span class="w-1.5 h-1.5 rounded-full inline-block" :style="{ background: u.status ? 'var(--primary)' : 'var(--accent2)' }"></span>
                  {{ u.status ? $t('active') : $t('inactive') }}
                </span>
              </td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ new Date(u.created_at).toLocaleDateString() }}</td>
              <td class="py-3 px-4">
                <div class="flex gap-2">
                  <button @click="viewUser(u)" class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> {{ $t('view') }}</button>
                  <RouterLink :to="'/admin/users/edit/' + u.id" class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> {{ $t('edit') }}</RouterLink>
                  <button v-if="u.role !== 'admin'" @click="deleteUser(u.id)" class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition" :style="{ background: 'var(--accent2)', color: 'var(--accent2-ink)' }"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg> {{ $t('delete') }}</button>
                </div>
              </td>
            </tr>
            <tr v-if="!users.length">
              <td colspan="7" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">{{ $t('no_users') }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="totalPages > 1" class="flex items-center justify-center gap-3 mt-4">
        <button @click="goPage(currentPage - 1)" :disabled="currentPage <= 1"
          class="px-3 py-1.5 rounded text-sm border hover:opacity-80 disabled:opacity-40 transition-opacity"
          :style="{ borderColor: 'var(--border)', color: 'var(--ink)' }">‹ {{ $t('prev') }}</button>
        <div class="flex items-center gap-1">
          <button
            v-for="p in paginationRange"
            :key="p"
            @click="goPage(p)"
            class="w-8 h-8 rounded text-sm font-medium transition-all"
            :style="{
              background: p === currentPage ? 'var(--primary)' : 'transparent',
              color: p === currentPage ? 'var(--primary-ink)' : 'var(--ink-soft)',
              border: p === currentPage ? 'none' : '1px solid var(--border)',
            }"
          >{{ p }}</button>
        </div>
        <button @click="goPage(currentPage + 1)" :disabled="currentPage >= totalPages"
          class="px-3 py-1.5 rounded text-sm border hover:opacity-80 disabled:opacity-40 transition-opacity"
          :style="{ borderColor: 'var(--border)', color: 'var(--ink)' }">{{ $t('next') }} ›</button>
      </div>

      <div v-if="viewing" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="viewing=null">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.5)"></div>
        <div class="relative rounded-xl p-6 max-w-lg w-full overflow-y-auto" :style="{ background: 'var(--surface)', color: 'var(--ink)', maxHeight: '80vh' }">
          <button @click="viewing=null" class="absolute top-3 right-4 text-2xl hover:opacity-70">&times;</button>
          <h2 class="text-xl font-bold mb-4">{{ viewing.name }}</h2>
          <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt>Email:</dt><dd>{{ viewing.email }}</dd></div>
            <div class="flex justify-between"><dt>{{ $t('role') }}:</dt><dd>{{ viewing.role }}</dd></div>
            <div class="flex justify-between"><dt>{{ $t('phone') }}:</dt><dd>{{ viewing.phone || '-' }}</dd></div>
            <div class="flex justify-between"><dt>{{ $t('status') }}:</dt><dd>{{ viewing.status ? 'Active' : 'Inactive' }}</dd></div>
            <div class="flex justify-between"><dt>{{ $t('donations') }}:</dt><dd>{{ viewing.donations_count || 0 }}</dd></div>
            <div class="flex justify-between"><dt>{{ $t('joined') }}:</dt><dd>{{ new Date(viewing.created_at).toLocaleDateString() }}</dd></div>
          </dl>
          <h3 class="font-semibold mt-4 mb-2">{{ $t('recent_donations') }}</h3>
          <div v-if="viewing.donations?.length" class="space-y-1">
            <div v-for="d in viewing.donations.slice(0, 5)" :key="d.id" class="flex justify-between text-xs py-1 border-b" :style="{ borderColor: 'var(--border)' }">
              <span>{{ d.project?.title || '-' }}</span>
              <span>${{ Number(d.amount).toLocaleString() }}</span>
              <span :style="{ color: 'var(--ink-soft)' }">{{ new Date(d.created_at).toLocaleDateString() }}</span>
            </div>
          </div>
          <p v-else class="text-xs" :style="{ color: 'var(--ink-soft)' }">{{ $t('no_donations') }}</p>
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

const breadcrumbs = [{ label: 'Users', labelBn: 'ব্যবহারকারী' }]
const admin = useAdminStore()
const router = useRouter()
const users = ref([])
const roles = ref([])
const loading = ref(true)
const viewing = ref(null)
const currentPage = ref(1)
const lastResponse = ref(null)

const filters = reactive({ search: '', role: '', status: '', sort_by: 'created_at', sort_dir: 'desc' })
const totalPages = computed(() => lastResponse.value?.last_page || 1)
const paginationRange = computed(() => {
  const total = totalPages.value
  const current = currentPage.value
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1)
  if (current <= 4) return [1, 2, 3, 4, 5, '...', total]
  if (current >= total - 3) return [1, '...', total - 4, total - 3, total - 2, total - 1, total]
  return [1, '...', current - 1, current, current + 1, '...', total]
})
let debounceTimer = null
function debouncedLoad() { clearTimeout(debounceTimer); debounceTimer = setTimeout(load, 300) }

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  loading.value = true
  try {
    const params = { ...filters, page: currentPage.value, per_page: 15 }
    const data = await admin.fetchUsers(params)
    users.value = data.data || []
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

function viewUser(u) { viewing.value = u }

async function deleteUser(id) {
  if (confirm('Delete this user?')) { await admin.deleteUser(id); await load() }
}

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const rolesRes = await admin.fetchRoles({ per_page: 50 })
  roles.value = rolesRes.data || []
  await load()
})
</script>
