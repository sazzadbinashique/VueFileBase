<template>
  <Layout>
    <div class="max-w-7xl mx-auto px-5 md:px-8 py-16">
      <!-- Breadcrumb -->
      <div class="text-sm mb-8" :style="{ color: 'var(--ink-soft)' }">
        <RouterLink to="/" class="hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ lang.t('Home', 'হোম') }}</RouterLink>
        <span class="mx-2">/</span>
        <span>{{ lang.t('About', 'পরিচিতি') }}</span>
      </div>

      <div class="grid md:grid-cols-2 gap-12 items-center mb-16 fb-reveal" v-reveal>
        <div>
          <p class="font-mono text-xs uppercase tracking-widest mb-3" :style="{ color: 'var(--accent2)' }">{{ lang.t('Who we are', 'আমরা কারা') }}</p>
          <h1 class="font-display text-4xl font-semibold mb-6">{{ lang.t('About Future Bridge Foundation', 'ফিউচার ব্রিজ ফাউন্ডেশন সম্পর্কে') }}</h1>
          <p class="leading-relaxed mb-4" :style="{ color: 'var(--ink-soft)' }">
            {{ lang.t('We are a Bangladesh-based non-profit working where the need is greatest — flood-prone char lands, low-income urban slums, and villages far from the nearest clinic or school.', 'আমরা বাংলাদেশ-ভিত্তিক একটি অলাভজনক সংস্থা, যারা কাজ করি সেখানেই যেখানে প্রয়োজন সবচেয়ে বেশি — বন্যাপ্রবণ চরাঞ্চল, নিম্ন-আয়ের শহুরে বস্তি, আর নিকটতম ক্লিনিক বা স্কুল থেকে দূরের গ্রামগুলোতে।') }}
          </p>
          <p class="leading-relaxed" :style="{ color: 'var(--ink-soft)' }">
            {{ lang.t('Everything we do is run by local staff and volunteers who live in the communities they serve.', 'আমাদের সব কার্যক্রম পরিচালনা করেন স্থানীয় কর্মী ও স্বেচ্ছাসেবকরা, যারা নিজেরাই সেই কমিউনিটিতে বাস করেন।') }}
          </p>
        </div>
        <div class="relative">
          <img src="https://placehold.co/700x600/0E6B5C/F3F7F1?text=Our+Team" alt="Our team" class="w-full rounded-2xl fb-arch-top object-cover shadow-sm">
        </div>
      </div>

      <div class="fb-divider fb-divider--accent2"></div>

      <!-- Mission section -->
      <div class="grid md:grid-cols-3 gap-8 py-16">
        <div v-for="item in mission" :key="item.key" class="fb-reveal text-center p-6 rounded-2xl" v-reveal :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" :style="{ background: item.bg }">
            <component :is="item.icon" :style="{ color: item.color }" class="w-6 h-6" />
          </div>
          <h3 class="font-display text-xl font-semibold mb-2">{{ lang.t(item.titleEn, item.titleBn) }}</h3>
          <p class="text-sm leading-relaxed" :style="{ color: 'var(--ink-soft)' }">{{ lang.t(item.descEn, item.descBn) }}</p>
        </div>
      </div>

      <div class="fb-divider flip"></div>

      <!-- History -->
      <section class="py-16 text-center fb-reveal" v-reveal>
        <h2 class="font-display text-3xl font-semibold mb-4">{{ lang.t('Our Story', 'আমাদের গল্প') }}</h2>
        <p class="max-w-2xl mx-auto leading-relaxed" :style="{ color: 'var(--ink-soft)' }">
          {{ lang.t('From a single food drive in 2016, we have grown into four ongoing programs across food security, education, healthcare, and clean water. Today we serve thousands of families across Bangladesh — always transparent about where every taka goes.', '২০১৬ সালে একটি মাত্র খাদ্য কর্মসূচি দিয়ে শুরু করে আজ আমরা খাদ্য নিরাপত্তা, শিক্ষা, স্বাস্থ্যসেবা আর নিরাপদ পানি — এই চারটি চলমান কর্মসূচিতে পরিণত হয়েছি। আজ আমরা বাংলাদেশজুড়ে হাজারো পরিবারকে সেবা দিচ্ছি — প্রতিটি টাকা কোথায় যায় তা নিয়ে সবসময় স্বচ্ছ থেকে।') }}
        </p>
      </section>
    </div>
  </Layout>
</template>

<script setup>
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
