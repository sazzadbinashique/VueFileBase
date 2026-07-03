<template>
  <AdminLayout>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Videos</h1>
      <button @click="openForm(null)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Add Video</button>
    </div>
    <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">{{ editing ? 'Edit Video' : 'Add Video' }}</h2>
      <form @submit.prevent="saveVideo" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div><label class="block text-sm font-medium text-gray-700">Title</label><input v-model="form.title" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Embed URL</label><input v-model="form.url" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Thumbnail URL</label><input v-model="form.thumbnail" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" /></div>
          <div><label class="block text-sm font-medium text-gray-700">Project</label><select v-model="form.project_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><option :value="null">-- None --</option><option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option></select></div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700">Description</label><textarea v-model="form.description" rows="3" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
        <div class="flex gap-2"><button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button><button type="button" @click="showForm = false" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">Cancel</button></div>
      </form>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div v-for="v in videos" :key="v.id" class="bg-white rounded-lg shadow overflow-hidden">
        <div class="aspect-video"><iframe :src="v.url" class="w-full h-full" frameborder="0" allowfullscreen></iframe></div>
        <div class="p-4"><h3 class="font-semibold">{{ v.title }}</h3><p v-if="v.description" class="text-sm text-gray-600 mt-1">{{ v.description }}</p><div class="flex gap-2 mt-2"><button @click="openForm(v)" class="text-blue-600 hover:underline text-xs">Edit</button><button @click="deleteVideo(v.id)" class="text-red-600 hover:underline text-xs">Delete</button></div></div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'

const admin = useAdminStore()
const router = useRouter()
const videos = ref([])
const projects = ref([])
const showForm = ref(false)
const editing = ref(null)
const form = ref({ title: '', description: '', url: '', thumbnail: '', project_id: null, sort_order: 0 })

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const [vidRes, projRes] = await Promise.all([admin.fetchVideos(), admin.fetchProjects({ per_page: 100 })])
  videos.value = vidRes.data || []
  projects.value = projRes.data || []
}

function openForm(v) {
  if (v) {
    editing.value = v.id
    form.value = { title: v.title, description: v.description || '', url: v.url, thumbnail: v.thumbnail || '', project_id: v.project_id, sort_order: v.sort_order || 0 }
  } else {
    editing.value = null
    form.value = { title: '', description: '', url: '', thumbnail: '', project_id: null, sort_order: 0 }
  }
  showForm.value = true
}

async function saveVideo() {
  try {
    editing.value ? await admin.updateVideo(editing.value, form.value) : await admin.storeVideo(form.value)
    showForm.value = false
    await load()
  } catch (e) { alert('Error saving video') }
}

async function deleteVideo(id) {
  if (confirm('Delete this video?')) { await admin.deleteVideo(id); await load() }
}

onMounted(load)
</script>
