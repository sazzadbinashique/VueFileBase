<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
      <h1 class="text-2xl font-bold text-center mb-2">Admin Login</h1>
      <p class="text-gray-500 text-center mb-6">FutureBridge Foundation</p>
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div><label class="block text-sm font-medium text-gray-700">Email</label><input v-model="form.email" type="email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
        <div><label class="block text-sm font-medium text-gray-700">Password</label><input v-model="form.password" type="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
        <p v-if="error" class="text-red-600 text-sm">{{ error }}</p>
        <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded hover:bg-blue-800 transition" :disabled="loading">{{ loading ? 'Signing in...' : 'Sign In' }}</button>
      </form>
      <RouterLink to="/login" class="block text-center text-sm text-gray-500 mt-4 hover:underline">User Login</RouterLink>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAdminStore } from '@/stores/admin'

const router = useRouter()
const admin = useAdminStore()
const error = ref(null)
const loading = ref(false)
const form = reactive({ email: '', password: '' })

async function handleLogin() {
  error.value = null
  loading.value = true
  try {
    await admin.login(form.email, form.password)
    router.push('/admin/dashboard')
  } catch (err) { error.value = err.response?.data?.message || 'Login failed' }
  finally { loading.value = false }
}
</script>
