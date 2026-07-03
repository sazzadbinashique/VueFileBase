<template>
  <AdminLayout>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Projects</h1>
      <button @click="openForm(null)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ New Project</button>
    </div>
    <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">{{ editing ? 'Edit Project' : 'New Project' }}</h2>
      <form @submit.prevent="saveProject" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div><label class="block text-sm font-medium text-gray-700">Title</label><input v-model="form.title" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Goal Amount ($)</label><input v-model.number="form.goal_amount" type="number" min="0" step="0.01" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Status</label><select v-model="form.status" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><option value="draft">Draft</option><option value="active">Active</option><option value="completed">Completed</option></select></div>
          <div><label class="block text-sm font-medium text-gray-700">Featured Image URL</label><input v-model="form.featured_image" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" /></div>
          <div><label class="block text-sm font-medium text-gray-700">Start Date</label><input v-model="form.start_date" type="date" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" /></div>
          <div><label class="block text-sm font-medium text-gray-700">End Date</label><input v-model="form.end_date" type="date" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" /></div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700">Description</label><textarea v-model="form.description" rows="5" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea></div>
        <div class="flex gap-2"><button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button><button type="button" @click="showForm = false" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">Cancel</button></div>
      </form>
    </div>
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b text-left"><th class="py-3 px-4">Title</th><th class="py-3 px-4">Goal</th><th class="py-3 px-4">Collected</th><th class="py-3 px-4">Status</th><th class="py-3 px-4">Donations</th><th class="py-3 px-4">Actions</th></tr></thead>
        <tbody>
          <tr v-for="p in projects" :key="p.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-4 font-medium">{{ p.title }}</td>
            <td class="py-3 px-4">${{ Number(p.goal_amount).toLocaleString() }}</td>
            <td class="py-3 px-4">${{ Number(p.collected_amount).toLocaleString() }}</td>
            <td class="py-3 px-4"><span :class="p.status === 'active' ? 'text-green-600' : p.status === 'completed' ? 'text-blue-600' : 'text-gray-500'">{{ p.status }}</span></td>
            <td class="py-3 px-4">{{ p.donations_count || 0 }}</td>
            <td class="py-3 px-4 flex gap-2"><button @click="openForm(p)" class="text-blue-600 hover:underline text-xs">Edit</button><button @click="deleteProject(p.id)" class="text-red-600 hover:underline text-xs">Delete</button></td>
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
const projects = ref([])
const showForm = ref(false)
const editing = ref(null)
const form = ref({ title: '', description: '', goal_amount: 0, featured_image: '', status: 'draft', start_date: '', end_date: '' })

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const data = await admin.fetchProjects()
  projects.value = data.data || []
}

function openForm(p) {
  if (p) {
    editing.value = p.id
    form.value = { title: p.title, description: p.description, goal_amount: p.goal_amount, featured_image: p.featured_image || '', status: p.status, start_date: p.start_date || '', end_date: p.end_date || '' }
  } else {
    editing.value = null
    form.value = { title: '', description: '', goal_amount: 0, featured_image: '', status: 'draft', start_date: '', end_date: '' }
  }
  showForm.value = true
}

async function saveProject() {
  try {
    editing.value ? await admin.updateProject(editing.value, form.value) : await admin.storeProject(form.value)
    showForm.value = false
    await load()
  } catch (e) { alert('Error saving project') }
}

async function deleteProject(id) {
  if (confirm('Delete this project?')) { await admin.deleteProject(id); await load() }
}

onMounted(load)
</script>
