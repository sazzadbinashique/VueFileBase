<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-5xl">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ $t('new_role') }}</h1>
      <div class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <form @submit.prevent="saveRole" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">{{ $t('name') }}</label><input v-model="form.name" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('label') }}</label><input v-model="form.label" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
          </div>

          <div>
            <p class="text-lg font-semibold mb-3" :style="{ color: 'var(--ink)' }">{{ $t('permissions') }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-for="group in groups" :key="group.prefix"
                class="rounded-lg p-4" :style="{ background: 'var(--bg)', border: '1px solid var(--border)' }">
                <div class="flex items-center justify-between mb-3 pb-2 border-b" :style="{ borderColor: 'var(--border)' }">
                  <div class="flex items-center gap-2 text-sm font-semibold" :style="{ color: 'var(--ink)' }">
                    <span v-html="moduleIcon(group.prefix)" class="shrink-0" :style="{ color: 'var(--primary)' }"></span>
                    {{ moduleLabel(group.prefix) }}
                  </div>
                  <label class="flex items-center gap-1.5 text-xs cursor-pointer select-none" :style="{ color: 'var(--primary)' }">
                    <input type="checkbox"
                      :checked="group.allSelected"
                      :indeterminate.prop="group.someSelected"
                      @change="toggleGroup(group)"
                      class="accent-current" />
                    {{ $t('select_all') }}
                  </label>
                </div>
                <div class="space-y-1.5">
                  <label v-for="perm in group.perms" :key="perm.id"
                    class="flex items-center gap-2 text-sm cursor-pointer py-1 px-1 rounded hover:opacity-80"
                    :style="{ color: 'var(--ink)' }">
                    <input type="checkbox" :value="perm.id" v-model="form.permissions" class="accent-current" />
                    {{ perm.label || perm.name }}
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div class="flex gap-2 pt-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ $t('save') }}</button>
            <RouterLink to="/admin/roles" class="px-6 py-2 rounded hover:opacity-90 inline-block" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ $t('cancel') }}</RouterLink>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAdminStore } from '@/stores/admin'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'

const breadcrumbs = [{ label: 'Roles', labelBn: 'ভূমিকা' }, { label: 'New', labelBn: 'নতুন' }]
const router = useRouter()
const admin = useAdminStore()
const allPermissions = ref([])
const form = ref({ name: '', label: '', permissions: [] })

const groups = computed(() => {
  const map = {}
  for (const p of allPermissions.value) {
    const prefix = p.name.split('.')[0]
    if (!map[prefix]) map[prefix] = { prefix, perms: [] }
    map[prefix].perms.push(p)
  }
  return Object.values(map).map(g => ({
    ...g,
    allSelected: g.perms.every(p => form.value.permissions.includes(p.id)),
    someSelected: g.perms.some(p => form.value.permissions.includes(p.id)) && !g.allSelected,
  }))
})

function toggleGroup(group) {
  const ids = group.perms.map(p => p.id)
  if (group.allSelected) {
    form.value.permissions = form.value.permissions.filter(id => !ids.includes(id))
  } else {
    const set = new Set(form.value.permissions)
    ids.forEach(id => set.add(id))
    form.value.permissions = [...set]
  }
}

function moduleLabel(prefix) {
  const labels = {
    dashboard: 'Dashboard',
    projects: 'Projects',
    donations: 'Donations',
    gallery: 'Gallery',
    videos: 'Videos',
    cms: 'CMS',
    users: 'Users',
    roles: 'Roles',
  }
  return labels[prefix] || prefix.charAt(0).toUpperCase() + prefix.slice(1)
}

function moduleIcon(prefix) {
  const icons = {
    dashboard: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>',
    projects: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>',
    donations: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>',
    gallery: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
    videos: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>',
    cms: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
    users: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
    roles: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
  }
  return icons[prefix] || ''
}

async function saveRole() {
  try {
    await admin.storeRole(form.value)
    router.push('/admin/roles')
  } catch (e) { alert('Error saving role') }
}

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const perms = await admin.fetchPermissions()
  allPermissions.value = perms || []
})
</script>
