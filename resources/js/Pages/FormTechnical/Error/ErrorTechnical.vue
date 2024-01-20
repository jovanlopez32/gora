<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';


</script>

<template>
<Head title="Error" />
<GuestLayout>


    <div class="flex items-center justify-start gap-2 pt-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="fill-red-500" width="30" height="30" viewBox="0 0 24 24" ><path d="M16.707 2.293A.996.996 0 0 0 16 2H8a.996.996 0 0 0-.707.293l-5 5A.996.996 0 0 0 2 8v8c0 .266.105.52.293.707l5 5A.996.996 0 0 0 8 22h8c.266 0 .52-.105.707-.293l5-5A.996.996 0 0 0 22 16V8a.996.996 0 0 0-.293-.707l-5-5zM13 17h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>
        <h1 class="text-3xl font-semibold text-red-600">Error</h1>
    </div>

    <div class="max-w-xs text-center px-5 pb-5">
        <p class="text-sm font-semibold my-4">El dispositivo ya no esta registrado o algunos datos fueron ingresados incorrectamente.</p>
    </div>

</GuestLayout>

</template>
