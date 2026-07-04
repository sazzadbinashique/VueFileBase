<template>
  <section class="max-w-7xl mx-auto px-5 md:px-8 pt-12 pb-4 text-center fb-reveal" v-reveal>
    <p v-if="eyebrow" class="font-mono text-xs uppercase tracking-widest mb-3" :style="{ color: `var(--${accent})` }">{{ eyebrow }}</p>
    <h1 class="font-display text-4xl font-semibold mb-4">{{ title }}</h1>
    <p v-if="description" class="max-w-xl mx-auto" :style="{ color: 'var(--ink-soft)' }">
      {{ description }}
    </p>
  </section>
</template>

<script setup>
defineProps({
  eyebrow: { type: String, default: '' },
  title: { type: String, required: true },
  description: { type: String, default: '' },
  accent: { type: String, default: 'primary' },
})

const vReveal = {
  mounted(el) {
    if (!('IntersectionObserver' in window)) { el.classList.add('is-visible'); return }
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target) } })
    }, { threshold: 0.12 })
    io.observe(el)
  }
}
</script>
