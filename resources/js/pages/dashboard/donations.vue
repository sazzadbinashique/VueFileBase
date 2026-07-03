<template>
  <Layout>
    <div class="max-w-6xl mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6">My Donations</h1>
      <div v-if="loading" class="text-center py-12">Loading...</div>
      <div v-else class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
          <thead><tr class="bg-gray-50 border-b text-left"><th class="py-3 px-4">Date</th><th class="py-3 px-4">Project</th><th class="py-3 px-4">Amount</th><th class="py-3 px-4">Transaction ID</th><th class="py-3 px-4">Status</th></tr></thead>
          <tbody>
            <tr v-for="d in donations" :key="d.id" class="border-b hover:bg-gray-50">
              <td class="py-3 px-4">{{ new Date(d.created_at).toLocaleDateString() }}</td>
              <td class="py-3 px-4">{{ d.project?.title }}</td>
              <td class="py-3 px-4 font-medium">${{ Number(d.amount).toLocaleString() }}</td>
              <td class="py-3 px-4 text-gray-500 text-xs">{{ d.transaction_id }}</td>
              <td class="py-3 px-4"><span class="text-green-600">{{ d.status }}</span></td>
            </tr>
          </tbody>
        </table>
        <div v-if="!donations.length" class="text-center py-12 text-gray-500">No donations found.</div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Layout from '@/layouts/Layout.vue'

const auth = useAuthStore()
const router = useRouter()
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
