// Utilities
import { defineStore } from 'pinia'
import { ky, setDefaults } from '@/lib/ky'

export const useAppStore = defineStore('app', {
  state: () => ({
    user: null,
    registeredNow: false,
  }),
  getters: {},
  actions: {
    async login(email, password) {
      const response = await ky.post('auth/login', { json: { email, password } }).json()
      console.log(response)
      this.user = response.user
      sessionStorage.setItem('token', response.token)
      await this.$router.push({ name: 'Home' })
      return this.user
    },
    async refresh() {
      const token = sessionStorage.getItem('token')
      if (!token) {
        return
      }

      try {
        console.log(token)

        const response = await ky.get('auth/refresh').json()
        console.log(response)
        this.user = response.user
        sessionStorage.setItem('token', response.token)

        await this.$router.push({ name: 'Home' })

        return this.user
      } catch (error) {
        return error
      }
    },
    async logout() {
      try {
        await ky.post('auth/logout')
        this.user = null
        this.token = null
        sessionStorage.removeItem('token')
        await this.$router.push({ name: 'Login' })
      } catch (error) {
        return error
      }
    },
  },
})
