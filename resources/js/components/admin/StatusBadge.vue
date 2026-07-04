<template>
  <span class="inline-block text-[11px] font-mono uppercase tracking-wider px-2 py-0.5 rounded-full"
    :style="{ background: bgColor, color: textColor }">
    {{ status }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ status: { type: String, default: 'draft' } })

const colors = {
  active: { bg: 'rgba(14,107,92,0.12)', text: 'var(--primary)' },
  completed: { bg: 'rgba(196,61,61,0.12)', text: 'var(--accent2)' },
  published: { bg: 'rgba(14,107,92,0.12)', text: 'var(--primary)' },
  draft: { bg: 'rgba(128,128,128,0.1)', text: 'var(--ink-soft)' },
  pending: { bg: 'rgba(232,169,59,0.12)', text: 'var(--accent)' },
  failed: { bg: 'rgba(196,61,61,0.12)', text: 'var(--accent2)' },
  cancelled: { bg: 'rgba(128,128,128,0.1)', text: 'var(--ink-soft)' },
}

const bgColor = computed(() => colors[props.status]?.bg || colors.draft.bg)
const textColor = computed(() => colors[props.status]?.text || colors.draft.text)
</script>
