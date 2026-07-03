<template>
  <Layout>
    <div class="min-h-[80vh] flex items-center justify-center p-6">
      <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Create Account</h2>
        <form @submit.prevent="handleRegister" class="space-y-4">
          <div><label class="block text-sm font-medium text-gray-700">Name</label><input v-model="form.name" type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Email</label><input v-model="form.email" type="email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Password</label><input v-model="form.password" type="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required minlength="8" /></div>
          <div><label class="block text-sm font-medium text-gray-700">Confirm Password</label><input v-model="form.password_confirmation" type="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <p v-if="error" class="text-red-600 text-sm">{{ error }}</p>
          <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Register</button>
        </form>
        <p class="text-center text-sm text-gray-600 mt-4">Already have an account? <RouterLink to="/login" class="text-blue-600 hover:underline">Sign In</RouterLink></p>
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
const form = reactive({ name: '', email: '', password: '', password_confirmation: '' })

async function handleRegister() {
  error.value = null
  try {
    await auth.register(form.name, form.email, form.password, form.password_confirmation)
    router.push('/dashboard')
  } catch (err) { error.value = err.response?.data?.message || 'Registration failed' }
}
</script>
