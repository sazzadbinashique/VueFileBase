<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <Header/>

    <!-- Hero Slider Section -->
    <Slider v-if="route.path !== '/login'" />

    <!-- Main Content with Sidebar -->
    <main >
        <slot/>
    </main>


    <!-- Footer -->
    <Footer/>
  </div>
</template>

<script setup>
import Header from '@/components/frontend/Header.vue'
import Footer from '@/components/frontend/Footer.vue'
import Slider from '@/components/frontend/Slider.vue'
import { useRoute } from 'vue-router'

const route = useRoute()


</script>