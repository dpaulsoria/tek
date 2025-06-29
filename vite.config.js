// // vite.config.js
// import { defineConfig } from 'vite'
// import laravel from 'laravel-vite-plugin'
// import vue from '@vitejs/plugin-vue'

// export default defineConfig({
//   base: '',
//   build: {
//     outDir: 'public/build',
//     assetsDir: 'assets',
//     manifest: true,
//     rollupOptions: {
//       input: 'resources/js/app.js',
//     },
//     emptyOutDir: true,
//   },
//   plugins: [
//     laravel({
//       input: ['resources/js/app.js'],
//       refresh: true,
//     }),
//     vue({
//       template: {
//         transformAssetUrls: {
//           base: null,
//           includeAbsolute: false,
//         },
//       },
//     }),
//   ],
// })


// Opcion A
// vite.config.js
// import { defineConfig } from 'vite'
// import laravel        from 'laravel-vite-plugin'
// import vue            from '@vitejs/plugin-vue'

// export default defineConfig({
//   base: '',
//   plugins: [
//     laravel({
//       input: [
//         'resources/css/app.css',
//         'resources/js/app.js',
//       ],
//       refresh: true,
//     }),
//     vue({
//       template: {
//         transformAssetUrls: {
//           base: null,
//           includeAbsolute: false,
//         },
//       },
//     }),
//   ],
// })

// Opcion B
// vite.config.js
import { defineConfig } from 'vite'
import laravel        from 'laravel-vite-plugin'
import vue            from '@vitejs/plugin-vue'
export default defineConfig({
  build: {
    outDir: 'build/vite',
    manifest: true,
    assetsDir: 'assets',
  },
  plugins: [
    laravel({
      input: ['resources/js/app.js'],
      refresh: true,
      buildDirectory: 'build/vite', // <--- aquí
    }),
    vue(),
  ],
})
