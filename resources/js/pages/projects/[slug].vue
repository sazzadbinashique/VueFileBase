<template>
  <Layout>
    <div v-if="loading" class="text-center py-20">
      <div class="inline-block w-8 h-8 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></div>
    </div>
    <template v-else-if="project">
      <div class="max-w-7xl mx-auto px-5 md:px-8 pt-6 text-sm" :style="{ color: 'var(--ink-soft)' }">
        <RouterLink to="/" class="hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ lang.t('Home', 'হোম') }}</RouterLink>
        <span class="mx-2">/</span>
        <RouterLink to="/projects" class="hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ lang.t('Projects', 'প্রকল্প') }}</RouterLink>
        <span class="mx-2">/</span>
        <span :style="{ color: 'var(--ink)' }">{{ lang.t(project.title, project.title_bn) }}</span>
      </div>

      <section class="max-w-7xl mx-auto px-5 md:px-8 pt-6">
        <div class="relative rounded-2xl overflow-hidden fb-reveal" v-reveal>
          <img :src="project.featured_image" :alt="lang.t(project.title, project.title_bn)" class="w-full h-64 md:h-80 object-cover">
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent flex items-end">
            <h1 class="font-display text-3xl md:text-5xl font-semibold text-white p-6 md:p-10">{{ lang.t(project.title, project.title_bn) }}</h1>
          </div>
        </div>
      </section>

      <div class="fb-divider fb-divider--accent"></div>

      <section class="max-w-7xl mx-auto px-5 md:px-8 py-14 grid md:grid-cols-3 gap-10">
        <div class="md:col-span-2 fb-reveal" v-reveal>
          <p class="font-mono text-xs uppercase tracking-widest mb-3" :style="{ color: 'var(--primary)' }">{{ lang.t('The project', 'প্রকল্পটি') }}</p>
          <p v-for="(para, i) in bodyParagraphs" :key="i" class="leading-relaxed mb-4" :style="{ color: 'var(--ink-soft)' }">{{ para }}</p>
        </div>
        <aside class="fb-reveal self-start rounded-2xl p-6 md:sticky md:top-24" v-reveal
          :style="{ border: '1px solid var(--border)', background: 'var(--surface)' }">
          <h2 class="font-display text-xl font-semibold mb-2">{{ lang.t('Support this project', 'এই প্রকল্পে সহায়তা করুন') }}</h2>
          <p class="text-sm mb-5" :style="{ color: 'var(--ink-soft)' }">
            {{ lang.t('100% of your donation goes directly to program costs.', 'আপনার অনুদানের ১০০% সরাসরি কর্মসূচি খরচে ব্যয় হয়।') }}
          </p>
          <div v-if="!auth.isLoggedIn" class="mb-4">
            <p class="text-sm mb-2" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Please log in to donate.', 'দান করতে লগইন করুন।') }}</p>
            <RouterLink :to="`/login?redirect=/projects/${$route.params.slug}`" class="block text-center py-3 rounded-lg font-semibold"
              :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
              {{ lang.t('Log in', 'লগইন') }}
            </RouterLink>
          </div>
          <form v-else @submit.prevent="handleDonate" class="space-y-3">
            <div class="grid grid-cols-3 gap-2">
              <button v-for="amt in [500, 1500, 5000]" :key="amt" type="button" @click="donationAmount = amt"
                class="py-2 rounded-lg border font-mono text-sm transition hover:opacity-80"
                :class="{ 'opacity-70': donationAmount !== amt }"
                :style="{ borderColor: donationAmount === amt ? 'var(--accent2)' : 'var(--border)', background: donationAmount === amt ? 'var(--accent2)' : 'transparent', color: donationAmount === amt ? 'var(--accent2-ink)' : 'var(--ink)' }">
                ৳{{ amt.toLocaleString() }}
              </button>
            </div>
            <input v-model.number="donationAmount" type="number" min="10"
              :placeholder="lang.t('Custom amount (BDT)', 'নিজের পরিমাণ (টাকা)')"
              class="w-full px-4 py-3 rounded-lg outline-none"
              :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
            <button type="submit" :disabled="donating"
              class="w-full py-3 rounded-lg font-semibold hover:opacity-90 transition disabled:opacity-50"
              :style="{ background: 'var(--accent2)', color: 'var(--accent2-ink)' }">
              {{ donating ? lang.t('Redirecting...', 'পুনঃনির্দেশ...') : lang.t('Donate with SSLCommerz', 'এসএসএলকমার্জে দান') }}
            </button>
            <p v-if="donationError" class="text-sm text-center" style="color: var(--accent2)">{{ donationError }}</p>
          </form>
        </aside>
      </section>

      <div class="fb-divider flip"></div>

      <section class="py-14" :style="{ background: 'var(--surface)' }">
        <div class="max-w-7xl mx-auto px-5 md:px-8">
          <h2 class="font-display text-2xl font-semibold mb-8 text-center fb-reveal" v-reveal>
            {{ lang.t('Our impact so far', 'আমাদের এখন পর্যন্ত অর্জন') }}
          </h2>
          <div class="grid gap-5 sm:grid-cols-3">
            <div v-for="imp in impacts" :key="imp.label" class="fb-reveal rounded-xl p-5 text-center" v-reveal
              :style="{ border: '1px solid var(--border)', background: 'var(--bg)' }">
              <p class="font-display text-2xl md:text-3xl font-bold" :style="{ color: 'var(--primary)' }">{{ imp.value }}</p>
              <p class="font-mono text-xs uppercase tracking-wide mt-1" :style="{ color: 'var(--ink-soft)' }">{{ imp.label }}</p>
            </div>
          </div>
        </div>
      </section>

      <section class="max-w-7xl mx-auto px-5 md:px-8 py-14" v-if="project.gallery_images?.length">
        <h2 class="font-display text-2xl font-semibold mb-8 text-center fb-reveal" v-reveal>
          {{ lang.t('Photos from the field', 'মাঠ থেকে ছবি') }}
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
          <button v-for="(img, i) in project.gallery_images" :key="img.id" type="button" @click="openGallery(i)"
            class="fb-card fb-reveal block w-full rounded-xl overflow-hidden" v-reveal
            :style="{ border: '1px solid var(--border)' }">
            <img :src="img.image_path" :alt="lang.t(img.title, img.title_bn)" class="w-full h-full object-cover aspect-square hover:scale-105 transition-transform duration-500">
          </button>
        </div>
      </section>

      <div class="fb-divider fb-divider--accent2"></div>

      <section class="max-w-7xl mx-auto px-5 md:px-8 py-14">
        <h2 class="font-display text-2xl font-semibold mb-8 text-center fb-reveal" v-reveal>
          {{ lang.t('Other projects', 'অন্যান্য প্রকল্প') }}
        </h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <RouterLink v-for="p in related" :key="p.id" :to="'/projects/' + p.slug"
            class="fb-card fb-reveal group block rounded-2xl overflow-hidden" v-reveal
            :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
            <div class="relative h-40 overflow-hidden">
              <img :src="p.featured_image" :alt="lang.t(p.title, p.title_bn)" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
            <div class="p-4">
              <h3 class="font-display text-lg font-semibold">{{ lang.t(p.title, p.title_bn) }}</h3>
            </div>
          </RouterLink>
        </div>
      </section>
    </template>
    <div v-else class="max-w-2xl mx-auto text-center px-5 py-24">
      <h1 class="font-display text-3xl font-semibold mb-4">{{ lang.t('Project not found', 'প্রকল্পটি খুঁজে পাওয়া যায়নি') }}</h1>
      <p class="mb-6" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('The project you\'re looking for may have moved.', 'আপনি যে প্রকল্পটি খুঁজছেন সেটি সরে গেছে হয়তো।') }}</p>
      <RouterLink to="/projects" class="inline-block px-6 py-3 rounded-full font-semibold"
        :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">
        {{ lang.t('Back to projects', 'প্রকল্পে ফিরুন') }}
      </RouterLink>
    </div>
  </Layout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useLangStore } from '@/stores/lang'
