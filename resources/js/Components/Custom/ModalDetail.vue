<script setup lang="ts">
import { ref, defineProps, defineExpose } from 'vue'

interface Field { label: string; value: string }

const props = defineProps<{ fields: Field[] }>()
const visible = ref(false)
const show = () => (visible.value = true)
const hide = () => (visible.value = false)
defineExpose({ show, hide })
</script>

<template>
  <div v-if="visible" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md relative">
      <button @click="hide" class="absolute top-2 right-2 text-gray-600">&times;</button>
      <h2 class="text-xl mb-4">Detalles</h2>
      <dl class="divide-y divide-gray-200">
        <div v-for="f in props.fields" :key="f.label" class="py-2 flex">
          <dt class="w-1/3 font-medium text-gray-700">{{ f.label }}</dt>
          <dd class="w-2/3 text-gray-900">{{ f.value }}</dd>
        </div>
      </dl>
    </div>
  </div>
</template>
