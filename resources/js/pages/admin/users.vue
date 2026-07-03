<template>
  <AdminLayout>
    <h1 class="text-3xl font-bold mb-6">Users</h1>
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b text-left"><th class="py-3 px-4">Name</th><th class="py-3 px-4">Email</th><th class="py-3 px-4">Role</th><th class="py-3 px-4">Status</th><th class="py-3 px-4">Donations</th><th class="py-3 px-4">Joined</th></tr></thead>
        <tbody>
          <tr v-for="u in users" :key="u.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-4 font-medium">{{ u.name }}</td>
            <td class="py-3 px-4">{{ u.email }}</td>
            <td class="py-3 px-4"><span :class="u.role === 'admin' ? 'text-purple-600' : 'text-gray-600'">{{ u.role }}</span></td>
            <td class="py-3 px-4"><span :class="u.status ? 'text-green-600' : 'text-red-600'">{{ u.status ? 'Active' : 'Inactive' }}</span></td>
            <td class="py-3 px-4">{{ u.donations_count || 0 }}</td>
            <td class="py-3 px-4 text-gray-500">{{ new Date(u.created_at).toLocaleDateString() }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="!users.length" class="text-center py-12 text-gray-500">No users found.</div>
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
const users = ref([])

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const data = await admin.fetchUsers()
  users.value = data.data || []
})
</script>
