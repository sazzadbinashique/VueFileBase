<template>
  <Layout>
    <!-- ================= HERO ================= -->
    <section class="relative overflow-hidden" :style="{ background: 'linear-gradient(180deg, var(--surface-2) 0%, var(--bg) 100%)' }">
      <div class="max-w-7xl mx-auto px-5 md:px-8 pt-14 pb-10 md:pt-20 md:pb-16 grid md:grid-cols-2 gap-10 items-center">
        <div class="fb-reveal" ref="heroRef" v-reveal>
          <p class="font-mono text-xs uppercase tracking-widest mb-4" :style="{ color: 'var(--primary)' }">
            {{ lang.t('Registered non-profit · Bangladesh', 'নিবন্ধিত অলাভজনক সংস্থা · বাংলাদেশ') }}
          </p>
          <h1 class="font-display text-4xl md:text-5xl font-semibold leading-[1.1] mb-5 text-balance">
            {{ lang.t('Building bridges between hope and opportunity', 'আশা আর সুযোগের মাঝে সেতুবন্ধন গড়ি') }}
          </h1>
          <p class="text-lg mb-8 max-w-md" :style="{ color: 'var(--ink-soft)' }">
            {{ lang.t('Food, education, healthcare and clean water for families across Bangladesh who need a hand up, not a hand-out.', 'বাংলাদেশজুড়ে সেই পরিবারগুলোর জন্য খাদ্য, শিক্ষা, স্বাস্থ্যসেবা আর নিরাপদ পানি, যাদের প্রয়োজন একটু সহায়তার হাত।') }}
          </p>
          <div class="flex flex-wrap gap-3">
            <RouterLink to="/projects" class="px-6 py-3 rounded-full font-semibold hover:opacity-90 transition"
              :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
              {{ lang.t('See our projects', 'প্রকল্পসমূহ দেখুন') }}
            </RouterLink>
            <a href="#contact" @click.prevent="scrollToContact" class="px-6 py-3 rounded-full font-semibold transition"
              :style="{ border: '1px solid var(--border)', color: 'var(--ink)' }">
              {{ lang.t('Get in touch', 'যোগাযোগ করুন') }}
            </a>
            <template v-if="!authStore.isLoggedIn">
              <RouterLink to="/register" class="px-6 py-3 rounded-full font-semibold hover:opacity-90 transition"
                :style="{ background: 'var(--accent)', color: 'var(--accent-ink)' }">
                {{ lang.t('Join Us', 'যোগ দিন') }}
              </RouterLink>
              <RouterLink to="/login" class="px-6 py-3 rounded-full font-semibold transition hover:opacity-80"
                :style="{ border: '1px solid var(--border)', color: 'var(--ink-soft)' }">
                {{ lang.t('Login', 'লগইন') }}
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
        <form @submit.prevent="handleQuickContact"
          class="rounded-2xl p-5 md:p-6 shadow-sm grid gap-3 md:grid-cols-[1fr_1fr_1.4fr_auto]"
          :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <input v-model="quickForm.name" :placeholder="lang.t('Your name', 'আপনার নাম')" required
            class="px-4 py-3 rounded-lg outline-none" :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
          <input v-model="quickForm.email" type="email" :placeholder="lang.t('Your email', 'আপনার ইমেইল')" required
            class="px-4 py-3 rounded-lg outline-none" :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
          <input v-model="quickForm.message" :placeholder="lang.t('How can we help?', 'আমরা কীভাবে সাহায্য করতে পারি?')" required
            class="px-4 py-3 rounded-lg outline-none" :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
          <button type="submit" class="px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition whitespace-nowrap"
            :style="{ background: 'var(--accent2)', color: 'var(--accent2-ink)' }">
            {{ lang.t('Send', 'পাঠান') }}
          </button>
        </form>
      </div>
    </section>

    <div class="fb-divider"></div>

    <!-- ================= IMPACT STATS ================= -->
    <section :style="{ background: 'var(--surface)' }">
      <div class="max-w-7xl mx-auto px-5 md:px-8 py-10 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div v-for="stat in stats" :key="stat.key" class="fb-reveal" v-reveal>
          <p class="font-display text-3xl md:text-4xl font-bold" :style="{ color: stat.color }">{{ stat.value }}</p>
          <p class="font-mono text-xs uppercase tracking-wide mt-1" :style="{ color: 'var(--ink-soft)' }">{{ lang.t(stat.labelEn, stat.labelBn) }}</p>
        </div>
      </div>
    </section>

    <div class="fb-divider flip" :style="{ backgroundImage: 'radial-gradient(circle at 11px 0, transparent 11px, var(--accent) 11.5px, var(--accent) 12px, transparent 12.5px)' }"></div>

    <!-- ================= ABOUT ================= -->
    <section id="about" class="max-w-7xl mx-auto px-5 md:px-8 py-16 grid md:grid-cols-2 gap-10 items-center">
      <div class="fb-reveal order-2 md:order-1" v-reveal>
        <p class="font-mono text-xs uppercase tracking-widest mb-3" :style="{ color: 'var(--accent2)' }">{{ lang.t('Who we are', 'আমরা কারা') }}</p>
        <h2 class="font-display text-3xl font-semibold mb-4">{{ lang.t('About Future Bridge Foundation', 'ফিউচার ব্রিজ ফাউন্ডেশন সম্পর্কে') }}</h2>
        <p class="leading-relaxed mb-4" :style="{ color: 'var(--ink-soft)' }">
          {{ lang.t(
            'We are a Bangladesh-based non-profit working where the need is greatest — flood-prone char lands, low-income urban slums, and villages far from the nearest clinic or school. Everything we do is run by local staff and volunteers who live in the communities they serve.',
            'আমরা বাংলাদেশ-ভিত্তিক একটি অলাভজনক সংস্থা, যারা কাজ করি সেখানেই যেখানে প্রয়োজন সবচেয়ে বেশি — বন্যাপ্রবণ চরাঞ্চল, নিম্ন-আয়ের শহুরে বস্তি, আর নিকটতম ক্লিনিক বা স্কুল থেকে দূরের গ্রামগুলোতে। আমাদের সব কার্যক্রম পরিচালনা করেন স্থানীয় কর্মী ও স্বেচ্ছাসেবকরা, যারা নিজেরাই সেই কমিউনিটিতে বাস করেন।'
          ) }}
        </p>
        <p class="leading-relaxed" :style="{ color: 'var(--ink-soft)' }">
          {{ lang.t(
            'From a single food drive in 2016, we\'ve grown into four ongoing programs across food security, education, healthcare, and clean water — always transparent about where every taka goes.',
            '২০১৬ সালে একটি মাত্র খাদ্য কর্মসূচি দিয়ে শুরু করে আজ আমরা খাদ্য নিরাপত্তা, শিক্ষা, স্বাস্থ্যসেবা আর নিরাপদ পানি — এই চারটি চলমান কর্মসূচিতে পরিণত হয়েছি, প্রতিটি টাকা কোথায় যায় তা নিয়ে সবসময় স্বচ্ছ থেকে।'
          ) }}
        </p>
      </div>
      <div class="fb-reveal order-1 md:order-2" v-reveal>
        <img src="https://placehold.co/700x520/0E6B5C/F3F7F1?text=Future+Bridge+Foundation" alt="Future Bridge Foundation team at work"
          class="w-full rounded-2xl fb-arch-top object-cover shadow-sm">
      </div>
    </section>

    <div class="fb-divider fb-divider--accent2"></div>

    <!-- ================= PROJECTS ================= -->
    <section id="projects" class="max-w-7xl mx-auto px-5 md:px-8 py-16">
      <div class="text-center max-w-2xl mx-auto mb-10 fb-reveal" v-reveal>
        <p class="font-mono text-xs uppercase tracking-widest mb-3" :style="{ color: 'var(--primary)' }">{{ lang.t('Where your support goes', 'আপনার সহায়তা যেখানে যায়') }}</p>
        <h2 class="font-display text-3xl font-semibold">{{ lang.t('Our Projects', 'আমাদের প্রকল্পসমূহ') }}</h2>
      </div>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <RouterLink v-for="p in projects" :key="p.id" :to="'/projects/' + p.slug"
          class="fb-card fb-reveal group block rounded-2xl overflow-hidden" v-reveal
          :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <div class="relative h-48 overflow-hidden">
            <img :src="p.featured_image" :alt="lang.t(p.title, p.title_bn)" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <span class="absolute top-3 left-3 font-mono text-[11px] uppercase tracking-wider px-2 py-1 rounded-full"
              :style="{ background: 'var(--surface)/90', color: 'var(--ink)' }">{{ lang.t('Project', 'প্রকল্প') }}</span>
          </div>
          <div class="p-5">
            <h3 class="font-display text-xl font-semibold mb-2">{{ lang.t(p.title, p.title_bn) }}</h3>
            <p class="text-sm leading-relaxed mb-4" :style="{ color: 'var(--ink-soft)' }">{{ lang.t(p.short_en, p.short_bn) }}</p>
            <span class="inline-flex items-center gap-1 text-sm font-semibold" :style="{ color: 'var(--primary)' }">
              {{ lang.t('Read more', 'বিস্তারিত') }} <span aria-hidden="true">&rarr;</span>
            </span>
          </div>
        </RouterLink>
      </div>
    </section>

    <div class="fb-divider flip"></div>

    <!-- ================= VIDEOS PREVIEW ================= -->
    <section class="py-16" :style="{ background: 'var(--surface)' }">
      <div class="max-w-7xl mx-auto px-5 md:px-8">
        <div class="flex items-end justify-between mb-8 fb-reveal" v-reveal>
          <h2 class="font-display text-3xl font-semibold">{{ lang.t('Our Videos', 'আমাদের ভিডিও') }}</h2>
          <RouterLink to="/videos" class="hidden sm:inline-block text-sm font-semibold" :style="{ color: 'var(--primary)' }">
            {{ lang.t('Show all →', 'সব দেখুন →') }}
          </RouterLink>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div v-for="v in videosPreview" :key="v.id"
            class="fb-card fb-reveal rounded-2xl overflow-hidden" v-reveal
            :style="{ background: 'var(--bg)', border: '1px solid var(--border)' }">
            <div class="aspect-video">
              <iframe class="w-full h-full" :src="v.url" :title="lang.t(v.title, v.title_bn)" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
            </div>
            <div class="p-4">
              <h3 class="font-display text-lg font-semibold mb-1">{{ lang.t(v.title, v.title_bn) }}</h3>
              <p class="text-sm" :style="{ color: 'var(--ink-soft)' }">{{ lang.t(v.description, v.description_bn) }}</p>
            </div>
          </div>
        </div>
        <div class="text-center mt-8 sm:hidden">
          <RouterLink to="/videos" class="inline-block px-6 py-3 rounded-full font-semibold"
            :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
            {{ lang.t('Show all videos', 'সব ভিডিও দেখুন') }}
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- ================= GALLERY PREVIEW ================= -->
    <section class="max-w-7xl mx-auto px-5 md:px-8 py-16">
      <div class="flex items-end justify-between mb-8 fb-reveal" v-reveal>
        <h2 class="font-display text-3xl font-semibold">{{ lang.t('Gallery', 'গ্যালারি') }}</h2>
        <RouterLink to="/gallery" class="hidden sm:inline-block text-sm font-semibold" :style="{ color: 'var(--primary)' }">
          {{ lang.t('View full gallery →', 'সম্পূর্ণ গ্যালারি →') }}
        </RouterLink>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
        <button v-for="(img, i) in galleryPreview" :key="img.id" type="button" @click="openGalleryLightbox(i)"
          class="fb-card fb-reveal group relative block w-full rounded-xl overflow-hidden" v-reveal
          :style="{ border: '1px solid var(--border)' }">
          <img :src="img.image_path" :alt="lang.t(img.title, img.title_bn)"
            class="w-full h-full object-cover aspect-square group-hover:scale-105 transition-transform duration-500">
          <span class="absolute inset-x-0 bottom-0 p-2 text-xs bg-gradient-to-t from-black/70 to-transparent text-white text-left">
            {{ lang.t(img.title, img.title_bn) }}
          </span>
        </button>
      </div>
      <div class="text-center mt-8 sm:hidden">
        <RouterLink to="/gallery" class="inline-block px-6 py-3 rounded-full font-semibold"
          :style="{ background: 'var(--accent)', color: 'var(--accent-ink)' }">
          {{ lang.t('View full gallery', 'সম্পূর্ণ গ্যালারি দেখুন') }}
        </RouterLink>
      </div>
    </section>

    <div class="fb-divider" :style="{ backgroundImage: 'radial-gradient(circle at 11px 0, transparent 11px, var(--accent) 11.5px, var(--accent) 12px, transparent 12.5px)' }"></div>

    <!-- ================= DONATE CTA ================= -->
    <section class="max-w-5xl mx-auto px-5 md:px-8 py-16 text-center fb-reveal" v-reveal>
      <h2 class="font-display text-3xl font-semibold mb-4">{{ lang.t('Every project needs a hand', 'প্রতিটি প্রকল্পের একটু সাহায্য দরকার') }}</h2>
      <p class="max-w-xl mx-auto mb-7" :style="{ color: 'var(--ink-soft)' }">
        {{ lang.t('Pick a cause on the projects page and donate directly — 100% of donations go to program costs.', 'প্রকল্প পাতা থেকে একটি কারণ বেছে সরাসরি অনুদান দিন — অনুদানের ১০০% কর্মসূচির খরচে ব্যয় হয়।') }}
      </p>
      <RouterLink to="/projects" class="inline-block px-8 py-3.5 rounded-full font-semibold hover:opacity-90 transition"
        :style="{ background: 'var(--accent2)', color: 'var(--accent2-ink)' }">
        {{ lang.t('Donate to a project', 'একটি প্রকল্পে অনুদান দিন') }}
      </RouterLink>
    </section>
  </Layout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, getCurrentInstance } from 'vue'
