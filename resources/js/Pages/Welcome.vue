<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: { type: String, required: true },
  phpVersion: { type: String, required: true },
})

// extraemos el usuario autenticado
const user = usePage<{ auth: { user: any } }>().props.auth.user

function handleImageError() {
  document.getElementById('screenshot-container')?.classList.add('!hidden')
  document.getElementById('docs-card')?.classList.add('!row-span-1')
  document.getElementById('docs-card-content')?.classList.add('!flex-row')
  document.getElementById('background')?.classList.add('!hidden')
}
</script>

<template>
  <Head title="Agenda tu cita" />

  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="sticky top-0 bg-white text-gray-600 z-50 shadow-sm">
      <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <img src="/svg/logo.svg" alt="Logo" class="h-10 w-10" />
          <span class="text-lg font-semibold">Peluquería Anita</span>
        </div>

        <nav class="flex space-x-4" v-if="props.canLogin">
          <Link
            v-if="user"
            :href="route('dashboard')"
            class="px-3 py-2 rounded hover:bg-gray-100"
          >Dashboard</Link>

          <template v-else>
            <Link
              :href="route('login')"
              class="px-3 py-2 rounded hover:bg-gray-100"
            >Iniciar sesión</Link>

            <Link
              v-if="props.canRegister"
              :href="route('register')"
              class="px-3 py-2 rounded hover:bg-gray-100"
            >Regístrate</Link>
          </template>
        </nav>
      </div>
    </header>

    <!-- Main -->
    <main class="flex-1 px-6 py-8 grid grid-cols-1 grid-rows-3 gap-6 md:grid-cols-2 md:grid-rows-2">
      <!-- ...tu contenido aquí... -->
    </main>

    <!-- Footer -->
    <footer class="sticky bottom-0 bg-white text-gray-500 z-50 shadow-inner">
      <div class="max-w-7xl mx-auto px-6 py-4 text-center">
        © 2025 Peluquería Anita — v{{ props.laravelVersion }} (PHP v{{ props.phpVersion }})
      </div>
    </footer>
  </div>
</template>
