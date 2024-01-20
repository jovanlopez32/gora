<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import html2canvas from 'html2canvas';
import { ref, computed, onMounted } from 'vue';
/* import QRCodeVue3 from "qrcode-vue3"; */
import VueQrcode from '@chenfengyuan/vue-qrcode';


const props = defineProps({
    devices: {
        type: Object,
    },
    sectors: {
        type: Object,
    },
    tags: {
        type: Object,
    }
});

//Formulario que añade una nueva etiqueta.
const tagform = useForm({
    sector: props.sectors[0] === undefined || null ? '' : props.sectors[0].id,
    dependence: '',
    device: props.devices[0] === undefined || null ? '' : props.devices[0].id,
    location: '',
    address: '',
});


const new_tag = () => {
    tagform.post(route('gora.add.tag'), {
        preserveState: true,
        preserveScroll: true,
        onFinish: (response) => {
            console.log(response.data);
            arrTags.value = props.sectors.map(sector => {
                return props.tags.filter(tag => tag.sector.name === sector.name);
            });
        },
        onSuccess: () => tagform.reset(),
    });
} ;

/* const exportImage = (qrid) => {

    console.log(qrid);
    html2canvas(document.getElementById(qrid), {
        scale: 10,
        ignoreElements: function(element) {
            // Ignore elements with id "n"
            return element.id === "data_ignore";
        }
    }).then(function(canvas) {
        var image = canvas.toDataURL("image/png");
        var downloadLink = document.createElement("a");
        downloadLink.href = image;
        downloadLink.download = qrid + ".png";
        downloadLink.click();
    });
}; */

const select_tag = ref(props.tags[0]);

const downloadTag = (tag) => {
    select_tag.value = tag;

    document.getElementById('tag').classList.remove('hidden');
    setTimeout(function(){
        html2canvas(document.getElementById('tag'), {
            scale:10,
            removeContainer: true,
        }).then(function(canvas) {
            var image = canvas.toDataURL("image/png");
            var downloadLink = document.createElement("a");
            downloadLink.href = image;
            downloadLink.download = tag.qr + tag.sector.name + ".png";
            downloadLink.click();
            document.getElementById('tag').classList.add('hidden');
        });
    }, 1000);

};

//Editar etiqueta
const modalTag = ref(false);
const idTag = ref('');

const editclassTag = computed(() => modalTag.value ? 'fixed w-full h-full bg-neutral-700 top-0 right-0 left-0 z-[100] flex items-center justify-center bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 z-20 transition-all duration-300 ease-in-out' : 'fixed w-full h-full bg-neutral-700 top-0 right-0 left-0 -z-20 bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-10 -z-20  transition-all duration-300 ease-in-out flex items-center justify-center')

const formEditTag = useForm({
    sector:'',
    dependence:'',
    location:'',
    device:'',
    address:'',
});

const openModalTag = (sector, dependence, location, device, address, tag) => {
    modalTag.value = true;
    idTag.value = tag;
    formEditTag.sector = sector;
    formEditTag.dependence = dependence;
    formEditTag.location = location;
    formEditTag.device = device;
    formEditTag.address = address;
}

const closeModalTag = () =>{
    modalTag.value = false;
    formEditTag.reset();
}

const editTag = () =>{
    formEditTag.put(route('gora.edit.tag', idTag.value), {
        onSuccess: () => {okTag('Técnico actualizado')}
    });
}

const okTag = (msj) =>{
    formEditTag.reset();
    closeModalTag();
};
//Fin de editar etiqueta


</script>

<template>

