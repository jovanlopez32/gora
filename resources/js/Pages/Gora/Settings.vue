<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    technicals: {
        type: Object,
    },
    sectors: {
        type: Object,
    },
    devices: {
        type: Object,
    },
    day: {
        type: Object,
    }
});

//Modal para el fomulario que añade un nuevo tecnico.
const technical_modal = ref(false);
const technical_modal_class = computed(() =>
    technical_modal.value ? 'fixed h-screen w-full max-w-sm bg-white top-0 -right-0 rounded transition-all duration-500  grid grid-cols-1 items-center justify-center' : 'fixed h-screen w-full max-w-sm bg-white top-0 -right-full rounded transition-all duration-500  grid grid-cols-1 items-center justify-center'
);
//Formulario que añade un nuevo tecnico.
const t_form = useForm({
    name_t: '',
    key_t: '',
    job_t: '',
});
//Funcion para crear un nuevo tecnico.
const new_technical = () => {
    t_form.post(route('gora.settings.add.technical'), {
        onFinish: () => {
            technical_modal.value = false;
            t_form.name_t = '';
            t_form.key_t = '';
            t_form.job_t = '';
        }
    });
};

//Modal para el fomulario que añade un nuevo sector.
const sector_modal = ref(false);
const sector_modal_class = computed(() =>
    sector_modal.value ? 'fixed h-screen w-full max-w-sm bg-white top-0 -right-0 rounded transition-all duration-500  grid grid-cols-1 items-center justify-center' : 'fixed h-screen w-full max-w-sm bg-white top-0 -right-full rounded transition-all duration-500  grid grid-cols-1 items-center justify-center'
);
//Formulario que añade un nuevo sector.
const s_form = useForm({
    name_s: '',
    color_s: '',
});
//Funcion para crear un nuevo sector.
const new_sector = () => {
    s_form.post(route('gora.settings.add.sector'), {
        onFinish: () => {
            sector_modal.value = false;
            s_form.name_s = '';
            s_form.color_s = '';
        }
    });
};

//Modal para el fomulario que añade un nuevo sector.
const device_modal = ref(false);
const device_modal_class = computed(() =>
    device_modal.value ? 'fixed h-screen w-full max-w-sm bg-white top-0 -right-0 rounded transition-all duration-500  grid grid-cols-1 items-center justify-center' : 'fixed h-screen w-full max-w-sm bg-white top-0 -right-full rounded transition-all duration-500  grid grid-cols-1 items-center justify-center'
);

/* Formulario que cambia el final del mes */
const f_endday = useForm({
    day: props.day.day,
})


//Formulario que añade un nuevo sector.
const d_form = useForm({
    model_d: '',
    type_d: 'MULTIFUNCIONAL',
    price_d: '',
    price_d_print: '',
    price_d_scan: '',

});
//Funcion para crear un nuevo sector.
const new_device = () => {
    d_form.post(route('gora.settings.add.device'), {
        onFinish: () => {
            device_modal.value = false;
            d_form.model_d = '';
            d_form.type_d = 'MULTIFUNCIONAL';
            d_form.price_d = '';
            d_form.price_d_print = '';
            d_form.price_d_scan = '';
        }
    });
};

//Editar técnico
const modal = ref(false);
const id = ref('');

const editclass = computed(() => modal.value ? 'fixed w-full h-full bg-neutral-700 top-0 right-0 left-0 z-[100] flex items-center justify-center bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 z-20 transition-all duration-300 ease-in-out' : 'fixed w-full h-full bg-neutral-700 top-0 right-0 left-0 -z-20 bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 -z-20  transition-all duration-300 ease-in-out flex items-center justify-center')

const formEditTechnical = useForm({
    name:'',
    key:'',
    job:'',
});

const openModal = (name, key, job, technical) =>{
    modal.value = true;
    id.value = technical;
    formEditTechnical.name = name;
    formEditTechnical.key = key;
    formEditTechnical.job = job;

}

const closeModal = () =>{
    modal.value = false;
    formEditTechnical.reset();
}

const editTechnical = () =>{
    formEditTechnical.put(route('gora.settings.edit.technical', id.value), {
        onSuccess: () => {okTechnical('Técnico actualizado')}
    });
}

const okTechnical = (msj) =>{
    formEditTechnical.reset();
    closeModal();
}
//Fin de editar técnico

//Editar equipo
const modalTeam = ref(false);
const idTeam = ref('');

