<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-6xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ lang.t('Projects', 'প্রকল্প') }}</h1>
        <button @click="openForm(null)" class="px-4 py-2 rounded font-semibold hover:opacity-90 whitespace-nowrap"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">+ {{ lang.t('New Project', 'নতুন প্রকল্প') }}</button>
      </div>

      <div class="flex flex-wrap gap-3 mb-4">
        <input v-model="filters.search" @input="debouncedLoad" :placeholder="lang.t('Search...', 'অনুসন্ধান...')"
          class="px-3 py-2 rounded border text-sm outline-none flex-1 min-w-[200px]"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }" />
        <select v-model="filters.status" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }">
          <option value="">{{ lang.t('All Status', 'সব স্ট্যাটাস') }}</option>
          <option value="active">Active</option>
          <option value="draft">Draft</option>
          <option value="completed">Completed</option>
        </select>
      </div>

      <div v-if="showForm" class="rounded-lg p-6 mb-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h2 class="text-xl font-semibold mb-4">{{ editing ? lang.t('Edit Project', 'প্রকল্প সম্পাদনা') : lang.t('New Project', 'নতুন প্রকল্প') }}</h2>
        <form @submit.prevent="saveProject" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">Title (EN)</label><input v-model="form.title" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">Title (BN)</label><input v-model="form.title_bn" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">Short (EN)</label><input v-model="form.short_en" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">Short (BN)</label><input v-model="form.short_bn" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Goal Amount ($)', 'লক্ষ্য ($)') }}</label><input v-model.number="form.goal_amount" type="number" min="0" step="0.01" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Status', 'স্ট্যাটাস') }}</label><select v-model="form.status" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option value="draft">Draft</option><option value="active">Active</option><option value="completed">Completed</option></select></div>
            <div><label class="block text-sm font-medium mb-1">Icon</label><select v-model="form.icon" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option value="utensils">Utensils</option><option value="book">Book</option><option value="heart-pulse">Heart Pulse</option><option value="droplet">Droplet</option></select></div>
            <div><label class="block text-sm font-medium mb-1">Color</label><select v-model="form.color" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option value="primary">Teal</option><option value="accent">Gold</option><option value="accent2">Crimson</option></select></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Featured Image URL', 'ছবির URL') }}</label><input v-model="form.featured_image" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">Start Date</label><input v-model="form.start_date" type="date" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">End Date</label><input v-model="form.end_date" type="date" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
          </div>
          <div><label class="block text-sm font-medium mb-1">Description (EN)</label><textarea v-model="form.description" rows="4" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required></textarea></div>
          <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ lang.t('Save', 'সংরক্ষণ') }}</button>
            <button type="button" @click="showForm = false" class="px-6 py-2 rounded hover:opacity-90" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ lang.t('Cancel', 'বাতিল') }}</button>
          </div>
        </form>
      </div>

      <div class="rounded-lg overflow-hidden" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <div v-if="loading" class="text-center py-12">
          <span class="inline-block w-6 h-6 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></span>
        </div>
        <table v-else class="w-full text-sm">
          <thead><tr class="border-b text-left" :style="{ borderColor: 'var(--border)' }">
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('title')">{{ lang.t('Title', 'শিরোনাম') }} <span v-if="filters.sort_by==='title'">{{ filters.sort_dir==='asc' ? '▲' : '▼' }}</span></th>
            <th class="py-3 px-4">{{ lang.t('Goal', 'লক্ষ্য') }}</th>
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('status')">{{ lang.t('Status', 'স্ট্যাটাস') }} <span v-if="filters.sort_by==='status'">{{ filters.sort_dir==='asc' ? '▲' : '▼' }}</span></th>
            <th class="py-3 px-4">{{ lang.t('Created', 'তৈরি') }}</th>
            <th class="py-3 px-4">{{ lang.t('Actions', 'কর্ম') }}</th>
          </tr></thead>
          <tbody>
            <tr v-for="p in projects" :key="p.id" class="border-b hover:opacity-80" :style="{ borderColor: 'var(--border)' }">
              <td class="py-3 px-4 font-medium">{{ p.title }}</td>
              <td class="py-3 px-4">${{ Number(p.goal_amount).toLocaleString() }}</td>
              <td class="py-3 px-4"><StatusBadge :status="p.status" /></td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ new Date(p.created_at).toLocaleDateString() }}</td>
              <td class="py-3 px-4 flex gap-3">
                <button @click="viewProject(p)" class="text-xs hover:opacity-80" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('View', 'দেখুন') }}</button>
                <button @click="openForm(p)" class="text-xs hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ lang.t('Edit', 'সম্পাদনা') }}</button>
                <button @click="deleteProject(p.id)" class="text-xs hover:opacity-80" :style="{ color: 'var(--accent2)' }">{{ lang.t('Delete', 'মুছুন') }}</button>
              </td>
            </tr>
            <tr v-if="!projects.length"><td colspan="5" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('No projects found.', 'কোনো প্রকল্প পাওয়া যায়নি।') }}</td></tr>
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
        <div class="relative rounded-xl p-6 max-w-2xl w-full max-h-[80vh] overflow-y-auto" :style="{ background: 'var(--surface)', color: 'var(--ink)' }">
          <button @click="viewing=null" class="absolute top-3 right-4 text-2xl hover:opacity-70">&times;</button>
          <h2 class="text-2xl font-bold mb-2">{{ viewing.title }}</h2>
          <p v-if="viewing.title_bn" class="mb-4" :style="{ color: 'var(--ink-soft)' }">{{ viewing.title_bn }}</p>
          <img v-if="viewing.featured_image" :src="viewing.featured_image" class="w-full h-48 object-cover rounded mb-4" />
          <p class="mb-2">{{ viewing.description }}</p>
          <p class="text-sm mt-4" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Goal', 'লক্ষ্য') }}: ${{ Number(viewing.goal_amount).toLocaleString() }} | {{ lang.t('Status', 'স্ট্যাটাস') }}: {{ viewing.status }}</p>
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

