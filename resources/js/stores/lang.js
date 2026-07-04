import { defineStore } from 'pinia'
import { computed, watch } from 'vue'
import { useI18n } from 'vue-i18n'

export const useLangStore = defineStore('lang', () => {
  const i18n = useI18n()

  const locale = computed({
    get: () => i18n.locale.value,
    set: (l) => setLocale(l),
  })

  function setLocale(l) {
    i18n.locale.value = l
    localStorage.setItem('locale', l)
    document.documentElement.lang = l
  }

  function t(en, bn) {
    if (!bn) return i18n.t(en)
    return i18n.locale.value === 'bn' ? bn : en
  }

  function f(obj, attr) {
    if (!obj) return ''
    const bnKey = attr + '_bn'
    return i18n.locale.value === 'bn' && obj[bnKey] ? obj[bnKey] : obj[attr]
  }

  function localeFromRoute(routeLocale) {
    if (routeLocale && ['en', 'bn'].includes(routeLocale)) {
      setLocale(routeLocale)
    }
  }

  if (typeof document !== 'undefined') {
    document.documentElement.lang = i18n.locale.value
    watch(() => i18n.locale.value, (l) => { document.documentElement.lang = l })
  }

  return { locale, setLocale, t, f, localeFromRoute }
})
