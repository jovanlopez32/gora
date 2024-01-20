<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage, useForm } from '@inertiajs/vue3';
import { utils, writeFileXLSX } from 'xlsx';
import { ref, computed } from 'vue';


const user = usePage().props.auth.user;
const props = defineProps({
    sectors: {
        type: Object,
    },
    tags: {
        type: Object,
    },
    mes: {
        type: String,
    },
    years: {
        type: Array,
    },
    current_year: {
        type: [Number, String],
    },
    suma_total_globales: {
        type: Number,
    },
    sector: {
        type: Object,
    },
});



const today = new Date();
const currentMonth = today.getMonth() + 1;
const currentYear = today.getFullYear();

const searchForm = useForm({
    month: currentMonth.toString(),
    year: currentYear,
    sector: props.sector.id,
});

const submitSearch = () => {
    searchForm.post(route('gora.search.reports'), {
        onFinish: () => console.log('Datos buscados con éxito.'),
    });
};

const cleanFilters = () => {
    router.post(route('gora.clean.searchreports'));
    searchForm.month = currentMonth.toString();
    searchForm.year = currentYear;
    searchForm.sector = props.sector.id;
};

//Eliminar reporte
const deleteReport = (id_report) => {
    try {
        axios.post(route('gora.technical.delete.count.report'), {idreport: id_report}).then(response => {
            router.reload( { only: ['tagsReport'] }, { preserveState: true }, { preserveScroll: true });
            console.log(response);
        });
    } catch (error) {
        console.log(error);
    }
};

function exportFile(table, name) {
  const wb = utils.table_to_book(document.getElementById(table));
  writeFileXLSX(wb, name + '.xlsx');
}

</script>

<template>
<Head title="Alterno Reporte de los contadores" />

