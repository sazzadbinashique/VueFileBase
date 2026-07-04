<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-5xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ lang.t('CMS Pages', 'সিএমএস পৃষ্ঠা') }}</h1>
        <button @click="openForm(null)" class="px-4 py-2 rounded font-semibold hover:opacity-90 whitespace-nowrap"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">+ {{ lang.t('New Page', 'নতুন পৃষ্ঠা') }}</button>
      </div>

      <div class="flex flex-wrap gap-3 mb-4">
        <input v-model="filters.search" @input="debouncedLoad" :placeholder="lang.t('Search...', 'অনুসন্ধান...')"
          class="px-3 py-2 rounded border text-sm outline-none flex-1 min-w-[200px]"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }" />
        <select v-model="filters.status" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }">
          <option value="">{{ lang.t('All Status', 'সব স্ট্যাটাস') }}</option>
          <option value="published">Published</option>
          <option value="draft">Draft</option>
        </select>
      </div>

      <div v-if="showForm" class="rounded-lg p-6 mb-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h2 class="text-xl font-semibold mb-4">{{ editing ? lang.t('Edit Page', 'পৃষ্ঠা সম্পাদনা') : lang.t('New Page', 'নতুন পৃষ্ঠা') }}</h2>
        <form @submit.prevent="savePage" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Title', 'শিরোনাম') }}</label><input v-model="form.title" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Status', 'স্ট্যাটাস') }}</label><select v-model="form.status" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option value="draft">Draft</option><option value="published">Published</option></select></div>
            <div><label class="block text-sm font-medium mb-1">Meta Title</label><input v-model="form.meta_title" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">Meta Description</label><input v-model="form.meta_description" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
          </div>
          <div><label class="block text-sm font-medium mb-1">{{ lang.t('Content', 'কন্টেন্ট') }}</label><textarea v-model="form.content" rows="12" class="w-full border rounded px-3 py-2 outline-none font-mono text-sm" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required></textarea></div>
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
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('title')">{{ lang.t('Title', 'শিরোনাম') }}</th>
            <th class="py-3 px-4">{{ lang.t('Slug', 'স্লাগ') }}</th>
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('status')">{{ lang.t('Status', 'স্ট্যাটাস') }}</th>
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('created_at')">{{ lang.t('Updated', 'হালনাগাদ') }}</th>
            <th class="py-3 px-4">{{ lang.t('Actions', 'কর্ম') }}</th>
          </tr></thead>
          <tbody>
            <tr v-for="p in pages" :key="p.id" class="border-b hover:opacity-80" :style="{ borderColor: 'var(--border)' }">
              <td class="py-3 px-4 font-medium">{{ p.title }}</td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">/{{ p.slug }}</td>
              <td class="py-3 px-4"><StatusBadge :status="p.status" /></td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ new Date(p.updated_at).toLocaleDateString() }}</td>
              <td class="py-3 px-4 flex gap-3">
                <button @click="openForm(p)" class="text-xs hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ lang.t('Edit', 'সম্পাদনা') }}</button>
                <button @click="viewPage(p)" class="text-xs hover:opacity-80" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('View', 'দেখুন') }}</button>
                <button @click="deletePage(p.id)" class="text-xs hover:opacity-80" :style="{ color: 'var(--accent2)' }">{{ lang.t('Delete', 'মুছুন') }}</button>
              </td>
            </tr>
            <tr v-if="!pages.length"><td colspan="5" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('No pages found.', 'কোনো পৃষ্ঠা পাওয়া যায়নি।') }}</td></tr>
          </tbody>
        </table>
      </div>

      <div v-if="totalPages > 1" class="flex items-center justify-center gap-2 mt-4">
        <button @click="goPage(currentPage - 1)" :disabled="currentPage <= 1" class="px-3 py-1 rounded text-sm border hover:opacity-80 disabled:opacity-40" :style="{ borderColor: 'var(--border)' }">‹</button>
        <span class="text-sm" :style="{ color: 'var(--ink-soft)' }">{{ currentPage }} / {{ totalPages }}</span>
        <button @click="goPage(currentPage + 1)" :disabled="currentPage >= totalPages" class="px-3 py-1 rounded text-sm border hover:opacity-80 disabled:opacity-40" :style="{ borderColor: 'var(--border)' }">›</button>
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

const breadcrumbs = [{ label: 'CMS Pages', labelBn: 'সিএমএস পৃষ্ঠা' }]
const admin = useAdminStore()
const lang = useLangStore()
const router = useRouter()
const pages = ref([])
const loading = ref(true)
const showForm = ref(false)
const editing = ref(null)
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
    const data = await admin.fetchCmsPages(params)
    pages.value = data.data || []
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

const form = ref({ title: '', content: '', meta_title: '', meta_description: '', status: 'draft' })

function openForm(p) {
  if (p) {
    editing.value = p.id
    form.value = { title: p.title, content: p.content, meta_title: p.meta_title || '', meta_description: p.meta_description || '', status: p.status }
  } else {
    editing.value = null
    form.value = { title: '', content: '', meta_title: '', meta_description: '', status: 'draft' }
  }
  showForm.value = true
}

function viewPage(p) { showPageContent.value = p; currentPage.value = 1 }

async function savePage() {
  try {
    editing.value ? await admin.updateCmsPage(editing.value, form.value) : await admin.storeCmsPage(form.value)
    showForm.value = false; await load()
  } catch (e) { alert('Error saving page') }
}

async function deletePage(id) {
  if (confirm('Delete this page?')) { await admin.deleteCmsPage(id); await load() }
}

onMounted(load)
</script>
