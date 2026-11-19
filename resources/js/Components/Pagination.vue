<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    paginacion: { type: Object, required: true },
});

function etiqueta(link, index) {
    if (index === 0) {
        return '« Anterior';
    }

    if (index === props.paginacion.links.length - 1) {
        return 'Siguiente »';
    }

    return link.label;
}
</script>

<template>
    <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-between">
        <p class="text-sm text-gray-600">
            Mostrando {{ paginacion.from ?? 0 }} a {{ paginacion.to ?? 0 }} de {{ paginacion.total }} personajes
        </p>

        <nav class="flex flex-wrap justify-center gap-1">
            <template v-for="(link, index) in paginacion.links" :key="index">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    preserve-scroll
                    class="rounded-md px-3 py-1 text-sm"
                    :class="link.active ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 hover:bg-gray-200'"
                >
                    {{ etiqueta(link, index) }}
                </Link>
                <span v-else class="rounded-md px-3 py-1 text-sm text-gray-400">
                    {{ etiqueta(link, index) }}
                </span>
            </template>
        </nav>
    </div>
</template>
