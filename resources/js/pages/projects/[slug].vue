<template>
  <Layout>
    <div v-if="loading" class="text-center py-20">Loading project...</div>
    <div v-else-if="project" class="max-w-4xl mx-auto p-6">
      <RouterLink to="/projects" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Projects</RouterLink>
      <img :src="project.featured_image || 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800'" :alt="project.title" class="w-full h-64 md:h-96 object-cover rounded-lg mb-6" />
      <h1 class="text-3xl font-bold mb-4">{{ project.title }}</h1>
      <p class="text-gray-700 mb-6 whitespace-pre-line">{{ project.description }}</p>
      <div class="bg-gray-50 rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Fundraising Progress</h2>
        <div class="grid grid-cols-3 gap-4 mb-4 text-center">
          <div><p class="text-2xl font-bold text-blue-600">${{ Number(project.collected_amount).toLocaleString() }}</p><p class="text-sm text-gray-500">Raised</p></div>
          <div><p class="text-2xl font-bold text-gray-700">${{ Number(project.goal_amount).toLocaleString() }}</p><p class="text-sm text-gray-500">Goal</p></div>
          <div><p class="text-2xl font-bold text-green-600">{{ project.progress }}%</p><p class="text-sm text-gray-500">Progress</p></div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3"><div class="bg-blue-600 rounded-full h-3 transition-all" :style="{ width: project.progress + '%' }"></div></div>
      </div>
      <div v-if="auth.isLoggedIn" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Make a Donation</h2>
        <form @submit.prevent="handleDonate" class="space-y-4">
          <div><label class="block text-sm font-medium text-gray-700">Amount ($)</label><input v-model.number="donationAmount" type="number" min="1" step="0.01" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required /></div>
          <div><label class="block text-sm font-medium text-gray-700">Message (optional)</label><textarea v-model="donationMessage" rows="3" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
          <p v-if="donationError" class="text-red-600 text-sm">{{ donationError }}</p>
          <p v-if="donationSuccess" class="text-green-600 text-sm">Thank you! Your donation has been received.</p>
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Donate Now</button>
        </form>
      </div>
      <div v-else class="bg-gray-50 rounded-lg p-6 text-center"><p class="mb-4">Please <RouterLink to="/login" class="text-blue-600 hover:underline">sign in</RouterLink> to make a donation.</p></div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import Layout from '@/layouts/Layout.vue'

const route = useRoute()
const auth = useAuthStore()
const project = ref(null)
const loading = ref(true)
const donationAmount = ref(10)
const donationMessage = ref('')
const donationError = ref(null)
const donationSuccess = ref(false)

onMounted(async () => {
  try {
    const { data } = await axios.get(`/api/projects/${route.params.slug}`)
    project.value = data
  } catch (e) { project.value = null }
  finally { loading.value = false }
})

async function handleDonate() {
  donationError.value = null
  donationSuccess.value = false
  try {
    await axios.post('/api/donations', { project_id: project.value.id, amount: donationAmount.value, message: donationMessage.value })
    project.value.collected_amount = Number(project.value.collected_amount) + donationAmount.value
    donationSuccess.value = true
    donationAmount.value = 10
    donationMessage.value = ''
  } catch (err) { donationError.value = err.response?.data?.message || 'Donation failed' }
}
</script>
