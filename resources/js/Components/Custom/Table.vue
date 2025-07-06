<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'

interface Column {
  key: string
  label: string
  align?: 'left' | 'center' | 'right'
  formatter?: (item: Record<string, any>) => string
}
interface Paginated<T> {
  data: T[]
  current_page: number
  last_page: number
}

const props = defineProps<{
  columns: Column[]
  items: Paginated<Record<string, any>>
  hideActions: boolean
}>()
const emit = defineEmits<{
  (e: 'edit', id: number): void
  (e: 'delete', id: number): void
  (e: 'page', page: number): void
}>()

function handleEdit(id: number) {
  emit('edit', id)
}
function handleDelete(id: number) {
  emit('delete', id)
}
function changePage(page: number) {
  emit('page', page)
}
</script>

<template>
  <div class="overflow-x-auto mx-5 bg-white rounded-lg shadow mb-5">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th
            v-for="col in columns"
            :key="col.key"
            :class="[
              'px-6 py-3 text-xs font-medium text-gray-500 uppercase',
              col.align === 'center'
                ? 'text-center'
                : col.align === 'right'
                ? 'text-right'
                : 'text-left'
            ]"
          >
            {{ col.label }}
          </th>
          <th v-if="!props.hideActions" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-right">
            Acciones
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-for="item in items.data" :key="item.id">
          <td
            v-for="col in columns"
            :key="col.key"
            :class="[
              'px-6 py-4 whitespace-nowrap',
              col.align === 'center'
                ? 'text-center'
                : col.align === 'right'
                ? 'text-right'
                : 'text-left'
            ]"
          >
            {{ col.formatter ? col.formatter(item) : item[col.key] }}
          </td>
          <td v-if="!props.hideActions" class="px-6 py-4 whitespace-nowrap text-right space-x-2">
            <slot name="actions" :item="item">
              <button
                @click="handleEdit(item.id)"
                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
              >
                Editar
              </button>
              <button
                @click="handleDelete(item.id)"
                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
              >
                Borrar
              </button>
            </slot>
          </td>
        </tr>
        <tr v-if="!items.data.length">
          <td :colspan="columns.length + 1" class="px-6 py-4 text-center text-gray-500">
            No hay registros
          </td>
        </tr>
      </tbody>
    </table>
    <div class="flex flex-wrap justify-center gap-1 mt-4 overflow-x-auto px-5">
      <button
        v-for="p in items.last_page"
        :key="p"
        @click="changePage(p)"
        :class="[
          'px-3 py-1 rounded',
          p === items.current_page
            ? 'bg-red-500 text-white'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        ]"
      >
        {{ p }}
      </button>
    </div>
  </div>
</template>
