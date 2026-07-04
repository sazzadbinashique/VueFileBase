<template>
  <Layout>
    <div class="max-w-xl mx-auto px-5 py-20 text-center">
      <div class="text-5xl mb-4" style="color: var(--accent2)">&#10007;</div>
      <h1 class="font-display text-3xl font-semibold mb-4" :style="{ color: 'var(--ink)' }">
        {{ $t('payment_failed') }}
      </h1>
      <p class="mb-2" :style="{ color: 'var(--ink-soft)' }">
        {{ $t('payment_failed_msg') }}
      </p>
      <p class="text-sm mb-6" :style="{ color: 'var(--ink-soft)' }">
        {{ $t('no_charges') }}
      </p>
      <RouterLink to="/projects" class="inline-block px-6 py-3 rounded-full font-semibold"
        :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
        {{ $t('try_again') }}
      </RouterLink>
    </div>
  </Layout>
</template>

<script setup>
import { useLangStore } from '@/stores/lang'
import Layout from '@/layouts/Layout.vue'

const lang = useLangStore()
</script>
