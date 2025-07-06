<script setup lang="ts">
import { defineProps } from "vue";

interface Props {
    title: string,
    onReload: () => void,
    loading: boolean,
    onCreate: () => void,
};

const props = defineProps<Props>();
</script>

<template>
    <div
        class="m-5 p-4 bg-white rounded-lg shadow flex items-center justify-between"
    >
        <h1 class="text-2xl font-semibold">{{ props.title }}</h1>
        <div class="flex items-center space-x-2">
            <button
                @click="props.onReload"
                :disabled="props.loading"
                class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200 shadow-sm"
            >
                Consultar
            </button>
            <button
                @click="props.onCreate"
                :disabled="loading"
                class="px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600 transition-colors duration-200 shadow-sm"
            >
                Nuevo
            </button>
        </div>
    </div>
</template>
