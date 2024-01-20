<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ref } from 'vue'
import { utils, writeFileXLSX } from 'xlsx';


const props = defineProps({
    problems_report: {
        type: Object,
    },
});

const arrImg = ref(new Array);
const modal = ref(false);

function exportFile() {
  const wb = utils.table_to_book(document.getElementById('table'));
  writeFileXLSX(wb, 'Reporte de Falllo Gora.xlsx');
}

function showImages(report){
    modal.value = true;
    arrImg.value = JSON.parse(report.url);
}

const classModal = computed(() => {
    return modal.value ? 'fixed top-0 left-0 w-full h-screen bg-gray-400 rounded-md bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 transition-all duration-300 flex items-center justify-center' : 'fixed top-full left-0 w-full h-screen bg-gray-400 rounded-md bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 transition-all duration-300 flex items-center justify-center'
});

</script>

<template>
<Head title="Reporte tecnico" />
<AuthenticatedLayout>

<button @click="exportFile()" class="my-5 bg-neutral-900 hover:bg-lime-600 p-3 rounded fill-zinc-100 ">
    <svg class="w-4 h-4" viewBox="0 0 50 50" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><defs><path d="M50 0L50 0L50 50L0 50L0 0L50 0Z" id="path_1"></path><clipPath id="clip_1"><use xlink:href="#path_1"></use></clipPath></defs><g id="Microsoft Excel" clip-path="url(#clip_1)"><path d="M50 0L50 0L50 50L0 50L0 0L50 0Z" id="Microsoft-Excel" fill="none" stroke="none"></path><path d="M28.8125 0.03125L0.8125 5.34375C0.339844 5.43359 0 5.86328 0 6.34375L0 43.6562C0 44.1367 0.339844 44.5664 0.8125 44.6562L28.8125 49.9688C28.875 49.9805 28.9375 50 29 50C29.2305 50 29.4453 49.9297 29.625 49.7812C29.8555 49.5898 30 49.2969 30 49L30 1C30 0.703125 29.8555 0.410156 29.625 0.21875C29.3945 0.0273438 29.1055 -0.0234375 28.8125 0.03125L28.8125 0.03125ZM32 6L32 13L34 13L34 15L32 15L32 20L34 20L34 22L32 22L32 27L34 27L34 29L32 29L32 35L34 35L34 37L32 37L32 44L47 44C48.1016 44 49 43.1016 49 42L49 8C49 6.89844 48.1016 6 47 6L32 6ZM36 13L44 13L44 15L36 15L36 13ZM6.6875 15.6875L11.8125 15.6875L14.5 21.2812C14.7109 21.7227 14.8984 22.2656 15.0625 22.875L15.0938 22.875C15.1992 22.5117 15.4023 21.9414 15.6875 21.2188L18.6562 15.6875L23.3438 15.6875L17.75 24.9375L23.5 34.375L18.5312 34.375L15.2812 28.2812C15.1602 28.0547 15.0352 27.6367 14.9062 27.0312L14.875 27.0312C14.8125 27.3164 14.6641 27.7617 14.4375 28.3438L11.1875 34.375L6.1875 34.375L12.1562 25.0312L6.6875 15.6875ZM36 20L44 20L44 22L36 22L36 20ZM36 27L44 27L44 29L36 29L36 27ZM36 35L44 35L44 37L36 37L36 35Z" id="Shape" fill-rule="evenodd" stroke="none"></path></g></svg>
</button>

<table id="table" class="table-fixed w-full text-center">

<thead>
    <tr class="[&>th]:text-xs [&>th]:py-3 [&>th]:border [&>th]:border-neutral-900 [&>th]:text-white bg-neutral-900 ">
        <th>#qr</th>
        <th>Modelo</th>
        <th>Tecnico</th>
        <th>Sector</th>
        <th>Dependencia </th>
        <th>Ubicación</th>
        <th>Dirección</th>
        <th>Tipo</th>
        <th>Descripción</th>
        <th>Fecha</th>
    </tr>
</thead>

<tbody class="text-xs">
    <tr @click="showImages(report)" v-for="(report, index) in props.problems_report.data" class="[&>td]:text-xs [&>td]:px-2 [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 font-medium [&>td]:bg-white cursor-pointer [&>td]:hover:bg-neutral-300 ">
        <td>{{ report.tag.qr }}</td>
        <td>{{ report.device.model }}</td>
        <td>{{ report.technical_name }}</td>
        <td class="font-bold" :style="'background-color: ' + report.sector.color">{{ report.sector.name }}</td>
        <td class="text-left">{{ report.tag.dependence }}</td>
        <td class="text-left">{{ report.tag.location }}</td>
        <td class="text-left">{{ report.tag.address }}</td>
        <td class="text-left">{{ report.problem }}</td>
        <td class="text-left">{{ report.description }}</td>
        <td class="uppercase">{{ new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium', timeStyle: 'medium' }).format(new Date(report.created_at)) }}</td>
    </tr>
</tbody>
</table>

<div :class="classModal">
    <div class="absolute top-0 right-0">
        <svg @click="modal = false" xmlns="http://www.w3.org/2000/svg" class="w-10 m-4 fill-red-600 cursor-pointer" viewBox="0 0 24 24" ><path d="M21 5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5zm-4.793 9.793-1.414 1.414L12 13.414l-2.793 2.793-1.414-1.414L10.586 12 7.793 9.207l1.414-1.414L12 10.586l2.793-2.793 1.414 1.414L13.414 12l2.793 2.793z"></path></svg>
    </div>

    <div class="relative grid grid-cols-2 lg:grid-cols-4 px-10 items-stretch place-content-center gap-10">


        <div v-for="img in arrImg" class="px-10 bg-neutral-100 py-2 rounded border-2 border-black">
            <img  :src="img" class="object-contain w-32 " alt="">
        </div>

    </div>

</div>

</AuthenticatedLayout>
</template>
