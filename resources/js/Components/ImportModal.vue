<script setup>
import { ref, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import Spinner from '@/Components/Spinner.vue';

const props = defineProps({
    abierto: { type: Boolean, required: true },
});

const emit = defineEmits(['cerrar']);

const page = usePage();

const form = useForm({
    swapi_id: '',
});

const lista = ref([]);
const pagina = ref(1);
const haySiguiente = ref(false);
const cargandoLista = ref(false);
const errorLista = ref('');
const mensajeExito = ref('');

async function cargarLista(numero) {
    cargandoLista.value = true;
    errorLista.value = '';

    try {
        const respuesta = await fetch(`/listaPersonajesSwapi?pagina=${numero}`, {
            headers: { Accept: 'application/json' },
        });
        const datos = await respuesta.json();

        if (!respuesta.ok) {
            errorLista.value = datos.message;
            return;
        }

        lista.value = datos.personajes;
        haySiguiente.value = datos.hay_siguiente;
        pagina.value = numero;
    } catch {
        errorLista.value = 'No se pudo cargar la lista de SWAPI.';
    } finally {
        cargandoLista.value = false;
    }
}

function importar(id) {
    form.swapi_id = id;
    mensajeExito.value = '';

    form.post('/importarPersonaje', {
        preserveScroll: true,
        onSuccess: () => {
            mensajeExito.value = page.props.flash.success;
            form.reset();
            cargarLista(pagina.value);
        },
    });
}

function cerrar() {
    form.reset();
    form.clearErrors();
    mensajeExito.value = '';
    emit('cerrar');
}

watch(
    () => props.abierto,
    (abierto) => {
        if (abierto) {
            cargarLista(1);
        }
    },
);
</script>

<template>
    <Modal :abierto="abierto" titulo="Importar desde SWAPI" @cerrar="cerrar">
        <form class="flex gap-2" @submit.prevent="importar(form.swapi_id)">
            <input
                v-model="form.swapi_id"
                type="number"
                min="1"
                max="83"
                required
                placeholder="ID del personaje (1 al 83)"
                class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-gray-900 focus:outline-none"
            />
            <button
                type="submit"
                :disabled="form.processing"
                class="flex items-center gap-2 rounded-md bg-yellow-400 px-4 py-2 font-medium text-gray-900 hover:bg-yellow-300 disabled:opacity-50"
            >
                <Spinner v-if="form.processing" />
                Importar
            </button>
        </form>

        <p v-if="form.errors.swapi_id" class="mt-2 text-sm text-red-600">{{ form.errors.swapi_id }}</p>
        <p v-else class="mt-2 text-xs text-gray-500">Número entero entre 1 y 83.</p>
        <p v-if="mensajeExito" class="mt-2 text-sm text-green-700">{{ mensajeExito }}</p>

        <div class="mt-4 border-t border-gray-200 pt-4">
            <p class="mb-2 text-sm font-medium text-gray-700">O elige de la lista de SWAPI:</p>

            <div v-if="cargandoLista" class="flex justify-center py-8 text-gray-500">
                <Spinner />
            </div>

            <p v-else-if="errorLista" class="py-4 text-sm text-red-600">{{ errorLista }}</p>

            <ul v-else class="max-h-64 divide-y divide-gray-100 overflow-y-auto">
                <li v-for="personaje in lista" :key="personaje.id" class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-800">
                        <span class="text-gray-400">#{{ personaje.id }}</span>
                        {{ personaje.nombre }}
                    </span>
                    <span v-if="personaje.importado" class="text-xs font-medium text-green-700">Importado</span>
                    <button
                        v-else
                        type="button"
                        :disabled="form.processing"
                        class="rounded-md border border-gray-300 px-2 py-1 text-xs hover:bg-gray-100 disabled:opacity-50"
                        @click="importar(personaje.id)"
                    >
                        Importar
                    </button>
                </li>
            </ul>

            <div class="mt-3 flex items-center justify-between">
                <button
                    type="button"
                    :disabled="pagina === 1 || cargandoLista"
                    class="text-sm text-gray-700 hover:text-gray-900 disabled:text-gray-300"
                    @click="cargarLista(pagina - 1)"
                >
                    « Anterior
                </button>
                <span class="text-sm text-gray-500">Página {{ pagina }}</span>
                <button
                    type="button"
                    :disabled="!haySiguiente || cargandoLista"
                    class="text-sm text-gray-700 hover:text-gray-900 disabled:text-gray-300"
                    @click="cargarLista(pagina + 1)"
                >
                    Siguiente »
                </button>
            </div>
        </div>
    </Modal>
</template>
