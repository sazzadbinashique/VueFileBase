<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-6xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ lang.t('Videos', 'ভিডিও') }}</h1>
        <button @click="openForm(null)" class="px-4 py-2 rounded font-semibold hover:opacity-90 whitespace-nowrap"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">+ {{ lang.t('Add Video', 'ভিডিও যোগ') }}</button>
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
        <h2 class="text-xl font-semibold mb-4">{{ editing ? lang.t('Edit Video', 'ভিডিও সম্পাদনা') : lang.t('Add Video', 'ভিডিও যোগ') }}</h2>
        <form @submit.prevent="saveVideo" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">Title (EN)</label><input v-model="form.title" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">Title (BN)</label><input v-model="form.title_bn" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">Embed URL</label><input v-model="form.url" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Project', 'প্রকল্প') }}</label><select v-model="form.project_id" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option :value="null">-- None --</option><option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option></select></div>
          </div>
          <div><label class="block text-sm font-medium mb-1">Description (EN)</label><textarea v-model="form.description" rows="3" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"></textarea></div>
          <div><label class="block text-sm font-medium mb-1">Description (BN)</label><textarea v-model="form.description_bn" rows="3" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"></textarea></div>
          <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ lang.t('Save', 'সংরক্ষণ') }}</button>
            <button type="button" @click="showForm = false" class="px-6 py-2 rounded hover:opacity-90" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ lang.t('Cancel', 'বাতিল') }}</button>
          </div>
        </form>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-for="v in videos" :key="v.id" class="rounded-lg overflow-hidden" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <div class="aspect-video"><iframe :src="v.url" class="w-full h-full" frameborder="0" allowfullscreen></iframe></div>
          <div class="p-4">
            <h3 class="font-semibold">{{ v.title }}</h3>
            <p v-if="v.description" class="text-sm mt-1" :style="{ color: 'var(--ink-soft)' }">{{ v.description }}</p>
            <p v-if="v.project" class="text-xs mt-1 font-mono" :style="{ color: 'var(--primary)' }">{{ v.project.title }}</p>
            <div class="flex gap-2 mt-2">
              <button @click="openForm(v)" class="text-xs hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ lang.t('Edit', 'সম্পাদনা') }}</button>
              <button @click="deleteVideo(v.id)" class="text-xs hover:opacity-80" :style="{ color: 'var(--accent2)' }">{{ lang.t('Delete', 'মুছুন') }}</button>
            </div>
          </div>
        </div>
        <div v-if="!videos.length" class="col-span-full text-center py-12" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('No videos found.', 'কোনো ভিডিও পাওয়া যায়নি।') }}</div>
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

const breadcrumbs = [{ label: 'Videos', labelBn: 'ভিডিও' }]
const admin = useAdminStore()
const lang = useLangStore()
const router = useRouter()
const videos = ref([])
const projects = ref([])
const loading = ref(true)
const showForm = ref(false)
const editing = ref(null)
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
    const data = await admin.fetchVideos(params)
    videos.value = data.data || []
    lastResponse.value = data
  } finally { loading.value = false }
}

function goPage(p) {
  if (p < 1 || p > totalPages.value) return
  currentPage.value = p; load()
}

const form = ref({ title: '', title_bn: '', description: '', description_bn: '', url: '', project_id: null })

function openForm(v) {
  if (v) {
    editing.value = v.id
    form.value = { title: v.title, title_bn: v.title_bn || '', description: v.description || '', description_bn: v.description_bn || '', url: v.url, project_id: v.project_id }
  } else {
    editing.value = null
    form.value = { title: '', title_bn: '', description: '', description_bn: '', url: '', project_id: null }
  }
  showForm.value = true
}

async function saveVideo() {
  try {
    editing.value ? await admin.updateVideo(editing.value, form.value) : await admin.storeVideo(form.value)
    showForm.value = false; await load()
  } catch (e) { alert('Error saving video') }
}

async function deleteVideo(id) {
  if (confirm('Delete this video?')) { await admin.deleteVideo(id); await load() }
}

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const projRes = await admin.fetchProjects({ per_page: 100 })
  projects.value = projRes.data || []
  await load()
})
</script>
