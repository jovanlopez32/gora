<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { utils, writeFileXLSX } from 'xlsx';



const props = defineProps({
    tagsReport: {
        type: Object,
    },
    sectors: {
        type: Object,
    },
});

function getMonthFromDate(dateStr) {
    const date = new Date(dateStr);
    return date.getMonth() + 1; // Se suma 1 porque los meses en JavaScript van de 0 a 11
}

// Objeto para almacenar los datos agrupados por mes
const dataPorMes = {};

// Recorrer el arreglo principal
for (const item of props.tagsReport) {
    // Recorrer los countreports de cada elemento
    for (const countreport of item.countreports) {
        // Obtener el mes de la fecha creada en el countreport
        const mes = getMonthFromDate(countreport.created_at);

        // Si aún no existe un arreglo para ese mes, se crea
        if (!dataPorMes[mes]) {
            dataPorMes[mes] = [];
        }

        // Agregar el countreport al arreglo correspondiente al mes
        dataPorMes[mes].push(countreport);
    }
}

function hasReportForTag(month, tagId) {
    return month.some(report => report.tag_id === tagId);
}

function getReportsForTag(month, tagId) {
    return month.filter(report => report.tag_id === tagId);
}

let meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio","julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];

// Declara un objeto para almacenar los totales de cada mes y sector
const totales = {};

// Declara un objeto para almacenar los totales por sector
const totalesPorSector = {};

for (const [index, month] of Object.entries(dataPorMes)) {
    // Inicializa el objeto del mes si aún no existe
    if (!totales[index]) {
        totales[index] = {};
    }

    // Recorre cada sector en props.sectors
    for (const sector of props.sectors) {
        const sectorId = sector.id;

        // Inicializa el total del sector para este mes a cero
        let totalMesSector = 0;

        // Recorre cada etiqueta (tag) en props.tagsReport
        for (const tag of props.tagsReport) {
            if (hasReportForTag(month, tag.id) && tag.sector_id === sectorId) {
                for (const report of getReportsForTag(month, tag.id)) {
                    totalMesSector += report.total_global;
                }
            }
        }

        // Guarda el total del sector para este mes
        totales[index][sectorId] = totalMesSector;

        // Suma el total del sector a su total general
        if (!totalesPorSector[sectorId]) {
            totalesPorSector[sectorId] = 0;
        }
        totalesPorSector[sectorId] += totalMesSector;
    }
}


const modal = ref(false);
const arrImg = ref(new Array());

function showImage(report) {
    modal.value = true;
    arrImg.value = JSON.parse(report.url);
}

const classModal = computed(() => {
    return modal.value ? 'fixed top-0 left-0 w-full h-screen bg-gray-400 rounded-md bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 transition-all duration-300 flex items-center justify-center z-20' : 'fixed top-full left-0 w-full h-screen bg-gray-400 rounded-md bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 transition-all duration-300 flex items-center justify-center z-20'
});


function exportFile(table, name) {
  const wb = utils.table_to_book(document.getElementById(table));
  writeFileXLSX(wb, name + '.xlsx');
}
</script>

<template>
<Head title="Reporte de los contadores" />
<AuthenticatedLayout>

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

