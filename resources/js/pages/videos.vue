<template>
  <Layout>
    <PageBanner
      accent="primary"
      :eyebrow="bannerEyebrow"
      :title="bannerTitle"
      :description="bannerDescription"
    />

    <div class="max-w-7xl mx-auto px-5 md:px-8 pb-8 flex flex-wrap justify-center gap-2">
      <button v-for="(chip, i) in filters" :key="chip.id" type="button" @click="setFilter(chip.id)"
        class="px-4 py-2 rounded-full border text-sm font-medium transition"
        :style="activeFilter === chip.id
          ? { background: 'var(--primary)', color: 'var(--primary-ink)', borderColor: 'var(--primary)' }
          : { borderColor: 'var(--border)', color: 'var(--ink)' }">
        {{ lang.t(chip.en) }}
      </button>
    </div>

    <section class="max-w-7xl mx-auto px-5 md:px-8 py-12">
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block w-8 h-8 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></div>
      </div>
      <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="v in filteredVideos" :key="v.id"
          class="fb-card fb-reveal rounded-2xl overflow-hidden" v-reveal
          :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <div class="aspect-video">
            <iframe class="w-full h-full" :src="v.url" :title="lang.f(v, 'title')" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
          </div>
          <div class="p-4">
            <h3 class="font-display text-lg font-semibold mb-1">{{ lang.f(v, 'title') }}</h3>
            <p class="text-sm" :style="{ color: 'var(--ink-soft)' }">{{ lang.f(v, 'description') }}</p>
          </div>
        </div>
      </div>
      <div v-if="!filteredVideos.length && !loading" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">
        {{ $t('no_videos') }}
      </div>
    </section>
  </Layout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useLangStore } from '@/stores/lang'
import Layout from '@/layouts/Layout.vue'
import PageBanner from '@/components/frontend/PageBanner.vue'
import { fetchCmsPage, cmsText } from '@/composables/useCmsPage'

const lang = useLangStore()
const videos = ref([])
const projects = ref([])
const cmsPage = ref(null)
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

const filteredVideos = computed(() => {
  if (activeFilter.value === 'all') return videos.value
  return videos.value.filter(v => v.project_id === parseInt(activeFilter.value) || v.project_id === activeFilter.value)
})

function setFilter(id) {
  activeFilter.value = id
}

onMounted(async () => {
  try {
    const [vidRes, projRes, pageRes] = await Promise.all([
      axios.get('/api/videos', { params: { per_page: 100 } }),
      axios.get('/api/projects', { params: { status: 'active', per_page: 100 } }),
      fetchCmsPage('videos'),
    ])
    videos.value = vidRes.data.data || []
    projects.value = projRes.data.data || []
    cmsPage.value = pageRes
  } finally { loading.value = false }
})

const bannerEyebrow = computed(() =>
  cmsText(cmsPage.value, lang.locale, 'banner_eyebrow', 'banner_eyebrow_bn', lang.t('see_in_motion'))
)

const bannerTitle = computed(() =>
  cmsText(cmsPage.value, lang.locale, 'banner_title', 'banner_title_bn', lang.t('our_videos'))
)

const bannerDescription = computed(() =>
  cmsText(cmsPage.value, lang.locale, 'banner_description', 'banner_description_bn', lang.t('videos_desc'))
)
</script>
