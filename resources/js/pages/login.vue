<template>
  <Layout>
    <div class="min-h-[60vh] flex items-center justify-center p-6">
      <div class="w-full max-w-md rounded-xl p-8" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h1 class="text-2xl font-bold text-center mb-1" :style="{ color: 'var(--ink)' }">{{ $t('sign_in') }}</h1>
        <p class="text-sm text-center mb-6" :style="{ color: 'var(--ink-soft)' }">{{ $t('welcome_back') }}</p>
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
            {{ loading ? $t('signing_in') : $t('sign_in') }}
          </button>
        </form>
        <p class="text-center text-sm mt-4" :style="{ color: 'var(--ink-soft)' }">
          {{ $t('no_account') }}
          <RouterLink to="/register" class="font-semibold hover:underline" :style="{ color: 'var(--primary)' }">{{ $t('register') }}</RouterLink>
        </p>
        <RouterLink to="/admin/login" class="block text-center text-xs mt-2 hover:underline" :style="{ color: 'var(--ink-soft)' }">Admin Login</RouterLink>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useLangStore } from '@/stores/lang'
import Layout from '@/layouts/Layout.vue'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()
const lang = useLangStore()
const error = ref(null)
const loading = ref(false)
const form = reactive({ email: '', password: '' })

async function handleLogin() {
  error.value = null
  loading.value = true
  try {
    await auth.login(form.email, form.password)
    const redirect = route.query.redirect || '/dashboard'
    router.push(redirect)
  } catch (err) { error.value = err.response?.data?.message || 'Login failed' }
  finally { loading.value = false }
}
</script>
