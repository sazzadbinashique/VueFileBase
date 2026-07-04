<template>
  <nav class="flex items-center gap-1.5 text-xs mb-4" :style="{ color: 'var(--ink-soft)' }">
    <RouterLink to="/admin/dashboard" class="hover:opacity-80 transition-opacity" :style="{ color: 'var(--primary)' }">
      {{ lang.t('Dashboard', 'ড্যাশবোর্ড') }}
    </RouterLink>
    <template v-for="(item, i) in items" :key="i">
      <span class="mx-1">/</span>
      <RouterLink
        v-if="item.to && i < items.length - 1"
        :to="item.to"
        class="hover:opacity-80 transition-opacity"
        :style="{ color: 'var(--primary)' }"
      >
        {{ lang.t(item.label, item.labelBn) }}
      </RouterLink>
      <span v-else class="font-medium" :style="{ color: 'var(--ink)' }">
        {{ lang.t(item.label, item.labelBn) }}
      </span>
    </template>
  </nav>
</template>

<script setup>
import { useLangStore } from '@/stores/lang'

defineProps({
  items: {
    type: Array,
    required: true,
  },
})

const lang = useLangStore()
</script>
