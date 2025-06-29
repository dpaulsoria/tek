// resources/js/app.js
import '../css/app.css'
import './bootstrap'

import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import Toast from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import { Inertia } from '@inertiajs/inertia'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

// Configura NProgress
NProgress.configure({
  showSpinner: false,
  trickleRate: 0.1,
  trickleSpeed: 200,
  parent: '#app',
})

Inertia.on('start', () => NProgress.start())
Inertia.on('finish', () => NProgress.done())
Inertia.on('error', () => NProgress.done())

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  // 1) resolvePageComponent con { eager: true }
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue', { eager: true })
    ),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Toast, { position: 'top-right', timeout: 3000 })
      .use(ZiggyVue)
      .mount(el)
  },
})
