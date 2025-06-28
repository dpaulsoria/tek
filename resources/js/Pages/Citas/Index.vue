<script setup lang="ts">
import { Head, usePage }    from '@inertiajs/inertia-vue3'
import { computed }         from 'vue'
import AuthenticatedLayout  from '@/Layouts/AuthenticatedLayout.vue'
import TableCrud            from '@/Components/Custom/TableCrud.vue'
import type { Cite }        from './type'    // define aquí tu interface Cite
import type { Paginated, User } from '@/Types'

interface PageProps {
  citas: Paginated<Cite>
  auth:  { user: User }
}

const page = usePage<PageProps>()

// 1) recogemos las citas de forma reactiva
const citas = computed(() => page.props.value.citas)
</script>

<template>
  <Head title="Citas" />

  <AuthenticatedLayout>
    <TableCrud
      resourceName="citas"
      title="Citas"
      :columns="[
        { key:'date',             label:'Fecha'         },
        { key:'time_arrival',     label:'Hora Llegada'  },
        { key:'cliente_id',       label:'Cliente'       },
        { key:'amount_attention', label:'Nº Atenciones' },
        { key:'total_service',    label:'Total Servicio' },
        { key:'status',           label:'Estado'        },
      ]"
      :formFields="[
        { key:'date',             label:'Fecha',           type:'date'       },
        { key:'time_arrival',     label:'Hora Llegada',    type:'time'       },
        { key:'cliente_id',       label:'Cliente',         type:'select', options: page.props.value.citas.data.map(c => ({ value: c.cliente_id, label: c.person.name })) },
        { key:'amount_attention', label:'Nº Atenciones',   type:'number',    min:1 },
        { key:'total_service',    label:'Total Servicio',   type:'number',    step:0.01 },
        { key:'status',           label:'Estado',          type:'select',    options:[
            { value:'pendiente',  label:'Pendiente'  },
            { value:'confirmada', label:'Confirmada' },
            { value:'cancelada',  label:'Cancelada'  }
          ]
        },
      ]"
      :items="citas"
    />
  </AuthenticatedLayout>
</template>
