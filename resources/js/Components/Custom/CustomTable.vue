<script setup lang="ts">
import { Head, usePage }    from '@inertiajs/inertia-vue3'
import { computed }         from 'vue'
import AuthenticatedLayout  from '@/Layouts/AuthenticatedLayout.vue'
import TableCrud            from '@/Components/Custom/TableCrud.vue'
import type { Person }      from './type'
import type { Paginated, User } from '@/Types'

interface PageProps {
  clientes: Paginated<Person>
  auth:     { user: User }
}

const page = usePage<PageProps>()
const clientes = computed(() => page.props.value.clientes)
</script>

<template>
  <Head title="Clientes" />
  <AuthenticatedLayout>
    <TableCrud
      resourceName="clientes"
      title="Clientes"
      :columns="[
        {
          key: 'document',
          label: 'Cliente',
          formatter: item => `${item.document} – ${item.first_name} ${item.last_name}`
        },
        { key:'email',   label:'Email'     },
        { key:'phone',   label:'Teléfono'  },
        { key:'address', label:'Dirección' },
      ]"
      :formFields="[
        { key:'document',   label:'Documento', placeholder:'DNI ...' },
        { key:'first_name', label:'Nombre' },
        { key:'last_name',  label:'Apellido' },
        { key:'email',      label:'Email', type:'email' },
        { key:'phone',      label:'Teléfono', type:'tel' },
        { key:'address',    label:'Dirección' },
      ]"
      :items="clientes.value"
    />
  </AuthenticatedLayout>
</template>
