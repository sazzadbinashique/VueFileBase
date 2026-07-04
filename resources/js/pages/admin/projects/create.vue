<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ $t('new_project') }}</h1>
      <div class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <form @submit.prevent="saveProject" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">Title (EN)</label><input v-model="form.title" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">Title (BN)</label><input v-model="form.title_bn" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">Short (EN)</label><input v-model="form.short_en" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">Short (BN)</label><input v-model="form.short_bn" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('goal_amount') }}</label><input v-model.number="form.goal_amount" type="number" min="0" step="0.01" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('status') }}</label><select v-model="form.status" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option value="draft">Draft</option><option value="active">Active</option><option value="completed">Completed</option></select></div>
            <div><label class="block text-sm font-medium mb-1">Icon</label><select v-model="form.icon" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option value="utensils">Utensils</option><option value="book">Book</option><option value="heart-pulse">Heart Pulse</option><option value="droplet">Droplet</option></select></div>
            <div><label class="block text-sm font-medium mb-1">Color</label><select v-model="form.color" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option value="primary">Teal</option><option value="accent">Gold</option><option value="accent2">Crimson</option></select></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('featured_image_url') }}</label><input v-model="form.featured_image" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">Start Date</label><input v-model="form.start_date" type="date" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">End Date</label><input v-model="form.end_date" type="date" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
          </div>
          <div><label class="block text-sm font-medium mb-1">Description (EN)</label><textarea v-model="form.description" rows="4" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required></textarea></div>
          <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ $t('save') }}</button>
            <RouterLink to="/admin/projects" class="px-6 py-2 rounded hover:opacity-90 inline-block" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ $t('cancel') }}</RouterLink>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAdminStore } from '@/stores/admin'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'

const breadcrumbs = [{ label: 'Projects', labelBn: 'প্রকল্প' }, { label: 'New', labelBn: 'নতুন' }]
const router = useRouter()
const admin = useAdminStore()
const form = ref({ title: '', title_bn: '', short_en: '', short_bn: '', description: '', goal_amount: 0, featured_image: '', status: 'draft', icon: 'utensils', color: 'primary', start_date: '', end_date: '' })

async function saveProject() {
  try {
    await admin.storeProject(form.value)
    router.push('/admin/projects')
  } catch (e) { alert('Error saving project') }
}
</script>
