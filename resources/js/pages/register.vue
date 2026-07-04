<template>
  <Layout>
    <div class="min-h-[80vh] flex items-center justify-center p-6">
      <div class="w-full max-w-md rounded-xl p-8" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h1 class="text-2xl font-bold text-center mb-1" :style="{ color: 'var(--ink)' }">{{ $t('create_account') }}</h1>
        <p class="text-sm text-center mb-6" :style="{ color: 'var(--ink-soft)' }">{{ $t('join_difference') }}</p>
        <form @submit.prevent="handleRegister" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1" :style="{ color: 'var(--ink)' }">{{ $t('name') }}</label>
            <input v-model="form.name" type="text" required
              class="w-full px-4 py-3 rounded-lg outline-none"
              :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :style="{ color: 'var(--ink)' }">Email</label>
            <input v-model="form.email" type="email" required
              class="w-full px-4 py-3 rounded-lg outline-none"
              :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :style="{ color: 'var(--ink)' }">{{ $t('password') }}</label>
            <input v-model="form.password" type="password" required minlength="8"
              class="w-full px-4 py-3 rounded-lg outline-none"
              :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :style="{ color: 'var(--ink)' }">{{ $t('confirm_password') }}</label>
            <input v-model="form.password_confirmation" type="password" required
              class="w-full px-4 py-3 rounded-lg outline-none"
              :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
          </div>
          <p v-if="error" class="text-sm" style="color: var(--accent2)">{{ error }}</p>
          <button type="submit" :disabled="loading"
            class="w-full py-3 rounded-lg font-semibold hover:opacity-90 transition disabled:opacity-50"
            :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
            {{ loading ? $t('creating_account') : $t('create_account') }}
          </button>
        </form>
        <p class="text-center text-sm mt-4" :style="{ color: 'var(--ink-soft)' }">
          {{ $t('already_have_account') }}
          <RouterLink to="/login" class="font-semibold hover:underline" :style="{ color: 'var(--primary)' }">{{ $t('sign_in') }}</RouterLink>
        </p>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useLangStore } from '@/stores/lang'
import Layout from '@/layouts/Layout.vue'

const router = useRouter()
const auth = useAuthStore()
const lang = useLangStore()
const error = ref(null)
const loading = ref(false)
const form = reactive({ name: '', email: '', password: '', password_confirmation: '' })

async function handleRegister() {
  error.value = null
  loading.value = true
  try {
    await auth.register(form.name, form.email, form.password, form.password_confirmation)
    router.push('/dashboard')
  } catch (err) { error.value = err.response?.data?.message || lang.t('registration_failed') }
  finally { loading.value = false }
}
</script>
