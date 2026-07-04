import { createApp } from 'vue'
import App from './App.vue'
import { createPinia } from 'pinia'
import { createRouter, createWebHistory } from 'vue-router'
import routes from 'virtual:generated-pages'
import { i18n } from './i18n'
import '../css/app.css'

const router = createRouter({
  history: createWebHistory(),
  routes,
})

const app = createApp(App)

app.use(router)
app.use(createPinia())
app.use(i18n)
app.mount('#app')