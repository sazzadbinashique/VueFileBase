import { defineStore } from 'pinia'
import axios from 'axios'

export const useAdminStore = defineStore('admin', {
  state: () => ({ user: null, token: null, isLoggedIn: false }),
  actions: {
    async login(email, password) {
      const { data } = await axios.post('/api/admin/login', { email, password })
      this.setAuth(data.user, data.token)
      return data
    },

    async init() {
      const saved = localStorage.getItem('admin_token')
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
      localStorage.setItem('admin_token', token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    },

    clearAuth() {
      this.user = null
      this.token = null
      this.isLoggedIn = false
      localStorage.removeItem('admin_token')
      delete axios.defaults.headers.common['Authorization']
    },
    async fetchDashboard() {
      const { data } = await axios.get('/api/admin/dashboard')
      return data
    },
    async fetchProjects(params = {}) {
      const { data } = await axios.get('/api/admin/projects', { params })
      return data
    },
    async storeProject(project) {
      const { data } = await axios.post('/api/admin/projects', project)
      return data
    },
    async updateProject(id, project) {
      const { data } = await axios.put(`/api/admin/projects/${id}`, project)
      return data
    },
    async deleteProject(id) {
      await axios.delete(`/api/admin/projects/${id}`)
    },
    async fetchDonations(params = {}) {
      const { data } = await axios.get('/api/admin/donations', { params })
      return data
    },
    async fetchGallery(params = {}) {
      const { data } = await axios.get('/api/admin/gallery', { params })
      return data
    },
    async storeGalleryImage(image) {
      const { data } = await axios.post('/api/admin/gallery', image)
      return data
    },
    async updateGalleryImage(id, image) {
      const { data } = await axios.put(`/api/admin/gallery/${id}`, image)
      return data
    },
    async deleteGalleryImage(id) {
      await axios.delete(`/api/admin/gallery/${id}`)
    },
    async fetchVideos(params = {}) {
      const { data } = await axios.get('/api/admin/videos', { params })
      return data
    },
    async storeVideo(video) {
      const { data } = await axios.post('/api/admin/videos', video)
      return data
    },
    async updateVideo(id, video) {
      const { data } = await axios.put(`/api/admin/videos/${id}`, video)
      return data
    },
    async deleteVideo(id) {
      await axios.delete(`/api/admin/videos/${id}`)
    },
    async fetchCmsPages(params = {}) {
      const { data } = await axios.get('/api/admin/cms-pages', { params })
      return data
    },
    async storeCmsPage(page) {
      const { data } = await axios.post('/api/admin/cms-pages', page)
      return data
    },
    async updateCmsPage(id, page) {
      const { data } = await axios.put(`/api/admin/cms-pages/${id}`, page)
      return data
    },
    async deleteCmsPage(id) {
      await axios.delete(`/api/admin/cms-pages/${id}`)
    },
    async fetchUsers(params = {}) {
      const { data } = await axios.get('/api/admin/users', { params })
      return data
    },
    async storeUser(user) {
      const { data } = await axios.post('/api/admin/users', user)
      return data
    },
    async updateUser(id, user) {
      const { data } = await axios.put(`/api/admin/users/${id}`, user)
      return data
    },
    async deleteUser(id) {
      await axios.delete(`/api/admin/users/${id}`)
    },
    async fetchRoles(params = {}) {
      const { data } = await axios.get('/api/admin/roles', { params })
      return data
    },
    async fetchPermissions() {
      const { data } = await axios.get('/api/admin/roles/permissions')
      return data
    },
    async storeRole(role) {
      const { data } = await axios.post('/api/admin/roles', role)
      return data
    },
    async updateRole(id, role) {
      const { data } = await axios.put(`/api/admin/roles/${id}`, role)
      return data
    },
    async deleteRole(id) {
      await axios.delete(`/api/admin/roles/${id}`)
    },
    logout() {
      this.clearAuth()
    },
  },
})
