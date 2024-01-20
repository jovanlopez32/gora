<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    tag: {
        type: Object,
    },
    technical: {
        type: Object,
    },
    count_reports: {
        type: Object,
    },
    problem_reports: {
        type: Object,
    }
});

const type_history = ref(true);
const openCount = ref(false)
const openProblem = ref(false);
const openHistory = ref(false);

const classCount = computed(() =>
    openCount.value ? 'absolute top-0 h-screen w-full bg-zinc-50 transition-all duration-500 flex items-center justify-center z-40 overflow-y-scroll' : 'h-0 overflow-y-scroll absolute top-full w-full bg-zinc-50 transition-all duration-500 flex items-center justify-center z-40'
);

const classProblem = computed(() =>
    openProblem.value ? 'absolute top-0 h-screen w-full bg-zinc-50 transition-all duration-500 flex items-center justify-center z-50 overflow-y-scroll' : 'h-0 overflow-y-scroll absolute top-full w-full bg-zinc-50 transition-all duration-500 flex items-center justify-center z-50'
);

const classHistory = computed(() =>
    openHistory.value ? 'absolute top-0 h-screen w-full bg-zinc-50 transition-all duration-500 flex items-start justify-center z-40 overflow-y-scroll' : 'h-0 absolute top-full w-full bg-zinc-50 transition-all duration-500 flex items-start justify-center z-40 overflow-y-scroll'
);

const formCount = useForm({
    count: null,
    count_print: null,
    count_scan: null,
    images: [],
    tag: props.tag.id,
    technical: props.technical.key,
});

const submitCount = () => {
    formCount.post(route('gora.technical.save.count.report'), {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            console.log('Datos con exito!');
        },
    });
};

const formProblem = useForm({
    problem: 'Problema Tecnico',
    description: '',
    images: [], // Change from image to images as an array
    tag: props.tag.id,
    technical: props.technical.key,
});

const handleCounterImages = (event) => {
    const selectedImages = event.target.files;
    formCount.images = Array.from(selectedImages);
};

const handleProblemImages = (event) => {
    const selectedImages = event.target.files;
    formProblem.images = Array.from(selectedImages);
};

const submitProblem = () => {
    formProblem.post(route('gora.technical.save.problem.report'), {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            console.log('Datos con exito!');
        },
    });
};


</script>

<template>
<Head title="Tecnico" />
<GuestLayout >
<div class="min-h-screen w-full flex items-center justify-center">
    <div class="max-w-xs w-full ">
        <!-- Instrucciones -->
        <div class="">
            <h1 class="text-base font-bold">Hola {{ props.technical.name }} !</h1>
            <p class="text-xs font-semibold mt-1">Corrobora los datos y despues selecciona la operacion que vas a realizar.</p>
        </div>

        <!-- Datos de la maquina y Historial -->
        <div class="w-full flex items-center justify-between my-3">
            <div>
                <p class="text-sm font-bold">{{ props.tag.device.model }}</p>
                <p class="text-xs font-semibold">{{ props.tag.device.type }}</p>
            </div>
            <button @click="openHistory = true" class=" flex items-center gap-2 py-1 pl-2 pr-10 rounded w-fit border-2 border-neutral-800" :class="props.tag.sector.name == 'SALUD' ? 'bg-orange-200' : 'bg-cyan-200'">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path><path d="M13 7h-2v6h6v-2h-4z"></path></svg>
                <p class="font-bold text-sm">Historial</p>
            </button>
        </div>
        <div class="w-full font-bold text-[8px] flex flex-wrap justify-between gap-1">
            <p>{{ props.tag.dependence }}</p>
            <p>{{ props.tag.location }}</p>
            <p class="w-full">{{ props.tag.address }}</p>
        </div>

        <!-- Operaciones -->
        <div class=" my-5 flex flex-col gap-5 ">
            <button @click="openCount = true" class="w-full py-6 rounded text-start px-4" :class="props.tag.sector.name == 'SALUD' ? 'bg-orange-400 border-2 border-neutral-900' : 'bg-cyan-200'">
                <span class="text-xl font-semibold">Registrar Contador</span>
            </button>
            <button @click="openProblem = true" class="w-full py-6 rounded text-start px-4" :class="props.tag.sector.name == 'SALUD' ? 'bg-orange-400 border-2 border-neutral-900' : 'bg-cyan-200'">
                <span class="text-xl font-semibold">Registrar Problema</span>
            </button>
        </div>

    </div>
</div>


