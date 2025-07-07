<script setup lang="ts">
import { ref, computed } from "vue";
import { Head, usePage } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TableCrud from "@/Components/Custom/TableCrud.vue";
import type { Person } from "./type";
import type { Cite } from "../Citas/type";
import type { Paginated, User } from "@/Types";
import ModalList from "@/Components/Custom/ModalList.vue";
import { formatDate } from "../Util";

interface PageProps {
    clientes: Paginated<Person>;
    auth: { user: User };
}

const page = usePage<PageProps>();

const citaList = ref<Cite[]>([]);
const modalCitas = ref<InstanceType<typeof ModalList> | null>(null);

const citaColumns = [
    { key: "id", label: "ID" },
    { key: "date", label: "Fecha" },
    { key: "time_arrival", label: "Hora llegada",  formatter: (it) => formatDate(it.time_arrival)  },
    { key: "status", label: "Estado" },
];

function showCitas(clienteId: number) {
    const cliente = clientes.value.data.find((c) => c.id === clienteId);
    citaList.value = cliente?.cites || [];
    modalCitas.value?.show();
}
const clientes = computed(() => page.props.value.clientes);
const grayButton = "px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600";
const yellowButton =
    "px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600";
</script>

<template>
    <Head title="Clientes" />
    <AuthenticatedLayout>
        <TableCrud
            resourceName="clientes"
            title="Clientes"
            :columns="[
                { key: 'id', label: 'ID' },
                { key: 'document', label: 'Documento' },
                { key: 'first_name', label: 'Nombre' },
                { key: 'last_name', label: 'Apellido' },
                { key: 'email', label: 'Email' },
                { key: 'phone', label: 'Teléfono' },
                { key: 'address', label: 'Dirección' },
            ]"
            :formFields="[
                { key: 'document', label: 'Documento', placeholder: 'DNI ...' },
                { key: 'first_name', label: 'Nombre' },
                { key: 'last_name', label: 'Apellido' },
                { key: 'email', label: 'Email', type: 'email' },
                { key: 'phone', label: 'Teléfono', type: 'tel' },
                { key: 'address', label: 'Dirección' },
            ]"
            :items="clientes"
            @view="showCitas"
        >
            <template #actions="{ item }">
                <button
                    :disabled="item?.cites.length === 0"
                    @click="showCitas(item.id)"
                    :class="
                        item?.cites.length === 0 ? grayButton : yellowButton
                    "
                >
                    Ver Citas
                </button>
            </template>
        </TableCrud>

        <ModalList ref="modalCitas" :columns="citaColumns" :items="citaList" />
    </AuthenticatedLayout>
</template>
