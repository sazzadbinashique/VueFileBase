<template>
  <Teleport to="body">
    <div v-if="lb.isOpen.value" class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-8"
      style="background: rgba(6,12,10,0.92)" @click.self="lb.close()">
      <button type="button" aria-label="Close" @click="lb.close()"
        class="absolute top-5 right-6 text-white text-3xl leading-none hover:opacity-80 z-10">&times;</button>
      <button type="button" aria-label="Previous" @click="lb.prev()"
        class="absolute left-3 md:left-8 top-1/2 -translate-y-1/2 text-white text-4xl hover:opacity-80 z-10">&#8249;</button>
      <figure class="text-center">
        <img :src="currentImage" :alt="currentCaption"
          class="max-h-[82vh] max-w-[90vw] rounded-lg shadow-2xl object-contain">
        <figcaption class="text-white/80 mt-3 text-sm">{{ currentCaption }}</figcaption>
      </figure>
      <button type="button" aria-label="Next" @click="lb.next()"
        class="absolute right-3 md:right-8 top-1/2 -translate-y-1/2 text-white text-4xl hover:opacity-80 z-10">&#8250;</button>
    </div>
  </Teleport>
</template>

<script setup>
import { computed, watch, onMounted, onUnmounted } from 'vue'
import { useLightbox } from '@/composables/useLightbox'
import { useLangStore } from '@/stores/lang'

const lb = useLightbox()
const lang = useLangStore()

const currentImage = computed(() => {
  const item = lb.items.value[lb.index.value]
  return item?.image_path || item?.img || ''
})

const currentCaption = computed(() => {
  const item = lb.items.value[lb.index.value]
  if (!item) return ''
  return lang.t(item.title || item.en || '', item.title_bn || item.bn || '')
})

function handleKey(e) {
  if (!lb.isOpen.value) return
  if (e.key === 'Escape') lb.close()
  if (e.key === 'ArrowLeft') lb.prev()
  if (e.key === 'ArrowRight') lb.next()
}

onMounted(() => document.addEventListener('keydown', handleKey))
onUnmounted(() => document.removeEventListener('keydown', handleKey))
</script>
