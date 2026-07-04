import { createI18n } from 'vue-i18n'
import en from './translations/en.json'
import bn from './translations/bn.json'

const savedLocale = typeof localStorage !== 'undefined'
  ? (localStorage.getItem('locale') || 'en')
  : 'en'

export const i18n = createI18n({
  locale: savedLocale,
  fallbackLocale: 'en',
  messages: { en, bn },
  legacy: false,
  globalInjection: true,
})
