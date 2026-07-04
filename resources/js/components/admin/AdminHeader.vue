<template>
  <header
    class="h-14 flex items-center justify-between px-4 border-b shrink-0"
    :style="{ background: 'var(--surface)', borderColor: 'var(--border)', color: 'var(--ink)' }"
  >
    <div class="flex items-center gap-3">
      <button @click="$emit('toggle')" class="text-xl hover:opacity-70 md:hidden">
        ☰
      </button>
      <span class="font-display font-semibold text-sm md:hidden">{{ lang.t('FBF Admin', 'এফবিএফ অ্যাডমিন') }}</span>
    </div>

    <div class="flex items-center gap-2">
      <button
        @click="lang.setLocale(lang.locale === 'bn' ? 'en' : 'bn')"
        class="text-xs px-2.5 py-1 rounded border hover:opacity-80"
        :style="{ borderColor: 'var(--border)' }"
      >
        {{ lang.locale === 'bn' ? 'EN' : 'বাং' }}
      </button>

      <button
        @click="theme.toggle()"
        class="text-base px-2 py-1 rounded border hover:opacity-80"
        :style="{ borderColor: 'var(--border)' }"
        :title="theme.isDark ? 'Light mode' : 'Dark mode'"
      >
        {{ theme.isDark ? '☀' : '☾' }}
      </button>

      <div class="relative" ref="dropdownRef">
        <button
          @click="dropdownOpen = !dropdownOpen"
          class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold hover:opacity-85 transition"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }"
          :title="admin.user?.name || 'User'"
        >
          {{ userInitial }}
        </button>

        <div
          v-if="dropdownOpen"
          class="absolute right-0 top-full mt-2 w-56 rounded-xl shadow-lg overflow-hidden z-50"
          :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }"
        >
          <div class="p-4" :style="{ borderBottom: '1px solid var(--border)' }">
            <p class="font-semibold text-sm truncate">{{ admin.user?.name || 'User' }}</p>
            <p class="text-xs truncate mt-0.5" :style="{ color: 'var(--ink-soft)' }">{{ admin.user?.email || '' }}</p>
          </div>
          <div class="p-2">
            <RouterLink to="/admin/profile"
              class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:opacity-80 transition"
              :style="{ color: 'var(--ink)' }"
              @click="dropdownOpen = false"
            >
              <span>👤</span>
              <span>{{ lang.t('Profile', 'প্রোফাইল') }}</span>
            </RouterLink>
            <button
              @click="handleLogout"
              class="flex items-center gap-2 w-full text-left px-3 py-2 rounded-lg text-sm hover:opacity-80 transition"
              :style="{ color: 'var(--accent2)' }"
            >
              <span>🚪</span>
              <span>{{ lang.t('Logout', 'লগআউট') }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useLangStore } from '@/stores/lang'
import { useThemeStore } from '@/stores/theme'
import { useAdminStore } from '@/stores/admin'
import { useRouter } from 'vue-router'

defineEmits(['toggle'])

const lang = useLangStore()
const theme = useThemeStore()
const admin = useAdminStore()
const router = useRouter()

const dropdownOpen = ref(false)
const dropdownRef = ref(null)

const userInitial = computed(() => {
  const name = admin.user?.name || 'U'
  return name.charAt(0).toUpperCase()
})

function handleLogout() {
  dropdownOpen.value = false
  admin.logout()
  router.push('/admin/login')
}

function onClickOutside(e) {
  if (dropdownOpen.value && dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    dropdownOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', onClickOutside))
onUnmounted(() => document.removeEventListener('click', onClickOutside))
</script>
