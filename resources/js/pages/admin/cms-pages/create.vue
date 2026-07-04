<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ $t('new_page') }}</h1>
      <div class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <form @submit.prevent="savePage" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">{{ $t('title_en') }}</label><input v-model="form.title" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('title_bn') }}</label><input v-model="form.title_bn" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('status') }}</label><select v-model="form.status" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option value="draft">Draft</option><option value="published">Published</option></select></div>
            <div><label class="block text-sm font-medium mb-1">Meta Title</label><input v-model="form.meta_title" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium mb-1">Meta Description</label><input v-model="form.meta_description" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
          </div>
          <div><label class="block text-sm font-medium mb-1">{{ $t('content_en') }}</label><textarea v-model="form.content" rows="10" class="w-full border rounded px-3 py-2 outline-none font-mono text-sm" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required></textarea></div>
          <div><label class="block text-sm font-medium mb-1">{{ $t('content_bn') }}</label><textarea v-model="form.content_bn" rows="10" class="w-full border rounded px-3 py-2 outline-none font-mono text-sm" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"></textarea></div>
          <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ $t('save') }}</button>
            <RouterLink to="/admin/cms-pages" class="px-6 py-2 rounded hover:opacity-90 inline-block" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ $t('cancel') }}</RouterLink>
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

const breadcrumbs = [{ label: 'CMS Pages', labelBn: 'সিএমএস পৃষ্ঠা' }, { label: 'New', labelBn: 'নতুন' }]
const router = useRouter()
const admin = useAdminStore()
const form = ref({ title: '', title_bn: '', content: '', content_bn: '', meta_title: '', meta_description: '', status: 'draft' })

async function savePage() {
  try {
    await admin.storeCmsPage(form.value)
    router.push('/admin/cms-pages')
  } catch (e) { alert('Error saving page') }
}
</script>