import { useAuthStore } from '@/stores/auth'
import Layout from '@/layouts/Layout.vue'

const route = useRoute()
const routerInstance = useRouter()
const lang = useLangStore()
const auth = useAuthStore()
const project = ref(null)
const related = ref([])
const loading = ref(true)
const donationAmount = ref(500)
const donating = ref(false)
const donationError = ref('')

const vReveal = {
  mounted(el) {
    if (!('IntersectionObserver' in window)) { el.classList.add('is-visible'); return }
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target) } })
    }, { threshold: 0.12 })
    io.observe(el)
  }
}

const bodyParagraphs = computed(() => {
  const p = project.value
  if (!p) return []
  const body = lang.locale === 'bn' && p.body_bn ? p.body_bn : p.body_en
  return body || [p.description]
})

const impacts = computed(() => {
  const p = project.value
  if (!p) return []
  const data = lang.locale === 'bn' && p.impact_bn ? p.impact_bn : p.impact_en
  return data || []
})

onMounted(async () => {
  try {
    const { data } = await axios.get(`/api/projects/${route.params.slug}`)
    project.value = data
    const { data: allProj } = await axios.get('/api/projects', { params: { status: 'active', per_page: 100 } })
    related.value = (allProj.data || []).filter(p => p.id !== data.id).slice(0, 3)
  } catch (e) { project.value = null }
  finally { loading.value = false }
})

async function handleDonate() {
  if (!auth.isLoggedIn) {
    routerInstance.push(`/login?redirect=/projects/${route.params.slug}`)
    return
  }
  donating.value = true
  donationError.value = ''
  try {
    const { data } = await axios.post('/api/donations/initiate', {
      project_id: project.value.id,
      amount: donationAmount.value,
    })
    if (data.redirect_url) {
      window.location.href = data.redirect_url
    } else {
      donationError.value = lang.t('Payment initiation failed.', 'পেমেন্ট শুরু করতে ব্যর্থ।')
    }
  } catch (e) {
    donationError.value = e.response?.data?.message || lang.t('Something went wrong.', 'কিছু সমস্যা হয়েছে।')
  } finally {
    donating.value = false
  }
}

function openGallery(index) {
  const lb = document.getElementById('fb-lightbox')
  if (!lb || !project.value?.gallery_images?.length) return
  window._lightboxData = project.value.gallery_images
  window._lightboxIndex = index
  const img = window._lightboxData[index]
  lb.querySelector('[data-lb-img]').src = img.image_path
  lb.querySelector('[data-lb-img]').alt = lang.t(img.title, img.title_bn)
  lb.querySelector('[data-lb-caption]').textContent = lang.t(img.title, img.title_bn)
  lb.classList.add('open')
}
</script>
