<script setup lang="ts">
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { computed } from "vue";
import Table from "@/Components/Custom/Table.vue";

interface Report {
    id: number;
    document: string;
    first_name: string;
    last_name: string;
    cites_count: number;
    cites_sum_total_service: string | null; // viene como string o null
    attentions_sum_price_service: string | null;
}

interface PageProps {
    counts: {
        clientes: number;
        citas: number;
        atenciones: number;
        servicios: number;
    };
    reports: { data: Report[]; current_page: number; last_page: number };
}

const page = usePage<PageProps>();
const counts = computed(() => page.props.value.counts);
const reports = computed(() => page.props.value.reports);

const columns = [
    { key: "document", label: "Documento" },
    { key: "first_name", label: "Nombre" },
    { key: "last_name", label: "Apellido" },
    { key: "cites_count", label: "Nº Citas" },
    {
        key: "cites_sum_total_service",
        label: "Total Servicios",
        formatter: (r: Report) => {
            // convierte a número (null→0, '470.14'→470.14), y si es NaN pone 0
            const num = Number(r.cites_sum_total_service);
            const sum = isNaN(num) ? 0 : num;
            return sum.toFixed(2);
        },
    },
    {
        key: "attentions_sum_price_service",
        label: "Ventas",
        formatter: (r: Report) => {
            const num = Number(r.attentions_sum_price_service);
            const sum = isNaN(num) ? 0 : num;
            return sum.toFixed(2);
        },
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
        </template>

        <main class="py-12 space-y-12">
            <!-- Estadísticas -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                    <Link
                        href="clientes"
                        class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 text-center"
                    >
                        <div class="text-5xl font-bold text-blue-600">
                            {{ counts.clientes }}
                        </div>
                        <div class="mt-2 text-gray-700">Clientes</div>
                    </Link>
                    <Link
                        href="citas"
                        class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 text-center"
                    >
                        <div class="text-5xl font-bold text-green-600">
                            {{ counts.citas }}
                        </div>
                        <div class="mt-2 text-gray-700">Citas</div>
                    </Link>
                    <Link
                        href="servicios"
                        class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 text-center"
                    >
                        <div class="text-5xl font-bold text-orange-600">
                            {{ counts.servicios }}
                        </div>
                        <div class="mt-2 text-gray-700">Servicios</div>
                    </Link>
                    <Link
                        href="atenciones"
                        class="block bg-white rounded-lg shadow hover:shadow-lg transition p-6 text-center"
                    >
                        <div class="text-5xl font-bold text-red-600">
                            {{ counts.atenciones }}
                        </div>
                        <div class="mt-2 text-gray-700">Atenciones</div>
                    </Link>
                </div>
            </div>

            <!-- Reporte Clientes – Citas y Ventas -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold mb-4">
                        Reporte: Clientes – Citas y Ventas
                    </h3>
                    <Table
                        :columns="columns"
                        :items="reports"
                        hide-actions
                        @page="
                            (p) => $inertia.get(route('dashboard'), { page: p })
                        "
                    />
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>