<AuthenticatedLayout>

    <!-- {{ props.sectors }} -->

    <form @submit.prevent="submitSearch">
        <!-- Seleccionar mes y año -->
        <div class="flex gap-5">
            <div class="flex flex-col gap-y-1 mb-5">
                <label for="sector" class="text-sm font-semibold w-full">Selecciona el mes:</label>
                <select v-model="searchForm.month" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                        <!-- <option v-for="sector in props.sectors" :value="sector.id">{{ sector.name }}</option> -->
                        <option class="bg-zinc-100" value="1">Enero</option>
                        <option class="bg-zinc-100" value="2">Febrero</option>
                        <option class="bg-zinc-100" value="3">Marzo</option>
                        <option class="bg-zinc-100" value="4">Abril</option>
                        <option class="bg-zinc-100" value="5">Mayo</option>
                        <option class="bg-zinc-100" value="6">Junio</option>
                        <option class="bg-zinc-100" value="7">Julio</option>
                        <option class="bg-zinc-100" value="8">Agosto</option>
                        <option class="bg-zinc-100" value="9">Septiembre</option>
                        <option class="bg-zinc-100" value="10">Octubre</option>
                        <option class="bg-zinc-100" value="11">Noviembre</option>
                        <option class="bg-zinc-100" value="12">Diciembre</option>
                </select>
            </div>

            <!-- Nuevo -->
            <div class="flex flex-col gap-y-1 mb-5">
                <label for="year" class="text-sm font-semibold w-full">Selecciona el año:</label>
                <select v-model="searchForm.year" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                        <option class="bg-zinc-100" v-for="year in props.years" :value="year">{{ year}}</option>
                </select>
            </div>

            <div class="flex flex-col gap-y-1 mb-5">
                <label for="year" class="text-sm font-semibold w-full">Selecciona el sector:</label>
                <select v-model="searchForm.sector" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                        <option class="bg-zinc-100" v-for="sector in props.sectors" :value="sector.id">{{ sector.name }}</option>
                </select>
            </div>
        </div>

        <button class="px-1 py-1 rounded-md bg-neutral-900 text-zinc-50 fill-zinc-100 hover:bg-neutral-500 transition-all duration-200">
            Buscar
        </button>

        <button @click.prevent="cleanFilters" class="mx-2 px-10 py-1 rounded-md bg-neutral-900 text-zinc-50 fill-zinc-100 hover:bg-neutral-500  transition-all duration-200">
            Limpiar filtros
        </button>

    </form>


    <!-- Estructura alterna -->
    <div class="flex flex-col-reverse w-full overflow-scroll">
        <div class="my-3 flex flex-col-reverse gap-2">
            <div>
                <div class="flex items-center gap-5">
                    <button @click="exportFile(props.sector.id, props.sector.name + props.mes)" class="bg-neutral-900 hover:bg-lime-600 p-3 rounded fill-zinc-100 ">
                        <svg class="w-4 h-4" viewBox="0 0 50 50" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><defs><path d="M50 0L50 0L50 50L0 50L0 0L50 0Z" id="path_1"></path><clipPath id="clip_1"><use xlink:href="#path_1"></use></clipPath></defs><g id="Microsoft Excel" clip-path="url(#clip_1)"><path d="M50 0L50 0L50 50L0 50L0 0L50 0Z" id="Microsoft-Excel" fill="none" stroke="none"></path><path d="M28.8125 0.03125L0.8125 5.34375C0.339844 5.43359 0 5.86328 0 6.34375L0 43.6562C0 44.1367 0.339844 44.5664 0.8125 44.6562L28.8125 49.9688C28.875 49.9805 28.9375 50 29 50C29.2305 50 29.4453 49.9297 29.625 49.7812C29.8555 49.5898 30 49.2969 30 49L30 1C30 0.703125 29.8555 0.410156 29.625 0.21875C29.3945 0.0273438 29.1055 -0.0234375 28.8125 0.03125L28.8125 0.03125ZM32 6L32 13L34 13L34 15L32 15L32 20L34 20L34 22L32 22L32 27L34 27L34 29L32 29L32 35L34 35L34 37L32 37L32 44L47 44C48.1016 44 49 43.1016 49 42L49 8C49 6.89844 48.1016 6 47 6L32 6ZM36 13L44 13L44 15L36 15L36 13ZM6.6875 15.6875L11.8125 15.6875L14.5 21.2812C14.7109 21.7227 14.8984 22.2656 15.0625 22.875L15.0938 22.875C15.1992 22.5117 15.4023 21.9414 15.6875 21.2188L18.6562 15.6875L23.3438 15.6875L17.75 24.9375L23.5 34.375L18.5312 34.375L15.2812 28.2812C15.1602 28.0547 15.0352 27.6367 14.9062 27.0312L14.875 27.0312C14.8125 27.3164 14.6641 27.7617 14.4375 28.3438L11.1875 34.375L6.1875 34.375L12.1562 25.0312L6.6875 15.6875ZM36 20L44 20L44 22L36 22L36 20ZM36 27L44 27L44 29L36 29L36 27ZM36 35L44 35L44 37L36 37L36 35Z" id="Shape" fill-rule="evenodd" stroke="none"></path></g></svg>
                    </button>
                    <p class="uppercase font-extrabold py-2 px-6 text-white rounded text-center my-4 bg-neutral-900 w-fit">{{ props.mes }} {{ props.current_year }}  | <span class=" underline decoration-2 underline-offset-4" :style="'text-decoration-color: ' + props.sector.color">{{ props.sector.name }}</span></p>
                </div>
                <table class=" w-full text-center left-0 " :id="props.sector.id">
                    <thead>
                        <tr class="[&>th]:text-xs [&>th]:py-3 [&>th]:border [&>th]:border-neutral-900 [&>th]:text-white bg-neutral-900 ">
                            <th colspan="6" class="bg-neutral-800"></th>
                            <th colspan="5" class="bg-lime-700">Impresiones/Copias</th>
                            <th colspan="5" class="bg-red-700">Escaneo</th>
                            <th colspan="5" class="bg-blue-700">Duplicadora</th>
                            <th colspan="3" class="bg-neutral-800"></th>
                        </tr>
                        <tr class="[&>th]:text-xs [&>th]:py-3 [&>th]:border [&>th]:border-neutral-900 text-white bg-neutral-900 ">
                            <th class="px-4">#qr</th>
                            <th class="px-4">Modelo</th>
                            <th class="px-8">Tecnico</th>
                            <th class="px-8">Dependencia </th>
                            <th class="px-8">Area </th>
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

                           <!--  <th  class="px-4">Acciones</th> -->

                        </tr>
                    </thead>

                    <tbody class="text-xs">
                        <template v-for="tag in props.tags" :key="tag.id">
                            <template v-if="tag.countreports && tag.countreports.length > 0">
                                <template v-for="report in tag.countreports">
                                    <tr class="[&>td]:text-xs [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 font-medium [&>td]:bg-white ">
                                        <td>{{ tag.qr }}</td>
                                        <td>{{ report.device.model }}</td>
                                        <td>{{ report.technical_name }}</td>
                                        <td>{{ tag.dependence }}</td>
                                        <td>{{ tag.location }}</td>
                                        <td>{{ tag.address }}</td>

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
                                        <td> $ {{ report.total_global }}</td>

                                        <!-- <td class="text-xs py-2 border border-neutral-900 font-medium">
                                            <button class="text-red-500 hover:text-red-700 select-none mx-1" @click="deleteReport(report.id)"> Eliminar registro</button>
                                        </td> -->
                                    </tr>
                                </template>


                            </template>

                            <template v-else>
                                <tr class="[&>td]:text-xs [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 ">
                                    <td class="bg-red-400">{{ tag.qr }} </td>

                                    <td class="bg-red-400">{{ tag.countreports[0] == undefined ? tag.device.model  : tag.countreports[0].device.model  }}</td>
                                    <td class="bg-red-400">X</td>
                                    <td class="bg-red-400">{{ tag.dependence }}</td>
                                    <td class="bg-red-400">{{ tag.location }}</td>
                                    <td class="bg-red-400">{{ tag.address }}</td>
                                    <!-- <td colspan="18" class="bg-red-600 text-white font-semibold">No hay datos disponibles.</td> -->

                                    <td>0</td>
                                    <td>0</td>
                                    <td>$ 0</td>
                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                    <td>$ 0</td>
                                    <td>0</td>
                                    <td>0</td>

                                    <td>0</td>
                                    <td>0</td>
                                    <td>$ 0</td>
                                    <td>0</td>
                                    <td>0</td>

                                    <td>{{ new Intl.DateTimeFormat('es-ES', { dateStyle: 'medium', timeStyle: 'medium' }).format(new Date()) }}</td>
                                    <td> $ 0</td>
                                </tr>
                            </template>

                        </template>

                        <tr class="[&>td]:text-xs [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 ">
                            <td colspan="21" class=" font-semibold py-1" :style="'background-color: ' + sector.color">TOTAL</td>
                            <td class="font-semibold py-1 bg-neutral-900 text-white" >$ {{ props.suma_total_globales }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Esta es la nueva estructura -->
    <!-- <div class="flex flex-col-reverse w-full overflow-scroll">
        <div class="my-3 flex flex-col-reverse gap-2">
            <div v-for="sector in sectors" :key="sector.id" class="">
                <div class="flex items-center gap-5">
                    <p class="uppercase font-extrabold py-2 px-6 text-white rounded text-center my-4 bg-neutral-900 w-fit">{{ props.mes }} {{ props.current_year }} | <span class=" underline decoration-2 underline-offset-4" :style="'text-decoration-color: ' + sector.color">{{ sector.name }}</span></p>
                </div>
                <table class=" w-full text-center left-0 ">
                    <thead>
                            <tr class="[&>th]:text-xs [&>th]:py-3 [&>th]:border [&>th]:border-neutral-900 [&>th]:text-white bg-neutral-900 ">
                                <th colspan="5" class="bg-neutral-800"></th>
                                <th colspan="5" class="bg-lime-700">Impresiones/Copias</th>
                                <th colspan="5" class="bg-red-700">Escaneo</th>
                                <th colspan="5" class="bg-blue-700">Duplicadora</th>
                                <th colspan="3" class="bg-neutral-800"></th>
                            </tr>
                            <tr class="[&>th]:text-xs [&>th]:py-3 [&>th]:border [&>th]:border-neutral-900 text-white bg-neutral-900 ">
                                <th class="px-4">#qr</th>
                                <th class="px-4">Modelo</th>
                                <th class="px-8">Tecnico</th>
                                <th class="px-8">Dependencia </th>
                                <th class="px-8">Dirección</th>

                                <th class="bg-lime-400 text-neutral-900 px-4">Lectura Anterior</th>
                                <th class="bg-lime-400 text-neutral-900 px-4">Lectura Actual</th>
                                <th class="bg-lime-400 text-neutral-900 px-4">Precio</th>
                                <th class="bg-lime-400 text-neutral-900 px-4">Producción</th>
                                <th class="bg-lime-400 text-neutral-900 px-4">Total</th>

                                <th class="bg-red-400 text-neutral-900 px-4">Lectura Anterior</th>
                                <th class="bg-red-400 text-neutral-900 px-4">Lectura Actual</th>
                                <th class="bg-red-400 text-neutral-900 px-4">Precio</th>
                                <th class="bg-red-400 text-neutral-900 px-4">Producción</th>
                                <th class="bg-red-400 text-neutral-900 px-4">Total</th>

                                <th class="bg-blue-400 text-neutral-900 px-4">Lectura Anterior</th>
                                <th class="bg-blue-400 text-neutral-900 px-4">Lectura Actual</th>
                                <th class="bg-blue-400 text-neutral-900 px-4">Precio</th>
                                <th class="bg-blue-400 text-neutral-900 px-4">Producción</th>
                                <th class="bg-blue-400 text-neutral-900 px-4">Total</th>

                                <th  class="px-20">Fecha</th>
                                <th  class="px-4">Total</th>

                                <th  class="px-4">Acciones</th>

                            </tr>
                    </thead>

                    <tbody class="text-xs">
                        <template v-for="tag in props.tags" :key="tag.id">
                            <template v-if="tag.sector_id === sector.id">
                                <template v-if="tag.countreports && tag.countreports.length > 0">
                                    <template v-for="report in tag.countreports">
                                        <tr class="[&>td]:text-xs [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 font-medium [&>td]:bg-white ">
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
                                            <td> $ {{ report.total_global }}</td>



                                            <td class="text-xs py-2 border border-neutral-900 font-medium">
                                                <button class="text-red-500 hover:text-red-700 select-none mx-1" @click="deleteReport(report.id)"> Eliminar registro</button>
                                            </td>



                                        </tr>


                                    </template>

                                </template>


                                <template v-else>
                                        <tr class="[&>td]:text-xs [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 ">
                                            <td>{{ tag.qr }} </td>
                                            <td>{{ tag.countreports[0] == undefined ? 'X' : tag.countreports[0].device.model  }}</td>
                                            <td>X</td>
                                            <td>{{ tag.dependence }}</td>
                                            <td>{{ tag.address }}</td>
                                            <td colspan="18" class="bg-red-600 text-white font-semibold">No hay datos disponibles.</td>
                                        </tr>


                                </template>
                            </template>
                        </template>
                        <tr class="[&>td]:text-xs [&>td]:py-2 [&>td]:border [&>td]:border-neutral-900 ">
                            <td colspan="21" class=" font-semibold py-1" :style="'background-color: ' + sector.color">TOTAL</td>
                            <td class="font-semibold py-1 bg-neutral-900 text-white" >$ {{ props.suma_total_globales }} </td>
                        </tr>



                    </tbody>



                </table>
            </div>
        </div>

    </div> -->
    <!-- Fin de la nueva estructura -->
</AuthenticatedLayout>
</template>
