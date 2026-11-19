<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PersonajeForm from '@/Components/PersonajeForm.vue';

defineProps({
    generos: { type: Array, required: true },
});

const form = useForm({
    nombre: '',
    altura: '',
    masa: '',
    color_cabello: '',
    color_ojos: '',
    genero: '',
    planeta_origen: '',
});

function guardar() {
    form.post('/crearPersonaje');
}
</script>

<template>
    <AppLayout>
        <h2 class="mb-4 text-lg font-semibold text-gray-800">Crear personaje</h2>
        <PersonajeForm :form="form" :generos="generos" texto-boton="Guardar" @submit="guardar" />
    </AppLayout>
</template>
