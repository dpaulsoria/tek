<script setup lang="ts">
import { Head, usePage }    from '@inertiajs/inertia-vue3'
import { computed }         from 'vue'
import AuthenticatedLayout  from '@/Layouts/AuthenticatedLayout.vue'
import TableCrud            from '@/Components/Custom/TableCrud.vue'
import type { Attention }   from './type'
import type { Paginated, User } from '@/Types'

interface PageProps {
  atenciones: Paginated<Attention>
  auth:        { user: User }
}

const page = usePage<PageProps>()

// reactivo al paginado de atenciones
const atenciones = computed(() => page.props.value.atenciones)
</script>

<template>
  <Head title="Atenciones" />

  <AuthenticatedLayout>
    <TableCrud
      resourceName="atenciones"
      title="Atenciones"
      :columns="[
        { key:'date',          label:'Fecha'        },
        { key:'cite_id',       label:'Cita'         },
        { key:'service_id',    label:'Servicio'     },
        { key:'price_service', label:'Precio'       },
      ]"
      :formFields="[
        {
          key:'date',          label:'Fecha',        type:'date'
        },
        {
          key:'cite_id',       label:'Cita',         type:'select',
          options: atenciones.value.data.map(a => ({
            value: a.cite_id,
            label: a.cite ? `${a.cite.date} (ID #${a.cite_id})` : `#${a.cite_id}`
          }))
        },
        {
          key:'service_id',    label:'Servicio',     type:'select',
          options: atenciones.value.data.map(a => ({
            value: a.service_id,
            label: a.service?.name || `#${a.service_id}`
          }))
        },
        {
          key:'price_service', label:'Precio',
          type:'number', step:0.01, min:0
        },
      ]"
      :items="atenciones"
    />
  </AuthenticatedLayout>
</template>
