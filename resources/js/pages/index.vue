<template>
  <Layout>
    <!-- ================= HERO ================= -->
    <section class="relative overflow-hidden" :style="{ background: 'linear-gradient(180deg, var(--surface-2) 0%, var(--bg) 100%)' }">
      <div class="max-w-7xl mx-auto px-5 md:px-8 pt-14 pb-10 md:pt-20 md:pb-16 grid md:grid-cols-2 gap-10 items-center">
        <div class="fb-reveal" ref="heroRef" v-reveal>
          <p class="font-mono text-xs uppercase tracking-widest mb-4" :style="{ color: 'var(--primary)' }">
            {{ $t('registered_nonprofit') }}
          </p>
          <h1 class="font-display text-4xl md:text-5xl font-semibold leading-[1.1] mb-5 text-balance">
            {{ $t('building_bridges') }}
          </h1>
          <p class="text-lg mb-8 max-w-md" :style="{ color: 'var(--ink-soft)' }">
            {{ $t('mission_statement') }}
          </p>
          <div class="flex flex-wrap gap-3">
            <RouterLink to="/projects" class="px-6 py-3 rounded-full font-semibold hover:opacity-90 transition"
              :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
              {{ $t('see_our_projects') }}
            </RouterLink>
            <a href="#contact" @click.prevent="scrollToContact" class="px-6 py-3 rounded-full font-semibold transition"
              :style="{ border: '1px solid var(--border)', color: 'var(--ink)' }">
              {{ $t('get_in_touch') }}
            </a>
            <template v-if="!authStore.isLoggedIn">
              <RouterLink to="/login" class="px-6 py-3 rounded-full font-semibold transition hover:opacity-80"
                :style="{ border: '1px solid var(--border)', color: 'var(--ink-soft)' }">
                {{ $t('login') }}
              </RouterLink>
            </template>
          </div>
        </div>
        <div class="fb-reveal" ref="heroSvgRef" v-reveal aria-hidden="true">
          <svg viewBox="0 0 600 380" class="w-full h-auto">
            <circle cx="470" cy="90" r="46" fill="var(--accent)" opacity="0.9"/>
            <path d="M0 260 C 100 200, 200 180, 300 180 C 400 180, 500 200, 600 260 L600 380 L0 380 Z" fill="var(--surface-2)"/>
            <path d="M40 300 Q 300 140 560 300" fill="none" stroke="var(--primary)" stroke-width="10" stroke-linecap="round"/>
            <path d="M40 300 Q 300 140 560 300" fill="none" stroke="var(--accent2)" stroke-width="3" stroke-dasharray="2 10" stroke-linecap="round" opacity="0.7"/>
            <g stroke="var(--primary)" stroke-width="6" stroke-linecap="round">
              <line x1="120" y1="300" x2="120" y2="248"/>
              <line x1="220" y1="300" x2="220" y2="205"/>
              <line x1="320" y1="300" x2="320" y2="192"/>
              <line x1="420" y1="300" x2="420" y2="212"/>
              <line x1="500" y1="300" x2="500" y2="252"/>
            </g>
            <rect x="0" y="296" width="140" height="18" rx="4" fill="var(--ink)" opacity="0.85"/>
            <rect x="460" y="296" width="140" height="18" rx="4" fill="var(--ink)" opacity="0.85"/>
            <path d="M0 340 Q 150 320 300 340 T 600 340 L600 380 L0 380 Z" fill="var(--primary)" opacity="0.18"/>
          </svg>
        </div>
      </div>
      <div class="max-w-4xl mx-auto px-5 md:px-8 pb-14 fb-reveal" ref="formRef" v-reveal>
        <form @submit.prevent="handleDonate"
          class="rounded-2xl p-5 md:p-6 shadow-sm space-y-3"
          :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <div class="grid gap-3 md:grid-cols-[1.5fr_1fr]">
            <select v-model="selectedProject" required
              class="px-4 py-3 rounded-lg outline-none"
              :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
              <option value="">{{ $t('select_project') }}</option>
              <option v-for="p in projects" :key="p.id" :value="p.id">{{ lang.f(p, 'title') }}</option>
            </select>
            <div class="grid grid-cols-2 gap-2">
              <button type="button" @click="paymentCurrency = 'BDT'"
                class="py-3 rounded-lg border font-mono text-sm transition"
                :style="{ borderColor: paymentCurrency === 'BDT' ? 'var(--accent2)' : 'var(--border)', background: paymentCurrency === 'BDT' ? 'var(--accent2)' : 'transparent', color: paymentCurrency === 'BDT' ? 'var(--accent2-ink)' : 'var(--ink)' }">
                {{ $t('bdt') }}
              </button>
              <button type="button" @click="paymentCurrency = 'USD'"
                class="py-3 rounded-lg border font-mono text-sm transition"
                :style="{ borderColor: paymentCurrency === 'USD' ? 'var(--accent2)' : 'var(--border)', background: paymentCurrency === 'USD' ? 'var(--accent2)' : 'transparent', color: paymentCurrency === 'USD' ? 'var(--accent2-ink)' : 'var(--ink)' }">
                {{ $t('usd') }}
              </button>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <button v-for="amt in presets" :key="amt" type="button" @click="donationAmount = amt"
              class="py-3 rounded-lg border font-mono text-sm transition"
              :class="{ 'opacity-70': donationAmount !== amt }"
              :style="{ borderColor: donationAmount === amt ? 'var(--accent2)' : 'var(--border)', background: donationAmount === amt ? 'var(--accent2)' : 'transparent', color: donationAmount === amt ? 'var(--accent2-ink)' : 'var(--ink)' }">
              {{ paymentCurrency === 'BDT' ? '৳' : '$' }}{{ amt.toLocaleString() }}
            </button>
          </div>
          <input v-model.number="donationAmount" type="number" min="1"
            :placeholder="$t('custom_amount')"
            class="w-full px-4 py-3 rounded-lg outline-none"
            :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
          <button type="submit" :disabled="donating"
            class="w-full py-3 rounded-lg font-semibold hover:opacity-90 transition disabled:opacity-50"
            :style="{ background: 'var(--accent2)', color: 'var(--accent2-ink)' }">
            {{ donating ? $t('redirecting') : $t('donate_now') }}
          </button>
          <p v-if="donationError" class="text-sm text-center" style="color: var(--accent2)">{{ donationError }}</p>
        </form>
      </div>
    </section>

    <!-- ================= IMPACT STATS ================= -->
    <section :style="{ background: 'var(--surface)' }">
      <div class="max-w-7xl mx-auto px-5 md:px-8 py-10 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div v-for="stat in stats" :key="stat.key" class="fb-reveal" v-reveal>
          <p class="font-display text-3xl md:text-4xl font-bold" :style="{ color: stat.color }">{{ stat.value }}</p>
          <p class="font-mono text-xs uppercase tracking-wide mt-1" :style="{ color: 'var(--ink-soft)' }">{{ lang.t(stat.labelEn) }}</p>
        </div>
      </div>
    </section>

    <!-- ================= ABOUT ================= -->
    <section id="about" class="max-w-7xl mx-auto px-5 md:px-8 py-16 grid md:grid-cols-2 gap-10 items-center">
      <div class="fb-reveal order-2 md:order-1" v-reveal>
        <p class="font-mono text-xs uppercase tracking-widest mb-3" :style="{ color: 'var(--accent2)' }">{{ $t('who_we_are') }}</p>
        <h2 class="font-display text-3xl font-semibold mb-4">{{ $t('about_fbf') }}</h2>
        <p class="leading-relaxed mb-4" :style="{ color: 'var(--ink-soft)' }">
          {{ $t('about_description_1') }}
        </p>
        <p class="leading-relaxed" :style="{ color: 'var(--ink-soft)' }">
          {{ $t('about_description_2') }}
        </p>
      </div>
      <div class="fb-reveal order-1 md:order-2" v-reveal>
        <img src="https://placehold.co/700x520/0E6B5C/F3F7F1?text=Future+Bridge+Foundation" alt="Future Bridge Foundation team at work"
          class="w-full rounded-2xl fb-arch-top object-cover shadow-sm">
      </div>
    </section>

    <!-- ================= PROJECTS ================= -->
    <section id="projects" class="max-w-7xl mx-auto px-5 md:px-8 py-16">
      <div class="text-center max-w-2xl mx-auto mb-10 fb-reveal" v-reveal>
        <p class="font-mono text-xs uppercase tracking-widest mb-3" :style="{ color: 'var(--primary)' }">{{ $t('where_your_support_goes') }}</p>
        <h2 class="font-display text-3xl font-semibold">{{ $t('our_projects') }}</h2>
      </div>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
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
            <span class="inline-flex items-center gap-1 text-sm font-semibold" :style="{ color: 'var(--primary)' }">
              {{ $t('read_more') }} <span aria-hidden="true">&rarr;</span>
            </span>
          </div>
        </RouterLink>
      </div>
    </section>

    <!-- ================= VIDEOS PREVIEW ================= -->
    <section class="py-16" :style="{ background: 'var(--surface)' }">
      <div class="max-w-7xl mx-auto px-5 md:px-8">
        <div class="flex items-end justify-between mb-8 fb-reveal" v-reveal>
          <h2 class="font-display text-3xl font-semibold">{{ $t('our_videos') }}</h2>
          <RouterLink to="/videos" class="hidden sm:inline-block text-sm font-semibold" :style="{ color: 'var(--primary)' }">
            {{ $t('show_all_arrow') }}
          </RouterLink>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div v-for="v in videosPreview" :key="v.id"
            class="fb-card fb-reveal rounded-2xl overflow-hidden" v-reveal
            :style="{ background: 'var(--bg)', border: '1px solid var(--border)' }">
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
        <div class="text-center mt-8 sm:hidden">
          <RouterLink to="/videos" class="inline-block px-6 py-3 rounded-full font-semibold"
            :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
            {{ $t('show_all_videos') }}
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- ================= GALLERY PREVIEW ================= -->
    <section class="max-w-7xl mx-auto px-5 md:px-8 py-16">
      <div class="flex items-end justify-between mb-8 fb-reveal" v-reveal>
        <h2 class="font-display text-3xl font-semibold">{{ $t('gallery') }}</h2>
        <RouterLink to="/gallery" class="hidden sm:inline-block text-sm font-semibold" :style="{ color: 'var(--primary)' }">
          {{ $t('view_full_gallery_arrow') }}
        </RouterLink>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
        <button v-for="(img, i) in galleryPreview" :key="img.id" type="button" @click="openGalleryLightbox(i)"
          class="fb-card fb-reveal group relative block w-full rounded-xl overflow-hidden" v-reveal
          :style="{ border: '1px solid var(--border)' }">
          <img :src="img.image_path" :alt="lang.f(img, 'title')"
            class="w-full h-full object-cover aspect-square group-hover:scale-105 transition-transform duration-500">
          <span class="absolute inset-x-0 bottom-0 p-2 text-xs bg-gradient-to-t from-black/70 to-transparent text-white text-left">
            {{ lang.f(img, 'title') }}
          </span>
        </button>
      </div>
      <div class="text-center mt-8 sm:hidden">
        <RouterLink to="/gallery" class="inline-block px-6 py-3 rounded-full font-semibold"
          :style="{ background: 'var(--accent)', color: 'var(--accent-ink)' }">
          {{ $t('view_full_gallery') }}
        </RouterLink>
      </div>
    </section>

    <!-- ================= DONATE CTA ================= -->
    <section class="max-w-5xl mx-auto px-5 md:px-8 py-16 text-center fb-reveal" v-reveal>
      <h2 class="font-display text-3xl font-semibold mb-4">{{ $t('every_project_needs_a_hand') }}</h2>
      <p class="max-w-xl mx-auto mb-7" :style="{ color: 'var(--ink-soft)' }">
        {{ $t('donate_cta') }}
      </p>
      <RouterLink to="/projects" class="inline-block px-8 py-3.5 rounded-full font-semibold hover:opacity-90 transition"
        :style="{ background: 'var(--accent2)', color: 'var(--accent2-ink)' }">
        {{ $t('donate_to_project') }}
      </RouterLink>
    </section>
  </Layout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Layout from '@/layouts/Layout.vue'
