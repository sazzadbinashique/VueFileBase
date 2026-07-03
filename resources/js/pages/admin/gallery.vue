<template>
  <AdminLayout>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Gallery</h1>
      <button @click="openForm(null)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Add Image</button>
    </div>
    <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">{{ editing ? 'Edit Image' : 'Add Image' }}</h2>
      <form @submit.prevent="saveImage" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div><label class="block text-sm font-medium text-gray-700">Title</label><input v-model="form.title" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Image URL</label><input v-model="form.image_path" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Project</label><select v-model="form.project_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><option :value="null">-- None --</option><option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option></select></div>
          <div><label class="block text-sm font-medium text-gray-700">Sort Order</label><input v-model.number="form.sort_order" type="number" min="0" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" /></div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700">Description</label><textarea v-model="form.description" rows="3" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
        <div class="flex gap-2"><button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button><button type="button" @click="showForm = false" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">Cancel</button></div>
      </form>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div v-for="img in images" :key="img.id" class="bg-white rounded-lg shadow overflow-hidden group relative">
        <img :src="img.image_path" :alt="img.title" class="w-full h-32 object-cover" />
        <div class="p-2"><p class="text-sm font-medium truncate">{{ img.title }}</p><div class="flex gap-2 mt-1"><button @click="openForm(img)" class="text-blue-600 hover:underline text-xs">Edit</button><button @click="deleteImage(img.id)" class="text-red-600 hover:underline text-xs">Delete</button></div></div>
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
const images = ref([])
const projects = ref([])
const showForm = ref(false)
const editing = ref(null)
const form = ref({ title: '', description: '', image_path: '', project_id: null, sort_order: 0 })

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const [imgRes, projRes] = await Promise.all([admin.fetchGallery(), admin.fetchProjects({ per_page: 100 })])
  images.value = imgRes.data || []
  projects.value = projRes.data || []
}

function openForm(img) {
  if (img) {
    editing.value = img.id
    form.value = { title: img.title, description: img.description || '', image_path: img.image_path, project_id: img.project_id, sort_order: img.sort_order || 0 }
  } else {
    editing.value = null
    form.value = { title: '', description: '', image_path: '', project_id: null, sort_order: 0 }
  }
  showForm.value = true
}

async function saveImage() {
  try {
    editing.value ? await admin.updateGalleryImage(editing.value, form.value) : await admin.storeGalleryImage(form.value)
    showForm.value = false
    await load()
  } catch (e) { alert('Error saving image') }
}

async function deleteImage(id) {
  if (confirm('Delete this image?')) { await admin.deleteGalleryImage(id); await load() }
}

onMounted(load)
</script>
