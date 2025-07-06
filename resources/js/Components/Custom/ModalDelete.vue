<script setup lang="ts">
import { ref, defineProps, defineExpose } from 'vue'

interface Props {
  loading: boolean
  onConfirm: () => void
}

const props = defineProps<Props>()
const visible = ref(false)

const show = () => { visible.value = true }
const hide = () => { visible.value = false }

defineExpose({ show, hide })
</script>

<template>
  <div v-if="visible" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm relative">
      <button @click="hide" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
      <h2 class="text-lg mb-4">Confirmar borrado</h2>
      <p class="mb-4">¿Estás seguro que quieres eliminar este registro?</p>
      <div class="flex justify-end gap-2">
        <button @click="hide" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancelar</button>
        <button
          @click="() => { props.onConfirm(); hide(); }"
          :disabled="props.loading"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:opacity-50"
        >
          Borrar
        </button>
      </div>
    </div>
  </div>
</template>