<!-- Register Count Modal -->
<div :class="classCount">
    <div class=" w-full max-w-xs py-10">

        <div class="w-full flex items-center justify-end">
            <svg @click="openCount = false" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
        </div>

        <h1 class="text-xl font-extrabold">CONTADORES</h1>
        <p class="text-sm font-semibold">El ultimo contador que se registro fue:</p>
        <div v-show="props.tag.device.type != 'MULTIFUNCIONAL'" class="my-2" >
            <p class="font-semibold text-xs bg-orange-100 w-fit px-4 py-1 rounded border-2 border-neutral-900">Contador: {{ props.count_reports[0] != null ? props.count_reports[0].current_reading : 'No existen registros anteriores' }}</p>
        </div>
        <div v-show="props.tag.device.type == 'MULTIFUNCIONAL'" class=" w-full flex flex-wrap gap-x-2 gap-y-2 my-2" >
            <p class="font-semibold text-xs bg-orange-200 text-start px-4 py-1 rounded border-2 border-neutral-900 w-fit">IC : {{  props.count_reports[0] != null ? props.count_reports[0].current_reading_print : 'No existen registros anteriores' }}</p>
            <p class="font-semibold text-xs bg-orange-200 text-start px-4 py-1 rounded border-2 border-neutral-900 w-fit">ES : {{ props.count_reports[0] != null ? props.count_reports[0].current_reading_scan : 'No existen registros anteriores'}}</p>
        </div>


        <form @submit.prevent="submitCount">
            <div v-show="props.tag.device.type != 'MULTIFUNCIONAL'"  class="my-5">
                <label for="count" class="font-semibold text-sm">Ingresa el contador actual:</label>
                <input v-model="formCount.count" min="0" type="number" id="count" class="border-2 border-neutral-900 text-sm bg-zinc-50 rounded font-semibold" placeholder="1975">
            </div>
            <div v-show="props.tag.device.type == 'MULTIFUNCIONAL'" class="my-5 flex flex-col gap-4">
                <div>
                    <label for="count_print" class="font-semibold text-sm">Ingresa el contador <span class="underline decoration-2 underline-offset-2">Impresiones/Copias</span>:</label>
                    <input v-model="formCount.count_print" :min="props.count_reports[0] != null ? props.count_reports[0].current_reading_print : 0" type="number" id="count_print" class="mt-1 w-full focus:ring-orange-500 focus:border-orange-500 border-2 border-neutral-900 text-sm bg-zinc-50 rounded font-semibold" placeholder="1975">
                </div>

                <div>
                    <label for="count_scan" class="font-semibold text-sm">Ingresa el contador <span class="underline decoration-2 underline-offset-2">Escaner</span>:</label>
                    <input v-model="formCount.count_scan" :min="props.count_reports[0] != null ? props.count_reports[0].current_reading_scan : 0" type="number" id="count_scan" class="mt-1 w-full focus:ring-orange-500 focus:border-orange-500 border-2 border-neutral-900 text-sm bg-zinc-50 rounded font-semibold" placeholder="1975">
                </div>
            </div>

            <label for="dropzone-file-count" class="flex flex-col items-center justify-center w-full h-36 p-5 border-2 border-neutral-500 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-100 ">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg  v-show="formCount.images.length == 0" class="w-8 h-8 mb-4 text-neutral-700 " aria-hidden=    "true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                    </svg>
                    <svg v-show="formCount.images.length > 0" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-4 text-neutral-700 " viewBox="0 0 24 24" ><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-1.999 14.413-3.713-3.705L7.7 11.292l2.299 2.295 5.294-5.294 1.414 1.414-6.706 6.706z"></path></svg>
                    <p class="mb-2 text-sm text-neutral-600"><span class="font-semibold text-xs">{{ formCount.images.length == 0 ? 'Selecciona para subir tu imagen' : 'IMAGEN CARGADA' }}</span></p>
                </div>
                <!-- <input required id="dropzone-file-count" @input="formCount.image = $event.target.files[0]" type="file" class="hidden" /> -->
                <input @change="handleCounterImages" required id="dropzone-file-count" type="file" multiple class="hidden" />
            </label>
            <button  class="text-sm px-8 py-3 bg-neutral-900 rounded font-semibold text-white hover:bg-orange-500 transition-all duration-200 focus:bg-orange-500 disabled:bg-gray-500">SUBIR</button>
        </form>
    </div>
</div>

