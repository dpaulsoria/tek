// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue     from '@vitejs/plugin-vue';

export default defineConfig({
  base: '',  // ← forzar rutas relativas: /build/assets/… en vez de https://…/build/assets/…
  plugins: [
    laravel({
      input: [
        'resources/js/app.js',
        "resources/js/Pages/{$page['component']}.vue",
      ],
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
});
