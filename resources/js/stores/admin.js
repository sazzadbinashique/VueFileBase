import { defineStore } from 'pinia'
import axios from 'axios'

export const useAdminStore = defineStore('admin', {
  state: () => ({ user: null, token: null, isLoggedIn: false }),
  actions: {
    async login(email, password) {
      const { data } = await axios.post('/api/admin/login', { email, password })
      this.user = data.user
      this.token = data.token
      this.isLoggedIn = true
      axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
      return data
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
    logout() {
      this.user = null
      this.token = null
      this.isLoggedIn = false
      delete axios.defaults.headers.common['Authorization']
    },
  },
})
