<script setup lang="ts">
import { Head, usePage }    from '@inertiajs/inertia-vue3'
import { computed }         from 'vue'
import AuthenticatedLayout  from '@/Layouts/AuthenticatedLayout.vue'
import TableCrud            from '@/Components/Custom/TableCrud.vue'
import type { Attention }   from './type'
import type { Cite }        from '@/Pages/Citas/type'
import type { Service }     from '@/Pages/Servicios/type'
import type { Paginated, User, SelectOption } from '@/Types'

interface PageProps {
  atenciones: Paginated<Attention>
  citas:       Cite[]        // vienen sin paginar
  servicios:   Service[]     // vienen sin paginar
  auth:        { user: User }
}

const page        = usePage<PageProps>()

const atenciones  = computed(() => page.props.value.atenciones)
const citasList    = computed(() => page.props.value.citas)
const serviciosList = computed(() => page.props.value.servicios)

// mapea tus citas y servicios para pasárselos al componente
const citaOptions: SelectOption[] = citasList.value.map(c => ({
  value: c.id,
  label: c.date,
}))

const servicioOptions: SelectOption[] = serviciosList.value.map(s => ({
  value: s.id,
  label: s.name,
}))
</script>

<template>
  <Head title="Atenciones" />

  <AuthenticatedLayout>
    <TableCrud
      resourceName="atenciones"
      title="Atenciones"
      :columns="[
        { key:'date',          label:'Fecha'    },
        {
          key:'cite_id',
          label:'Cita',
          formatter: item => item.cite
            ? `${item.cite.date} (#${item.cite_id})`
            : `#${item.cite_id}`
        },
        {
          key:'service_id',
          label:'Servicio',
          formatter: item => item.service
            ? item.service.name
            : `#${item.service_id}`
        },
        { key:'price_service', label:'Precio'   },
      ]"
      :formFields="[
        { key:'date',          label:'Fecha',     type:'date' },
        { key:'cite_id',       label:'Cita',      type:'select', options: citaOptions },
        { key:'service_id',    label:'Servicio',  type:'select', options: servicioOptions },
        { key:'price_service', label:'Precio',    type:'number', placeholder:'0.00' },
      ]"
      :items="atenciones"
    />
  </AuthenticatedLayout>
</template>
