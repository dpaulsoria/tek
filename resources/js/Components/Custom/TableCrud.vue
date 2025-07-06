<script setup lang="ts">
import { ref, computed, defineProps } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { route } from "ziggy-custom";
import { toast } from "vue3-toastify";
import ModalDelete from '@/Components/Custom/ModalDelete.vue';
import ModalForm from '@/Components/Custom/ModalForm.vue';
import Header from '@/Components/Custom/Header.vue';

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
    resourceName: string;
    title: string;
    columns: Column[];
    formFields: FormField[];
    items: Paginated<Record<string, any>>;
}>();

// form con Inertia (inicializado con un objeto vacío que luego llenamos)
const form = useForm<Record<string, any>>(
    props.formFields.reduce((o, f) => ((o[f.key] = ""), o), {})
);

const loading = computed(() => form.processing);
const isEdit = ref(false);
const editingId = ref<number | null>(null);
const deletingId = ref<number | null>(null);
const showModalDelete = ref<InstanceType<typeof ModalDelete> | null>(null);
const showForm = ref<InstanceType<typeof ModalForm> | null>(null);
const header = ref<InstanceType<typeof Header> | null>(null);

function reload(page = 1) {
    Inertia.get(route(`${props.resourceName}.index`), { page });
}

function openCreate() {
    isEdit.value = false;
    editingId.value = null;
    form.reset();
    showForm.value?.show();
}

function openEdit(item: Record<string, any>) {
    isEdit.value = true;
    editingId.value = item.id;
    Object.assign(form, item);
    showForm.value?.show();
}

function confirmDelete(id: number) {
    deletingId.value = id;
    showModalDelete.value?.show()
}

function submit() {
    const opts = {
        onSuccess: () => {
            toast.success(
                `${props.title} ${
                    isEdit.value ? "actualizado" : "creado"
                } con éxito`
            );
            showForm.value?.hide();
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
            showModalDelete.value?.hide()
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
        <Header
            ref="header"
            :title="title"
            :loading="loading"
            :onCreate="openCreate"
            :onReaload="reload"
        />

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
        <ModalForm
            ref="showForm"
            :title="title"
            :fields="formFields"
            :form="form"
            :errors="form.errors"
            :is-edit="isEdit"
            :loading="loading"
            :on-submit="submit"
        />
        <ModalDelete
            ref="showModalDelete"
            :loading="loading"
            :onConfirm="doDelete"
        />
    </div>
</template>
