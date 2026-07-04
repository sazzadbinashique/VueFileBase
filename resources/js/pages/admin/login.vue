<template>
  <div class="min-h-screen flex items-center justify-center p-6" :style="{ background: 'var(--bg)' }">
    <div class="w-full max-w-md rounded-xl p-8" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
      <h1 class="text-2xl font-bold text-center mb-1" :style="{ color: 'var(--ink)' }">Admin Login</h1>
      <p class="text-sm text-center mb-6" :style="{ color: 'var(--ink-soft)' }">FutureBridge Foundation</p>
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1" :style="{ color: 'var(--ink)' }">Email</label>
          <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2 outline-none"
            :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1" :style="{ color: 'var(--ink)' }">Password</label>
          <input v-model="form.password" type="password" class="w-full border rounded px-3 py-2 outline-none"
            :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required />
        </div>
        <p v-if="error" class="text-sm" style="color: var(--accent2)">{{ error }}</p>
        <button type="submit" :disabled="loading"
          class="w-full py-3 rounded-lg font-semibold hover:opacity-90 transition disabled:opacity-50"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
      </form>
      <div class="flex items-center justify-center gap-4 mt-4 text-sm">
        <RouterLink to="/login" class="hover:underline" :style="{ color: 'var(--ink-soft)' }">User Login</RouterLink>
        <span :style="{ color: 'var(--border)' }">|</span>
        <RouterLink to="/register" class="hover:underline" :style="{ color: 'var(--primary)' }">Register</RouterLink>
      </div>
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
