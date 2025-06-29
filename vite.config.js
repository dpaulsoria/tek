import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  base: '',
  build: {
    outDir: 'public/build',
    assetsDir: 'assets',
    manifest: 'manifest.json',
    rollupOptions: {
      input: ['resources/js/app.js', 'resources/css/app.css'],
    },
    emptyOutDir: true,
  },
  plugins: [
    laravel({
      input: ['resources/js/app.js', 'resources/css/app.css'],
      buildDirectory: 'build',
      hotFile: '',
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
})
