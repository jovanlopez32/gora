<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
<Head title="Datos guardados con exito" />

<GuestLayout class="">

    <div class="flex max-w-xs gap-5 my-5 ">
        <img src="/img/gora.png" class="object-Countain w-10" alt="Logo Gora">
        <div>
            <h1 class="text-xl font-bold tracking-wider">GORA</h1>
            <h3 class="text-xs tracking-wider font-semibold">SOLUCIONES INTEGRALES</h3>
        </div>
    </div>

    <div class="flex justify-center gap-5 max-w-xs fill-cyan-500">

        <h1 class="text-xl font-bold ">LOS DATOS FUERON GUARDADOS CON EXITO.</h1>
    </div>

</GuestLayout>
</template>
