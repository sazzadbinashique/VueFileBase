<template>
  <aside
    class="flex flex-col h-full transition-all duration-300 ease-in-out overflow-hidden"
    :class="collapsed ? 'w-16' : 'w-60'"
    :style="{ background: 'var(--surface)', color: 'var(--ink)', borderRight: '1px solid var(--border)' }"
  >
    <div class="flex items-center justify-between px-3 h-14 shrink-0" :style="{ borderBottom: '1px solid var(--border)' }">
      <RouterLink to="/admin/dashboard" class="flex items-center gap-2 overflow-hidden">
        <span class="text-lg shrink-0" :style="{ color: 'var(--primary)' }">⚓</span>
        <span v-show="!collapsed" class="font-display font-bold text-sm whitespace-nowrap">{{ lang.t('FBF Admin', 'এফবিএফ অ্যাডমিন') }}</span>
      </RouterLink>
      <button @click="$emit('toggle')" class="text-lg hover:opacity-50 shrink-0 p-1 transition-opacity" :title="collapsed ? 'Expand' : 'Collapse'">
        <span v-if="collapsed">&#9654;</span>
        <span v-else>&#9664;</span>
      </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-3 space-y-4 px-2">
      <div v-for="group in navGroups" :key="group.label">
        <p v-show="!collapsed" class="font-mono text-[10px] uppercase tracking-wider px-2 mb-1" :style="{ color: 'var(--ink-soft)' }">{{ lang.t(group.label, group.labelBn) }}</p>
        <RouterLink
          v-for="item in group.items"
          :key="item.to"
          :to="item.to"
          class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm transition-all"
          :class="{ 'font-semibold': isActive(item.to) }"
          :style="{
            color: isActive(item.to) ? 'var(--primary-ink)' : 'var(--ink-soft)',
            background: isActive(item.to) ? 'var(--primary)' : 'transparent',
          }"
        >
          <span class="text-lg shrink-0 w-6 text-center">{{ item.icon }}</span>
          <span v-show="!collapsed" class="truncate">{{ lang.t(item.en, item.bn) }}</span>
        </RouterLink>
      </div>
    </nav>
  </aside>
</template>

<script setup>
import { useLangStore } from '@/stores/lang'
import { useRoute } from 'vue-router'

defineProps({ collapsed: Boolean })
defineEmits(['toggle'])

const lang = useLangStore()
const route = useRoute()

const navGroups = [
  {
    label: 'Content', labelBn: 'কন্টেন্ট',
    items: [
      { to: '/admin/dashboard', icon: '\u2302', en: 'Dashboard', bn: '\u09A1\u09CD\u09AF\u09BE\u09B6\u09AC\u09CB\u09B0\u09CD\u09A1' },
      { to: '/admin/projects', icon: '\u263C', en: 'Projects', bn: '\u09AA\u09CD\u09B0\u0995\u09B2\u09CD\u09AA' },
      { to: '/admin/donations', icon: '\u2665', en: 'Donations', bn: '\u09A6\u09BE\u09A8' },
      { to: '/admin/gallery', icon: '\u25A3', en: 'Gallery', bn: '\u0997\u09CD\u09AF\u09BE\u09B2\u09BE\u09B0\u09BF' },
      { to: '/admin/videos', icon: '\u25B6', en: 'Videos', bn: '\u09AD\u09BF\u09A1\u09BF\u0993' },
      { to: '/admin/cms-pages', icon: '\u2630', en: 'CMS Pages', bn: '\u09B8\u09BF\u098F\u09AE\u098F\u09B8 \u09AA\u09C3\u09B7\u09CD\u09A0\u09BE' },
    ],
  },
  {
    label: 'Administration', labelBn: '\u09AA\u09B0\u09BF\u099A\u09BE\u09B2\u09A8\u09BE',
    items: [
      { to: '/admin/users', icon: '\u263A', en: 'Users', bn: '\u09AC\u09CD\u09AF\u09AC\u09B9\u09BE\u09B0\u0995\u09BE\u09B0\u09C0' },
      { to: '/admin/roles', icon: '\u2699', en: 'Roles', bn: '\u09AD\u09C2\u09AE\u09BF\u0995\u09BE' },
    ],
  },
]

function isActive(to) {
  return route.path.startsWith(to)
}
</script>
