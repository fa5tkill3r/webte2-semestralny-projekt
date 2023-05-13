<template>
  <v-app-bar flat>
    <v-app-bar-title>
      <v-icon icon='mdi-circle-slice-6' />

      Zadanie Final
    </v-app-bar-title>

    <v-spacer />
    <div v-if='!store.user'>
      <v-btn to='/login'>
        Login
      </v-btn>

      <v-btn to='/register'>
        Register
      </v-btn>
    </div>
    <div v-else>
      <v-btn to='/assignments'>
        Assignments
      </v-btn>
      <v-btn to='/generate'>
        Generate
      </v-btn>

      <v-btn :loading='loading' @click='logout'>
        Logout
      </v-btn>
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
