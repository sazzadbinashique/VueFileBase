import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    isLoggedIn: false,
  }),
  getters: {
    isAdmin: (state) => state.user?.role === 'admin',
  },
  actions: {
    async register(name, email, password, passwordConfirmation) {
      const { data } = await axios.post('/api/register', { name, email, password, password_confirmation: passwordConfirmation })
      this.setAuth(data.user, data.token)
      return data
    },
    async login(email, password) {
      const { data } = await axios.post('/api/login', { email, password })
      this.setAuth(data.user, data.token)
      return data
    },

    async init() {
      const saved = localStorage.getItem('auth_token')
      if (!saved) return
      this.token = saved
      this.isLoggedIn = true
      axios.defaults.headers.common['Authorization'] = `Bearer ${saved}`
      try {
        const { data } = await axios.get('/api/user')
        this.user = data
      } catch {
        this.clearAuth()
      }
    },

    setAuth(user, token) {
      this.user = user
      this.token = token
      this.isLoggedIn = true
      localStorage.setItem('auth_token', token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    },

    clearAuth() {
      this.user = null
      this.token = null
      this.isLoggedIn = false
      localStorage.removeItem('auth_token')
      delete axios.defaults.headers.common['Authorization']
    },

    async fetchUser() {
      const { data } = await axios.get('/api/user')
      this.user = data
      this.isLoggedIn = true
    },
    async logout() {
      await axios.post('/api/logout')
      this.clearAuth()
    },
  },
})
