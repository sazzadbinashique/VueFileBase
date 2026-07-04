<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ $t('edit_page') }}</h1>
      <div v-if="page" class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
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

const breadcrumbs = [{ label: 'CMS Pages', labelBn: 'সিএমএস পৃষ্ঠা' }, { label: 'Edit', labelBn: 'সম্পাদনা' }]
const router = useRouter()
const route = useRoute()
const admin = useAdminStore()
const page = ref(null)
const form = ref({ title: '', title_bn: '', content: '', content_bn: '', meta_title: '', meta_description: '', status: 'draft' })

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  try {
    const p = await admin.fetchCmsPage(route.params.id)
    page.value = p
    form.value = { title: p.title, title_bn: p.title_bn || '', content: p.content, content_bn: p.content_bn || '', meta_title: p.meta_title || '', meta_description: p.meta_description || '', status: p.status }
  } catch (e) { router.push('/admin/cms-pages') }
})

async function savePage() {
  try {
    await admin.updateCmsPage(route.params.id, form.value)
    router.push('/admin/cms-pages')
  } catch (e) { alert('Error saving page') }
}
</script>
