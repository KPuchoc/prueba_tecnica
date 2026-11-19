<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ImportModal from '@/Components/ImportModal.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import Spinner from '@/Components/Spinner.vue';

const props = defineProps({
    personajes: { type: Object, required: true },
    filtros: { type: Object, required: true },
    generos: { type: Array, required: true },
});

const modalImportarAbierto = ref(false);
const personajeAEliminar = ref(null);
const eliminando = ref(false);
const buscar = ref(props.filtros.buscar ?? '');
const genero = ref(props.filtros.genero ?? '');

let temporizador;

watch([buscar, genero], () => {
    clearTimeout(temporizador);

    temporizador = setTimeout(() => {
        router.get('/listaPersonajes', { buscar: buscar.value, genero: genero.value }, { preserveState: true, replace: true });
    }, 300);
});

function eliminar() {
    router.delete(`/eliminar-personaje/${personajeAEliminar.value.id}`, {
        preserveScroll: true,
        onStart: () => (eliminando.value = true),
        onFinish: () => {
            eliminando.value = false;
            personajeAEliminar.value = null;
        },
    });
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleString('es-PE', { dateStyle: 'short', timeStyle: 'short' });
}
</script>

<template>
    <AppLayout>
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Listado de personajes</h2>
            <div class="flex gap-2">
                <button class="rounded-md bg-yellow-400 px-4 py-2 font-medium text-gray-900 hover:bg-yellow-300" @click="modalImportarAbierto = true">
                    Importar desde SWAPI
                </button>
                <Link href="/personaje/crear" class="rounded-md bg-gray-900 px-4 py-2 text-white hover:bg-gray-700">
                    Crear Nuevo
                </Link>
            </div>
        </div>

        <div class="mb-4 flex flex-col gap-2 sm:flex-row">
            <input
                v-model="buscar"
                type="text"
                placeholder="Buscar por código o nombre..."
                class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-gray-900 focus:outline-none sm:w-64"
            />
            <select v-model="genero" class="rounded-md border border-gray-300 px-3 py-2 focus:border-gray-900 focus:outline-none">
                <option value="">Todos los géneros</option>
                <option v-for="opcion in generos" :key="opcion" :value="opcion">{{ opcion }}</option>
            </select>
        </div>

        <p v-if="personajes.data.length === 0" class="rounded-lg bg-white p-6 text-center text-gray-500 shadow">
            No hay personajes.
        </p>

        <template v-else>
            <table class="hidden w-full overflow-hidden rounded-lg bg-white shadow lg:table">
                <thead class="bg-gray-50 text-left text-sm text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Código</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Altura</th>
                        <th class="px-4 py-3">Masa</th>
                        <th class="px-4 py-3">Género</th>
                        <th class="px-4 py-3">Origen</th>
                        <th class="px-4 py-3">Creado</th>
                        <th class="px-4 py-3">Actualizado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <tr v-for="personaje in personajes.data" :key="personaje.id">
                        <td class="px-4 py-3 text-gray-500">#{{ personaje.id }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ personaje.nombre }}</td>
                        <td class="px-4 py-3">{{ personaje.altura ? `${personaje.altura} cm` : '—' }}</td>
                        <td class="px-4 py-3">{{ personaje.masa ? `${personaje.masa} kg` : '—' }}</td>
                        <td class="px-4 py-3">{{ personaje.genero }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="rounded-full px-2 py-1 text-xs font-medium"
                                :class="personaje.origen_swapi ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700'"
                            >
                                {{ personaje.origen_swapi ? 'SWAPI' : 'Manual' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ formatearFecha(personaje.created_at) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ formatearFecha(personaje.updated_at) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <Link :href="`/personaje/${personaje.id}/editar`" class="rounded-md border border-gray-300 px-3 py-1 hover:bg-gray-100">
                                    Editar
                                </Link>
                                <button class="rounded-md bg-red-600 px-3 py-1 text-white hover:bg-red-500" @click="personajeAEliminar = personaje">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="space-y-3 lg:hidden">
                <div v-for="personaje in personajes.data" :key="personaje.id" class="rounded-lg bg-white p-4 shadow">
                    <div class="flex items-start justify-between">
                        <h3 class="font-medium text-gray-800">
                            <span class="text-gray-500">#{{ personaje.id }}</span>
                            {{ personaje.nombre }}
                        </h3>
                        <span
                            class="rounded-full px-2 py-1 text-xs font-medium"
                            :class="personaje.origen_swapi ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700'"
                        >
                            {{ personaje.origen_swapi ? 'SWAPI' : 'Manual' }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ personaje.altura ? `${personaje.altura} cm` : '—' }} ·
                        {{ personaje.masa ? `${personaje.masa} kg` : '—' }} ·
                        {{ personaje.genero }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Creado: {{ formatearFecha(personaje.created_at) }} · Actualizado: {{ formatearFecha(personaje.updated_at) }}
                    </p>
                    <div class="mt-3 flex gap-2">
                        <Link :href="`/personaje/${personaje.id}/editar`" class="flex-1 rounded-md border border-gray-300 px-3 py-1 text-center text-sm hover:bg-gray-100">
                            Editar
                        </Link>
                        <button class="flex-1 rounded-md bg-red-600 px-3 py-1 text-sm text-white hover:bg-red-500" @click="personajeAEliminar = personaje">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <div class="mt-4">
            <Pagination :paginacion="personajes" />
        </div>

        <ImportModal :abierto="modalImportarAbierto" @cerrar="modalImportarAbierto = false" />

        <Modal :abierto="personajeAEliminar !== null" titulo="Eliminar personaje" @cerrar="personajeAEliminar = null">
            <p class="text-gray-700">
                ¿Seguro que quieres eliminar a <strong>{{ personajeAEliminar?.nombre }}</strong>?
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="rounded-md border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-100" @click="personajeAEliminar = null">
                    Cancelar
                </button>
                <button
                    type="button"
                    :disabled="eliminando"
                    class="flex items-center gap-2 rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-500 disabled:opacity-50"
                    @click="eliminar"
                >
                    <Spinner v-if="eliminando" />
                    Eliminar
                </button>
            </div>
        </Modal>
    </AppLayout>
</template>
