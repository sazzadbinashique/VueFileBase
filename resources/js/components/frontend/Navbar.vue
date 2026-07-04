<template>
  <header class="sticky top-0 z-50 backdrop-blur" :style="{ background: 'var(--bg)/90', borderBottom: '1px solid var(--border)' }">
    <nav class="max-w-7xl mx-auto px-5 md:px-8 flex items-center justify-between h-16">
      <RouterLink to="/" class="flex items-center gap-2 font-display font-semibold text-lg" :style="{ color: 'var(--ink)' }">
        <svg width="30" height="30" viewBox="0 0 40 40" aria-hidden="true">
          <path d="M2 28c4-10 10-16 18-16s14 6 18 16" fill="none" stroke="var(--primary)" stroke-width="4" stroke-linecap="round"/>
          <circle cx="20" cy="10" r="4" fill="var(--accent)"/>
        </svg>
        <span>{{ lang.t('Future Bridge Foundation', 'ফিউচার ব্রিজ ফাউন্ডেশন') }}</span>
      </RouterLink>

      <ul class="hidden md:flex items-center gap-7 text-sm font-medium">
        <li><RouterLink to="/" class="fb-nav-link" :class="{ active: $route.path === '/' }">{{ lang.t('Home', 'হোম') }}</RouterLink></li>
        <li><RouterLink to="/about" class="fb-nav-link">{{ lang.t('About', 'পরিচিতি') }}</RouterLink></li>
        <li><RouterLink to="/projects" class="fb-nav-link">{{ lang.t('Projects', 'প্রকল্প') }}</RouterLink></li>
        <li><RouterLink to="/videos" class="fb-nav-link">{{ lang.t('Videos', 'ভিডিও') }}</RouterLink></li>
        <li><RouterLink to="/gallery" class="fb-nav-link">{{ lang.t('Gallery', 'গ্যালারি') }}</RouterLink></li>
        <li><a href="#contact" class="fb-nav-link" @click.prevent="scrollToContact">{{ lang.t('Contact', 'যোগাযোগ') }}</a></li>
        <li v-if="authStore.isAdmin">
          <RouterLink to="/admin/dashboard" class="fb-nav-link" style="color: var(--accent2)">{{ lang.t('Admin', 'অ্যাডমিন') }}</RouterLink>
        </li>
        <li v-if="!authStore.isLoggedIn" class="flex items-center gap-2">
          <RouterLink to="/login" class="text-sm font-medium hover:opacity-80 transition" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Login', 'লগইন') }}</RouterLink>
          <RouterLink to="/register" class="px-4 py-1.5 rounded-full text-sm font-semibold hover:opacity-90 transition whitespace-nowrap"
            :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ lang.t('Register', 'রেজিস্টার') }}</RouterLink>
        </li>
        <li v-if="authStore.isLoggedIn" class="flex items-center gap-2">
          <RouterLink to="/dashboard" class="text-sm font-medium hover:opacity-80 transition" :style="{ color: 'var(--primary)' }">{{ authStore.user?.name }}</RouterLink>
          <button @click="handleLogout" class="text-xs font-medium hover:opacity-70 transition" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Logout', 'লগআউট') }}</button>
        </li>
      </ul>

      <div class="flex items-center gap-2">
        <button type="button" @click="lang.setLocale(lang.locale === 'bn' ? 'en' : 'bn')"
          class="w-11 h-9 rounded-full border text-xs font-semibold grid place-items-center hover:opacity-80 transition font-mono"
          :style="{ borderColor: 'var(--border)', color: 'var(--ink)' }"
          :aria-label="lang.t('Toggle language', 'ভাষা পরিবর্তন')">
          {{ lang.locale === 'bn' ? 'EN' : 'বাং' }}
        </button>
        <button type="button" @click="theme.toggle()"
          class="w-11 h-9 rounded-full border grid place-items-center hover:opacity-80 transition"
          :style="{ borderColor: 'var(--border)' }"
          :aria-label="lang.t('Toggle dark mode', 'ডার্ক মোড')">
          <svg v-if="!theme.isDark" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
          </svg>
          <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 12.8A9 9 0 1111.2 3 7 7 0 0021 12.8z"/>
          </svg>
        </button>
        <button @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" type="button" class="md:hidden w-9 h-9 grid place-items-center" :style="{ color: 'var(--ink)' }" aria-label="Menu">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 6h18M3 12h18M3 18h18"/>
          </svg>
        </button>
      </div>
    </nav>
    <ul v-show="mobileOpen" class="md:hidden border-t text-sm" :style="{ borderColor: 'var(--border)', background: 'var(--bg)' }">
      <li><RouterLink to="/" class="block px-6 py-3" @click="mobileOpen = false">{{ lang.t('Home', 'হোম') }}</RouterLink></li>
      <li><RouterLink to="/about" class="block px-6 py-3" @click="mobileOpen = false">{{ lang.t('About', 'পরিচিতি') }}</RouterLink></li>
      <li><RouterLink to="/projects" class="block px-6 py-3" @click="mobileOpen = false">{{ lang.t('Projects', 'প্রকল্প') }}</RouterLink></li>
      <li><RouterLink to="/videos" class="block px-6 py-3" @click="mobileOpen = false">{{ lang.t('Videos', 'ভিডিও') }}</RouterLink></li>
      <li><RouterLink to="/gallery" class="block px-6 py-3" @click="mobileOpen = false">{{ lang.t('Gallery', 'গ্যালারি') }}</RouterLink></li>
      <li><RouterLink to="/admin/dashboard" v-if="authStore.isAdmin" class="block px-6 py-3" @click="mobileOpen = false" style="color: var(--accent2)">{{ lang.t('Admin', 'অ্যাডমিন') }}</RouterLink></li>
      <li v-if="!authStore.isLoggedIn"><RouterLink to="/login" class="block px-6 py-3" @click="mobileOpen = false" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Login', 'লগইন') }}</RouterLink></li>
      <li v-if="!authStore.isLoggedIn"><RouterLink to="/register" class="block px-6 py-3 font-semibold" @click="mobileOpen = false" :style="{ color: 'var(--primary)' }">{{ lang.t('Register', 'রেজিস্টার') }}</RouterLink></li>
      <li v-if="authStore.isLoggedIn"><RouterLink to="/dashboard" class="block px-6 py-3" @click="mobileOpen = false" :style="{ color: 'var(--primary)' }">{{ authStore.user?.name }}</RouterLink></li>
      <li v-if="authStore.isLoggedIn"><button @click="handleLogout" class="block px-6 py-3 w-full text-left" :style="{ color: 'var(--ink-soft)' }">{{ lang.t('Logout', 'লগআউট') }}</button></li>
    </ul>
  </header>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useLangStore } from '@/stores/lang'
import { useThemeStore } from '@/stores/theme'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const lang = useLangStore()
const theme = useThemeStore()
const authStore = useAuthStore()
const mobileOpen = ref(false)

async function handleLogout() {
  try { await authStore.logout() } catch {}
  mobileOpen.value = false
  router.push('/')
}

function scrollToContact() {
  const el = document.getElementById('contact')
  if (el) el.scrollIntoView({ behavior: 'smooth' })
}
</script>
