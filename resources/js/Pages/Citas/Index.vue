<script setup lang="ts">
import { Head, usePage }      from '@inertiajs/inertia-vue3'
import { computed }           from 'vue'
import AuthenticatedLayout    from '@/Layouts/AuthenticatedLayout.vue'
import TableCrud              from '@/Components/Custom/TableCrud.vue'
import { CITE_STATE } from './type'
import type { Cite }          from './type'
import type { Person }        from '@/Pages/Clientes/type'
import type { Paginated, User, SelectOption } from '@/Types'

interface PageProps {
  citas: Paginated<Cite & { person: Person }>
  auth:  { user: User }
  clientes: Person[]
}

const page          = usePage<PageProps>()
const citas         = computed(() => page.props.value.citas)
const clientesList  = computed(() => page.props.value.clientes)

/** Mapeamos a opciones para el select */
const clienteOptions: SelectOption[] = clientesList.value.map(c => ({
  value: c.id,
  label: `${c.document} — ${c.first_name} ${c.last_name}`,
}))

const statusOptions: SelectOption[] = Object.values(CITE_STATE).map((state, index) => ({
  value: index,   // 0 → PENDIENTE, 1 → CONFIRMADA, 2 → CANCELADA
  label: state    // “PENDIENTE”, “CONFIRMADA”, “CANCELADA”
}))

</script>

<template>
  <Head title="Citas" />

  <AuthenticatedLayout>
    <TableCrud
      resourceName="citas"
      title="Citas"
      :columns="[
        { key:'date',        label:'Fecha' },
        { key:'time_arrival',label:'Hora llegada' },
        {
          key:'cliente_id',
          label:'Cliente',
          formatter: item => `${item.person.document} - ${item.person.first_name}`
        },
        { key:'status',      label:'Estado' }
      ]"
      :formFields="[
        { key:'date',          label:'Fecha',    type:'date' },
        { key:'time_arrival',  label:'Hora',     type:'time' },
        { key:'cliente_id',     label:'Cliente',  type:'select', options: clienteOptions },
        { key:'amount_attention', label:'# Atenciones', type:'number', placeholder:'0' },
        { key:'total_service', label:'Total',    type:'number', placeholder:'0.00' },
        { key:'status',        label:'Estado',   type:'select', options: statusOptions },
      ]"
      :items="citas"
    />
  </AuthenticatedLayout>
</template>
