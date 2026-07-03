<template>
  <Layout>
    <div class="max-w-6xl mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6">Our Projects</h1>
      <div v-if="loading" class="text-center py-12">Loading projects...</div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="project in projects" :key="project.id" class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
          <img :src="project.featured_image || 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=400'" :alt="project.title" class="w-full h-48 object-cover" />
          <div class="p-4">
            <h3 class="text-xl font-semibold mb-2">{{ project.title }}</h3>
            <p class="text-gray-600 text-sm mb-4">{{ project.description?.substring(0, 150) }}...</p>
            <div class="mb-2">
              <div class="flex justify-between text-sm text-gray-600 mb-1"><span>Progress</span><span>{{ project.progress }}%</span></div>
              <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-blue-600 rounded-full h-2" :style="{ width: project.progress + '%' }"></div></div>
            </div>
            <div class="flex justify-between text-sm text-gray-500 mb-3">
              <span>${{ Number(project.collected_amount).toLocaleString() }} raised</span>
              <span>Goal: ${{ Number(project.goal_amount).toLocaleString() }}</span>
            </div>
            <RouterLink :to="'/projects/' + project.slug" class="inline-block bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition">View & Donate</RouterLink>
          </div>
        </div>
      </div>
      <div v-if="meta && meta.last_page > 1" class="flex justify-center gap-2 mt-8">
        <button v-for="page in meta.last_page" :key="page" @click="loadPage(page)" :class="['px-3 py-1 rounded', page === meta.current_page ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300']">{{ page }}</button>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Layout from '@/layouts/Layout.vue'

const projects = ref([])
const meta = ref(null)
const loading = ref(true)

async function loadPage(page = 1) {
  loading.value = true
  try {
    const { data } = await axios.get('/api/projects', { params: { page } })
    projects.value = data.data
    meta.value = { current_page: data.current_page, last_page: data.last_page }
  } finally { loading.value = false }
}

onMounted(() => loadPage())
</script>