import axios from 'axios'
import Layout from '@/layouts/Layout.vue'
import { useLangStore } from '@/stores/lang'
import { useThemeStore } from '@/stores/theme'
import { useAuthStore } from '@/stores/auth'

const lang = useLangStore()
const authStore = useAuthStore()
useThemeStore()

const projects = ref([])
const videosPreview = ref([])
const galleryPreview = ref([])
const galleryAll = ref([])
const quickForm = ref({ name: '', email: '', message: '' })

const stats = [
  { key: 'meals', value: '48,000+', labelEn: 'Meals served', labelBn: 'বেলা খাবার', color: 'var(--primary)' },
  { key: 'students', value: '1,150', labelEn: 'Students sponsored', labelBn: 'শিক্ষার্থী সহায়তা', color: 'var(--accent2)' },
  { key: 'patients', value: '9,400+', labelEn: 'Patients treated', labelBn: 'রোগী চিকিৎসা', color: 'var(--primary)' },
  { key: 'wells', value: '76', labelEn: 'Tube-wells installed', labelBn: 'নলকূপ স্থাপিত', color: 'var(--accent2)' },
]

let lightboxRef = null

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

function handleQuickContact() {
  alert(lang.t('Thanks! We will be in touch soon.', 'ধন্যবাদ! আমরা শীঘ্রই যোগাযোগ করব।'))
  quickForm.value = { name: '', email: '', message: '' }
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
