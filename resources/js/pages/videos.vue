<template>
  <Layout>
    <div class="max-w-6xl mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6">Videos</h1>
      <div v-if="loading" class="text-center py-12">Loading videos...</div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="video in videos" :key="video.id" class="bg-white rounded-lg shadow overflow-hidden">
          <div class="aspect-video"><iframe :src="video.url" class="w-full h-full" frameborder="0" allowfullscreen></iframe></div>
          <div class="p-4">
            <h3 class="text-lg font-semibold">{{ video.title }}</h3>
            <p v-if="video.description" class="text-gray-600 text-sm mt-1">{{ video.description }}</p>
          </div>
        </div>
      </div>
      <div v-if="!videos.length && !loading" class="text-center py-12 text-gray-500">No videos available yet.</div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Layout from '@/layouts/Layout.vue'

const videos = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/videos')
    videos.value = data.data || []
  } finally { loading.value = false }
})
</script>
