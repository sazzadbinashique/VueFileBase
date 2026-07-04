<template>
  <AdminLayout>
    <template #breadcrumb><AdminBreadcrumb :items="breadcrumbs" /></template>
    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold mb-6" :style="{ color: 'var(--ink)' }">{{ $t('settings') }}</h1>

      <div class="flex gap-1 mb-6 p-1 rounded-lg" :style="{ background: 'var(--bg)', border: '1px solid var(--border)' }">
        <button v-for="tab in tabs" :key="tab.group"
          @click="activeTab = tab.group"
          class="flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-all"
          :style="activeTab === tab.group ? { background: 'var(--primary)', color: 'var(--primary-ink)' } : { color: 'var(--ink-soft)' }">
          <span v-html="tab.icon" class="shrink-0"></span>
          {{ $t(tab.titleKey) }}
        </button>
      </div>

      <!-- Site Settings Tab -->
      <div v-if="activeTab === 'site'" class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <div class="flex items-center gap-2 mb-4 pb-3 border-b" :style="{ borderColor: 'var(--border)' }">
          <span v-html="tabs[0].icon" class="shrink-0" :style="{ color: 'var(--primary)' }"></span>
          <h2 class="text-lg font-semibold" :style="{ color: 'var(--ink)' }">{{ $t('site_settings') }}</h2>
        </div>
        <form @submit.prevent="saveGroup('site')" class="space-y-4">
          <div v-for="field in siteFields" :key="field.key" class="grid grid-cols-1 md:grid-cols-4 gap-2 items-center">
            <label class="text-sm font-medium md:col-span-1" :style="{ color: 'var(--ink)' }">{{ field.label }}</label>
            <div class="md:col-span-3">
              <textarea v-if="field.type === 'textarea'" v-model="field.value" rows="2"
                class="w-full border rounded px-3 py-2 outline-none text-sm"
                :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }"></textarea>
              <input v-else v-model="field.value"
                class="w-full border rounded px-3 py-2 outline-none text-sm"
                :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" />
            </div>
          </div>
          <div class="flex justify-end pt-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold text-sm hover:opacity-90"
              :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }"
              :disabled="saving === 'site'">
              <span v-if="saving === 'site'" class="inline-block w-4 h-4 border-2 rounded-full animate-spin align-middle mr-1" :style="{ borderColor: 'var(--primary-ink)', borderTopColor: 'transparent' }"></span>
              {{ $t('save') }}
            </button>
          </div>
        </form>
      </div>

      <!-- Payment Tab -->
      <div v-if="activeTab === 'payment'" class="space-y-6">
        <div v-for="sub in paymentSubsections" :key="sub.key"
          class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
          <div class="flex items-center gap-2 mb-4 pb-3 border-b" :style="{ borderColor: 'var(--border)' }">
            <span v-html="sub.icon" class="shrink-0" :style="{ color: 'var(--primary)' }"></span>
            <h2 class="text-lg font-semibold" :style="{ color: 'var(--ink)' }">{{ sub.label }}</h2>
          </div>
          <form @submit.prevent="saveGroup('payment')" class="space-y-4">
            <div v-for="field in sub.fields" :key="field.key" class="grid grid-cols-1 md:grid-cols-4 gap-2 items-center">
              <label class="text-sm font-medium md:col-span-1" :style="{ color: 'var(--ink)' }">{{ field.label }}</label>
              <div class="md:col-span-3">
                <input v-if="field.type === 'password'" :type="showPass[field.key] ? 'text' : 'password'"
                  v-model="field.value" class="w-full border rounded px-3 py-2 outline-none text-sm"
                  :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" />
                <div v-else-if="field.type === 'boolean'" class="flex items-center gap-2">
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" v-model="field.value" class="sr-only peer" />
                    <div class="w-9 h-5 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all" :class="field.value ? 'bg-green-500' : 'bg-gray-300'"></div>
                  </label>
                  <span class="text-xs" :style="{ color: 'var(--ink-soft)' }">{{ field.value ? 'On' : 'Off' }}</span>
                </div>
              </div>
            </div>
            <div class="flex justify-end pt-2">
              <button type="submit" class="px-6 py-2 rounded font-semibold text-sm hover:opacity-90"
                :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }"
                :disabled="saving === 'payment'">
                <span v-if="saving === 'payment'" class="inline-block w-4 h-4 border-2 rounded-full animate-spin align-middle mr-1" :style="{ borderColor: 'var(--primary-ink)', borderTopColor: 'transparent' }"></span>
                {{ $t('save') }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Mail Settings Tab -->
      <div v-if="activeTab === 'mail'" class="rounded-lg p-6" :style="{ background: 'var(--surface)', border: '1px solid var(--border)' }">
        <div class="flex items-center gap-2 mb-4 pb-3 border-b" :style="{ borderColor: 'var(--border)' }">
          <span v-html="tabs[2].icon" class="shrink-0" :style="{ color: 'var(--primary)' }"></span>
          <h2 class="text-lg font-semibold" :style="{ color: 'var(--ink)' }">{{ $t('mail_settings') }}</h2>
        </div>
        <form @submit.prevent="saveGroup('mail')" class="space-y-4">
          <div v-for="field in mailFields" :key="field.key" class="grid grid-cols-1 md:grid-cols-4 gap-2 items-center">
            <label class="text-sm font-medium md:col-span-1" :style="{ color: 'var(--ink)' }">{{ field.label }}</label>
            <div class="md:col-span-3">
              <input v-if="field.type === 'password'" :type="showPass[field.key] ? 'text' : 'password'"
                v-model="field.value" class="w-full border rounded px-3 py-2 outline-none text-sm"
                :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" />
              <select v-else-if="field.type === 'select'" v-model="field.value"
                class="w-full border rounded px-3 py-2 outline-none text-sm"
                :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }">
                <option v-for="opt in field.options" :key="opt" :value="opt">{{ opt || '(none)' }}</option>
              </select>
              <input v-else v-model="field.value"
                class="w-full border rounded px-3 py-2 outline-none text-sm"
                :style="{ borderColor: 'var(--border)', background: 'var(--bg)', color: 'var(--ink)' }" />
            </div>
          </div>
          <div class="flex justify-end pt-2">
            <button type="submit" class="px-6 py-2 rounded font-semibold text-sm hover:opacity-90"
              :style="{ background: 'var(--primary)', color: 'var(--primary-ink)' }"
              :disabled="saving === 'mail'">
              <span v-if="saving === 'mail'" class="inline-block w-4 h-4 border-2 rounded-full animate-spin align-middle mr-1" :style="{ borderColor: 'var(--primary-ink)', borderTopColor: 'transparent' }"></span>
              {{ $t('save') }}
            </button>
          </div>
        </form>
      </div>

      <div v-if="flash" class="fixed bottom-6 right-6 px-4 py-2 rounded-lg text-sm font-medium shadow-lg z-50 animate-fade-in"
        :style="{ background: flash.type === 'success' ? 'var(--primary)' : 'var(--accent2)', color: flash.type === 'success' ? 'var(--primary-ink)' : 'var(--accent2-ink)' }">
        {{ flash.message }}
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import AdminBreadcrumb from '@/components/admin/AdminBreadcrumb.vue'

