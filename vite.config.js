import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  base: '',
  resolve: {
    alias: [
      {
        find: /^ziggy-custom$/,
        replacement: path.resolve(__dirname, 'resources/js/ziggy-shim.js'),
      },
    ],
  },
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
