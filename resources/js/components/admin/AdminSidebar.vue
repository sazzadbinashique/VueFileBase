<template>
  <aside
    class="flex flex-col h-full transition-all duration-300 ease-in-out overflow-hidden"
    :class="collapsed ? 'w-16' : 'w-60'"
    :style="{ background: 'var(--surface)', color: 'var(--ink)', borderRight: '1px solid var(--border)' }"
  >
    <div class="flex items-center justify-between px-3 h-14 shrink-0" :style="{ borderBottom: '1px solid var(--border)' }">
      <RouterLink to="/admin/dashboard" class="flex items-center gap-2 overflow-hidden">
        <span class="text-lg shrink-0" :style="{ color: 'var(--primary)' }">⚓</span>
        <span v-show="!collapsed" class="font-display font-bold text-sm whitespace-nowrap">{{ $t('fbf_admin') }}</span>
      </RouterLink>
      <button @click="$emit('toggle')" class="hover:opacity-60 shrink-0 p-1.5 rounded transition-opacity" :title="collapsed ? 'Expand' : 'Collapse'" :style="{ color: 'var(--ink-soft)' }">
        <svg v-if="collapsed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-3 space-y-4 px-2">
      <div v-for="group in navGroups" :key="group.label">
          <p v-show="!collapsed" class="font-mono text-[10px] uppercase tracking-wider px-2 mb-1" :style="{ color: 'var(--ink-soft)' }">{{ lang.t(group.label) }}</p>
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
          <span v-show="!collapsed" class="truncate">{{ $t(item.key) }}</span>
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
    label: 'content',
    items: [
      { to: '/admin/dashboard', icon: '\u2302', key: 'dashboard' },
      { to: '/admin/projects', icon: '\u263C', key: 'projects' },
      { to: '/admin/donations', icon: '\u2665', key: 'donations' },
      { to: '/admin/gallery', icon: '\u25A3', key: 'gallery' },
      { to: '/admin/videos', icon: '\u25B6', key: 'videos' },
      { to: '/admin/cms-pages', icon: '\u2630', key: 'cms_pages' },
    ],
  },
  {
    label: 'Administration', labelBn: '\u09AA\u09B0\u09BF\u099A\u09BE\u09B2\u09A8\u09BE',
    items: [
      { to: '/admin/users', icon: '\u263A', key: 'users' },
      { to: '/admin/roles', icon: '\u2699', key: 'roles' },
      { to: '/admin/settings', icon: '\u2699\uFE0F', key: 'settings' },
      { to: '/admin/log-viewer', icon: '\u2630', key: 'log_viewer' },
    ],
  },
]

function isActive(to) {
  return route.path.startsWith(to)
}
</script>
