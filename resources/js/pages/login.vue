<template>
  <Layout>
    <div class="min-h-[80vh] flex items-center justify-center p-6">
      <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Sign In</h2>
        <form @submit.prevent="handleLogin" class="space-y-4">
          <div><label class="block text-sm font-medium text-gray-700">Email</label><input v-model="form.email" type="email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Password</label><input v-model="form.password" type="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <p v-if="error" class="text-red-600 text-sm">{{ error }}</p>
          <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Sign In</button>
        </form>
        <p class="text-center text-sm text-gray-600 mt-4">Don't have an account? <RouterLink to="/register" class="text-blue-600 hover:underline">Register</RouterLink></p>
        <div class="text-center mt-2"><RouterLink to="/admin/login" class="text-sm text-gray-500 hover:underline">Admin Login</RouterLink></div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import Layout from '@/layouts/Layout.vue'

const router = useRouter()
const auth = useAuthStore()
const error = ref(null)
const form = reactive({ email: '', password: '' })

async function handleLogin() {
  error.value = null
  try {
    await auth.login(form.email, form.password)
    router.push('/dashboard')
  } catch (err) { error.value = err.response?.data?.message || 'Login failed' }
}
</script>
