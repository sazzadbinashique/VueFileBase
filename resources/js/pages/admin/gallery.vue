<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-6xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ lang.t('Gallery', 'গ্যালারি') }}</h1>
        <button @click="openForm(null)" class="px-4 py-2 rounded font-semibold hover:opacity-90 whitespace-nowrap"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">+ {{ lang.t('Add Image', 'ছবি যোগ') }}</button>
      </div>

      <div class="flex flex-wrap gap-3 mb-4">
        <input v-model="filters.search" @input="debouncedLoad" :placeholder="lang.t('Search...', 'অনুসন্ধান...')"
          class="px-3 py-2 rounded border text-sm outline-none flex-1 min-w-[200px]"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }" />
        <select v-model="filters.project_id" @change="load" class="px-3 py-2 rounded border text-sm outline-none"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }">
          <option value="">{{ lang.t('All Projects', 'সব প্রকল্প') }}</option>
          <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option>
        </select>
      </div>

      <div v-if="showForm" class="rounded-lg p-6 mb-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h2 class="text-xl font-semibold mb-4">{{ editing ? lang.t('Edit Image', 'ছবি সম্পাদনা') : lang.t('Add Image', 'ছবি যোগ') }}</h2>
        <form @submit.prevent="saveImage" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">Title (EN)</label><input v-model="form.title" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">Title (BN)</label><input v-model="form.title_bn" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Image URL', 'ছবির URL') }}</label><input v-model="form.image_path" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Project', 'প্রকল্প') }}</label><select v-model="form.project_id" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option :value="null">-- None --</option><option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option></select></div>
          </div>
          <div><label class="block text-sm font-medium mb-1">Description (EN)</label><textarea v-model="form.description" rows="2" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"></textarea></div>
          <div><label class="block text-sm font-medium mb-1">Description (BN)</label><textarea v-model="form.description_bn" rows="2" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"></textarea></div>
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
        <div v-else-if="viewMode === 'grid'" class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4">
          <div v-for="img in images" :key="img.id" class="rounded-lg overflow-hidden group relative" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
            <img :src="img.image_path" :alt="img.title" class="w-full h-32 object-cover" />
            <div class="p-2">
              <p class="text-sm font-medium truncate">{{ img.title }}</p>
              <div class="flex gap-2 mt-1">
                <button @click="openForm(img)" class="text-xs hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ lang.t('Edit', 'সম্পাদনা') }}</button>
                <button @click="deleteImage(img.id)" class="text-xs hover:opacity-80" :style="{ color: 'var(--accent2)' }">{{ lang.t('Delete', 'মুছুন') }}</button>
              </div>
            </div>
          </div>
          <div v-if="!images.length" class="col-span-full text-center py-12" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('No images found.', 'কোনো ছবি পাওয়া যায়নি।') }}</div>
        </div>
        <table v-else class="w-full text-sm">
          <thead><tr class="border-b text-left" :style="{ borderColor: 'var(--border)' }">
            <th class="py-3 px-4">{{ lang.t('Image', 'ছবি') }}</th>
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('title')">{{ lang.t('Title', 'শিরোনাম') }}</th>
            <th class="py-3 px-4">{{ lang.t('Project', 'প্রকল্প') }}</th>
            <th class="py-3 px-4 cursor-pointer hover:opacity-70" @click="sortBy('created_at')">{{ lang.t('Created', 'তৈরি') }}</th>
            <th class="py-3 px-4">{{ lang.t('Actions', 'কর্ম') }}</th>
          </tr></thead>
          <tbody>
            <tr v-for="img in images" :key="img.id" class="border-b hover:opacity-80" :style="{ borderColor: 'var(--border)' }">
              <td class="py-3 px-4"><img :src="img.image_path" class="w-12 h-12 object-cover rounded" /></td>
              <td class="py-3 px-4 font-medium">{{ img.title }}</td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ img.project?.title }}</td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ new Date(img.created_at).toLocaleDateString() }}</td>
              <td class="py-3 px-4 flex gap-2">
                <button @click="openForm(img)" class="text-xs hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ lang.t('Edit', 'সম্পাদনা') }}</button>
                <button @click="deleteImage(img.id)" class="text-xs hover:opacity-80" :style="{ color: 'var(--accent2)' }">{{ lang.t('Delete', 'মুছুন') }}</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="flex items-center justify-between mt-4">
        <button @click="viewMode = viewMode === 'grid' ? 'table' : 'grid'" class="text-sm px-3 py-1 rounded border hover:opacity-80" :style="{ borderColor: 'var(--border)' }">
          {{ viewMode === 'grid' ? lang.t('Table View', 'টেবিল ভিউ') : lang.t('Grid View', 'গ্রিড ভিউ') }}
        </button>
        <div v-if="totalPages > 1" class="flex items-center gap-2">
          <button @click="goPage(currentPage - 1)" :disabled="currentPage <= 1" class="px-3 py-1 rounded text-sm border hover:opacity-80 disabled:opacity-40" :style="{ borderColor: 'var(--border)' }">‹</button>
          <span class="text-sm" :style="{ color: 'var(--ink-soft)' }">{{ currentPage }} / {{ totalPages }}</span>
          <button @click="goPage(currentPage + 1)" :disabled="currentPage >= totalPages" class="px-3 py-1 rounded text-sm border hover:opacity-80 disabled:opacity-40" :style="{ borderColor: 'var(--border)' }">›</button>
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

const breadcrumbs = [{ label: 'Gallery', labelBn: 'গ্যালারি' }]
const admin = useAdminStore()
const lang = useLangStore()
const router = useRouter()
const images = ref([])
const projects = ref([])
const loading = ref(true)
const showForm = ref(false)
const editing = ref(null)
const viewMode = ref('grid')
const currentPage = ref(1)
const lastResponse = ref(null)

const filters = reactive({ search: '', project_id: '', sort_by: 'sort_order', sort_dir: 'asc' })
const totalPages = computed(() => lastResponse.value?.last_page || 1)
let debounceTimer = null
function debouncedLoad() { clearTimeout(debounceTimer); debounceTimer = setTimeout(load, 300) }

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  loading.value = true
  try {
    const params = { ...filters, page: currentPage.value, per_page: 20 }
    const data = await admin.fetchGallery(params)
    images.value = data.data || []
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

function openForm(img) {
  if (img) {
    editing.value = img.id
    form.value = { title: img.title, title_bn: img.title_bn || '', description: img.description || '', description_bn: img.description_bn || '', image_path: img.image_path, project_id: img.project_id }
  } else {
    editing.value = null
    form.value = { title: '', title_bn: '', description: '', description_bn: '', image_path: '', project_id: null }
  }
  showForm.value = true
}

const form = ref({ title: '', title_bn: '', description: '', description_bn: '', image_path: '', project_id: null })

async function saveImage() {
  try {
    editing.value ? await admin.updateGalleryImage(editing.value, form.value) : await admin.storeGalleryImage(form.value)
    showForm.value = false; await load()
  } catch (e) { alert('Error saving image') }
}

async function deleteImage(id) {
  if (confirm('Delete this image?')) { await admin.deleteGalleryImage(id); await load() }
}

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const projRes = await admin.fetchProjects({ per_page: 100 })
  projects.value = projRes.data || []
  await load()
})
</script>
