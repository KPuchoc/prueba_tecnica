<script setup>
defineProps({
    abierto: { type: Boolean, required: true },
    titulo: { type: String, required: true },
});

defineEmits(['cerrar']);
</script>

<template>
    <div v-if="abierto" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="$emit('cerrar')">
        <div class="w-full max-w-lg rounded-lg bg-white p-6 shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">{{ titulo }}</h3>
                <button type="button" class="text-2xl leading-none text-gray-400 hover:text-gray-600" @click="$emit('cerrar')">
                    &times;
                </button>
            </div>

            <div class="mt-4">
                <slot />
            </div>
        </div>
    </div>
</template>
