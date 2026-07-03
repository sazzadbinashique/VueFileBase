<template>
  <AdminLayout>
    <h1 class="text-3xl font-bold mb-6">Donations</h1>
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead><tr class="bg-gray-50 border-b text-left"><th class="py-3 px-4">Date</th><th class="py-3 px-4">Donor</th><th class="py-3 px-4">Project</th><th class="py-3 px-4">Amount</th><th class="py-3 px-4">Status</th><th class="py-3 px-4">Transaction</th></tr></thead>
        <tbody>
          <tr v-for="d in donations" :key="d.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-4">{{ new Date(d.created_at).toLocaleDateString() }}</td>
            <td class="py-3 px-4">{{ d.user?.name || d.donor_name }}</td>
            <td class="py-3 px-4">{{ d.project?.title }}</td>
            <td class="py-3 px-4 font-medium">${{ Number(d.amount).toLocaleString() }}</td>
            <td class="py-3 px-4"><span class="text-green-600">{{ d.status }}</span></td>
            <td class="py-3 px-4 text-xs text-gray-500">{{ d.transaction_id }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="!donations.length" class="text-center py-12 text-gray-500">No donations yet.</div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'

const admin = useAdminStore()
const router = useRouter()
const donations = ref([])

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const data = await admin.fetchDonations()
  donations.value = data.data || []
})
</script>