const editclassTeam = computed(() => modalTeam.value ? 'fixed w-full h-full bg-neutral-700 top-0 right-0 left-0 z-[100] flex items-center justify-center bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 z-20 transition-all duration-300 ease-in-out' : 'fixed w-full h-full bg-neutral-700 top-0 right-0 left-0 -z-20 bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 -z-20  transition-all duration-300 ease-in-out flex items-center justify-center')

const formEditTeam = useForm({
    model:'',
    type:'',
    price_print:'',
    price_scan:'',
    price:'',
});

const openModalTeam = (model, type, price_print, price_scan, price, device) =>{
    modalTeam.value = true;
    idTeam.value = device;
    formEditTeam.model = model;
    formEditTeam.type = type;
    formEditTeam.price_print = price_print;
    formEditTeam.price_scan = price_scan;
    formEditTeam.price = price;
}

const closeModalTeam = () =>{
    modalTeam.value = false;
    formEditTechnical.reset();
}

const editTeam = () =>{
    formEditTeam.put(route('gora.settings.edit.device', idTeam.value), {
        onSuccess: () => {okTeam('Técnico actualizado')}
    });
}

const okTeam = (msj) =>{
    formEditTeam.reset();
    closeModalTeam();
}
//Fin de editar equipo

//Editar sector
const modalSector= ref(false);
const idSector = ref('');

const editclassSector = computed(() => modalSector.value ? 'fixed w-full h-full bg-neutral-700 top-0 right-0 left-0 z-[100] flex items-center justify-center bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 z-20 transition-all duration-300 ease-in-out' : 'fixed w-full h-full bg-neutral-700 top-0 right-0 left-0 -z-20 bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 -z-20  transition-all duration-300 ease-in-out flex items-center justify-center')

const formEditSector = useForm({
    name:'',
    color:'',
});

const openModalSector = (name, color, sector) =>{
    modalSector.value = true;
    idSector.value = sector;
    formEditSector.name = name;
    formEditSector.color = color;
}

const closeModalSector = () =>{
    modalSector.value = false;
    formEditSector.reset();
}

const editSector = () =>{
    formEditSector.put(route('gora.settings.edit.sector', idSector.value), {
        onSuccess: () => {okSector('Sector actualizado')}
    });
}

const editEndDay = () => {
    f_endday.put(route('gora.settings.edit.day', f_endday.day), {
        onSuccess: () => {okSector('Dia actualizado')}
    });
}

const okSector = (msj) =>{
    formEditSector.reset();
    closeModalSector();
}
//Fin de editar equipo


</script>

<template>

