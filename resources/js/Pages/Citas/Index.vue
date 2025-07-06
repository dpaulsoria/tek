<script setup lang="ts">
import { Head, usePage } from "@inertiajs/inertia-vue3";
import { computed, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TableCrud from "@/Components/Custom/TableCrud.vue";
import ModalDetail from "@/Components/Custom/ModalDetail.vue";
import { CITE_STATE } from "./type";
import type { Cite } from "./type";
import type { Person } from "@/Pages/Clientes/type";
import type { Paginated, User, SelectOption } from "@/Types";
import { Attention } from "../Atenciones/type";
import ModalList from "@/Components/Custom/ModalList.vue";
import { formatDate } from "../Util";

interface PageProps {
    citas: Paginated<Cite & { person: Person }>;
    auth: { user: User };
    clientes: Person[];
}

const page = usePage<PageProps>();
const citas = computed(() => page.props.value.citas);
const clientesList = computed(() => page.props.value.clientes);

// Opciones selects (igual que antes)…
const clienteOptions: SelectOption[] = clientesList.value.map((c) => ({
    value: c.id,
    label: `${c.document} — ${c.first_name} ${c.last_name}`,
}));
const statusOptions: SelectOption[] = Object.values(CITE_STATE).map((s, i) => ({
    value: i,
    label: s,
}));
const attList = ref<Attention[]>([]);
const modalAtt = ref<InstanceType<typeof ModalList> | null>(null);

function showAttentions(citeId: number) {
    const cita = citas.value.data.find((c) => c.id === citeId);
    attList.value = cita?.attentions || [];
    modalAtt.value?.show();
}
// Columnas y formFields (igual que antes)…
const columns = [
    { key: "id", label: "ID" },
    { key: "date", label: "Fecha" },
    { key: "time_arrival", label: "Hora llegada", formatter: (it) => formatDate(it) },
    {
        key: "cliente_id",
        label: "Cliente",
        formatter: (i) =>
            `${i.person.document} — ${i.person.first_name} ${i.person.last_name}`,
    },
    { key: "status", label: "Estado" },
];
const formFields = [
    { key: "date", label: "Fecha", type: "date" },
    { key: "time_arrival", label: "Hora", type: "time" },
    {
        key: "cliente_id",
        label: "Cliente",
        type: "select",
        options: clienteOptions,
    },
    {
        key: "amount_attention",
        label: "# Atenciones",
        type: "number",
        placeholder: "0",
    },
    {
        key: "total_service",
        label: "Total",
        type: "number",
        placeholder: "0.00",
    },
    { key: "status", label: "Estado", type: "select", options: statusOptions },
];

const attentionColumns = [
    { key: "id", label: "ID" },
    { key: "date", label: "Fecha Atención", formatter: (it) => formatDate(it) },
    {
        key: "service.name",
        label: "Servicio",
        formatter: (a) => a.service.name,
    },
    { key: "price_service", label: "Precio" },
];

// Para el modal de detalle de cliente
const detailFields = ref<{ label: string; value: string }[]>([]);
const modalClient = ref<InstanceType<typeof ModalDetail> | null>(null);

function showClient(person: Person) {
    detailFields.value = [
        { label: "ID", value: person.id },
        { label: "Documento", value: person.document },
        { label: "Nombre", value: `${person.first_name} ${person.last_name}` },
        { label: "Email", value: person.email },
        { label: "Teléfono", value: person.phone ?? "—" },
        { label: "Dirección", value: person.address ?? "—" },
    ];
    modalClient.value?.show();
}

const orangeButton =
    "px-3 py-1 bg-orange-500 text-white rounded hover:bg-orange-600";
const grayButton = "px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600";
</script>

<template>
    <Head title="Citas" />
    <AuthenticatedLayout>
        <TableCrud
            resourceName="citas"
            title="Citas"
            :columns="columns"
            :formFields="formFields"
            :items="citas"
        >
            <template #actions="{ item }">
                <button
                    @click="showClient(item.person)"
                    class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600"
                >
                    Ver Cliente
                </button>
                <button
                    :disabled="item?.attentions.length === 0"
                    @click="showAttentions(item.id)"
                    :class="
                        item?.attentions.length === 0
                            ? grayButton
                            : orangeButton
                    "
                >
                    Ver Atenciones
                </button>
            </template>
        </TableCrud>

        <ModalDetail ref="modalClient" :fields="detailFields" />
        <ModalList
            ref="modalAtt"
            :columns="attentionColumns"
            :items="attList"
        />
    </AuthenticatedLayout>
</template>
