<script setup lang="ts">
import { ref, computed, defineProps } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { route } from "ziggy-custom";
import { toast } from "vue3-toastify";
import ModalDelete from '@/Components/Custom/ModalDelete.vue';
import ModalForm from '@/Components/Custom/ModalForm.vue';
import Header from '@/Components/Custom/Header.vue';
import Table from '@/Components/Custom/Table.vue';

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

const showForm = ref<InstanceType<typeof ModalForm> | null>(null);
const showDelete = ref<InstanceType<typeof ModalDelete> | null>(null);
const header = ref<InstanceType<typeof Header> | null>(null);

/** Recarga de página */
function reload(page = 1) {
    Inertia.get(route(`${props.resourceName}.index`), { page });
}

/** Nuevo registro */
function openCreate() {
    isEdit.value = false;
    editingId.value = null;
    form.reset();
    showForm.value?.show();
}

/** Editar registro por ID */
function openEdit(id: number) {
    const item = props.items.data.find((i) => i.id === id);
    if (!item) return;
    isEdit.value = true;
    editingId.value = id;
    Object.assign(form, item);
    showForm.value?.show();
}

/** Confirmar borrado */
function confirmDelete(id: number) {
    deletingId.value = id;
    showDelete.value?.show();
}

/** Enviar formulario Create/Update */
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

/** Ejecutar borrado */
function doDelete() {
    if (!deletingId.value) return;
    Inertia.delete(route(`${props.resourceName}.destroy`, deletingId.value), {
        onSuccess: () => {
            toast.success(`${props.title} eliminado`);
            showDelete.value?.hide();
            reload();
        },
        onError: () => toast.error(`Error al eliminar ${props.title}`),
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
            :onReload="reload"
        />
        <Table
            :columns="columns"
            :items="items"
            @edit="openEdit"
            @delete="confirmDelete"
            @page="reload"
        />
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
            ref="showDelete"
            :loading="loading"
            :onConfirm="doDelete"
        />
    </div>
</template>
