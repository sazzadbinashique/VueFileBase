import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import Pages from 'vite-plugin-pages'
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/main.js'],
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
