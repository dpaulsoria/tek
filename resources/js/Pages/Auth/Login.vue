<!-- resources/js/Pages/Login.vue -->
<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded shadow">
      <h2 class="text-2xl font-semibold text-center mb-6">
        Iniciar sesión
      </h2>

      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">
            Correo electrónico
          </label>
          <input
            id="email"
            type="email"
            v-model="email"
            required
            autofocus
            class="mt-1 block w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
          />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600">
            {{ errors.email }}
          </p>
        </div>

        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-700">
            Contraseña
          </label>
          <input
            id="password"
            type="password"
            v-model="password"
            required
            class="mt-1 block w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
          />
          <p v-if="errors.password" class="mt-1 text-sm text-red-600">
            {{ errors.password }}
          </p>
        </div>

        <button
          type="submit"
          :disabled="processing"
          class="w-full py-2 px-4 bg-blue-600 text-white font-medium rounded hover:bg-blue-700 disabled:opacity-50"
        >
          {{ processing ? 'Ingresando...' : 'Entrar' }}
        </button>
      </form>

      <p class="mt-4 text-center text-sm text-gray-600">
        ¿No tienes cuenta?
        <router-link to="/register" class="text-blue-600 hover:underline">
          Regístrate aquí
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const email = ref('')
const password = ref('')
const processing = ref(false)
const errors = ref<{ email?: string; password?: string }>({})

const submit = async () => {
  processing.value = true
  errors.value = {}
  try {
    await axios.post('/api/login', {
      email: email.value,
      password: password.value,
    })
    router.push('/dashboard')
  } catch (err: any) {
    if (err.response && err.response.data) {
      const data = err.response.data
      // Manejo de errores de validación
      if (data.errors) {
        errors.value = { 
          email: data.errors.email?.[0], 
          password: data.errors.password?.[0] 
        }
      } else if (data.message) {
        errors.value.email = data.message
      }
    }
  } finally {
    processing.value = false
  }
}
</script>
