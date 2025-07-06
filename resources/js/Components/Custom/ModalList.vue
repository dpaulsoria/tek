<script setup lang="ts">
import { ref, defineProps, defineExpose } from 'vue'

interface Column { key: string; label: string, formatter?: (item: Record<string, any>) => string }
interface Props<T> {
  columns: Column[]
  items: T[]
}

const props = defineProps<Props<any>>()
const visible = ref(false)
const show = () => (visible.value = true)
const hide = () => (visible.value = false)
defineExpose({ show, hide })
</script>

<template>
  <div v-if="visible" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl relative">
      <button @click="hide" class="absolute top-2 right-2 text-gray-600">&times;</button>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th v-for="col in columns" :key="col.key" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                {{ col.label }}
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="(item, i) in items" :key="i">
              <td v-for="col in columns" :key="col.key" class="px-4 py-2 whitespace-nowrap">
                {{ col.formatter ? col.formatter(item) : item[col.key] }}
              </td>
            </tr>
            <tr v-if="!items.length">
              <td :colspan="columns.length" class="px-4 py-2 text-center text-gray-500">
                No hay datos
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
