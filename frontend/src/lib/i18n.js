import { createI18n } from 'vue-i18n'
import * as en from '@/locales/en.json'
import * as sk from '@/locales/sk.json'

const messages = {
  en: en,
  sk: sk,
}


export let i18n = new createI18n({
  locale: 'en',
  fallbackLocale: 'en',
  messages: messages
})

export function getLocale() {
  return i18n.global.locale
}
