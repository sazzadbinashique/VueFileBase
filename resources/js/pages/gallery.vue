<template>
  <Layout>
    <section class="max-w-7xl mx-auto px-5 md:px-8 pt-12 pb-4 text-center fb-reveal" v-reveal>
      <p class="font-mono text-xs uppercase tracking-widest mb-3" :style="{ color: 'var(--accent2)' }">{{ $t('moments_from_field') }}</p>
      <h1 class="font-display text-4xl font-semibold mb-4">{{ $t('gallery') }}</h1>
      <p class="max-w-xl mx-auto" :style="{ color: 'var(--ink-soft)' }">
        {{ $t('gallery_desc') }}
      </p>
    </section>

    <div class="max-w-7xl mx-auto px-5 md:px-8 pb-8 flex flex-wrap justify-center gap-2">
      <button v-for="(chip, i) in filters" :key="chip.id" type="button" @click="setFilter(chip.id)"
        class="px-4 py-2 rounded-full border text-sm font-medium transition"
        :style="activeFilter === chip.id
          ? { background: 'var(--accent2)', color: 'var(--accent2-ink)', borderColor: 'var(--accent2)' }
          : { borderColor: 'var(--border)', color: 'var(--ink)' }">
        {{ lang.t(chip.en) }}
      </button>
    </div>

    <section class="max-w-7xl mx-auto px-5 md:px-8 py-12">
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block w-8 h-8 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></div>
      </div>
      <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <button v-for="(img, i) in filteredImages" :key="img.id" type="button" @click="openLightbox(i)"
          class="fb-card fb-reveal group relative block w-full rounded-xl overflow-hidden" v-reveal
          :style="{ border: '1px solid var(--border)' }">
          <img :src="img.image_path" :alt="lang.f(img, 'title')"
            class="w-full h-full object-cover aspect-square group-hover:scale-105 transition-transform duration-500">
          <span class="absolute inset-x-0 bottom-0 p-2 text-xs bg-gradient-to-t from-black/70 to-transparent text-white text-left">
            {{ lang.f(img, 'title') }}
          </span>
        </button>
      </div>
      <div v-if="!filteredImages.length && !loading" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">
        {{ $t('no_images') }}
      </div>
    </section>
  </Layout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useLangStore } from '@/stores/lang'
import { useLightbox } from '@/composables/useLightbox'
import Layout from '@/layouts/Layout.vue'

const lang = useLangStore()
const lb = useLightbox()
const images = ref([])
const projects = ref([])
const loading = ref(true)
const activeFilter = ref('all')

const vReveal = {
  mounted(el) {
    if (!('IntersectionObserver' in window)) { el.classList.add('is-visible'); return }
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target) } })
    }, { threshold: 0.12 })
    io.observe(el)
  }
}

const filters = computed(() => {
  const all = { id: 'all', en: 'All', bn: 'সব' }
  const projFilters = projects.value.map(p => ({ id: p.id + '', en: p.title, bn: p.title_bn || p.title }))
  return [all, ...projFilters]
})

const filteredImages = computed(() => {
  if (activeFilter.value === 'all') return images.value
  return images.value.filter(img => img.project_id === parseInt(activeFilter.value) || img.project_id === activeFilter.value)
})

function setFilter(id) {
  activeFilter.value = id
}

function openLightbox(index) {
  lb.open(filteredImages.value, index)
}

onMounted(async () => {
  try {
    const [imgRes, projRes] = await Promise.all([
      axios.get('/api/gallery', { params: { per_page: 100 } }),
      axios.get('/api/projects', { params: { status: 'active', per_page: 100 } })
    ])
    images.value = imgRes.data.data || []
    projects.value = projRes.data.data || []
  } finally { loading.value = false }
})
</script>