import { useLangStore } from '@/stores/lang'
import { useThemeStore } from '@/stores/theme'
import { useAuthStore } from '@/stores/auth'

const lang = useLangStore()
const authStore = useAuthStore()
const router = useRouter()
useThemeStore()

const projects = ref([])
const videosPreview = ref([])
const galleryPreview = ref([])
const selectedProject = ref('')
const paymentCurrency = ref('BDT')
const donationAmount = ref(500)
const donating = ref(false)
const donationError = ref('')

const presets = computed(() => paymentCurrency.value === 'BDT' ? [500, 1500, 5000] : [5, 15, 50])

const stats = [
  { key: 'meals', value: '48,000+', labelEn: 'Meals served', labelBn: 'বেলা খাবার', color: 'var(--primary)' },
  { key: 'students', value: '1,150', labelEn: 'Students sponsored', labelBn: 'শিক্ষার্থী সহায়তা', color: 'var(--accent2)' },
  { key: 'patients', value: '9,400+', labelEn: 'Patients treated', labelBn: 'রোগী চিকিৎসা', color: 'var(--primary)' },
  { key: 'wells', value: '76', labelEn: 'Tube-wells installed', labelBn: 'নলকূপ স্থাপিত', color: 'var(--accent2)' },
]

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
    const [projRes, vidRes, galRes] = await Promise.all([
      axios.get('/api/projects', { params: { status: 'active', per_page: 100 } }),
      axios.get('/api/videos', { params: { per_page: 4 } }),
      axios.get('/api/gallery', { params: { per_page: 6 } })
    ])
    projects.value = projRes.data.data || []
    videosPreview.value = vidRes.data.data || []
    galleryPreview.value = galRes.data.data || []
  } catch (e) {}
})

async function handleDonate() {
  if (!selectedProject.value) { donationError.value = lang.t('select_project'); return }
  if (!donationAmount.value || donationAmount.value < 1) { donationError.value = lang.t('enter_amount'); return }
  donationError.value = ''
  if (!authStore.isLoggedIn) {
    router.push('/login?redirect=/')
    return
  }
  donating.value = true
  try {
    const { data } = await axios.post('/api/donations/initiate', {
      project_id: selectedProject.value,
      amount: donationAmount.value,
      currency: paymentCurrency.value,
    })
    if (data.redirect_url) {
      window.location.href = data.redirect_url
    } else {
      donationError.value = lang.t('initiation_failed')
    }
  } catch (e) {
    donationError.value = e.response?.data?.message || lang.t('something_wrong')
  } finally {
    donating.value = false
  }
}

function scrollToContact() {
  const el = document.getElementById('contact')
  if (el) el.scrollIntoView({ behavior: 'smooth' })
}

function openGalleryLightbox(index) {
  const lb = document.getElementById('fb-lightbox')
  if (!lb) return
  const imgs = galleryPreview.value.map(g => ({ ...g }))
  window._lightboxData = imgs
  window._lightboxIndex = index
  paintLightbox()
  lb.classList.add('open')
}
</script>
