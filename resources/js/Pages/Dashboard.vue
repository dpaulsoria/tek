<!-- resources/js/Pages/Dashboard.vue -->
<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/inertia-vue3'
import AuthenticatedLayout      from '@/Layouts/AuthenticatedLayout.vue'
import { computed }             from 'vue'

interface PageProps {
  counts: {
    clientes:   number
    citas:      number
    atenciones: number
  }
}

const page   = usePage<PageProps>()
const counts = computed(() => page.props.value.counts)
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
    </template>

    <main class="py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
          <Link
            href="clientes"
            class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 text-center"
          >
            <div class="text-5xl font-bold text-blue-600">{{ counts.clientes }}</div>
            <div class="mt-2 text-gray-700">Clientes</div>
          </Link>

          <Link
            href="citas"
            class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 text-center"
          >
            <div class="text-5xl font-bold text-green-600">{{ counts.citas }}</div>
            <div class="mt-2 text-gray-700">Citas</div>
          </Link>

          <Link
            href="servicios"
            class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 text-center"
          >
            <div class="text-5xl font-bold text-orange-600">{{ counts.servicios }}</div>
            <div class="mt-2 text-gray-700">Servicios</div>
          </Link>


          <Link
            href="atenciones"
            class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 text-center"
          >
            <div class="text-5xl font-bold text-red-600">{{ counts.atenciones }}</div>
            <div class="mt-2 text-gray-700">Atenciones</div>
          </Link>
        </div>
      </div>
    </main>
  </AuthenticatedLayout>
</template>
