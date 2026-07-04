<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ $t('edit_image') }}</h1>
      <div v-if="image" class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <form @submit.prevent="saveImage" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">Title (EN)</label><input v-model="form.title" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">Title (BN)</label><input v-model="form.title_bn" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('image_url') }}</label><input v-model="form.image_path" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" required /></div>
            <div><label class="block text-sm font-medium mb-1">{{ $t('project') }}</label><select v-model="form.project_id" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"><option :value="null">-- None --</option><option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option></select></div>
          </div>
          <div><label class="block text-sm font-medium mb-1">Description (EN)</label><textarea v-model="form.description" rows="2" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"></textarea></div>
          <div><label class="block text-sm font-medium mb-1">Description (BN)</label><textarea v-model="form.description_bn" rows="2" class="w-full border rounded px-3 py-2 outline-none" :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"></textarea></div>
          <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold hover:opacity-90" :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }">{{ $t('save') }}</button>
            <RouterLink to="/admin/gallery" class="px-6 py-2 rounded hover:opacity-90 inline-block" :style="{ background: 'var(--surface)', border: '1px solid var(--border)', color: 'var(--ink)' }">{{ $t('cancel') }}</RouterLink>
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

const breadcrumbs = [{ label: 'Gallery', labelBn: 'গ্যালারি' }, { label: 'Edit', labelBn: 'সম্পাদনা' }]
const router = useRouter()
const route = useRoute()
const admin = useAdminStore()
const image = ref(null)
const projects = ref([])
const form = ref({ title: '', title_bn: '', description: '', description_bn: '', image_path: '', project_id: null })

onMounted(async () => {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  try {
    const [projRes, img] = await Promise.all([
      admin.fetchProjects({ per_page: 100 }),
      admin.fetchGalleryImage(route.params.id),
    ])
    projects.value = projRes.data || []
    image.value = img
    form.value = { title: img.title, title_bn: img.title_bn || '', description: img.description || '', description_bn: img.description_bn || '', image_path: img.image_path, project_id: img.project_id }
  } catch (e) { router.push('/admin/gallery') }
})

async function saveImage() {
  try {
    await admin.updateGalleryImage(route.params.id, form.value)
    router.push('/admin/gallery')
  } catch (e) { alert('Error saving image') }
}
</script>
