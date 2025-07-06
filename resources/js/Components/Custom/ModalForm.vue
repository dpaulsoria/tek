<script setup lang="ts">
import { ref, defineProps, defineExpose } from 'vue'

interface Props {
  title: string
  fields: any[]
  form: Record<string, any>
  errors: Record<string, string[] | string>
  isEdit: boolean
  loading: boolean
  onSubmit: () => void
}

const props = defineProps<Props>()
const visible = ref(false)
const show = () => (visible.value = true)
const hide = () => (visible.value = false)
defineExpose({ show, hide })
</script>

<template>
  <div v-if="visible" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-lg relative">
      <button @click="hide" class="absolute top-2 right-2">&times;</button>
      <h2 class="text-xl mb-4">{{ isEdit ? 'Editar' : 'Crear' }} {{ title }}</h2>
      <form @submit.prevent="() => { props.onSubmit(); }" class="space-y-4">
        <div v-for="f in fields" :key="f.key">
          <label class="block text-sm mb-1">{{ f.label }}</label>
          <template v-if="f.type === 'select'">
            <select v-model="props.form[f.key]" class="w-full rounded border-gray-300">
              <option value="">{{ f.placeholder || '-- Selecciona --' }}</option>
              <option v-for="o in f.options!" :key="o.value" :value="o.value">{{ o.label }}</option>
            </select>
          </template>
          <template v-else>
            <input
              v-model="props.form[f.key]"
              :type="f.type || 'text'"
              :placeholder="f.placeholder"
              class="w-full rounded border-gray-300"
            />
          </template>
            <p v-if="props.errors[f.key]" class="text-red-600 text-sm">
                {{ Array.isArray(props.errors[f.key])
                    ? (props.errors[f.key] as string[])[0]
                    : (props.errors[f.key] as string)
                }}
            </p>
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" @click="hide" class="px-4 py-2 bg-gray-200 rounded">Cancelar</button>
          <button type="submit" :disabled="loading" class="px-4 py-2 bg-blue-600 text-white rounded">
            {{ isEdit ? 'Actualizar' : 'Guardar' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
