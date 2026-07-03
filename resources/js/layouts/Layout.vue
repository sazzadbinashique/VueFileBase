<template>
  <div class="min-h-screen flex flex-col">
    <Header />
    <Slider v-if="showSlider" />
    <main class="flex-1"><slot /></main>
    <Footer />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Header from '@/components/frontend/Header.vue'
import Footer from '@/components/frontend/Footer.vue'
import Slider from '@/components/frontend/Slider.vue'

const route = useRoute()
const hideSlider = ['login', 'register', 'admin-login']
const showSlider = computed(() => !hideSlider.includes(route.name))
</script>
