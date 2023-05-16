/**
 * plugins/index.js
 *
 * Automatically included in `./src/main.js`
 */

// Plugins
import { loadFonts } from './webfontloader'
import vuetify from './vuetify'
import router from '../router'
import { i18n } from '@/lib/i18n'

export function registerPlugins (app) {
  loadFonts()
  app
    .use(vuetify)
    .use(router)
    .use(i18n)
}
