<template>
  <Layout>
    <div class="max-w-4xl mx-auto p-6">
      <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-bold mb-4">About FutureBridge Foundation</h1>
        <p class="text-gray-700 mb-4">FutureBridge Foundation is a non-profit organization dedicated to building bridges to a better future. We focus on community development projects, education initiatives, and sustainable programs that create lasting impact.</p>
        <p class="text-gray-700 mb-4">Our mission is to empower communities through collaborative projects, transparent fundraising, and meaningful engagement with our donors and volunteers.</p>
        <h2 class="text-2xl font-semibold mt-8 mb-4">Our Impact</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-blue-50 rounded-lg p-6 text-center">
            <p class="text-4xl font-bold text-blue-600">{{ stats.total_projects }}</p>
            <p class="text-gray-600 mt-2">Projects</p>
          </div>
          <div class="bg-green-50 rounded-lg p-6 text-center">
            <p class="text-4xl font-bold text-green-600">${{ Number(stats.total_raised).toLocaleString() }}</p>
            <p class="text-gray-600 mt-2">Total Raised</p>
          </div>
          <div class="bg-purple-50 rounded-lg p-6 text-center">
            <p class="text-4xl font-bold text-purple-600">{{ stats.total_donors }}</p>
            <p class="text-gray-600 mt-2">Donors</p>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import axios from 'axios'
import Layout from '@/layouts/Layout.vue'

const stats = reactive({ total_projects: 0, total_raised: 0, total_donors: 0 })

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/projects')
    stats.total_projects = data.total || 0
  } catch (e) {}
})
</script>
