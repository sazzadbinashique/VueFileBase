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
      this.setUser(data.user, data.token)
      return data
    },
    async login(email, password) {
      const { data } = await axios.post('/api/login', { email, password })
      this.setUser(data.user, data.token)
      return data
    },
    setUser(user, token) {
      this.user = user
      this.token = token
      this.isLoggedIn = true
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    },
    async fetchUser() {
      const { data } = await axios.get('/api/user')
      this.user = data
      this.isLoggedIn = true
    },
    async logout() {
      await axios.post('/api/logout')
      this.user = null
      this.token = null
      this.isLoggedIn = false
      delete axios.defaults.headers.common['Authorization']
    },
  },
})
