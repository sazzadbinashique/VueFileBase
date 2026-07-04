<template>
  <Layout>
    <div class="max-w-xl mx-auto px-5 py-20 text-center">
      <div class="text-5xl mb-4" style="color: var(--accent)">&#9888;</div>
      <h1 class="font-display text-3xl font-semibold mb-4" :style="{ color: 'var(--ink)' }">
        {{ $t('payment_cancelled') }}
      </h1>
      <p class="mb-6" :style="{ color: 'var(--ink-soft)' }">
        {{ $t('payment_cancelled_msg') }}
      </p>
      <RouterLink to="/projects" class="inline-block px-6 py-3 rounded-full font-semibold"
        :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
        {{ $t('back_to_projects') }}
      </RouterLink>
    </div>
  </Layout>
</template>

<script setup>
import { useLangStore } from '@/stores/lang'
import Layout from '@/layouts/Layout.vue'

const lang = useLangStore()
</script>
