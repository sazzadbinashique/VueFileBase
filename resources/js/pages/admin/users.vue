<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-6xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ lang.t('Users', 'ব্যবহারকারী') }}</h1>
        <button @click="openForm(null)" class="px-4 py-2 rounded font-semibold hover:opacity-90 whitespace-nowrap"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">+ {{ lang.t('New User', 'নতুন ব্যবহারকারী') }}</button>
      </div>

      <!-- Filter Card -->
      <div class="rounded-lg p-4 mb-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <div class="flex flex-wrap gap-3">
          <input v-model="filters.search" @input="debouncedLoad" :placeholder="lang.t('Search name/email...', 'নাম/ইমেইল অনুসন্ধান...')"
            class="px-3 py-2 rounded border text-sm outline-none flex-1 min-w-[200px]"
            :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" />
          <select v-model="filters.role" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
            :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }">
            <option value="">{{ lang.t('All Roles', 'সব ভূমিকা') }}</option>
            <option v-for="r in roles" :key="r.id" :value="r.name">{{ r.label || r.name }}</option>
          </select>
          <select v-model="filters.status" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
            :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }">
            <option value="">{{ lang.t('All Status', 'সব স্ট্যাটাস') }}</option>
            <option value="1">{{ lang.t('Active', 'সক্রিয়') }}</option>
            <option value="0">{{ lang.t('Inactive', 'নিষ্ক্রিয়') }}</option>
          </select>
          <button @click="filters = { search: '', role: '', status: '', sort_by: 'created_at', sort_dir: 'desc' }; load()"
            class="px-3 py-2 rounded text-sm border hover:opacity-80"
            :style="{ borderColor: 'var(--border)', color: 'var(--ink-soft)' }">{{ lang.t('Clear', 'মুছুন') }}</button>
        </div>
      </div>

      <div v-if="showForm" class="rounded-lg p-6 mb-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h2 class="text-xl font-semibold mb-4">{{ editing ? lang.t('Edit User', 'ব্যবহারকারী সম্পাদনা') : lang.t('New User', 'নতুন ব্যবহারকারী') }}</h2>
        <form @submit.prevent="saveUser" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Name', 'নাম') }}</label><input v-model="form.name" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">Email</label><input v-model="form.email" type="email" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Password', 'পাসওয়ার্ড') }} <span v-if="editing" class="text-xs opacity-60">(leave blank to keep)</span></label><input v-model="form.password" type="password" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" :required="!editing" /></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Role', 'ভূমিকা') }}</label><select v-model="form.role" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option v-for="r in roles" :key="r.id" :value="r.name">{{ r.label || r.name }}</option></select></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Status', 'স্ট্যাটাস') }}</label><select v-model="form.status" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option :value="1">{{ lang.t('Active', 'সক্রিয়') }}</option><option :value="0">{{ lang.t('Inactive', 'নিষ্ক্রিয়') }}</option></select></div>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ lang.t('Save', 'সংরক্ষণ') }}</button>
            <button type="button" @click="showForm = false" class="px-6 py-2 rounded hover:opacity-90" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ lang.t('Cancel', 'বাতিল') }}</button>
          </div>
        </form>
      </div>

      <!-- Table Card -->
      <div class="rounded-lg" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <div class="flex items-center justify-between px-4 py-3" :style="{ borderBottom: '1px solid var(--border)' }">
          <span class="text-sm font-medium" :style="{ color: 'var(--ink-soft)' }">
            {{ lang.t('Total', 'মোট') }}: {{ lastResponse?.total || 0 }} {{ lang.t('users', 'ব্যবহারকারী') }}
          </span>
          <span class="text-xs" :style="{ color: 'var(--ink-soft)' }">
            {{ lang.t('Sorted by', 'সাজানো') }} {{ filters.sort_by }} ({{ filters.sort_dir }})
          </span>
        </div>
        <div v-if="loading" class="text-center py-12">
          <span class="inline-block w-6 h-6 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></span>
        </div>
        <table v-else class="w-full text-sm border-collapse">
          <thead>
            <tr :style="{ borderBottom: '2px solid var(--border)' }">
              <th class="py-3 px-4 text-left cursor-pointer select-none hover:opacity-70" @click="sortBy('name')">
                {{ lang.t('Name', 'নাম') }} <span v-if="filters.sort_by === 'name'" class="text-xs">{{ filters.sort_dir === 'asc' ? '▲' : '▼' }}</span>
              </th>
              <th class="py-3 px-4 text-left">{{ lang.t('Email', 'ইমেইল') }}</th>
              <th class="py-3 px-4 text-left">{{ lang.t('Role', 'ভূমিকা') }}</th>
              <th class="py-3 px-4 text-left">{{ lang.t('Donations', 'দান') }}</th>
              <th class="py-3 px-4 text-left">{{ lang.t('Status', 'স্ট্যাটাস') }}</th>
              <th class="py-3 px-4 text-left cursor-pointer select-none hover:opacity-70" @click="sortBy('created_at')">
                {{ lang.t('Joined', 'যোগদান') }} <span v-if="filters.sort_by === 'created_at'" class="text-xs">{{ filters.sort_dir === 'asc' ? '▲' : '▼' }}</span>
              </th>
              <th class="py-3 px-4 text-left">{{ lang.t('Actions', 'কর্ম') }}</th>
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
                <span
                  class="inline-flex items-center gap-1 text-xs"
                  :style="{ color: u.status ? 'var(--primary)' : 'var(--accent2)' }"
                >
                  <span class="w-1.5 h-1.5 rounded-full inline-block" :style="{ background: u.status ? 'var(--primary)' : 'var(--accent2)' }"></span>
                  {{ u.status ? lang.t('Active', 'সক্রিয়') : lang.t('Inactive', 'নিষ্ক্রিয়') }}
                </span>
              </td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ new Date(u.created_at).toLocaleDateString() }}</td>
              <td class="py-3 px-4">
                <div class="flex gap-3">
                  <button @click="viewUser(u)" class="text-xs hover:opacity-80 underline underline-offset-2" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('View', 'দেখুন') }}</button>
                  <button @click="openForm(u)" class="text-xs hover:opacity-80 underline underline-offset-2" :style="{ color: 'var(--primary)' }">{{ lang.t('Edit', 'সম্পাদনা') }}</button>
                  <button v-if="u.role !== 'admin'" @click="deleteUser(u.id)" class="text-xs hover:opacity-80 underline underline-offset-2" :style="{ color: 'var(--accent2)' }">{{ lang.t('Delete', 'মুছুন') }}</button>
                </div>
              </td>
            </tr>
            <tr v-if="!users.length">
              <td colspan="7" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('No users found.', 'কোনো ব্যবহারকারী পাওয়া যায়নি।') }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="flex items-center justify-center gap-3 mt-4">
        <button @click="goPage(currentPage - 1)" :disabled="currentPage <= 1"
          class="px-3 py-1.5 rounded text-sm border hover:opacity-80 disabled:opacity-40 transition-opacity"
          :style="{ borderColor: 'var(--border)', color: 'var(--ink)' }">‹ {{ lang.t('Prev', 'আগে') }}</button>
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
          :style="{ borderColor: 'var(--border)', color: 'var(--ink)' }">{{ lang.t('Next', 'পরবর্তী') }} ›</button>
      </div>

      <!-- View Modal -->
      <div v-if="viewing" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="viewing=null">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.5)"></div>
        <div class="relative rounded-xl p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto" :style="{ background: 'var(--surface)', color: 'var(--ink)' }">
          <button @click="viewing=null" class="absolute top-3 right-4 text-2xl hover:opacity-70">&times;</button>
          <h2 class="text-xl font-bold mb-4">{{ viewing.name }}</h2>
          <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt>Email:</dt><dd>{{ viewing.email }}</dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Role', 'ভূমিকা') }}:</dt><dd>{{ viewing.role }}</dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Phone', 'ফোন') }}:</dt><dd>{{ viewing.phone || '-' }}</dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Status', 'স্ট্যাটাস') }}:</dt><dd>{{ viewing.status ? 'Active' : 'Inactive' }}</dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Donations', 'দান') }}:</dt><dd>{{ viewing.donations_count || 0 }}</dd></div>
            <div class="flex justify-between"><dt>{{ lang.t('Joined', 'যোগদান') }}:</dt><dd>{{ new Date(viewing.created_at).toLocaleDateString() }}</dd></div>
          </dl>
          <h3 class="font-semibold mt-4 mb-2">{{ lang.t('Recent Donations', 'সাম্প্রতিক দান') }}</h3>
          <div v-if="viewing.donations?.length" class="space-y-1">
            <div v-for="d in viewing.donations.slice(0, 5)" :key="d.id" class="flex justify-between text-xs py-1 border-b" :style="{ borderColor: 'var(--border)' }">
              <span>{{ d.project?.title || '-' }}</span>
              <span>${{ Number(d.amount).toLocaleString() }}</span>
              <span :style="{ color: 'var(--ink-soft)' }">{{ new Date(d.created_at).toLocaleDateString() }}</span>
            </div>
          </div>
          <p v-else class="text-xs" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('No donations yet.', 'এখনো কোনো দান নেই।') }}</p>
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

const breadcrumbs = [{ label: 'Users', labelBn: 'ব্যবহারকারী' }]
const admin = useAdminStore()
const lang = useLangStore()
const router = useRouter()
const users = ref([])
const roles = ref([])
const loading = ref(true)
const showForm = ref(false)
const editing = ref(null)
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
const form = ref({ name: '', email: '', password: '', role: 'user', status: 1 })
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

function openForm(u) {
  if (u) {
    editing.value = u.id
    form.value = { name: u.name, email: u.email, password: '', role: u.role, status: u.status ? 1 : 0 }
  } else {
    editing.value = null
    form.value = { name: '', email: '', password: '', role: 'user', status: 1 }
  }
  showForm.value = true
}

function viewUser(u) { viewing.value = u }

async function saveUser() {
  try {
    if (editing.value) {
      const payload = { ...form.value }
      if (!payload.password) delete payload.password
      await admin.updateUser(editing.value, payload)
    } else {
      await admin.storeUser(form.value)
    }
    showForm.value = false; await load()
  } catch (e) { alert('Error saving user') }
}

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