const breadcrumbs = [{ label: 'Settings', labelBn: 'সেটিংস' }]
const admin = useAdminStore()
const router = useRouter()

const activeTab = ref('site')
const saving = ref(null)
const flash = ref(null)
const showPass = reactive({})

const siteFields = ref([])
const mailFields = ref([])
const paymentSubsections = ref([])

const tabs = [
  { titleKey: 'site_settings', group: 'site', icon: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.32 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/></svg>' },
  { titleKey: 'payment_credentials', group: 'payment', icon: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>' },
  { titleKey: 'mail_settings', group: 'mail', icon: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>' },
]

function setFlash(type, message) {
  flash.value = { type, message }
  setTimeout(() => { flash.value = null }, 3000)
}

async function load() {
  if (!admin.isLoggedIn) { router.push('/admin/login'); return }
  try {
    const data = await admin.fetchSettings()
    const byGroup = {}
    for (const group in data) {
      for (const s of data[group]) {
        if (!byGroup[group]) byGroup[group] = {}
        byGroup[group][s.key] = s.value ?? ''
      }
    }

    siteFields.value = [
      { key: 'site_name', label: 'Site Name', value: byGroup.site?.site_name ?? '' },
      { key: 'site_description', label: 'Site Description', type: 'textarea', value: byGroup.site?.site_description ?? '' },
      { key: 'logo_url', label: 'Logo URL', value: byGroup.site?.logo_url ?? '' },
      { key: 'favicon_url', label: 'Favicon URL', value: byGroup.site?.favicon_url ?? '' },
      { key: 'address', label: 'Address', type: 'textarea', value: byGroup.site?.address ?? '' },
      { key: 'phone', label: 'Phone', value: byGroup.site?.phone ?? '' },
      { key: 'email', label: 'Email', value: byGroup.site?.email ?? '' },
    ]

    paymentSubsections.value = [
      {
        key: 'stripe',
        label: 'Stripe',
        icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>',
        fields: [
          { key: 'stripe_key', label: 'Publishable Key', type: 'password', value: byGroup.payment?.stripe_key ?? '' },
          { key: 'stripe_secret', label: 'Secret Key', type: 'password', value: byGroup.payment?.stripe_secret ?? '' },
          { key: 'stripe_webhook_secret', label: 'Webhook Secret', type: 'password', value: byGroup.payment?.stripe_webhook_secret ?? '' },
        ],
      },
      {
        key: 'sslcommerz',
        label: 'SSLCommerz',
        icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        fields: [
          { key: 'sslcommerz_sandbox', label: 'Sandbox Mode', type: 'boolean', value: byGroup.payment?.sslcommerz_sandbox === '1' || byGroup.payment?.sslcommerz_sandbox === true },
          { key: 'sslcommerz_store_id', label: 'Store ID', type: 'password', value: byGroup.payment?.sslcommerz_store_id ?? '' },
          { key: 'sslcommerz_store_password', label: 'Store Password', type: 'password', value: byGroup.payment?.sslcommerz_store_password ?? '' },
        ],
      },
    ]

    mailFields.value = [
      { key: 'mail_mailer', label: 'Mailer', type: 'select', options: ['smtp', 'log', 'sendmail', 'ses', 'postmark'], value: byGroup.mail?.mail_mailer ?? 'log' },
      { key: 'mail_host', label: 'Host', value: byGroup.mail?.mail_host ?? '' },
      { key: 'mail_port', label: 'Port', value: byGroup.mail?.mail_port ?? '' },
      { key: 'mail_username', label: 'Username', value: byGroup.mail?.mail_username ?? '' },
      { key: 'mail_password', label: 'Password', type: 'password', value: byGroup.mail?.mail_password ?? '' },
      { key: 'mail_encryption', label: 'Encryption', type: 'select', options: ['tls', 'ssl', ''], value: byGroup.mail?.mail_encryption ?? '' },
      { key: 'mail_from_address', label: 'From Address', value: byGroup.mail?.mail_from_address ?? '' },
      { key: 'mail_from_name', label: 'From Name', value: byGroup.mail?.mail_from_name ?? '' },
    ]

    for (const f of [...mailFields.value]) {
      if (f.type === 'password') showPass[f.key] = false
    }
    for (const sub of paymentSubsections.value) {
      for (const f of sub.fields) {
        if (f.type === 'password') showPass[f.key] = false
      }
    }
  } catch (e) { /* handled */ }
}

async function saveGroup(group) {
  saving.value = group
  try {
    const settings = {}
    if (group === 'site') {
      for (const f of siteFields.value) settings[f.key] = f.value
    } else if (group === 'payment') {
      for (const sub of paymentSubsections.value) {
        for (const f of sub.fields) {
          settings[f.key] = f.type === 'boolean' ? (f.value ? '1' : '0') : f.value
        }
      }
    } else if (group === 'mail') {
      for (const f of mailFields.value) settings[f.key] = f.type === 'boolean' ? (f.value ? '1' : '0') : f.value
    }
    await admin.updateSettings(group, settings)
    setFlash('success', 'Settings saved successfully.')
  } catch (e) {
    setFlash('error', 'Failed to save settings.')
  } finally {
    saving.value = null
  }
}

onMounted(load)
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
