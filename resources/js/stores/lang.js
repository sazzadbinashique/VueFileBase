import { defineStore } from 'pinia'
import { ref, watch } from 'vue'

export const useLangStore = defineStore('lang', () => {
  const locale = ref(localStorage.getItem('locale') || 'en')

  function setLocale(l) {
    locale.value = l
    localStorage.setItem('locale', l)
    document.documentElement.lang = l
  }

  function t(en, bn) {
    return locale.value === 'bn' ? bn : en
  }

  if (typeof document !== 'undefined') {
    document.documentElement.lang = locale.value
    watch(locale, (l) => { document.documentElement.lang = l })
  }

  return { locale, setLocale, t }
})
