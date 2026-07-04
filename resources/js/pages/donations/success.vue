<template>
  <Layout>
    <div class="max-w-xl mx-auto px-5 py-20 text-center">
      <div class="text-5xl mb-4" style="color: var(--primary)">&#10003;</div>
      <h1 class="font-display text-3xl font-semibold mb-4" :style="{ color: 'var(--ink)' }">
        {{ lang.t('Payment Successful!', 'পেমেন্ট সফল!') }}
      </h1>
      <p class="mb-2" :style="{ color: 'var(--ink-soft)' }">
        {{ lang.t('Thank you for your generous donation.', 'আপনার উদার অনুদানের জন্য ধন্যবাদ।') }}
      </p>
      <p v-if="tranId" class="text-sm font-mono mb-6" :style="{ color: 'var(--primary)' }">
        {{ lang.t('Transaction ID:', 'লেনদেন আইডি:') }} {{ tranId }}
      </p>
      <div class="flex flex-wrap justify-center gap-3">
        <RouterLink to="/dashboard" class="px-6 py-3 rounded-full font-semibold"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
          {{ lang.t('View Dashboard', 'ড্যাশবোর্ড দেখুন') }}
        </RouterLink>
        <RouterLink to="/projects" class="px-6 py-3 rounded-full font-semibold"
          :style="{ border: '1px solid var(--border)', color: 'var(--ink)' }">
          {{ lang.t('More Projects', 'আরও প্রকল্প') }}
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
