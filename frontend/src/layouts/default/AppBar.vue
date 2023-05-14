<template>
  <v-app-bar flat>
    <v-app-bar-title>
      <v-icon icon="mdi-circle-slice-6" />

      {{$t('finalAssignment')}}
    </v-app-bar-title>

    <v-spacer />
    <div v-if="!store.user">
      <v-btn to="/login"> {{$t('login')}} </v-btn>

      <v-btn to="/register"> {{$t('register')}} </v-btn>
    </div>
    <div v-else>
      <v-btn type="submit" @click="$i18n.locale = 'en'">{{
        $t('english')
      }}</v-btn>
      &nbsp;
      <v-btn type="submit" @click="$i18n.locale = 'sk'">{{
        $t('slovak')
      }}</v-btn>
      <v-btn to="/assignments"> {{$t('Assignments')}} </v-btn>
      <v-btn to="/generate"> {{$t('Generate')}} </v-btn>

      <v-btn :loading="loading" @click="logout"> {{$t('Logout')}} </v-btn>
    </div>
  </v-app-bar>
</template>

<script setup>
import { useAppStore } from '@/store/app'
import { ref } from 'vue'
import router from '@/router'

const store = useAppStore()

const loading = ref(false)

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
