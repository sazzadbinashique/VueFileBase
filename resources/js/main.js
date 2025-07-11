import { createApp } from 'vue'
import App from './App.vue'
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router'
import routes from 'virtual:generated-pages'
import '../css/app.css'


const router = createRouter({
  history: createWebHistory(),
  routes,
})

const app = createApp(App)

app.use(router)
app.use(createPinia())
app.mount('#app')