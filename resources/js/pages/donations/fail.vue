<template>
  <Layout>
    <div class="max-w-xl mx-auto px-5 py-20 text-center">
      <div class="text-5xl mb-4" style="color: var(--accent2)">&#10007;</div>
      <h1 class="font-display text-3xl font-semibold mb-4" :style="{ color: 'var(--ink)' }">
        {{ lang.t('Payment Failed', 'পেমেন্ট ব্যর্থ') }}
      </h1>
      <p class="mb-2" :style="{ color: 'var(--ink-soft)' }">
        {{ lang.t('The payment could not be processed. Please try again.', 'পেমেন্ট প্রক্রিয়া করা যায়নি। আবার চেষ্টা করুন।') }}
      </p>
      <p class="text-sm mb-6" :style="{ color: 'var(--ink-soft)' }">
        {{ lang.t('No charges were made to your account.', 'আপনার অ্যাকাউন্ট থেকে কোনো চার্জ নেওয়া হয়নি।') }}
      </p>
      <RouterLink to="/projects" class="inline-block px-6 py-3 rounded-full font-semibold"
        :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
        {{ lang.t('Try Again', 'আবার চেষ্টা করুন') }}
      </RouterLink>
    </div>
  </Layout>
</template>

<script setup>
import { useLangStore } from '@/stores/lang'
import Layout from '@/layouts/Layout.vue'

const lang = useLangStore()
</script>
