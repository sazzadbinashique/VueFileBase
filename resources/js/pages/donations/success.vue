<template>
  <Layout>
    <div class="max-w-xl mx-auto px-5 py-20 text-center">
      <div class="text-5xl mb-4" style="color: var(--primary)">&#10003;</div>
      <h1 class="font-display text-3xl font-semibold mb-4" :style="{ color: 'var(--ink)' }">
        {{ $t('payment_successful') }}
      </h1>
      <p class="mb-2" :style="{ color: 'var(--ink-soft)' }">
        {{ $t('thank_you_donation') }}
      </p>
      <p v-if="tranId" class="text-sm font-mono mb-6" :style="{ color: 'var(--primary)' }">
        {{ $t('transaction_id') }} {{ tranId }}
      </p>
      <div class="flex flex-wrap justify-center gap-3">
        <RouterLink to="/dashboard" class="px-6 py-3 rounded-full font-semibold"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
          {{ $t('view_dashboard') }}
        </RouterLink>
        <RouterLink to="/projects" class="px-6 py-3 rounded-full font-semibold"
          :style="{ border: '1px solid var(--border)', color: 'var(--ink)' }">
          {{ $t('more_projects') }}
        </RouterLink>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useLangStore } from '@/stores/lang'
import Layout from '@/layouts/Layout.vue'

const route = useRoute()
const lang = useLangStore()
const tranId = ref('')

onMounted(() => {
  tranId.value = route.query.tran_id || ''
})
</script>
