import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import Pages from 'vite-plugin-pages'
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
         vue(),
    Pages({
      dirs: 'resources/js/pages',
    }),
    ],
    resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
    },
  },
});
