<!-- resources/js/Pages/Welcome.vue -->
<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/inertia-vue3'

// 1) Definimos sólo los props explícitos pasados en Inertia::render()
const { canLogin, canRegister, laravelVersion, phpVersion } = defineProps<{
  canLogin: boolean
  canRegister: boolean
  laravelVersion: string
  phpVersion: string
}>()

const page = usePage()
const user = page.props.auth?.user ?? null

</script>

<template>
  <Head title="Bienvenido" />

  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="sticky top-0 bg-white shadow-sm">
      <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <img src="/svg/logo.svg" alt="Logo" class="h-10 w-10" />
          <span class="text-lg font-semibold">Peluquería Anita</span>
        </div>

        <nav class="flex space-x-4" v-if="canLogin">
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
              v-if="canRegister"
              :href="route('register')"
              class="px-3 py-2 rounded hover:bg-gray-100"
            >Regístrate</Link>
          </template>
        </nav>
      </div>
    </header>

    <!-- Main vacío por ahora -->
    <main class="flex-1"></main>

    <!-- Footer -->
    <footer class="bg-white text-center text-gray-500 py-4 shadow-inner">
      © 2025 Peluquería Anita — v{{ laravelVersion }} (PHP v{{ phpVersion }})
    </footer>
  </div>
</template>
