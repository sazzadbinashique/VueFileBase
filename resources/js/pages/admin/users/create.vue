<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ $t('new_user') }}</h1>
      <div class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <form @submit.prevent="saveUser" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">{{ $t('name') }}</label><input v-model="form.name" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">Email</label><input v-model="form.email" type="email" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('password') }}</label><input v-model="form.password" type="password" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('role') }}</label><select v-model="form.role" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option v-for="r in roles" :key="r.id" :value="r.name">{{ r.label || r.name }}</option></select></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('status') }}</label><select v-model="form.status" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option :value="1">{{ $t('active') }}</option><option :value="0">{{ $t('inactive') }}</option></select></div>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ $t('save') }}</button>
            <RouterLink to="/admin/users" class="px-6 py-2 rounded hover:opacity-90 inline-block" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ $t('cancel') }}</RouterLink>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAdminStore } from '@/stores/admin'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'

const breadcrumbs = [{ label: 'Users', labelBn: 'ব্যবহারকারী' }, { label: 'New', labelBn: 'নতুন' }]
const router = useRouter()
const admin = useAdminStore()
const roles = ref([])
const form = ref({ name: '', email: '', password: '', role: 'user', status: 1 })

async function saveUser() {
  try {
    await admin.storeUser(form.value)
    router.push('/admin/users')
  } catch (e) { alert('Error saving user') }
}

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  const rolesRes = await admin.fetchRoles({ per_page: 50 })
  roles.value = rolesRes.data || []
})
</script>