<Head title="Administrador de etiquetas" />
<AuthenticatedLayout>



    <div class="fixed right-0 top-0" v-if="props.tags[0] != null">
        <div id="tag" class="w-[800px]  border-8 px-4 py-8 bg-zinc-50 relative hidden"  :style="'border-color: ' + select_tag.sector.color">
            <!-- qr code -->
            <div class="flex items-center gap-5">
                <vue-qrcode :value="select_tag.url" :options="{ width: 260, margin: 2 }"></vue-qrcode>

                <div>

                    <h1 class="font-extrabold text-2xl"> {{ select_tag.device.model }} </h1>
                    <h3 class="font-semibold">{{ select_tag.dependence }}</h3>

                    <div class="flex items-center gap-4 border-b-4 mt-2 border-neutral-300 pb-2">
                        <p class="text-xs font-bold inline-block"> {{ select_tag.location }}</p>
                        <p class="text-xs font-bold inline-block"> {{ select_tag.address }}</p>
                    </div>

                    <div class="flex items-center mt-2">

                        <div>
                            <h2 class="font-extrabold text-xl">ATENCIÓN A CLIENTES:</h2>
                            <h2 class="font-extrabold text-4xl ">(+52) 444 641 5763</h2>
                            <h2 class="font-extrabold text-3xl text-orange-600">info@pomslp.com</h2>
                        </div>
                    </div>

                    <div class="absolute bottom-2 left-10" v-if="select_tag.sector.id == 1">
                        <p class="text-[10px] font-bold">* ESTE EQUIPO ES PROPIEDAD DE: POTENCIALIZADOR DE OPORTUNIDADES MEXICANAS S.A. DE C.V.</p>
                    </div>
                    <div class="absolute bottom-2 left-10" v-if="select_tag.sector.id == 2">
                        <p class="text-[10px] font-bold">* ESTE EQUIPO ES PROPIEDAD DE: POTENCIALIZADOR DE OPORTUNIDADES MEXICANAS S.A. DE C.V.</p>
                    </div>

                    <div if class="absolute bottom-0 right-4" v-if="select_tag.sector.id == 1">
                        <img src="/img/pom.png" class="object-contain w-20" alt="">
                    </div>
                    <div if class="absolute bottom-0 right-4" v-if="select_tag.sector.id == 2">
                        <img src="/img/pom.png" class="object-contain w-20 m-4" alt="">
                    </div>
                    <h3 class=" absolute text-5xl right-5 top-5 font-extrabold text-orange-600"># {{ select_tag.sector.id == 1 ? select_tag.qr : select_tag.qr + '-G'}}</h3>
                </div>
            </div>
        </div>
    </div>


    <div>
        <h1 class=" font-bold text-xl">Administrador de etiquetas</h1>
        <p class="font-medium text-sm my-1 max-w-2xl">Agrega etiquetas conforme a tus modelos y sectores. Cada etiqueta contendra un codigo qr que se podra leer para la captura de los reportes.</p>
    </div>

    <div class="max-w-md my-10 flex items-center">
        <form @submit.prevent="new_tag">

            <div class="flex gap-5">
                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="sector" class="text-sm font-semibold w-full">Selecciona un sector:</label>
                    <select :disabled="props.sectors[0] === undefined" id="sector" v-model="tagform.sector" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                        <option v-for="sector in props.sectors" :value="sector.id">{{ sector.name }}</option>
                    </select>
                </div>

                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="dependence" class="text-sm font-semibold w-full">Dependencia ó Area</label>
                    <input v-model="tagform.dependence" placeholder="RECURSOS HUMANOS" type="text" id="dependence" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                </div>
            </div>

            <div class="flex flex-col gap-y-1 mb-5">
                <label for="device" class="text-sm font-semibold w-full">Selecciona un dispositivo:</label>
                <select :disabled="props.devices[0] === undefined" v-model="tagform.device" id="device" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                    <option v-for="device in props.devices" :value="device.id">{{ device.model }}</option>
                </select>
            </div>

            <div class="flex gap-5">
                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="location" class="text-sm font-semibold w-full">Ubicación</label>
                    <input v-model="tagform.location" placeholder="HOSPITAL CENTRAL" type="text" id="location" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                </div>

                <div class="flex flex-col gap-y-1 mb-5">
                    <label for="address" class="text-sm font-semibold w-full">Dirección</label>
                    <input v-model="tagform.address" placeholder="AV. VENUSTIANO CARRANZA S/N" type="text" id="address" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                </div>
            </div>

            <div class="flex w-full justify-start">
                <button class="text-sm px-8 py-2 bg-neutral-900 rounded font-semibold text-white hover:bg-cyan-500 transition-all duration-200 focus:bg-cyan-500">Agregar</button>
            </div>

        </form>
    </div>

    <div v-if="props.tags.length == 0">
        <p>No hay ninguna etiqueta creada aun.</p>
        <p class="text-xs underline" v-if="props.sectors[0] === undefined || props.devices[0] === undefined">Ve a Configuración de datos y alimenta los datos que se requieren para poder crear etiquetas qr.</p>
    </div>
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class=" divide-y divide-gray-400 table-fixed w-full">
                        <thead>
                            <tr>
                                <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase">#qr</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modelo</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dependencia / Area</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dirección</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sector</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Url</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Etiqueta</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 ">
                            <tr v-for="tag in props.tags">
                                <td class="px-2 py-4 break-words text-sm font-medium text-gray-800 ">{{ tag.qr }}</td>
                                <td class="px-6 py-4 break-words text-sm text-gray-800 ">{{ tag.device.model }}</td>
                                <td class="px-6 py-4 break-words text-sm text-gray-800 ">{{ tag.dependence }}</td>
                                <td class="px-6 py-4 break-words text-sm text-gray-800 ">{{ tag.location }}</td>
                                <td class="px-6 py-4 break-words text-sm text-gray-800 ">{{ tag.address }}</td>

                                <td class="px-6 py-4 break-words text-sm text-gray-800">
                                    <div class=" py-2 px-4 rounded font-bold text-neutral-900 text-center" :style="'background-color: ' + tag.sector.color">
                                        {{ tag.sector.name }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 break-words text-sm text-gray-800 ">{{ tag.url }}</td>
                                <td class="px-6 py-4 break-words text-right text-sm font-medium">
                                    <button class="text-blue-500 hover:text-blue-700 select-none" @click="downloadTag(tag)">Descargar</button>
                                    <button class="text-blue-500 hover:text-blue-700 select-none mx-1" @click="openModalTag(tag.sector.id, tag.dependence, tag.location, tag.device.id, tag.address, tag.id)"> / Editar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar equipo -->
    <div :class="editclassTag" :show="modalTag" @close="closeModalTag">
        <div class=" bg-zinc-200 px-8 py-4 rounded-md">
            <div class=" flex flex-wrap gap-5 items-center justify-between">

                <div class=" w-full fill-neutral-900flex justify-end">
                    <svg @click="closeModalTag" class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" ><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
                </div>

                <div class="self-center px-5">
                    <form @submit.prevent="editTag">

                        <div class="flex gap-5">
                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="sector" class="text-sm font-semibold w-full">Selecciona un sector:</label>
                                <select v-model="formEditTag.sector" id="sector" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                                    <option v-for="sector in props.sectors" :value="sector.id">{{ sector.name }}</option>
                                </select>
                            </div>

                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="dependence" class="text-sm font-semibold w-full">Dependencia ó Area</label>
                                <input v-model="formEditTag.dependence" placeholder="RECURSOS HUMANOS" type="text" id="dependence" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                            </div>
                        </div>

                        <div class="flex flex-col gap-y-1 mb-5">
                            <label for="device" class="text-sm font-semibold w-full">Selecciona un dispositivo:</label>
                            <select :disabled="props.devices[0] === undefined" v-model="formEditTag.device" id="device" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium">
                                <option v-for="device in props.devices" :value="device.id">{{ device.model }}</option>
                            </select>
                        </div>

                        <div class="flex gap-5">
                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="location" class="text-sm font-semibold w-full">Ubicación</label>
                                <input v-model="formEditTag.location" placeholder="HOSPITAL CENTRAL" type="text" id="location" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                            </div>

                            <div class="flex flex-col gap-y-1 mb-5">
                                <label for="address" class="text-sm font-semibold w-full">Dirección</label>
                                <input v-model="formEditTag.address" placeholder="AV. VENUSTIANO CARRANZA S/N" type="text" id="address" class="w-full text-xs px-3 py-2 rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" required>
                            </div>
                        </div>

                        <div class="flex w-full justify-start">
                            <button class="text-sm px-8 py-2 bg-neutral-900 rounded font-semibold text-white hover:bg-cyan-500 transition-all duration-200 focus:bg-cyan-500">ACTUALIZAR</button>
                        </div>

                    </form>
                </div>


            </div>
        </div>
    </div>
    <!-- Fin de modal para editar equipo -->

</AuthenticatedLayout>

</template>
