<template>
  <Layout>
    <div class="max-w-6xl mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6">Gallery</h1>
      <div v-if="loading" class="text-center py-12">Loading gallery...</div>
      <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div v-for="image in images" :key="image.id" class="group relative overflow-hidden rounded-lg shadow cursor-pointer" @click="lightboxImage = image">
          <img :src="image.image_path" :alt="image.title" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300" />
          <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-end p-3">
            <p class="text-white font-medium opacity-0 group-hover:opacity-100 transition">{{ image.title }}</p>
          </div>
        </div>
      </div>
      <div v-if="!images.length && !loading" class="text-center py-12 text-gray-500">No images in the gallery yet.</div>
    </div>
    <div v-if="lightboxImage" class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4" @click="lightboxImage = null">
      <img :src="lightboxImage.image_path" :alt="lightboxImage.title" class="max-w-full max-h-[90vh] rounded" />
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Layout from '@/layouts/Layout.vue'

const images = ref([])
const loading = ref(true)
const lightboxImage = ref(null)

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/gallery')
    images.value = data.data || []
  } finally { loading.value = false }
})
</script>
