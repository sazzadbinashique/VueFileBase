<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-5xl">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-3xl font-bold" :style="{ color: 'var(--ink)' }">{{ lang.t('Roles', 'ভূমিকা') }}</h1>
        <button @click="openForm(null)" class="px-4 py-2 rounded font-semibold hover:opacity-90 whitespace-nowrap"
          :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">+ {{ lang.t('New Role', 'নতুন ভূমিকা') }}</button>
      </div>

      <div v-if="showForm" class="rounded-lg p-6 mb-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <h2 class="text-xl font-semibold mb-4">{{ editing ? lang.t('Edit Role', 'ভূমিকা সম্পাদনা') : lang.t('New Role', 'নতুন ভূমিকা') }}</h2>
        <form @submit.prevent="saveRole" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Name', 'নাম') }}</label><input v-model="form.name" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ lang.t('Label', 'লেবেল') }}</label><input v-model="form.label" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
          </div>
          <div>
            <p class="text-sm font-medium mb-2">{{ lang.t('Permissions', 'অনুমতি') }}</p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
              <label v-for="perm in allPermissions" :key="perm.id" class="flex items-center gap-2 text-sm cursor-pointer p-2 rounded hover:opacity-80" :style="{ border: '1px solid var(--border)' }">
                <input type="checkbox" :value="perm.id" v-model="form.permissions" class="accent-current" />
                {{ perm.label || perm.name }}
              </label>
            </div>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ lang.t('Save', 'সংরক্ষণ') }}</button>
            <button type="button" @click="showForm = false" class="px-6 py-2 rounded hover:opacity-90" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ lang.t('Cancel', 'বাতিল') }}</button>
          </div>
        </form>
      </div>

      <div class="rounded-lg overflow-hidden" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <table class="w-full text-sm">
          <thead><tr class="border-b text-left" :style="{ borderColor: 'var(--border)' }">
            <th class="py-3 px-4">{{ lang.t('Name', 'নাম') }}</th>
            <th class="py-3 px-4">{{ lang.t('Label', 'লেবেল') }}</th>
            <th class="py-3 px-4">{{ lang.t('Permissions', 'অনুমতি') }}</th>
            <th class="py-3 px-4">{{ lang.t('Users', 'ব্যবহারকারী') }}</th>
            <th class="py-3 px-4">{{ lang.t('Actions', 'কর্ম') }}</th>
          </tr></thead>
          <tbody>
            <tr v-for="r in roles" :key="r.id" class="border-b hover:opacity-80" :style="{ borderColor: 'var(--border)' }">
              <td class="py-3 px-4 font-medium">{{ r.name }}</td>
              <td class="py-3 px-4" :style="{ color: 'var(--ink-soft)' }">{{ r.label }}</td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--primary)' }">{{ r.permissions_count || 0 }}</td>
              <td class="py-3 px-4 text-xs" :style="{ color: 'var(--ink-soft)' }">{{ r.users_count || 0 }}</td>
              <td class="py-3 px-4 flex gap-3">
                <button @click="openForm(r)" class="text-xs hover:opacity-80" :style="{ color: 'var(--primary)' }">{{ lang.t('Edit', 'সম্পাদনা') }}</button>
                <button v-if="r.name !== 'admin'" @click="deleteRole(r.id)" class="text-xs hover:opacity-80" :style="{ color: 'var(--accent2)' }">{{ lang.t('Delete', 'মুছুন') }}</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useLangStore } from '@/stores/lang'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'

const breadcrumbs = [{ label: 'Roles', labelBn: 'ভূমিকা' }]
const admin = useAdminStore()
const lang = useLangStore()
const router = useRouter()
const roles = ref([])
const allPermissions = ref([])
const showForm = ref(false)
const editing = ref(null)
const form = ref({ name: '', label: '', permissions: [] })

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const [rolesRes, permsRes] = await Promise.all([
    admin.fetchRoles({ per_page: 50 }),
    admin.fetchPermissions(),
  ])
  roles.value = rolesRes.data || []
  allPermissions.value = permsRes || []
}

function openForm(r) {
  if (r) {
    editing.value = r.id
    form.value = {
      name: r.name,
      label: r.label || '',
      permissions: (r.permissions || []).map(p => p.id),
    }
  } else {
    editing.value = null
    form.value = { name: '', label: '', permissions: [] }
  }
  showForm.value = true
}

async function saveRole() {
  try {
    if (editing.value) {
      await admin.updateRole(editing.value, form.value)
    } else {
      await admin.storeRole(form.value)
    }
    showForm.value = false
    await load()
  } catch (e) { alert('Error saving role') }
}

async function deleteRole(id) {
  if (confirm('Delete this role?')) {
    await admin.deleteRole(id)
    await load()
  }
}

onMounted(load)
</script>
