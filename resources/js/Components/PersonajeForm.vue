<script setup>
import { Link } from '@inertiajs/vue3';
import Spinner from '@/Components/Spinner.vue';

defineProps({
    form: { type: Object, required: true },
    generos: { type: Array, required: true },
    textoBoton: { type: String, required: true },
});

defineEmits(['submit']);

const claseInput = 'mt-1 w-full rounded-md border border-gray-300 px-3 py-2 focus:border-gray-900 focus:outline-none';
</script>

<template>
    <form class="rounded-lg bg-white p-6 shadow" @submit.prevent="$emit('submit')">
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="nombre" class="text-sm font-medium text-gray-700">Nombre *</label>
                <input id="nombre" v-model="form.nombre" type="text" required minlength="3" maxlength="100" :class="claseInput" />
                <p v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">{{ form.errors.nombre }}</p>
                <p v-else class="mt-1 text-xs text-gray-500">Obligatorio. Entre 3 y 100 caracteres.</p>
            </div>

            <div>
                <label for="altura" class="text-sm font-medium text-gray-700">Altura (cm)</label>
                <input id="altura" v-model="form.altura" type="number" min="1" max="300" :class="claseInput" />
                <p v-if="form.errors.altura" class="mt-1 text-sm text-red-600">{{ form.errors.altura }}</p>
                <p v-else class="mt-1 text-xs text-gray-500">Opcional. Número entero entre 1 y 300.</p>
            </div>

            <div>
                <label for="masa" class="text-sm font-medium text-gray-700">Masa (kg)</label>
                <input id="masa" v-model="form.masa" type="number" min="1" max="500" :class="claseInput" />
                <p v-if="form.errors.masa" class="mt-1 text-sm text-red-600">{{ form.errors.masa }}</p>
                <p v-else class="mt-1 text-xs text-gray-500">Opcional. Número entero entre 1 y 500.</p>
            </div>

            <div>
                <label for="color_cabello" class="text-sm font-medium text-gray-700">Color de cabello</label>
                <input id="color_cabello" v-model="form.color_cabello" type="text" maxlength="100" :class="claseInput" />
                <p v-if="form.errors.color_cabello" class="mt-1 text-sm text-red-600">{{ form.errors.color_cabello }}</p>
                <p v-else class="mt-1 text-xs text-gray-500">Opcional. Máximo 100 caracteres.</p>
            </div>

            <div>
                <label for="color_ojos" class="text-sm font-medium text-gray-700">Color de ojos</label>
                <input id="color_ojos" v-model="form.color_ojos" type="text" maxlength="100" :class="claseInput" />
                <p v-if="form.errors.color_ojos" class="mt-1 text-sm text-red-600">{{ form.errors.color_ojos }}</p>
                <p v-else class="mt-1 text-xs text-gray-500">Opcional. Máximo 100 caracteres.</p>
            </div>

            <div>
                <label for="genero" class="text-sm font-medium text-gray-700">Género *</label>
                <select id="genero" v-model="form.genero" required :class="claseInput">
                    <option value="" disabled>Selecciona un género</option>
                    <option v-for="genero in generos" :key="genero" :value="genero">{{ genero }}</option>
                </select>
                <p v-if="form.errors.genero" class="mt-1 text-sm text-red-600">{{ form.errors.genero }}</p>
                <p v-else class="mt-1 text-xs text-gray-500">Obligatorio. Masculino, femenino o n/a.</p>
            </div>

            <div>
                <label for="planeta_origen" class="text-sm font-medium text-gray-700">Planeta de origen</label>
                <input id="planeta_origen" v-model="form.planeta_origen" type="text" maxlength="100" :class="claseInput" />
                <p v-if="form.errors.planeta_origen" class="mt-1 text-sm text-red-600">{{ form.errors.planeta_origen }}</p>
                <p v-else class="mt-1 text-xs text-gray-500">Opcional. Máximo 100 caracteres.</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <Link href="/listaPersonajes" class="rounded-md border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-100">
                Cancelar
            </Link>
            <button
                type="submit"
                :disabled="form.processing"
                class="flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-white hover:bg-gray-700 disabled:opacity-50"
            >
                <Spinner v-if="form.processing" />
                {{ textoBoton }}
            </button>
        </div>
    </form>
</template>
