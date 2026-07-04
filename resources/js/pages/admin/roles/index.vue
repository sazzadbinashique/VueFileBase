<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-5xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ $t('roles') }}</h1>
        <RouterLink to="/admin/roles/create" class="px-4 py-2 rounded font-semibold hover:opacity-90 whitespace-nowrap inline-block"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">+ {{ $t('new_role') }}</RouterLink>
      </div>

      <div class="mb-4">
        <input v-model="search" @input="debouncedLoad" :placeholder="$t('search')"
          class="px-3 py-2 rounded border text-sm outline-none w-full max-w-xs"
          :style="{ borderColor: 'var(--border)', background: 'var(--surface)', color: 'var(--ink)' }" />
      </div>

      <div class="rounded-lg overflow-hidden" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <table class="w-full text-sm">
          <thead><tr class="border-b text-left" :style="{ borderColor: 'var(--border)', background: 'var(--bg)' }">
            <th class="py-3 px-4">{{ $t('name') }}</th>
            <th class="py-3 px-4">{{ $t('label') }}</th>
            <th class="py-3 px-4">{{ $t('permissions') }}</th>
            <th class="py-3 px-4">{{ $t('users') }}</th>
            <th class="py-3 px-4">{{ $t('actions') }}</th>
          </tr></thead>
          <tbody>
            <tr v-for="(r, index) in roles" :key="r.id"
              class="border-b hover:opacity-80"
              :class="index % 2 === 0 ? 'bg-black/[0.03] dark:bg-white/[0.03]' : ''"
              :style="{ borderColor: 'var(--border)' }">
              <td class="py-3 px-4 font-medium">{{ r.name }}</td>
              <td class="py-3 px-4" :style="{ color: 'var(--ink-soft)' }">{{ r.label }}</td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--primary)' }">{{ r.permissions_count || 0 }}</td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ r.users_count || 0 }}</td>
              <td class="py-3 px-4">
                <div class="flex gap-2">
                  <button @click="viewRole(r)" class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> {{ $t('view') }}</button>
                  <RouterLink :to="'/admin/roles/edit/' + r.id" class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> {{ $t('edit') }}</RouterLink>
                  <button v-if="r.name !== 'admin'" @click="deleteRole(r.id)" class="px-3 py-1.5 rounded text-xs font-medium hover:opacity-85 inline-flex items-center gap-1.5 transition" :style="{ background: 'var(--accent2)', color: 'var(--accent2-ink)' }"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg> {{ $t('delete') }}</button>
                </div>
              </td>
            </tr>
            <tr v-if="!roles.length"><td colspan="5" class="text-center py-12" :style="{ color: 'var(--ink-soft)' }">No roles found.</td></tr>
          </tbody>
        </table>
      </div>

      <div v-if="viewing" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="viewing = null">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.5)"></div>
        <div class="relative rounded-xl p-6 max-w-lg w-full overflow-y-auto" :style="{ background: 'var(--surface)', color: 'var(--ink)', maxHeight: '80vh' }">
          <button @click="viewing = null" class="absolute top-3 right-4 text-2xl hover:opacity-70">&times;</button>
          <h2 class="text-2xl font-bold mb-1">{{ viewing.name }}</h2>
          <p v-if="viewing.label" class="mb-4" :style="{ color: 'var(--ink-soft)' }">{{ viewing.label }}</p>

          <div class="flex gap-4 mb-4 text-sm">
            <span class="px-3 py-1 rounded-full text-xs font-medium" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ viewing.permissions_count || 0 }} {{ $t('permissions') }}</span>
            <span class="px-3 py-1 rounded-full text-xs font-medium" :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ viewing.users_count || 0 }} {{ $t('users') }}</span>
          </div>

          <div v-if="viewing.permissions && viewing.permissions.length">
            <p class="text-sm font-semibold mb-2">{{ $t('permissions') }}</p>
            <div class="flex flex-wrap gap-1.5">
              <span v-for="p in viewing.permissions" :key="p.id"
                class="px-2 py-0.5 rounded text-xs" :style="{ background: 'var(--bg)', border: '1px solid var(--border)', color: 'var(--ink)' }">
                {{ p.label || p.name }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'

const breadcrumbs = [{ label: 'Roles', labelBn: 'ভূমিকা' }]
const admin = useAdminStore()
const router = useRouter()
const roles = ref([])
const viewing = ref(null)
const search = ref('')
let debounceTimer = null

function viewRole(r) { viewing.value = r }

function debouncedLoad() { clearTimeout(debounceTimer); debounceTimer = setTimeout(load, 300) }

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const rolesRes = await admin.fetchRoles({ search: search.value, per_page: 50 })
  roles.value = rolesRes.data || []
}

async function deleteRole(id) {
  if (confirm('Delete this role?')) { await admin.deleteRole(id); await load() }
}

onMounted(load)
</script>
