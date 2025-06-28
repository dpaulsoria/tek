// resources/js/env.d.ts
import { Inertia } from '@inertiajs/inertia'
import type { ComponentCustomProperties } from 'vue'

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $inertia: typeof Inertia
  }
}
