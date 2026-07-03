<template>
  <div class="min-h-screen flex">
    <aside class="w-64 bg-gray-900 text-white flex flex-col">
      <div class="p-4 border-b border-gray-700">
        <RouterLink to="/admin/dashboard" class="text-xl font-bold">FBF Admin</RouterLink>
      </div>
      <nav class="flex-1 p-4 space-y-1">
        <RouterLink to="/admin/dashboard" class="block px-3 py-2 rounded hover:bg-gray-700" active-class="bg-gray-700">Dashboard</RouterLink>
        <RouterLink to="/admin/projects" class="block px-3 py-2 rounded hover:bg-gray-700" active-class="bg-gray-700">Projects</RouterLink>
        <RouterLink to="/admin/donations" class="block px-3 py-2 rounded hover:bg-gray-700" active-class="bg-gray-700">Donations</RouterLink>
        <RouterLink to="/admin/gallery" class="block px-3 py-2 rounded hover:bg-gray-700" active-class="bg-gray-700">Gallery</RouterLink>
        <RouterLink to="/admin/videos" class="block px-3 py-2 rounded hover:bg-gray-700" active-class="bg-gray-700">Videos</RouterLink>
        <RouterLink to="/admin/cms-pages" class="block px-3 py-2 rounded hover:bg-gray-700" active-class="bg-gray-700">CMS Pages</RouterLink>
        <RouterLink to="/admin/users" class="block px-3 py-2 rounded hover:bg-gray-700" active-class="bg-gray-700">Users</RouterLink>
      </nav>
      <div class="p-4 border-t border-gray-700">
        <button @click="handleLogout" class="w-full text-left px-3 py-2 rounded hover:bg-gray-700">Logout</button>
      </div>
    </aside>
    <main class="flex-1 bg-gray-100 p-6"><slot /></main>
  </div>
</template>

<script setup>
import { useAdminStore } from '@/stores/admin'
import { useRouter } from 'vue-router'

const admin = useAdminStore()
const router = useRouter()

function handleLogout() {
  admin.logout()
  router.push('/admin/login')
}
</script>
