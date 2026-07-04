<template>
  <Layout>
    <div class="max-w-7xl mx-auto px-5 md:px-8 py-16">
      <!-- Breadcrumb -->
      <div class="text-sm mb-8" :style="{ color: 'var(--ink-soft)' }">
        <RouterLink to="/" class="hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ $t('home') }}</RouterLink>
        <span class="mx-2">/</span>
        <span>{{ $t('about') }}</span>
      </div>

      <div class="grid md:grid-cols-2 gap-12 items-center mb-16 fb-reveal" v-reveal>
        <div>
          <p class="font-mono text-xs uppercase tracking-widest mb-3" :style="{ color: 'var(--accent2)' }">{{ $t('who_we_are') }}</p>
          <h1 class="font-display text-4xl font-semibold mb-6">{{ $t('about_fbf') }}</h1>
          <p class="leading-relaxed mb-4" :style="{ color: 'var(--ink-soft)' }">
            {{ $t('about_description_1') }}
          </p>
          <p class="leading-relaxed" :style="{ color: 'var(--ink-soft)' }">
            {{ $t('about_description_1') }}
          </p>
        </div>
        <div class="relative">
          <img src="https://placehold.co/700x600/0E6B5C/F3F7F1?text=Our+Team" alt="Our team" class="w-full rounded-2xl fb-arch-top object-cover shadow-sm">
        </div>
      </div>

      <!-- Mission section -->
      <div class="grid md:grid-cols-3 gap-8 py-16">
        <div v-for="item in mission" :key="item.key" class="fb-reveal text-center p-6 rounded-2xl" v-reveal :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" :style="{ background: item.bg }">
            <component :is="item.icon" :style="{ color: item.color }" class="w-6 h-6" />
          </div>
          <h3 class="font-display text-xl font-semibold mb-2">{{ lang.t(item.titleEn) }}</h3>
          <p class="text-sm leading-relaxed" :style="{ color: 'var(--ink-soft)' }">{{ lang.t(item.descEn) }}</p>
        </div>
      </div>

      <!-- History -->
      <section class="py-16 text-center fb-reveal" v-reveal>
        <h2 class="font-display text-3xl font-semibold mb-4">{{ $t('our_story') }}</h2>
        <p class="max-w-2xl mx-auto leading-relaxed" :style="{ color: 'var(--ink-soft)' }">
          {{ $t('our_story_full') }}
        </p>
      </section>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '@/layouts/Layout.vue'
import { useLangStore } from '@/stores/lang'

const lang = useLangStore()

const vReveal = {
  mounted(el) {
    if (!('IntersectionObserver' in window)) { el.classList.add('is-visible'); return }
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target) } })
    }, { threshold: 0.12 })
    io.observe(el)
  }
}

const UtensilsIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 002-2V2M7 2v20M21 15V2v0a5 5 0 00-5 5v6c0 1.1.9 2 2 2h3Zm-6 0v7"/></svg>' }
const BookIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>' }
const HeartIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.5-1.5 3-3 3-5.5A5.5 5.5 0 0011 6.5 5.5 5.5 0 002 8.5C2 11 3.5 12.5 5 14l7 7 7-7z"/></svg>' }
const DropletIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/></svg>' }

const mission = [
  {
    key: 'food', icon: UtensilsIcon, bg: 'rgba(232,169,59,0.15)', color: 'var(--accent)',
    titleEn: 'Food Security', titleBn: 'খাদ্য নিরাপত্তা',
    descEn: 'Monthly ration bags and hot meals for families facing hunger during Ramadan and flood season.',
    descBn: 'রমজান ও বন্যার মৌসুমে ক্ষুধার্ত পরিবারগুলোর জন্য মাসিক রেশন ব্যাগ ও গরম খাবার।'
  },
  {
    key: 'education', icon: BookIcon, bg: 'rgba(14,107,92,0.15)', color: 'var(--primary)',
    titleEn: 'Education', titleBn: 'শিক্ষা',
    descEn: 'Scholarships, school supplies, and free tutoring so no child drops out over money.',
    descBn: 'বৃত্তি, স্কুল উপকরণ আর বিনামূল্যে টিউশন — যেন টাকার অভাবে কোনো শিশুর পড়াশোনা না থামে।'
  },
  {
    key: 'health', icon: HeartIcon, bg: 'rgba(196,61,61,0.15)', color: 'var(--accent2)',
    titleEn: 'Healthcare', titleBn: 'স্বাস্থ্যসেবা',
    descEn: 'Free medical camps, medicine, and emergency transport for families with nowhere else to turn.',
    descBn: 'যাদের যাওয়ার আর কোনো জায়গা নেই, তাদের জন্য বিনামূল্যে চিকিৎসা ক্যাম্প, ওষুধ আর জরুরি পরিবহন।'
  },
  {
    key: 'water', icon: DropletIcon, bg: 'rgba(14,107,92,0.15)', color: 'var(--primary)',
    titleEn: 'Water & Sanitation', titleBn: 'পানি ও স্যানিটেশন',
    descEn: 'Safe tube-wells, latrines, and hygiene training for villages battling contaminated water.',
    descBn: 'দূষিত পানির সাথে লড়াই করা গ্রামগুলোর জন্য নিরাপদ নলকূপ, ল্যাট্রিন আর স্বাস্থ্যবিধি প্রশিক্ষণ।'
  }
]
</script>
