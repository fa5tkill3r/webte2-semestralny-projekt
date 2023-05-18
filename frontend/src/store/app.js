// Utilities
import { defineStore } from 'pinia'
import { ky } from '@/lib/ky'
import jwtDecode from 'jwt-decode'

export const useAppStore = defineStore('app', {
  state: () => ({
    user: null,
    registeredNow: false,
  }),
  getters: {
    token: () => sessionStorage.getItem('token'),
    role() {
      // noinspection JSCheckFunctionSignatures
      return jwtDecode(this.token).role
    }

  },
  actions: {
    async login(email, password) {
      const response = await ky.post('auth/login', { json: { email, password } }).json()
      this.user = response.user
      sessionStorage.setItem('token', response.token)
      return this.user
    },
    async refresh() {
      const token = sessionStorage.getItem('token')
      if (!token) {
        return
      }

      try {
        const response = await ky.get('auth/refresh').json()
        this.user = response.user
        sessionStorage.setItem('token', response.token)

        return this.user
      } catch (error) {
        return error
      }
    },
    async logout() {
      try {
        await ky.post('auth/logout')
        this.user = null
        sessionStorage.removeItem('token')
      } catch (error) {
        return error
      }
    },
  },
})
