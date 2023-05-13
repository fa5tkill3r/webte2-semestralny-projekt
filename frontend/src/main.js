/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Components
import App from './App.vue'

// Composables
import { createApp } from 'vue'

// Plugins
import { registerPlugins } from '@/plugins'
import { useAppStore } from '@/store/app'
import pinia from './store'

const app = createApp(App)
  .use(pinia)

useAppStore().refresh().then(() => {
  registerPlugins(app)
  app.mount('#app')
})

