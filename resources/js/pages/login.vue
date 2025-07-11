<template>
  <Layout>
  <div class="min-h-screen flex flex-col md:flex-row">
    <!-- Left side (Image or illustration) -->
    <div class="hidden md:flex md:w-1/2 bg-blue-600 items-center justify-center text-white p-10">
      <div class="text-center space-y-4">
        <h1 class="text-4xl font-bold">Welcome Back!</h1>
        <p class="text-lg">Please login to access your dashboard</p>
         <img
        src="https://picsum.photos/seed/login/600/400"
        alt="Login"
        class="w-2/3 mx-auto rounded"
        />
      </div>
    </div>

    <!-- Right side (Login form) -->
    <div class="flex flex-1 items-center justify-center p-6">
      <div class="w-full max-w-md space-y-6">
        <h2 class="text-2xl font-bold text-center">Login</h2>

        <form @submit.prevent="handleLogin" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input v-model="form.password" type="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required />
          </div>

          <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Sign In
          </button>
        </form>

        <p class="text-center text-sm text-gray-600">
          Forgot your password?
          <router-link to="/forgot-password" class="text-blue-600 hover:underline">Reset it</router-link>
        </p>
        <RouterLink
      to="/"
      class="inline-block mt-6 px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
    >
      ← Back to Home
    </RouterLink>
      </div>
    </div>
  </div>
  </Layout>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import Layout from '@/layouts/Layout.vue'

const router = useRouter()
const auth = useAuthStore()

const form = reactive({
  email: '',
  password: ''
})

const handleLogin = async () => {
  try {
    const response = await axios.post('/api/login', form)
    auth.setUser(response.data.user, response.data.token)
    router.push('/dashboard')
  } catch (err) {
    alert('Login failed: ' + err.response?.data?.message || err.message)
  }
}
</script>
