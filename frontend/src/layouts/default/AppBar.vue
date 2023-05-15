<template>
  <v-app-bar flat>
    <v-app-bar-title>
      <v-icon icon="mdi-circle-slice-6" />

      {{ $t('finalAssignment') }}
    </v-app-bar-title>

    <v-spacer />
    <div v-if="!store.user">
      <v-btn @click="toggleImage">
        <v-img
          style="border-style: solid; border-color: black; border-width: 1px"
          width="25"
          :src="currentImage"
          alt="Image"
        />
      </v-btn>
      <v-btn to="/login"> {{ $t('login') }} </v-btn>
      <v-btn to="/register"> {{ $t('register') }} </v-btn>
    </div>
    <div v-else>
      <v-btn @click="toggleImage">
        <v-img
          style="border-style: solid; border-color: black; border-width: 1px"
          width="25"
          :src="currentImage"
          alt="Image"
        />
      </v-btn>
      <v-btn to="/assignments"> {{ $t('Assignments') }} </v-btn>
      <v-btn :loading="loading" @click="logout"> {{ $t('Logout') }} </v-btn>
    </div>
  </v-app-bar>
</template>

<script setup>
import { useAppStore } from '@/store/app'
import { ref, getCurrentInstance } from 'vue'
import router from '@/router'
import enImage from '@/assets/en.jpg'
import skImage from '@/assets/sk.jpg'

const { proxy } = getCurrentInstance()

const store = useAppStore()

const loading = ref(false)

const currentImage = ref(enImage)

const toggleImage = () => {
  proxy.$i18n.locale = proxy.$i18n.locale === 'en' ? 'sk' : 'en'
  if (proxy.$i18n.locale === 'sk') {
    currentImage.value = enImage
  } else {
    currentImage.value = skImage
  }
}

const logout = async () => {
  try {
    loading.value = true
    await store.logout()
    await router.push({ name: 'Login' })
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}
</script>
