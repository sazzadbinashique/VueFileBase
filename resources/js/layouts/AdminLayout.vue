<template>
  <div v-if="ready" class="flex min-h-screen" :style="{ background: 'var(--bg)', color: 'var(--ink)' }">
    <div
      class="hidden md:block shrink-0 transition-all duration-300 ease-in-out"
      :class="sidebarOpen ? 'min-w-[240px] max-w-[240px]' : 'min-w-[64px] max-w-[64px]'"
    >
      <AdminSidebar :collapsed="!sidebarOpen" @toggle="sidebarOpen = !sidebarOpen" />
    </div>

    <div
      v-if="mobileSidebar"
      class="fixed inset-0 z-40 md:hidden"
      @click="mobileSidebar = false"
    >
      <div class="absolute inset-0" style="background: rgba(0,0,0,0.5)"></div>
      <aside
        class="absolute left-0 top-0 bottom-0 w-60"
        :style="{ background: 'var(--surface)' }"
        @click.stop
      >
        <AdminSidebar :collapsed="false" @toggle="mobileSidebar = false" />
      </aside>
    </div>

    <div class="flex flex-col flex-1 min-w-0">
      <AdminHeader @toggle="sidebarOpen = !sidebarOpen" />
      <main class="flex-1 overflow-y-auto p-6">
        <slot name="breadcrumb" />
        <slot />
      </main>
    </div>
  </div>
  <div v-else class="min-h-screen flex items-center justify-center" :style="{ background: 'var(--bg)' }">
    <span class="w-8 h-8 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></span>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminHeader from '@/components/admin/AdminHeader.vue'
import AdminSidebar from '@/components/admin/AdminSidebar.vue'
import { useThemeStore } from '@/stores/theme'
import { useLangStore } from '@/stores/lang'
import { useAdminStore } from '@/stores/admin'

useThemeStore()
useLangStore()
const admin = useAdminStore()

const sidebarOpen = ref(true)
const mobileSidebar = ref(false)
const ready = ref(false)

onMounted(async () => {
  await admin.init()
  ready.value = true
})
</script>