<!-- Problem Modal -->
<div :class="classProblem">
    <div class=" w-full max-w-xs py-10">
        <div class="w-full flex items-center justify-end">
            <svg @click="openProblem = false" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
        </div>

        <h1 class="text-xl font-extrabold mb-3">REPORTE DE PROBLEMAS</h1>
        <form @submit.prevent="submitProblem">
            <div class="flex flex-col gap-y-2">
                <div>
                    <label class="font-semibold text-sm" for="typeproblem">Selecciona el problema que se presenta</label>
                    <select v-model="formProblem.problem" id="typeproblem" class="mt-1 w-full focus:ring-orange-500 focus:border-orange-500 border-2 border-neutral-900 text-sm bg-zinc-50 rounded font-semibold" required>
                        <option value="Problema Tecnico">Problema Tecnico</option>
                        <option value="Cambio de Tonner">Cambio de Tonner</option>
                    </select>
                </div>
                <div>
                    <label class="font-semibold text-sm" for="descproblem">Agrega una breve descripcion</label>
                    <textarea placeholder="Describe el problema presente." v-model="formProblem.description" id="descproblem" class="mt-1 w-full focus:ring-orange-500 focus:border-orange-500 border-2 border-neutral-900 text-sm bg-zinc-50 rounded font-semibold" rows="6" required />
                </div>

                <label for="dropzone-file-problem" class="flex flex-col items-center justify-center w-full h-36 p-5 border-2 border-neutral-800 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-100 ">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg  v-show="formProblem.images.length == 0" class="w-8 h-8 mb-4 text-neutral-700 " aria-hidden=    "true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <svg v-show="formProblem.images.length > 0" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-4 text-neutral-700 " viewBox="0 0 24 24" ><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-1.999 14.413-3.713-3.705L7.7 11.292l2.299 2.295 5.294-5.294 1.414 1.414-6.706 6.706z"></path></svg>
                        <p class="mb-2 text-sm text-neutral-600"><span class="font-semibold text-xs">{{ formProblem.images.length == 0 ? 'Selecciona para subir tu imagen' : 'IMAGEN CARGADA' }}</span></p>
                    </div>
                    <input @change="handleProblemImages" required id="dropzone-file-problem" type="file" multiple class="hidden" />
                </label>
                <button  class=" w-full text-sm px-8 py-3 bg-neutral-900 rounded font-semibold text-white hover:bg-orange-500 transition-all duration-200 focus:bg-orange-500 disabled:bg-gray-500">SUBIR</button>
            </div>
        </form>
    </div>
</div>


<!-- History Modal -->
<div :class="classHistory">

    <div class=" w-full max-w-xs py-10">
        <div class="w-full flex items-center justify-end">
            <svg @click="openHistory = false" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
        </div>

        <div class="flex items-center gap-4">
            <button @click="type_history = true" class="text-xs font-semibold px-2 py-1 rounded border-2 border-neutral-800 text-neutral-900" :class="type_history == true ? 'bg-orange-400' : 'bg-orange-200' ">
                <span>Contadores</span>
            </button>
            <button @click="type_history = false" class="text-xs font-semibold px-2 py-1 rounded border-2 border-neutral-800 text-neutral-900" :class="type_history == false ? 'bg-orange-400' : 'bg-orange-200' ">
                <span>Problemas Tecnicos</span>
            </button>
        </div>

        <div class="flex items-center gap-2 my-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path><path d="M13 7h-2v6h6v-2h-4z"></path></svg>
            <h1 class="font-bold text-xl">Historial</h1>
        </div>

        <!-- Counter Report -->
        <div class="" v-show="type_history == true">
            <div v-for="history_counts in props.count_reports" class="text-xs mb-3">
                <!-- {{ history_counts }} -->
                <p class="font-bold mb-2">{{ history_counts.technical_name }} registro:</p>
                <table class="table-auto w-full border-2 border-black">
                    <thead class="border-b border-black">
                        <tr class="text-left font-medium">
                            <th class=" bg-neutral-300 pl-2 py-1" v-show="history_counts.device.type == 'MULTIFUNCIONAL'">Impresiones/Copias</th>
                            <th class=" bg-neutral-300 border-l border-black pl-2 py-1" v-show="history_counts.device.type == 'MULTIFUNCIONAL'">Escaneo</th>
                            <th class=" bg-neutral-300 pl-2 py-1" v-show="history_counts.device.type != 'MULTIFUNCIONAL'">Duplicadora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="font-medium" >
                            <td class=" pl-2 py-1" v-show="history_counts.device.type == 'MULTIFUNCIONAL'">{{ history_counts.current_reading_print }}</td>
                            <td class=" border-l border-black pl-2 py-1" v-show="history_counts.device.type == 'MULTIFUNCIONAL'">{{ history_counts.current_reading_scan }}</td>
                            <td class=" pl-2 py-1" v-show="history_counts.device.type != 'MULTIFUNCIONAL'">{{ history_counts.current_reading }}</td>
                        </tr>
                        <tr class=" border-black border-t-2" >
                            <td colspan="2" class="pl-2 font-semibold py-1">{{ new Date(history_counts.updated_at).toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' }) + ', ' + new Date(history_counts.updated_at).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Problems Report -->
        <div class="" v-show="type_history == false">
            <div v-for="history_problems in props.problem_reports" class="text-xs mb-3">
                <!-- {{ history_counts }} -->
                <p class="font-bold mb-2">{{ history_problems.technical_name }} registro:</p>

                <div class=" w-full border-2 border-neutral-900 rounded-md">
                    <div class="w-full bg-neutral-900 py-1.5 rounded-t-sm text-neutral-100 px-2">
                        <span class="font-bold">{{ history_problems.problem }}</span>
                    </div>
                    <div class="w-full p-2">
                        <p class="font-medium">{{ history_problems.description }}</p>
                    </div>
                    <div class="w-full bg-neutral-900 py-1.5 rounded-b-sm text-neutral-100 px-2">
                        <span class="font-bold text-[11px]">{{ new Date(history_problems.updated_at).toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' }) + ', ' + new Date(history_problems.updated_at).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</GuestLayout>

</template>
