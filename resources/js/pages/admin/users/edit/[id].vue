<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ $t('edit_user') }}</h1>
      <div v-if="user" class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <form @submit.prevent="saveUser" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">{{ $t('name') }}</label><input v-model="form.name" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">Email</label><input v-model="form.email" type="email" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('password') }} <span class="text-xs opacity-60">(leave blank to keep)</span></label><input v-model="form.password" type="password" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('role') }}</label><select v-model="form.role" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option v-for="r in roles" :key="r.id" :value="r.name">{{ r.label || r.name }}</option></select></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('status') }}</label><select v-model="form.status" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option :value="1">{{ $t('active') }}</option><option :value="0">{{ $t('inactive') }}</option></select></div>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ $t('save') }}</button>
            <RouterLink to="/admin/users" class="px-6 py-2 rounded hover:opacity-90 inline-block" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ $t('cancel') }}</RouterLink>
          </div>
        </form>
      </div>
      <div v-else class="text-center py-12">
        <span class="inline-block w-6 h-6 border-2 rounded-full animate-spin" :style="{ borderColor: 'var(--primary)', borderTopColor: 'transparent' }"></span>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAdminStore } from '@/stores/admin'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'

const breadcrumbs = [{ label: 'Users', labelBn: 'ব্যবহারকারী' }, { label: 'Edit', labelBn: 'সম্পাদনা' }]
const router = useRouter()
const route = useRoute()
const admin = useAdminStore()
const user = ref(null)
const roles = ref([])
const form = ref({ name: '', email: '', password: '', role: 'user', status: 1 })

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  try {
    const [rolesRes, u] = await Promise.all([
      admin.fetchRoles({ per_page: 50 }),
      admin.fetchUser(route.params.id),
    ])
    roles.value = rolesRes.data || []
    user.value = u
    form.value = { name: u.name, email: u.email, password: '', role: u.role, status: u.status ? 1 : 0 }
  } catch (e) { router.push('/admin/users') }
})

async function saveUser() {
  try {
    const payload = { ...form.value }
    if (!payload.password) delete payload.password
    await admin.updateUser(route.params.id, payload)
    router.push('/admin/users')
  } catch (e) { alert('Error saving user') }
}
</script>
