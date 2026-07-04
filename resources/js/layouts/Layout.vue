<template>
  <div class="min-h-screen flex flex-col" :style="{ background: 'var(--bg)', color: 'var(--ink)' }">
    <Navbar />
    <main class="flex-1"><slot /></main>
    <AppFooter />
    <Lightbox />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import Navbar from '@/components/frontend/Navbar.vue'
import AppFooter from '@/components/frontend/AppFooter.vue'
import Lightbox from '@/components/shared/Lightbox.vue'
import { useThemeStore } from '@/stores/theme'
import { useLangStore } from '@/stores/lang'
import { useAuthStore } from '@/stores/auth'

useThemeStore()
useLangStore()
const auth = useAuthStore()

onMounted(() => auth.init())
</script>
