<script setup lang="ts">
import { ref, computed, defineProps } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { route } from "ziggy-js";
import { toast } from "vue3-toastify";

/** Columnas de la tabla */
interface Column {
    key: string;
    label: string;
    align?: "left" | "center" | "right";
    formatter?: (item: Record<string, any>) => string;
}
/** Campo de formulario */
interface FormField {
    key: string;
    label: string;
    type?: string; // 'text', 'email', 'number', ...
    placeholder?: string;
    options?: { value: any; label: string }[];
}
/** Forma mínima de paginación Inertia */
interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
}

/** Props que configuran todo el CRUD */
const props = defineProps<{
    /** Nombre del recurso para Ziggy: 'clientes', 'citas', etc. */
    resourceName: string;
    /** Título que muestra en cabecera y toasts ('Clientes', 'Citas', ...) */
    title: string;
    /** Columnas de la tabla */
    columns: Column[];
    /** Definición de campos para el modal */
    formFields: FormField[];
    /** Datos paginados que viene de usePage().props */
    items: Paginated<Record<string, any>>;
}>();

// form con Inertia (inicializado con un objeto vacío que luego llenamos)
const form = useForm<Record<string, any>>(
    props.formFields.reduce((o, f) => ((o[f.key] = ""), o), {})
);

const loading = computed(() => form.processing);
const showModal = ref(false);
const isEdit = ref(false);
const editingId = ref<number | null>(null);
const showDelete = ref(false);
const deletingId = ref<number | null>(null);

function reload(page = 1) {
    Inertia.get(route(`${props.resourceName}.index`), { page });
}

function openCreate() {
    isEdit.value = false;
    editingId.value = null;
    form.reset();
    showModal.value = true;
}

function openEdit(item: Record<string, any>) {
    isEdit.value = true;
    editingId.value = item.id;
    Object.assign(form, item);
    showModal.value = true;
}

function confirmDelete(id: number) {
    deletingId.value = id;
    showDelete.value = true;
}

function submit() {
    const opts = {
        onSuccess: () => {
            toast.success(
                `${props.title} ${
                    isEdit.value ? "actualizado" : "creado"
                } con éxito`
            );
            showModal.value = false;
            form.reset();
            reload();
        },
        onError: () => {
            toast.error(
                `Error al ${isEdit.value ? "actualizar" : "crear"} ${
                    props.title
                }`
            );
        },
    };
    if (isEdit.value && editingId.value) {
        form.put(route(`${props.resourceName}.update`, editingId.value), opts);
    } else {
        form.post(route(`${props.resourceName}.store`), opts);
    }
}

function doDelete() {
    if (!deletingId.value) return;
    Inertia.delete(route(`${props.resourceName}.destroy`, deletingId.value), {
        onSuccess: () => {
            toast.success(`${props.title} eliminado`);
            showDelete.value = false;
            reload();
        },
        onError: () => {
            toast.error(`Error al eliminar ${props.title}`);
        },
    });
}
</script>

<template>
    <div class="space-y-6">
        <!-- Cabecera + controles -->
        <div
            class="m-5 p-4 bg-white rounded-lg shadow flex items-center justify-between"
        >
            <h1 class="text-2xl font-semibold">{{ title }}</h1>
            <div class="flex items-center space-x-2">
                <button
                    @click="reload()"
                    :disabled="loading"
                    class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200 shadow-sm"
                >
                    Consultar
                </button>
                <button
                    @click="openCreate"
                    :disabled="loading"
                    class="px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600 transition-colors duration-200 shadow-sm"
                >
                    Nuevo
                </button>
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto mx-5 bg-white rounded-lg shadow">
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
                                    : 'text-left',
                            ]"
                        >
                            {{ col.label }}
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-right"
                        >
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in props.items.data" :key="item.id">
                        <td
                            v-for="col in columns"
                            :key="col.key"
                            :class="[
                                'px-6 py-4 whitespace-nowrap',
                                col.align === 'center'
                                    ? 'text-center'
                                    : col.align === 'right'
                                    ? 'text-right'
                                    : 'text-left',
                            ]"
                        >
                            {{ item[col.key] }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-right space-x-2"
                        >
                            <button
                                @click="openEdit(item)"
                                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                            >
                                Editar
                            </button>
                            <button
                                @click="confirmDelete(item.id)"
                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                            >
                                Borrar
                            </button>
                        </td>
                    </tr>
                    <tr v-if="!props.items.data.length">
                        <td
                            :colspan="columns.length + 1"
                            class="px-6 py-4 text-center text-gray-500"
                        >
                            No hay registros
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="flex justify-center space-x-1">
            <button
                v-for="p in props.items.last_page"
                :key="p"
                @click="reload(p)"
                :class="[
                    'px-3 py-1 rounded',
                    p === props.items.current_page
                        ? 'bg-red-500 text-white'
                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                ]"
            >
                {{ p }}
            </button>
        </div>

        <!-- Modal Crear/Editar -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 w-full max-w-lg relative">
                <button
                    @click="showModal = false"
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                >
                    &times;
                </button>
                <h2 class="text-xl mb-4">
                    {{ isEdit ? "Editar" : "Crear" }} {{ title }}
                </h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div v-for="f in formFields" :key="f.key">
                        <label class="block text-sm mb-1">{{ f.label }}</label>
                        <!-- si es select, renderiza un <select> -->
                        <template v-if="f.type === 'select'">
                            <select
                                v-model="form[f.key]"
                                class="mt-1 block w-full rounded border-gray-300"
                            >
                                <option value="">
                                    {{ f.placeholder || "-- Selecciona --" }}
                                </option>
                                <option
                                    v-for="opt in f.options"
                                    :key="opt.value"
                                    :value="opt.value"
                                >
                                    {{ opt.label }}
                                </option>
                            </select>
                        </template>
                        <!-- en cualquier otro caso, un input normal -->
                        <template v-else>
                            <input
                                v-model="form[f.key]"
                                :type="f.type || 'text'"
                                :step="0.01"
                                :placeholder="f.placeholder || ''"
                                class="mt-1 block w-full rounded border-gray-300"
                            />
                        </template>
                        <div
                            v-if="form.errors[f.key]"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors[f.key] }}
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ isEdit ? "Actualizar" : "Guardar" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Borrar -->
        <div
            v-if="showDelete"
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 w-full max-w-sm relative">
                <button
                    @click="showDelete = false"
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                >
                    &times;
                </button>
                <h2 class="text-lg mb-4">Confirmar borrado</h2>
                <p class="mb-4">
                    ¿Estás seguro que quieres eliminar este registro?
                </p>
                <div class="flex justify-end gap-2">
                    <button
                        @click="showDelete = false"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="doDelete"
                        :disabled="loading"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:opacity-50"
                    >
                        Borrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
