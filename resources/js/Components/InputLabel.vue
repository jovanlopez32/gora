<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="font-semibold text-sm text-neutral-900">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
