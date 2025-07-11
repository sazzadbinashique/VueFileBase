// stores/auth.js
import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isLoggedIn: false,
  }),
  actions: {
    async fetchUser() {
      const { data } = await axios.get('/api/user')
      this.user = data
      this.isLoggedIn = true
    },
    async logout() {
      await axios.post('/logout')
      this.user = null
      this.isLoggedIn = false
    },
  }
})
