<script setup lang="ts">
import { Head, usePage } from '@inertiajs/inertia-vue3'
import { ref, computed }     from 'vue'
import { useForm }           from '@inertiajs/inertia-vue3'
import { Inertia }           from '@inertiajs/inertia'
import { route }                 from 'ziggy-js'

import type { Person }       from './type'
import type { Paginated, User }    from '@/Types'
import AuthenticatedLayout   from '@/Layouts/AuthenticatedLayout.vue'

// Definimos solo la parte de los props que nos interesan
interface PageProps {
  clientes: Paginated<Person>;
  auth: { user: User }
}

const page = usePage<PageProps>()
const clientes = page.props.value.clientes ?? {
  data: [],
  current_page: 1,
  last_page: 1,
  total: 1,
}

const fields    = ['document','first_name','last_name','email','actions']
const pageNum   = computed(() => clientes.current_page)

function goTo(p: number) {
  Inertia.get(route('clientes.index'), { page: p })
}

const showModal  = ref(false)
const isEdit     = ref(false)
const editingId  = ref<number|null>(null)

// Creamos el form con Inertia
const form = useForm({
  document:   '',
  first_name: '',
  last_name:  '',
  email:      '',
  phone:      '',
  address:    '',
})

function openCreate() {
  isEdit.value    = false
  editingId.value = null
  form.reset()
  showModal.value = true
}

function openEdit(cliente: Person) {
  isEdit.value    = true
  editingId.value = cliente.id
  form.set({
    document:   cliente.document,
    first_name: cliente.first_name,
    last_name:  cliente.last_name,
    email:      cliente.email,
    phone:      cliente.phone  ?? '',
    address:    cliente.address?? '',
  })
  showModal.value = true
}

function submitForm() {
  const opts = {
    onSuccess: () => { form.reset(); Inertia.reload() },
    onFinish:  () => { showModal.value = false },
  }

  if (isEdit.value && editingId.value != null) {
    form.put(route('clientes.update', editingId.value), opts)
  } else {
    form.post(route('clientes.store'), opts)
  }
}

function reloadClientes() {
  Inertia.reload()
}

function deleteCliente(id: number) {
  if (!confirm('¿Eliminar este cliente?')) return
  Inertia.delete(route('clientes.destroy', id), {
    onSuccess: () => Inertia.reload(),
  })
}
</script>

<template>
    <Head title="Clientes" />
    <AuthenticatedLayout>
        <div class="m-5 p-4 bg-white rounded-lg shadow flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Clientes</h1>
        <div class="flex items-center space-x-2">
            <button
            @click="reloadClientes"
            class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200 shadow-sm"
            >
            Consultar
            </button>
            <button
            @click="openCreate"
            class="px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600 transition-colors duration-200 shadow-sm"
            >
            Nuevo
            </button>
        </div>
        </div>

        <div class="overflow-x-auto mx-5 bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apellido</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dirección</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 shadow">
          <tr v-for="c in clientes.data || []" :key="c.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ c.document }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ c.first_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ c.last_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ c.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ c.phone }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ c.address }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
              <button
                @click="openEdit(c)"
                class="inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 shadow"
              >Editar</button>
              <button
                @click="deleteCliente(c.id)"
                class="inline-block rounded bg-red-500 px-4 py-2 text-white hover:bg-red-700 shadow"
              >Borrar</button>
            </td>
          </tr>
          <tr v-if="clientes.data.length === 0">
            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay clientes</td>
          </tr>
        </tbody>
      </table>
        </div>
        <!-- Paginación simple -->
        <div class="flex justify-center space-x-1">
            <button
                v-for="p in clientes.last_page"
                :key="p"
                @click="goTo(p)"
                :class="[
                    'px-3 py-1 rounded',
                    p === page.value
                        ? 'bg-red-500 text-white'
                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                ]"
            >
                {{ p }}
            </button>
        </div>
        <!-- Modal Crear Cliente -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        >
            <div class="absolute inset-0" v-if="form.processing"></div>
            <div class="bg-white rounded-lg p-6 w-full max-w-xl">
                <!-- Cabecera -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">{{ isEdit ? 'Editar' : 'Crear' }} Cliente</h2>
                    <button
                        @click="showModal = false"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        &times;
                    </button>
                </div>

                <!-- Cuerpo del formulario -->
                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-sm">Documento</label>
                        <input
                            v-model="form.document"
                            type="text"
                            class="mt-1 block w-full rounded border-gray-300"
                        />
                        <div
                            v-if="form.errors.document"
                            class="text-red-600 text-sm"
                        >
                            {{ form.errors.document }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm">Nombre</label>
                        <input
                            v-model="form.first_name"
                            type="text"
                            class="mt-1 block w-full rounded border-gray-300"
                        />
                        <div
                            v-if="form.errors.first_name"
                            class="text-red-600 text-sm"
                        >
                            {{ form.errors.first_name }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm">Apellido</label>
                        <input
                            v-model="form.last_name"
                            type="text"
                            class="mt-1 block w-full rounded border-gray-300"
                        />
                        <div
                            v-if="form.errors.last_name"
                            class="text-red-600 text-sm"
                        >
                            {{ form.errors.last_name }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full rounded border-gray-300"
                        />
                        <div
                            v-if="form.errors.email"
                            class="text-red-600 text-sm"
                        >
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm">Teléfono</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            class="mt-1 block w-full rounded border-gray-300"
                        />
                        <div
                            v-if="form.errors.phone"
                            class="text-red-600 text-sm"
                        >
                            {{ form.errors.phone }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm">Dirección</label>
                        <input
                            v-model="form.address"
                            type="text"
                            class="mt-1 block w-full rounded border-gray-300"
                        />
                        <div
                            v-if="form.errors.address"
                            class="text-red-600 text-sm"
                        >
                            {{ form.errors.address }}
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 shadow"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow"
                        >
                            {{ isEdit ? 'Editar' : 'Crear' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