<div class="flex flex-col-reverse w-full overflow-scroll">
    <!-- Recorre cada mes en dataPorMes -->
    <div v-for="(month, index) in dataPorMes" :key="index" class="my-10 flex flex-col-reverse gap-2">
        <!-- Muestra el texto "Aqui va el mes" -->
        <div v-for="sector in sectors" :key="sector.id" class="">
            <div class="flex items-center gap-5">
                <button @click="exportFile(sector.id+index, sector.name + meses[index-1])" class="bg-neutral-900 hover:bg-lime-600 p-3 rounded fill-zinc-100 ">
                    <svg class="w-4 h-4" viewBox="0 0 50 50" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><defs><path d="M50 0L50 0L50 50L0 50L0 0L50 0Z" id="path_1"></path><clipPath id="clip_1"><use xlink:href="#path_1"></use></clipPath></defs><g id="Microsoft Excel" clip-path="url(#clip_1)"><path d="M50 0L50 0L50 50L0 50L0 0L50 0Z" id="Microsoft-Excel" fill="none" stroke="none"></path><path d="M28.8125 0.03125L0.8125 5.34375C0.339844 5.43359 0 5.86328 0 6.34375L0 43.6562C0 44.1367 0.339844 44.5664 0.8125 44.6562L28.8125 49.9688C28.875 49.9805 28.9375 50 29 50C29.2305 50 29.4453 49.9297 29.625 49.7812C29.8555 49.5898 30 49.2969 30 49L30 1C30 0.703125 29.8555 0.410156 29.625 0.21875C29.3945 0.0273438 29.1055 -0.0234375 28.8125 0.03125L28.8125 0.03125ZM32 6L32 13L34 13L34 15L32 15L32 20L34 20L34 22L32 22L32 27L34 27L34 29L32 29L32 35L34 35L34 37L32 37L32 44L47 44C48.1016 44 49 43.1016 49 42L49 8C49 6.89844 48.1016 6 47 6L32 6ZM36 13L44 13L44 15L36 15L36 13ZM6.6875 15.6875L11.8125 15.6875L14.5 21.2812C14.7109 21.7227 14.8984 22.2656 15.0625 22.875L15.0938 22.875C15.1992 22.5117 15.4023 21.9414 15.6875 21.2188L18.6562 15.6875L23.3438 15.6875L17.75 24.9375L23.5 34.375L18.5312 34.375L15.2812 28.2812C15.1602 28.0547 15.0352 27.6367 14.9062 27.0312L14.875 27.0312C14.8125 27.3164 14.6641 27.7617 14.4375 28.3438L11.1875 34.375L6.1875 34.375L12.1562 25.0312L6.6875 15.6875ZM36 20L44 20L44 22L36 22L36 20ZM36 27L44 27L44 29L36 29L36 27ZM36 35L44 35L44 37L36 37L36 35Z" id="Shape" fill-rule="evenodd" stroke="none"></path></g></svg>
                </button>
                <p class="uppercase font-extrabold py-2 px-6 text-white rounded text-center my-4 bg-neutral-900 w-fit">{{ meses[index-1] }} | <span class=" underline decoration-2 underline-offset-4" :style="'text-decoration-color: ' + sector.color">{{ sector.name }}</span></p>
            </div>
            <!-- Recorre cada etiqueta (tag) en props.tagsReport -->
            <table class=" w-full text-center left-0 " :id="sector.id+index">

                <thead>
                    <tr class="[&>th]:text-xs [&>th]:py-3 [&>th]:border [&>th]:border-neutral-900 [&>th]:text-white bg-neutral-900 ">
                        <th colspan="5" class="bg-neutral-800"></th>
                        <th colspan="5" class="bg-lime-700">Impresiones/Copias</th>
                        <th colspan="5" class="bg-red-700">Escaneo</th>
                        <th colspan="5" class="bg-blue-700">Duplicadora</th>
                        <th colspan="2" class="bg-neutral-800"></th>
                    </tr>
                    <tr class="[&>th]:text-xs [&>th]:py-3 [&>th]:border [&>th]:border-neutral-900 text-white bg-neutral-900 ">
                        <th class="px-4">#qr</th>
                        <th class="px-4">Modelo</th>
                        <th class="px-8">Tecnico</th>
                        <th class="px-8">Dependencia </th>
                        <th class="px-8">Dirección</th>
                        <!-- Impresiones -->
                        <th class="bg-lime-400 text-neutral-900 px-4">Lectura Anterior</th>
                        <th class="bg-lime-400 text-neutral-900 px-4">Lectura Actual</th>
                        <th class="bg-lime-400 text-neutral-900 px-4">Precio</th>
                        <th class="bg-lime-400 text-neutral-900 px-4">Producción</th>
                        <th class="bg-lime-400 text-neutral-900 px-4">Total</th>
                        <!-- Escaneo -->
                        <th class="bg-red-400 text-neutral-900 px-4">Lectura Anterior</th>
                        <th class="bg-red-400 text-neutral-900 px-4">Lectura Actual</th>
                        <th class="bg-red-400 text-neutral-900 px-4">Precio</th>
                        <th class="bg-red-400 text-neutral-900 px-4">Producción</th>
                        <th class="bg-red-400 text-neutral-900 px-4">Total</th>
                        <!-- Duplicadora -->
                        <th class="bg-blue-400 text-neutral-900 px-4">Lectura Anterior</th>
                        <th class="bg-blue-400 text-neutral-900 px-4">Lectura Actual</th>
                        <th class="bg-blue-400 text-neutral-900 px-4">Precio</th>
                        <th class="bg-blue-400 text-neutral-900 px-4">Producción</th>
                        <th class="bg-blue-400 text-neutral-900 px-4">Total</th>

                        <th  class="px-20">Fecha</th>
                        <th  class="px-4">Total</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    <template v-for="tag in  props.tagsReport" :key="tag.id">
                        <template v-if="tag.sector_id === sector.id">
                            <!-- Verifica si hay un informe (report) correspondiente para el mes y etiqueta actual -->
                            <template v-if="hasReportForTag(month, tag.id)">
                                <!-- Si hay informes, recorre cada informe y muestra el tag.id -->
                                <template v-for="report in getReportsForTag(month, tag.id)" :key="report.id">
                                    <tr @click="showImage(report)" class="[&>td]:text-xs [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 font-medium [&>td]:bg-white cursor-pointer [&>td]:hover:bg-neutral-300">
                                        <td>{{ tag.qr }}</td>
                                        <td>{{ report.device.model }}</td>
                                        <td>{{ report.technical_name }}</td>
                                        <td>{{ tag.dependence }}</td>
                                        <td>{{ tag.location }}</td>

                                        <td>{{ report.previous_reading_print }}</td>
                                        <td>{{ report.current_reading_print }}</td>
                                        <td>$ {{ report.device.price_print }}</td>
                                        <td>{{ report.production_print }}</td>
                                        <td>{{ report.total_print }}</td>

                                        <td>{{ report.previous_reading_scan }}</td>
                                        <td>{{ report.current_reading_scan }}</td>
                                        <td>$ {{ report.device.price_scan }}</td>
                                        <td>{{ report.production_scan }}</td>
                                        <td>{{ report.total_scan }}</td>

                                        <td>{{ report.previous_reading }}</td>
                                        <td>{{ report.current_reading }}</td>
                                        <td>$ {{ report.device.price }}</td>
                                        <td>{{ report.production }}</td>
                                        <td>{{ report.total }}</td>


                                        <td>{{ new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium', timeStyle: 'medium' }).format(new Date(report.created_at)) }}</td>
                                        <td>$ {{ report.total_global }}</td>

                                    </tr>
                                </template>
                            </template>
                            <!-- Si no hay informes, muestra el tag.id seguido del texto "Aun no tiene datos" -->
                            <template v-else>
                                <tr class="[&>td]:text-xs [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 ">
                                    <td>{{ tag.qr }} </td>
                                    <td>{{ tag.countreports[0] == undefined ? 'X' : tag.countreports[0].device.model  }}</td>
                                    <td>X</td>
                                    <td>{{ tag.dependence }}</td>
                                    <td>{{ tag.address }}</td>
                                    <td colspan="17" class="bg-red-600 text-white font-semibold">No hay datos disponibles.</td>
                                </tr>
                            </template>
                        </template>
                    </template>
                    <tr class="[&>td]:text-xs [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 ">
                        <td colspan="21" class=" font-semibold py-1" :style="'background-color: ' + sector.color">TOTAL</td>
                        <td class="font-semibold py-1 bg-neutral-900 text-white" >$ {{ totales[index] && totales[index][sector.id] ? totales[index][sector.id] : 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


</AuthenticatedLayout>
</template>
