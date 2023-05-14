import { createI18n } from 'vue-i18n'
import * as en from '@/locales/en.json'
import * as sk from '@/locales/sk.json'

const messages = {
  en: en,
  sk: sk,
}


const i18n = new createI18n({
  locale: 'en',
  fallbackLocale: 'en',
  messages: messages
})

export default i18n