<Head title="Configuración de datos" />
<AuthenticatedLayout class="relative overflow-hidden">

    <div>
        <h1 class=" font-bold text-xl">Configuración de datos</h1>
        <p class="font-medium text-sm my-1">En esta sección podras agregar y modificar los datos principales para que la plataforma funcione correctamente.</p>
    </div>

    <div class="my-10">
        <h1 class=" font-bold text-xl">Ultimo dia del mes</h1>
        <form @submit.prevent="editEndDay" class="flex flex-col w-fit gap-2 my-5">
            <input type="number" v-model="f_endday.day" min="1" max="31" class="w-fit px-3 py-2 text-xl rounded border-2 border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">

            <button class="text-sm px-8 py-2 bg-neutral-900 rounded font-semibold text-white hover:bg-cyan-500 transition-all duration-200 focus:bg-cyan-500"> ACTUALIZAR</button>
        </form>
    </div>

    <div class="my-10">

        <div class="flex items-center justify-between">
            <h1 class=" font-semibold text-lg">Tecnicos</h1>
            <button @click="technical_modal = true" class="text-xs px-4 py-2 rounded bg-cyan-400 hover:bg-cyan-500 font-semibold flex items-center gap-2 transition-all duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" ><path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"></path></svg>
                <span>Nuevo Tecnico</span>
            </button>
        </div>

        <div v-if="props.technicals.length == 0" class="my-5">
            <p class="text-sm">No hay ningun tecnico registrado actualmente.</p>
        </div>

        <div v-else class="my-5 flex flex-wrap gap-5">
            <div v-for="technical in props.technicals" class="flex flex-col gap-2 px-8 py-2 rounded border-2 border-neutral-900 w-fulls lg:w-fit bg-white">
                <h1 class="font-semibold text-lg">{{ technical.name }}</h1>
                <div class="flex items-center gap-2 ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" ><path d="M7 17a5.007 5.007 0 0 0 4.898-4H14v2h2v-2h2v3h2v-3h1v-2h-9.102A5.007 5.007 0 0 0 7 7c-2.757 0-5 2.243-5 5s2.243 5 5 5zm0-8c1.654 0 3 1.346 3 3s-1.346 3-3 3-3-1.346-3-3 1.346-3 3-3z"></path></svg>
                    <p class="text-xs underline underline-offset-4">{{ technical.key }}</p>
                </div>
                <div class="flex flex-wrap">
                    <h3 class="text-sm">{{ technical.job }}</h3>
                    <h3 class="text-sm mx-2 underline underline-offset-4 hover:cursor-pointer" @click="openModal(technical.name, technical.key, technical.job, technical.id)"><strong>EDITAR TÉCNICO</strong></h3>
                </div>

            </div>
        </div>

    </div>

    <div class="my-10">

        <div class="flex items-center justify-between">
            <h1 class=" font-semibold text-lg">Sectores</h1>
            <button @click="sector_modal = true" class="text-xs px-4 py-2 rounded bg-cyan-400 hover:bg-cyan-500 font-semibold flex items-center gap-2 transition-all duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" ><path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"></path></svg>
                <span>Nuevo Sector</span>
            </button>
        </div>

        <div v-if="props.sectors.length == 0" class="my-5">
            <p class="text-sm">No hay ningun sector registrado actualmente.</p>
        </div>

        <div v-else class="my-5 flex flex-wrap gap-5">
            <div v-for="sector in props.sectors" class="relative flex items-center gap-4 w-full lg:w-fit pl-14 pr-6 py-1 rounded font-semibold border-2 border-neutral-900 bg-white">
                <div class="w-10 h-full absolute left-0" :style="'background-color: ' + sector.color + ';'"></div>
                <p>{{ sector.name }}</p>
                <h3 class="text-sm mx-2 underline underline-offset-4 hover:cursor-pointer" @click="openModalSector(sector.name, sector.color, sector.id)"> - EDITAR</h3>
            </div>
        </div>

    </div>

    <div class="my-10">

        <div class="flex items-center justify-between">
            <h1 class=" font-semibold text-lg">Equipos</h1>
            <button @click="device_modal = true" class="text-xs px-4 py-2 rounded bg-cyan-400 hover:bg-cyan-500 font-semibold flex items-center gap-2 transition-all duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" ><path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"></path></svg>
                <span>Nuevo Equipo</span>
            </button>
        </div>

        <div v-if="props.devices.length == 0" class="my-5">
            <p class="text-sm">No hay ningun equipo registrado actualmente.</p>
        </div>

        <div v-else class="my-5 flex flex-wrap gap-5">
            <!-- <div v-for="device in props.devices" class="flex items-center gap-3 px-6 py-2 border-2 rounded border-neutral-900 bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="object-contain w-14" viewBox="0 0 24 24" ><path d="M19 7h-1V2H6v5H5a3 3 0 0 0-3 3v7a2 2 0 0 0 2 2h2v3h12v-3h2a2 2 0 0 0 2-2v-7a3 3 0 0 0-3-3zM8 4h8v3H8V4zm0 16v-4h8v4H8zm11-8h-4v-2h4v2z"></path></svg>
                <div class="">
                    <h1 class="text-lg font-semibold">{{ device.model }}</h1>
                    <h2 class="text-xs">{{ device.type }}</h2>
                    <div class="flex flex-wrap gap-4">
                        <p v-show="device.type != 'MULTIFUNCIONAL'" class="text-sm font-semibold">$ {{ device.price }}</p>
                        <p v-show="device.type == 'MULTIFUNCIONAL'" class="text-xs font-semibold">Imp: $ {{ device.price_print }}</p>
                        <p v-show="device.type == 'MULTIFUNCIONAL'" class="text-xs font-semibold">Esc: $ {{ device.price_scan }}</p>
                    </div>
                </div>

            </div> -->


            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 border-2 border-black">
                    <thead class="text-xs text-gray-900 uppercase bg-zinc-300" >
                        <tr>
                            <th scope="col" class="px-6 py-3 border border-black hidden lg:table-cell">ID</th>
                            <th scope="col" class="px-6 py-3 border border-black">Modelo</th>
                            <th scope="col" class="px-6 py-3 border border-black">Tipo</th>
                            <th scope="col" class="px-6 py-3 border border-black hidden lg:table-cell">Precio Impresion</th>
                            <th scope="col" class="px-6 py-3 border border-black hidden lg:table-cell">Precio Escaner</th>
                            <th scope="col" class="px-6 py-3 border border-black hidden lg:table-cell">Precio Duplicadora</th>
                            <th scope="col" class="px-6 py-3 border border-black hidden lg:table-cell">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>


                        <tr v-for="device in props.devices" class="bg-white ">
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50  hidden lg:table-cell">{{ device.id }}</td>
                            <td class="px-6 py-4 text-black border border-black">{{ device.model }}</td>
                            <td class="px-3 py-4 border border-black">{{ device.type }}</td>
                            <td class="px-6 py-4 border border-black hidden lg:table-cell" :class="device.price_print == null ? 'bg-zinc-300' : ''">{{ device.price_print }}</td>
                            <td class="px-6 py-4 border border-black hidden lg:table-cell" :class="device.price_scan == null ? 'bg-zinc-300' : ''">{{ device.price_scan }}</td>
                            <td class="px-6 py-4 border border-black hidden lg:table-cell" :class="device.price == null ? 'bg-zinc-300' : ''">{{ device.price }}</td>
                            <td class="px-6 py-4 text-black border border-black underline underline-offset-4 hover:cursor-pointer hidden lg:table-cell" @click="openModalTeam(device.model, device.type, device.price_print, device.price_scan, device.price, device.id)">EDITAR</td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>

    </div>


    <!-- Technical Form -->
    <div :class="technical_modal_class" >
        <div class="flex items-center justify-between px-5 py-4 w-full self-start rounded-t">
            <h4 class="text-xs underline underline-offset-4 decoration-2 decoration-cyan-400">Agregar un nuevo tecnico</h4>
            <svg @click="technical_modal = false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="cursor-pointer" viewBox="0 0 24 24" ><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
        </div>

        <div class="self-center px-5">

           <form @submit.prevent="new_technical">
                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="name_T" class="text-sm font-semibold w-full">Nombre del tecnico:</label>
                    <input type="text" v-model="t_form.name_t" id="name_T" placeholder="Edgar Rangel" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                </div>

                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="key_T" class="text-sm font-semibold w-full">Clave de seguridad:</label>
                    <input type="text" id="key_T" v-model="t_form.key_t" placeholder="289832LJ19" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                    <p class="text-xs">La clave de seguridad permite el acceso a los formularios y el reconocimiento automático del técnico. Esta clave debe ser unica y no debe de repetirse.</p>
                </div>

                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="job_T" class="text-sm font-semibold w-full">Puesto que desarolla:</label>
                    <input type="text" id="job_T" v-model="t_form.job_t" placeholder="Tecnico Local" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                </div>

                <div class="flex w-full justify-end">
                    <button class="text-sm px-8 py-2 bg-neutral-900 rounded font-semibold text-white hover:bg-cyan-500 transition-all duration-200 focus:bg-cyan-500">Agregar</button>
                </div>
           </form>

        </div>

        <div class="flex items-center justify-between px-5 py-4 w-full self-end rounded-t">
            <p class="text-xs">Complete todos los campos del formulario.</p>
        </div>
    </div>

    <!-- Sector Form -->
    <div :class="sector_modal_class" >
        <div class="flex items-center justify-between px-5 py-4 w-full self-start rounded-t">
            <h4 class="text-xs underline underline-offset-4 decoration-2 decoration-cyan-400">Agregar un nuevo sector</h4>
            <svg @click="sector_modal = false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="cursor-pointer" viewBox="0 0 24 24" ><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
        </div>

        <div class="self-center px-5">

            <form @submit.prevent="new_sector">
                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="name_S" class="text-sm font-semibold w-full">Nombre del sector:</label>
                    <input type="text" v-model="s_form.name_s" id="name_S" placeholder="SALUD" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                </div>

                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="color_S" class="text-sm font-semibold w-full">Color de procedencia:</label>
                    <input type="color" id="color_S" v-model="s_form.color_s" placeholder="289832LJ19" class="w-full h-14 text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                    <p class="text-xs mt-2">El color se vera reflejado al momento de imprimir las etiquetas que contienen su respectivo qr.</p>
                </div>

                <div class="flex w-full justify-end">
                    <button class="text-sm px-8 py-2 bg-neutral-900 rounded font-semibold text-white hover:bg-cyan-500 transition-all duration-200 focus:bg-cyan-500">Agregar</button>
                </div>
            </form>
        </div>

        <div class="flex items-center justify-between px-5 py-4 w-full self-end rounded-t">
            <p class="text-xs">Complete todos los campos del formulario.</p>
        </div>
    </div>

    <!-- Device Form -->
    <div :class="device_modal_class" >
        <div class="flex items-center justify-between px-5 py-4 w-full self-start rounded-t">
            <h4 class="text-xs underline underline-offset-4 decoration-2 decoration-cyan-400">Agregar un nuevo equipo</h4>
            <svg @click="device_modal = false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="cursor-pointer" viewBox="0 0 24 24" ><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
        </div>

        <div class="self-center px-5">

           <form @submit.prevent="new_device">


                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="model_D" class="text-sm font-semibold w-full">Modelo del equipo:</label>
                    <input type="text" v-model="d_form.model_d" id="model_D" placeholder="BROTHER-1890" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                </div>
                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="model_D" class="text-sm font-semibold w-full">Tipo del equipo:</label>
                    <select  v-model="d_form.type_d" id="model_D" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                        <option value="MULTIFUNCIONAL" selected>MULTIFUNCIONAL</option>
                        <option value="ESCANER">ESCANER</option>
                        <option value="DUPLICADOR">DUPLICADOR</option>
                    </select>
                    <!-- <input type="text" v-model="d_form.model_d" id="model_D" placeholder="BROTHER-1890" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required> -->
                </div>

                <div v-show="d_form.type_d != 'MULTIFUNCIONAL'" class="flex flex-col gap-y-1 mb-5">
                    <label for="model_D" class="text-sm font-semibold w-full">Precio de producción:</label>
                    <input type="number" step="any" min="0" v-model="d_form.price_d" id="model_D" placeholder="0.40" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                </div>

                <div v-show="d_form.type_d == 'MULTIFUNCIONAL'" class="flex flex-col gap-y-1 mb-5">
                    <label for="model_D" class="text-sm font-semibold w-full">Precio de producción (Impresión/Copia):</label>
                    <input type="number" step="any" min="0" v-model="d_form.price_d_print" id="model_D" placeholder="0.40" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                </div>

                <div v-show="d_form.type_d == 'MULTIFUNCIONAL'" class="flex flex-col gap-y-1 mb-5">
                    <label for="model_D" class="text-sm font-semibold w-full">Precio de producción (Escaner):</label>
                    <input type="number" step="any" min="0" v-model="d_form.price_d_scan" id="model_D" placeholder="0.40" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                </div>



                <div class="flex w-full justify-end">
                    <button class="text-sm px-8 py-2 bg-neutral-900 rounded font-semibold text-white hover:bg-cyan-500 transition-all duration-200 focus:bg-cyan-500">Agregar</button>
                </div>
           </form>
        </div>

        <div class="flex items-center justify-between px-5 py-4 w-full self-end rounded-t">
            <p class="text-xs">Complete todos los campos del formulario.</p>
        </div>
    </div>

        <!-- Modal para editar técnico -->
        <div :class="editclass" :show="modal" @close="closeModal">
            <div class=" bg-zinc-200 px-8 py-4 rounded-md">
                <div class=" flex flex-wrap gap-5 items-center justify-between">

                    <div class=" w-full fill-neutral-900flex justify-end">
                        <svg @click="closeModal" class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" ><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
                    </div>

                    <div class="self-center px-5">
                        <form @submit.prevent="editTechnical">

                            <h1 class="font-bold mb-2">EDITAR TÉCNICO</h1>

                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="name_T" class="text-sm font-semibold w-full">Nombre del técnico:</label>
                                <input type="text" v-model="formEditTechnical.name" id="name_T" placeholder="Edgar Rangel" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                            </div>

                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="key_T" class="text-sm font-semibold w-full">Clave de seguridad:</label>
                                <input type="text" id="key_T" v-model="formEditTechnical.key" placeholder="289832LJ19" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                                <p class="text-xs">La clave de seguridad permite el acceso a los formularios y el reconocimiento automático del técnico. Esta clave debe ser unica y no debe de repetirse.</p>
                            </div>

                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="job_T" class="text-sm font-semibold w-full">Puesto que desarolla:</label>
                                <input type="text" id="job_T" v-model="formEditTechnical.job" placeholder="Tecnico Local" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                            </div>

                            <div class="flex w-full justify-end">
                                <button class="text-sm px-8 py-2 bg-neutral-900 rounded font-semibold text-white hover:bg-cyan-500 transition-all duration-200 focus:bg-cyan-500">ACTUALIZAR</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
        <!-- Fin de modal para editar técnico -->

        <!-- Modal para editar equipo -->
        <div :class="editclassTeam" :show="modalTeam" @close="closeModalTeam">
            <div class=" bg-zinc-200 px-8 py-4 rounded-md">
                <div class=" flex flex-wrap gap-5 items-center justify-between">

                    <div class=" w-full fill-neutral-900flex justify-end">
                        <svg @click="closeModalTeam" class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" ><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
                    </div>

                    <div class="self-center px-5">
                        <form @submit.prevent="editTeam">
                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="model_D" class="text-sm font-semibold w-full">Modelo del equipo:</label>
                                <input type="text" v-model="formEditTeam.model" id="model_D" placeholder="BROTHER-1890" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                            </div>
                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="model_D" class="text-sm font-semibold w-full">Tipo del equipo:</label>
                                <select  v-model="formEditTeam.type" id="model_D" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                                    <option value="MULTIFUNCIONAL" selected>MULTIFUNCIONAL</option>
                                    <option value="ESCANER">ESCANER</option>
                                    <option value="DUPLICADOR">DUPLICADOR</option>
                                </select>
                                <!-- <input type="text" v-model="d_form.model_d" id="model_D" placeholder="BROTHER-1890" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required> -->
                            </div>

                            <div v-show="formEditTeam.type != 'MULTIFUNCIONAL'" class="flex flex-col gap-y-1 mb-5">
                                <label for="model_D" class="text-sm font-semibold w-full">Precio de producción:</label>
                                <input type="number" step="any" min="0" v-model="formEditTeam.price" id="model_D" placeholder="0.40" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                            </div>

                            <div v-show="formEditTeam.type == 'MULTIFUNCIONAL'" class="flex flex-col gap-y-1 mb-5">
                                <label for="model_D" class="text-sm font-semibold w-full">Precio de producción (Impresión/Copia):</label>
                                <input type="number" step="any" min="0" v-model="formEditTeam.price_print" id="model_D" placeholder="0.40" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                            </div>

                            <div v-show="formEditTeam.type == 'MULTIFUNCIONAL'" class="flex flex-col gap-y-1 mb-5">
                                <label for="model_D" class="text-sm font-semibold w-full">Precio de producción (Escaner):</label>
                                <input type="number" step="any" min="0" v-model="formEditTeam.price_scan" id="model_D" placeholder="0.40" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                            </div>



                            <div class="flex w-full justify-end">
                                <button class="text-sm px-8 py-2 bg-neutral-900 rounded font-semibold text-white hover:bg-cyan-500 transition-all duration-200 focus:bg-cyan-500">ACTUALIZAR</button>
                            </div>
                    </form>
                    </div>


                </div>
            </div>
        </div>
        <!-- Fin de modal para editar equipo -->

        <!-- Modal para editar sector -->
        <div :class="editclassSector" :show="modalSector" @close="closeModalSector">
            <div class=" bg-zinc-200 px-8 py-4 rounded-md">
                <div class=" flex flex-wrap gap-5 items-center justify-between">

                    <div class=" w-full fill-neutral-900flex justify-end">
                        <svg @click="closeModalSector" class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" ><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
                    </div>

                    <div class="self-center px-5">
                        <form @submit.prevent="editSector">
                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="name_S" class="text-sm font-semibold w-full">Nombre del sector:</label>
                                <input type="text" v-model="formEditSector.name" id="name_S" placeholder="SALUD" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                            </div>

                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="color_S" class="text-sm font-semibold w-full">Color de procedencia:</label>
                                <input type="color" id="color_S" v-model="formEditSector.color" placeholder="289832LJ19" class="w-full h-14 text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                                <p class="text-xs mt-2">El color se vera reflejado al momento de imprimir las etiquetas que contienen su respectivo qr.</p>
                            </div>

                            <div class="flex w-full justify-end">
                                <button class="text-sm px-8 py-2 bg-neutral-900 rounded font-semibold text-white hover:bg-cyan-500 transition-all duration-200 focus:bg-cyan-500">ACTUALIZAR</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
        <!-- Fin de modal para editar sector -->

</AuthenticatedLayout>
</template>
