<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="container py-6">
                    <div class="row g-4">
                    <div class="col-md-4">
                        <Link href="/clientes" class="card p-4 text-center">Clientes</Link>
                    </div>
                    <div class="col-md-4">
                        <Link href="/citas" class="card p-4 text-center">Citas</Link>
                    </div>
                    <div class="col-md-4">
                        <Link href="/atenciones" class="card p-4 text-center">Atenciones</Link>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
