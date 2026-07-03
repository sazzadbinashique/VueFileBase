<template>
  <header class="bg-blue-700 text-white px-6 py-4 flex justify-between items-center">
    <RouterLink to="/">
      <img src="https://cdn-icons-png.flaticon.com/512/1055/1055688.png" alt="Logo" class="h-10 w-auto" />
    </RouterLink>
    <nav class="space-x-4">
      <RouterLink to="/" class="hover:underline">Home</RouterLink>
      <RouterLink to="/projects" class="hover:underline">Projects</RouterLink>
      <RouterLink to="/gallery" class="hover:underline">Gallery</RouterLink>
      <RouterLink to="/videos" class="hover:underline">Videos</RouterLink>
      <RouterLink to="/about" class="hover:underline">About</RouterLink>
      <template v-if="auth.isLoggedIn">
        <RouterLink to="/dashboard" class="hover:underline">Dashboard</RouterLink>
        <button @click="handleLogout" class="hover:underline">Logout</button>
      </template>
      <template v-else>
        <RouterLink to="/login" class="hover:underline">Login</RouterLink>
        <RouterLink to="/register" class="hover:underline">Register</RouterLink>
      </template>
    </nav>
  </header>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

async function handleLogout() {
  await auth.logout()
  router.push('/')
}
</script>
