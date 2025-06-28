<script setup lang="ts">
import { Head, usePage }    from '@inertiajs/inertia-vue3'
import AuthenticatedLayout  from '@/Layouts/AuthenticatedLayout.vue'
import TableCrud from '@/Components/Custom/TableCrud.vue'
import type { Person }      from './type'
import { computed } from 'vue'
import type { Paginated, User } from '@/Types'

interface PageProps {
  clientes: Paginated<Person>
  auth:     { user: User }
}

const page = usePage<PageProps>()

// 3) extrae clientes de manera reactiva
const clientes = computed(() => page.props.value.clientes)
</script>

<template>
  <Head title="Clientes" />
  <AuthenticatedLayout>
    <TableCrud
      resourceName="clientes"
      title="Clientes"
      :columns="[
        { key:'document',   label:'Documento' },
        { key:'first_name', label:'Nombre'    },
        { key:'last_name',  label:'Apellido'  },
        { key:'email',      label:'Email'     },
        { key:'phone',      label:'Teléfono'  },
        { key:'address',    label:'Dirección' },
      ]"
      :formFields="[
        { key:'document',   label:'Documento', placeholder:'DNI ...' },
        { key:'first_name', label:'Nombre' },
        { key:'last_name',  label:'Apellido' },
        { key:'email',      label:'Email', type:'email' },
        { key:'phone',      label:'Teléfono', type:'tel' },
        { key:'address',    label:'Dirección' },
      ]"
      :items="clientes"
    />
  </AuthenticatedLayout>
</template>
