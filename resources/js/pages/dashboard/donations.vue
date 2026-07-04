<template>
  <Layout>
    <div class="max-w-6xl mx-auto px-5 py-8">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ $t('my_donations') }}</h1>
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block w-8 h-8 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></div>
      </div>
      <div v-else-if="donations.length" class="rounded-xl overflow-hidden" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left" :style="{ background: 'var(--surface)', borderBottom: '1px solid var(--border)' }">
              <th class="py-3 px-4 font-mono text-xs uppercase tracking-wider" :style="{ color: 'var(--ink-soft)' }">{{ $t('date') }}</th>
              <th class="py-3 px-4 font-mono text-xs uppercase tracking-wider" :style="{ color: 'var(--ink-soft)' }">{{ $t('project') }}</th>
              <th class="py-3 px-4 text-right font-mono text-xs uppercase tracking-wider" :style="{ color: 'var(--ink-soft)' }">{{ $t('amount') }}</th>
              <th class="py-3 px-4 font-mono text-xs uppercase tracking-wider hidden sm:table-cell" :style="{ color: 'var(--ink-soft)' }">{{ $t('transaction') }}</th>
              <th class="py-3 px-4 font-mono text-xs uppercase tracking-wider" :style="{ color: 'var(--ink-soft)' }">{{ $t('status') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="d in donations" :key="d.id" class="border-b" :style="{ borderColor: 'var(--border)' }">
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ new Date(d.created_at).toLocaleDateString() }}</td>
              <td class="py-3 px-4 font-medium" :style="{ color: 'var(--ink)' }">{{ d.project?.title }}</td>
              <td class="py-3 px-4 text-right font-mono" :style="{ color: 'var(--ink)' }">${{ Number(d.amount).toLocaleString() }}</td>
              <td class="py-3 px-4 text-xs font-mono hidden sm:table-cell" :style="{ color: 'var(--ink-soft)' }">{{ d.transaction_id }}</td>
              <td class="py-3 px-4"><StatusBadge :status="d.status" /></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="rounded-xl p-10 text-center" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <p :style="{ color: 'var(--ink-soft)' }">{{ $t('no_donations_found') }}</p>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { useLangStore } from '@/stores/lang'
import axios from 'axios'
import Layout from '@/layouts/Layout.vue'
import StatusBadge from '@/components/admin/StatusBadge.vue'

const auth = useAuthStore()
const router = useRouter()
const lang = useLangStore()
const donations = ref([])
const loading = ref(true)

onMounted(async () => {
  if (!auth.isLoggedIn) { router.push('/login'); return }
  try {
    const { data } = await axios.get('/api/donations')
    donations.value = data.data || []
  } finally { loading.value = false }
})
</script>
