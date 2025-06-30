<script setup lang="ts">
import { Head, usePage } from '@inertiajs/inertia-vue3'
import { computed }      from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TableCrud          from '@/Components/Custom/TableCrud.vue'
import type { Service }   from './type'
import type { Paginated, User } from '@/Types'

/** La forma que Inertia envía la paginación */
interface PageProps {
  servicios: Paginated<Service>
  auth:     { user: User }
}

const page     = usePage<PageProps>()
// reactivo al objeto paginado
const servicios = computed(() => page.props.value.servicios)
</script>

<template>
  <Head title="Servicios" />

  <AuthenticatedLayout>
    <TableCrud
      resourceName="servicios"
      title="Servicios"
      :columns="[
        { key:'name', label:'Nombre' },
        { key:'slug', label:'Slug'   }
      ]"
      :formFields="[
        { key:'name', label:'Nombre', placeholder:'Corte de pelo...' },
        { key:'slug', label:'Slug',   placeholder:'corte-de-pelo' }
      ]"
      :items="servicios"
    />
  </AuthenticatedLayout>
</template>
