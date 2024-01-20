<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;
const props = defineProps({
    data: {
        type: Object,
    },
});

</script>

<template>
<Head title="Panel principal" />

<AuthenticatedLayout>

    <div>
        <h1 class=" font-bold text-xl">Bienvenido {{ user.name }}</h1>
        <p class="font-medium text-sm my-1">Este es tu panel principal aqui podras ver las actividades recientes realizadas por los tecnicos.</p>
    </div>

<table id="table" class="table-fixed w-full text-center my-10">

    <thead>
        <tr class="[&>th]:text-xs [&>th]:py-3 [&>th]:border [&>th]:border-neutral-900 [&>th]:text-white bg-neutral-900 ">
            <th>Nombre</th>
            <th>Operación</th>
            <th>Fecha</th>
        </tr>
    </thead>

    <tbody class="text-xs">
        <tr v-for="dt in props.data" class="[&>td]:text-xs [&>td]:px-2 [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 font-medium cursor-pointer [&>td]:hover:bg-neutral-300 ">
            <td class="bg-white">{{  dt.technical_name }}</td>
            <td v-if="dt.hasOwnProperty('problem')" class="bg-green-100">Registro un problema</td>
            <td v-else class="bg-yellow-100"> Registro un contador</td>
            <td class="bg-white">{{ new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium', timeStyle: 'medium' }).format(new Date(dt.created_at)) }}</td>
        </tr>
    </tbody>
</table>



    <!-- <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template> -->

    <!-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">You're logged in!</div>
            </div>
        </div>
    </div> -->
</AuthenticatedLayout>
</template>
