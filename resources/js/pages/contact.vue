<template>
  <Layout>
    <PageBanner
      accent="accent2"
      :eyebrow="bannerEyebrow"
      :title="bannerTitle"
      :description="bannerDescription"
    />

    <section class="max-w-7xl mx-auto px-5 md:px-8 py-12 grid lg:grid-cols-2 gap-8 items-start">
      <div class="rounded-2xl p-6 fb-reveal" v-reveal :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h2 class="font-display text-2xl font-semibold mb-4">{{ $t('contact_details') }}</h2>
        <ul class="space-y-3">
          <li v-for="(item, idx) in details" :key="idx" class="text-sm">
            <p class="font-semibold mb-1">{{ item.label }}</p>
            <p :style="{ color: 'var(--ink-soft)' }">{{ item.value }}</p>
          </li>
        </ul>
      </div>

      <div class="rounded-2xl p-6 fb-reveal" v-reveal :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h2 class="font-display text-2xl font-semibold mb-2">{{ formTitle }}</h2>
        <p class="text-sm mb-5" :style="{ color: 'var(--ink-soft)' }">{{ formDescription }}</p>

        <form @submit.prevent="submitForm" class="space-y-3">
          <input
            v-model="form.name"
            type="text"
            required
            :placeholder="$t('your_name')"
            class="w-full px-4 py-3 rounded-lg outline-none"
            :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }"
          />
          <input
            v-model="form.email"
            type="email"
            required
            :placeholder="$t('your_email')"
            class="w-full px-4 py-3 rounded-lg outline-none"
            :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }"
          />
          <textarea
            v-model="form.message"
            required
            rows="5"
            :placeholder="$t('how_can_we_help')"
            class="w-full px-4 py-3 rounded-lg outline-none"
            :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }"
          ></textarea>
          <button
            type="submit"
            class="w-full py-3 rounded-lg font-semibold hover:opacity-90 transition"
            :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }"
          >
            {{ $t('send') }}
          </button>
        </form>
        <p v-if="submitted" class="text-sm mt-4" :style="{ color: 'var(--primary)' }">{{ $t('thanks_contact') }}</p>
      </div>
    </section>
  </Layout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Layout from '@/layouts/Layout.vue'
import { useLangStore } from '@/stores/lang'
import PageBanner from '@/components/frontend/PageBanner.vue'
import { fetchCmsPage, cmsLayout, cmsText } from '@/composables/useCmsPage'

const lang = useLangStore()
const cmsPage = ref(null)
const submitted = ref(false)
const form = ref({ name: '', email: '', message: '' })

const vReveal = {
  mounted(el) {
    if (!('IntersectionObserver' in window)) { el.classList.add('is-visible'); return }
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target) } })
    }, { threshold: 0.12 })
    io.observe(el)
  }
}

const bannerEyebrow = computed(() =>
  cmsText(cmsPage.value, lang.locale, 'banner_eyebrow', 'banner_eyebrow_bn', lang.t('contact'))
)

const bannerTitle = computed(() =>
  cmsText(cmsPage.value, lang.locale, 'banner_title', 'banner_title_bn', lang.t('get_in_touch'))
)

const bannerDescription = computed(() =>
  cmsText(cmsPage.value, lang.locale, 'banner_description', 'banner_description_bn', lang.t('contact_page_desc'))
)

const layout = computed(() => cmsLayout(cmsPage.value, lang.locale))

const details = computed(() => {
  const fromCms = Array.isArray(layout.value.details) ? layout.value.details : []
  if (fromCms.length) return fromCms
  return [
    { label: lang.t('email_label'), value: 'hello@futurebridgefoundation.org' },
    { label: lang.t('phone_label'), value: '+880 1XXX-XXXXXX' },
    { label: lang.t('address_label'), value: lang.t('dhaka') },
  ]
})

const formTitle = computed(() => layout.value.form?.title || lang.t('contact_form_title'))
const formDescription = computed(() => layout.value.form?.description || lang.t('contact_form_description'))

function submitForm() {
  submitted.value = true
  form.value = { name: '', email: '', message: '' }
}

onMounted(async () => {
  cmsPage.value = await fetchCmsPage('contact')
})
</script>