const breadcrumbs = [{ label: 'Projects', labelBn: 'প্রকল্প' }]
const admin = useAdminStore()
const lang = useLangStore()
const router = useRouter()
const projects = ref([])
const loading = ref(true)
const showForm = ref(false)
const editing = ref(null)
const viewing = ref(null)
const currentPage = ref(1)
const lastResponse = ref(null)

const filters = reactive({
  search: '', status: '', sort_by: 'created_at', sort_dir: 'desc',
})

const form = ref({
  title: '', title_bn: '', short_en: '', short_bn: '', description: '',
  goal_amount: 0, featured_image: '', status: 'draft', icon: 'utensils',
  color: 'primary', start_date: '', end_date: '',
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
    const data = await admin.fetchProjects(params)
    projects.value = data.data || []
    lastResponse.value = data
  } finally { loading.value = false }
}

function sortBy(col) {
  if (filters.sort_by === col) {
    filters.sort_dir = filters.sort_dir === 'asc' ? 'desc' : 'asc'
  } else {
    filters.sort_by = col
    filters.sort_dir = 'asc'
  }
  load()
}

function goPage(p) {
  if (p < 1 || p > totalPages.value) return
  currentPage.value = p
  load()
}

function openForm(p) {
  if (p) {
    editing.value = p.id
    form.value = {
      title: p.title, title_bn: p.title_bn || '', short_en: p.short_en || '', short_bn: p.short_bn || '',
      description: p.description, goal_amount: p.goal_amount, featured_image: p.featured_image || '',
      status: p.status, icon: p.icon || 'utensils', color: p.color || 'primary',
      start_date: p.start_date || '', end_date: p.end_date || '',
    }
  } else {
    editing.value = null
    form.value = { title: '', title_bn: '', short_en: '', short_bn: '', description: '', goal_amount: 0, featured_image: '', status: 'draft', icon: 'utensils', color: 'primary', start_date: '', end_date: '' }
  }
  showForm.value = true
}

function viewProject(p) { viewing.value = p }

async function saveProject() {
  try {
    editing.value ? await admin.updateProject(editing.value, form.value) : await admin.storeProject(form.value)
    showForm.value = false
    await load()
  } catch (e) { alert('Error saving project') }
}

async function deleteProject(id) {
  if (confirm(lang.t('Delete this project?', 'এই প্রকল্পটি মুছবেন?'))) {
    await admin.deleteProject(id); await load()
  }
}

onMounted(load)
</script>
