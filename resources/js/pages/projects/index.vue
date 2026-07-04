<template>
  <Layout>
    <div class="max-w-7xl mx-auto px-5 md:px-8 pt-12 pb-4 text-center fb-reveal" v-reveal>
      <p class="font-mono text-xs uppercase tracking-widest mb-3" :style="{ color: 'var(--primary)' }">{{ $t('where_your_support_goes') }}</p>
      <h1 class="font-display text-4xl font-semibold mb-4">{{ $t('our_projects') }}</h1>
      <p class="max-w-xl mx-auto" :style="{ color: 'var(--ink-soft)' }">
        {{ $t('projects_desc') }}
      </p>
    </div>

    <section class="max-w-7xl mx-auto px-5 md:px-8 py-12">
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block w-8 h-8 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></div>
      </div>
      <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <RouterLink v-for="p in projects" :key="p.id" :to="'/projects/' + p.slug"
          class="fb-card fb-reveal group block rounded-2xl overflow-hidden" v-reveal
          :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <div class="relative h-48 overflow-hidden">
            <img :src="p.featured_image" :alt="lang.f(p, 'title')" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <span class="absolute top-3 left-3 font-mono text-[11px] uppercase tracking-wider px-2 py-1 rounded-full"
              :style="{ background: 'var(--surface)/90', color: 'var(--ink)' }">{{ $t('project') }}</span>
          </div>
          <div class="p-5">
            <h3 class="font-display text-xl font-semibold mb-2">{{ lang.f(p, 'title') }}</h3>
            <p class="text-sm leading-relaxed mb-4" :style="{ color: 'var(--ink-soft)' }">{{ lang.f(p, 'short') }}</p>
            <div class="flex gap-3 text-sm font-medium">
              <span class="inline-flex items-center gap-1" :style="{ color: 'var(--primary)' }">
                {{ $t('read_more') }} <span aria-hidden="true">&rarr;</span>
              </span>
            </div>
          </div>
        </RouterLink>
      </div>
      <div v-if="!projects.length && !loading" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">
        {{ $t('no_projects') }}
      </div>
    </section>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useLangStore } from '@/stores/lang'
import Layout from '@/layouts/Layout.vue'

const lang = useLangStore()
const projects = ref([])
const loading = ref(true)

const vReveal = {
  mounted(el) {
    if (!('IntersectionObserver' in window)) { el.classList.add('is-visible'); return }
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target) } })
    }, { threshold: 0.12 })
    io.observe(el)
  }
}

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/projects', { params: { status: 'active', per_page: 100 } })
    projects.value = data.data || []
  } finally { loading.value = false }
})
</script>
