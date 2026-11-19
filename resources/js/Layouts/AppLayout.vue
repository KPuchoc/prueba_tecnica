<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const flash = computed(() => page.props.flash);
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <header class="bg-gray-900">
            <div class="mx-auto max-w-6xl px-4 py-4">
                <Link href="/listaPersonajes" class="text-xl font-bold text-yellow-400">Personajes Star Wars</Link>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-6">
            <div v-if="flash.success" class="mb-4 rounded-md bg-green-100 px-4 py-3 text-green-800">
                {{ flash.success }}
            </div>
            <div v-if="flash.error" class="mb-4 rounded-md bg-red-100 px-4 py-3 text-red-800">
                {{ flash.error }}
            </div>

            <slot />
        </main>
    </div>
</template>
