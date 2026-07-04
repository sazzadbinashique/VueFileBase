<template>
  <footer id="contact" class="mt-auto" :style="{ background: 'var(--ink)', color: 'var(--bg)' }">
    <div class="max-w-7xl mx-auto px-5 md:px-8 py-14 grid gap-10 md:grid-cols-4">
      <div>
        <p class="font-display text-xl font-semibold mb-3">{{ lang.t('Future Bridge Foundation', 'ফিউচার ব্রিজ ফাউন্ডেশন') }}</p>
        <p class="text-sm opacity-75 leading-relaxed">{{ lang.t('Building bridges between hope and opportunity across Bangladesh.', 'বাংলাদেশজুড়ে আশা আর সুযোগের মাঝে সেতুবন্ধন গড়ছি।') }}</p>
      </div>
      <div>
        <p class="font-mono text-xs uppercase tracking-widest opacity-60 mb-3">{{ lang.t('Projects', 'প্রকল্প') }}</p>
        <ul class="space-y-2 text-sm">
          <li v-for="p in projects" :key="p.id">
            <RouterLink :to="'/projects/' + p.slug" class="hover:opacity-80 transition">{{ lang.t(p.title, p.title_bn || p.title) }}</RouterLink>
          </li>
        </ul>
      </div>
      <div>
        <p class="font-mono text-xs uppercase tracking-widest opacity-60 mb-3">{{ lang.t('Explore', 'অন্বেষণ') }}</p>
        <ul class="space-y-2 text-sm">
          <li><RouterLink to="/about" class="hover:opacity-80 transition">{{ lang.t('About us', 'আমাদের সম্পর্কে') }}</RouterLink></li>
          <li><RouterLink to="/videos" class="hover:opacity-80 transition">{{ lang.t('Videos', 'ভিডিও') }}</RouterLink></li>
          <li><RouterLink to="/gallery" class="hover:opacity-80 transition">{{ lang.t('Gallery', 'গ্যালারি') }}</RouterLink></li>
        </ul>
      </div>
      <div>
        <p class="font-mono text-xs uppercase tracking-widest opacity-60 mb-3">{{ lang.t('Contact', 'যোগাযোগ') }}</p>
        <ul class="space-y-2 text-sm opacity-90">
          <li>{{ site.email }}</li>
          <li>{{ site.phone }}</li>
          <li>{{ lang.t('Dhaka, Bangladesh', 'ঢাকা, বাংলাদেশ') }}</li>
        </ul>
      </div>
    </div>
    <div class="border-t" :style="{ borderColor: 'rgba(255,255,255,0.1)' }">
      <p class="max-w-7xl mx-auto px-5 md:px-8 py-5 text-xs opacity-60 text-center">
        &copy; 2026 {{ lang.t('Future Bridge Foundation. All rights reserved.', 'ফিউচার ব্রিজ ফাউন্ডেশন। সর্বস্বত্ব সংরক্ষিত।') }}
      </p>
    </div>
  </footer>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useLangStore } from '@/stores/lang'

const lang = useLangStore()
const projects = ref([])
const site = { email: 'hello@futurebridgefoundation.org', phone: '+880 1XXX-XXXXXX' }

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/projects', { params: { per_page: 100 } })
    projects.value = data.data || []
  } catch (e) {}
})
</script>
