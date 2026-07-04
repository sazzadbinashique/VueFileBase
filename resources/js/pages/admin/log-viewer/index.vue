<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-6xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ $t('log_viewer') }}</h1>
        <div class="flex items-center gap-3">
          <label class="flex items-center gap-1.5 text-xs cursor-pointer" :style="{ color: 'var(--ink-soft)' }">
            <input type="checkbox" v-model="autoRefresh" @change="toggleAutoRefresh" class="accent-current" />
            {{ $t('auto_refresh') }}
          </label>
          <button @click="clearLogs"
            class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition"
            :style="{ background: 'var(--accent2)', color: 'var(--accent2-ink)' }">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
            {{ $t('clear_logs') }}
          </button>
        </div>
      </div>

      <div class="rounded-lg overflow-hidden" :style="{ background: '#1a1a2e', border: '1px solid var(--border)' }">
        <div class="flex items-center gap-2 px-4 py-2 border-b" :style="{ borderColor: '#2a2a4a' }">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00ff88" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span class="text-xs font-mono" style="color:#888">{{ logPath }}</span>
          <span class="text-xs ml-auto" style="color:#666">{{ lines.length }} lines</span>
        </div>
        <div class="overflow-y-auto font-mono text-xs leading-relaxed" style="max-height:calc(100vh - 280px); min-height:400px">
          <div v-if="!lines.length" class="p-4 text-center" style="color:#555">No log entries found.</div>
          <div v-for="(line, i) in filteredLines" :key="i" class="flex hover:bg-white/5">
            <span class="shrink-0 w-12 text-right pr-3 py-0.5 select-none" style="color:#444; border-right:1px solid #2a2a4a">{{ i + 1 }}</span>
            <span class="px-3 py-0.5 whitespace-pre-wrap break-all" :style="logStyle(line)">{{ line }}</span>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'

const breadcrumbs = [{ label: 'Log Viewer', labelBn: 'লগ ভিউয়ার' }]
const admin = useAdminStore()
const router = useRouter()
const lines = ref([])
const autoRefresh = ref(false)
let refreshTimer = null
const logPath = 'storage/logs/laravel.log'

const filteredLines = computed(() => lines.value)

function logStyle(line) {
  if (/ERROR|CRITICAL|ALERT|EMERGENCY/i.test(line)) return { color: '#ff6b6b' }
  if (/WARNING|NOTICE/i.test(line)) return { color: '#ffd93d' }
  if (/INFO/i.test(line)) return { color: '#6bcbff' }
  if (/DEBUG/i.test(line)) return { color: '#888' }
  return { color: '#ccc' }
}

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  try {
    const data = await admin.fetchLogs()
    lines.value = data.lines || []
  } catch (e) { /* handled */ }
}

function toggleAutoRefresh() {
  if (autoRefresh.value) {
    refreshTimer = setInterval(load, 5000)
  } else {
    clearInterval(refreshTimer)
    refreshTimer = null
  }
}

async function clearLogs() {
  if (!confirm('Clear all logs?')) return
  try {
    await admin.clearLogs()
    lines.value = []
  } catch (e) { /* handled */ }
}

onMounted(() => { load(); if (autoRefresh.value) toggleAutoRefresh() })
onUnmounted(() => { if (refreshTimer) clearInterval(refreshTimer) })
</script>
