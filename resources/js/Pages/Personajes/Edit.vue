<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PersonajeForm from '@/Components/PersonajeForm.vue';

const props = defineProps({
    personaje: { type: Object, required: true },
    generos: { type: Array, required: true },
});

const form = useForm({
    nombre: props.personaje.nombre,
    altura: props.personaje.altura ?? '',
    masa: props.personaje.masa ?? '',
    color_cabello: props.personaje.color_cabello ?? '',
    color_ojos: props.personaje.color_ojos ?? '',
    genero: props.personaje.genero,
    planeta_origen: props.personaje.planeta_origen ?? '',
});

function actualizar() {
    form.put(`/actualizar-personaje/${props.personaje.id}`);
}
</script>

<template>
    <AppLayout>
        <h2 class="mb-4 text-lg font-semibold text-gray-800">Editar personaje</h2>
        <PersonajeForm :form="form" :generos="generos" texto-boton="Actualizar" @submit="actualizar" />
    </AppLayout>
</template>
