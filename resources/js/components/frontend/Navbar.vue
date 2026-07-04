<template>
  <header class="sticky top-0 z-50 backdrop-blur" :style="{ background: 'var(--bg)/90', borderBottom: '1px solid var(--border)' }">
    <nav class="max-w-7xl mx-auto px-5 md:px-8 flex items-center justify-between h-16">
      <RouterLink to="/" class="flex items-center gap-2 font-display font-semibold text-lg" :style="{ color: 'var(--ink)' }">
        <svg width="30" height="30" viewBox="0 0 40 40" aria-hidden="true">
          <path d="M2 28c4-10 10-16 18-16s14 6 18 16" fill="none" stroke="var(--primary)" stroke-width="4" stroke-linecap="round"/>
          <circle cx="20" cy="10" r="4" fill="var(--accent)"/>
        </svg>
        <span>{{ $t('fbf') }}</span>
      </RouterLink>

      <ul class="hidden md:flex items-center gap-7 text-sm font-medium">
        <li><RouterLink to="/" class="fb-nav-link" :class="{ active: $route.path === '/' }">{{ $t('home') }}</RouterLink></li>
        <li><RouterLink to="/about" class="fb-nav-link" :class="{ active: $route.path.startsWith('/about') }">{{ $t('about') }}</RouterLink></li>
        <li><RouterLink to="/projects" class="fb-nav-link" :class="{ active: $route.path.startsWith('/projects') }">{{ $t('projects') }}</RouterLink></li>
        <li><RouterLink to="/videos" class="fb-nav-link" :class="{ active: $route.path.startsWith('/videos') }">{{ $t('videos') }}</RouterLink></li>
        <li><RouterLink to="/gallery" class="fb-nav-link" :class="{ active: $route.path.startsWith('/gallery') }">{{ $t('gallery') }}</RouterLink></li>
        <li><a href="#contact" class="fb-nav-link" @click.prevent="scrollToContact">{{ $t('contact') }}</a></li>
        <li v-if="authStore.isAdmin">
          <RouterLink to="/admin/dashboard" class="fb-nav-link" style="color: var(--accent2)">{{ $t('admin') }}</RouterLink>
        </li>
      </ul>

      <div class="flex items-center gap-2">
        <template v-if="authStore.isLoggedIn">
          <RouterLink to="/dashboard" class="text-sm font-medium hover:opacity-80 transition hidden md:inline" :style="{ color: 'var(--primary)' }">{{ authStore.user?.name }}</RouterLink>
          <button @click="handleLogout" class="text-xs font-medium hover:opacity-70 transition hidden md:inline" :style="{ color: 'var(--ink-soft)' }">{{ $t('logout') }}</button>
        </template>
        <button v-else type="button" @click="router.push('/login')"
          class="w-11 h-9 rounded-full border grid place-items-center hover:opacity-80 transition"
          :style="{ borderColor: 'var(--border)', color: 'var(--primary)' }"
          :aria-label="$t('login')">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </button>
        <button type="button" @click="lang.setLocale(lang.locale === 'bn' ? 'en' : 'bn')"
          class="w-11 h-9 rounded-full border text-xs font-semibold grid place-items-center hover:opacity-80 transition font-mono"
          :style="{ borderColor: 'var(--border)', color: 'var(--ink)' }"
          :aria-label="$t('toggle_language')">
          {{ lang.locale === 'bn' ? 'EN' : 'বাং' }}
        </button>
        <button type="button" @click="theme.toggle()"
          class="w-11 h-9 rounded-full border grid place-items-center hover:opacity-80 transition"
          :style="{ borderColor: 'var(--border)' }"
          :aria-label="$t('toggle_dark_mode')">
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
      <li><RouterLink to="/" class="block px-6 py-3" @click="mobileOpen = false">{{ $t('home') }}</RouterLink></li>
      <li><RouterLink to="/about" class="block px-6 py-3" @click="mobileOpen = false">{{ $t('about') }}</RouterLink></li>
      <li><RouterLink to="/projects" class="block px-6 py-3" @click="mobileOpen = false">{{ $t('projects') }}</RouterLink></li>
      <li><RouterLink to="/videos" class="block px-6 py-3" @click="mobileOpen = false">{{ $t('videos') }}</RouterLink></li>
      <li><RouterLink to="/gallery" class="block px-6 py-3" @click="mobileOpen = false">{{ $t('gallery') }}</RouterLink></li>
      <li><RouterLink to="/admin/dashboard" v-if="authStore.isAdmin" class="block px-6 py-3" @click="mobileOpen = false" style="color: var(--accent2)">{{ $t('admin') }}</RouterLink></li>
      <li v-if="!authStore.isLoggedIn"><RouterLink to="/login" class="block px-6 py-3" @click="mobileOpen = false" :style="{ color: 'var(--primary)' }">{{ $t('login') }}</RouterLink></li>
      <li v-if="authStore.isLoggedIn"><RouterLink to="/dashboard" class="block px-6 py-3" @click="mobileOpen = false" :style="{ color: 'var(--primary)' }">{{ authStore.user?.name }}</RouterLink></li>
      <li v-if="authStore.isLoggedIn"><button @click="handleLogout" class="block px-6 py-3 w-full text-left" :style="{ color: 'var(--ink-soft)' }">{{ $t('logout') }}</button></li>
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
