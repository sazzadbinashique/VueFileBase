<template>
  <AdminLayout>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">CMS Pages</h1>
      <button @click="openForm(null)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ New Page</button>
    </div>
    <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">{{ editing ? 'Edit Page' : 'New Page' }}</h2>
      <form @submit.prevent="savePage" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div><label class="block text-sm font-medium text-gray-700">Title</label><input v-model="form.title" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Status</label><select v-model="form.status" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><option value="draft">Draft</option><option value="published">Published</option></select></div>
          <div><label class="block text-sm font-medium text-gray-700">Meta Title</label><input v-model="form.meta_title" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" /></div>
          <div><label class="block text-sm font-medium text-gray-700">Meta Description</label><input v-model="form.meta_description" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" /></div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700">Content</label><textarea v-model="form.content" rows="12" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm" required></textarea></div>
        <div class="flex gap-2"><button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button><button type="button" @click="showForm = false" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">Cancel</button></div>
      </form>
    </div>
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b text-left"><th class="py-3 px-4">Title</th><th class="py-3 px-4">Slug</th><th class="py-3 px-4">Status</th><th class="py-3 px-4">Updated</th><th class="py-3 px-4">Actions</th></tr></thead>
        <tbody>
          <tr v-for="p in pages" :key="p.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-4 font-medium">{{ p.title }}</td>
            <td class="py-3 px-4 text-gray-500">/{{ p.slug }}</td>
            <td class="py-3 px-4"><span :class="p.status === 'published' ? 'text-green-600' : 'text-gray-500'">{{ p.status }}</span></td>
            <td class="py-3 px-4 text-gray-500">{{ new Date(p.updated_at).toLocaleDateString() }}</td>
            <td class="py-3 px-4 flex gap-2"><button @click="openForm(p)" class="text-blue-600 hover:underline text-xs">Edit</button><button @click="deletePage(p.id)" class="text-red-600 hover:underline text-xs">Delete</button></td>
          </tr>
        </tbody>
      </table>
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
const pages = ref([])
const showForm = ref(false)
const editing = ref(null)
const form = ref({ title: '', content: '', meta_title: '', meta_description: '', status: 'draft' })

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const data = await admin.fetchCmsPages()
  pages.value = data.data || []
}

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

async function savePage() {
  try {
    editing.value ? await admin.updateCmsPage(editing.value, form.value) : await admin.storeCmsPage(form.value)
    showForm.value = false
    await load()
  } catch (e) { alert('Error saving page') }
}

async function deletePage(id) {
  if (confirm('Delete this page?')) { await admin.deleteCmsPage(id); await load() }
}

onMounted(load)
</script>
