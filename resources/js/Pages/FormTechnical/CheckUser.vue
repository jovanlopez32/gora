<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';


const props = defineProps({
    error: {
        type: String,
    },
    qr_code: {
        type: String,
    },
    sector: {
        type: String,
    }
});


const keyForm = useForm({
    key: '',
    qr_code: props.qr_code,
});

const submit = () => {
    keyForm.post( route('gora.technical.check.key') );
};

</script>

<template>
<Head title="Tecnico" />
<GuestLayout>

    <div v-show="props.sector != 'SALUD'" class="flex max-w-xs gap-5">
        <img src="/img/gora.png" class="object-contain w-10" alt="Logo Gora">
        <div>
            <h1 class="text-xl font-bold tracking-wider">GORA</h1>
            <h3 class="text-xs tracking-wider font-semibold">SOLUCIONES INTEGRALES</h3>
        </div>
    </div>

    <div  v-show="props.sector == 'SALUD'" class="flex max-w-xs gap-5">
        <img src="/img/pom.png" class="object-contain w-24" alt="Logo Gora">

    </div>

    <div class="max-w-xs mt-8 flex flex-col gap-2">
        <p class="text-sm font-semibold">Inserta tu clave para poder acceder al formulario de reporte.</p>
        <form @submit.prevent="submit" class="w-full">
            <input v-model="keyForm.key" type="text" placeholder="LILJ090MX" class="w-full text-sm rounded border-neutral-700 focus:border-cyan-500 focus:ring-cyan-500 placeholder:text-neutral-600 font-medium" autofocus>
            <button class="w-full p-2 bg-cyan-400 flex items-center justify-center rounded my-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path></svg>
            </button>
        </form>
        <small class="flex  justify-between">{{ new Intl.DateTimeFormat("es-ES", {day: "2-digit", month: "long", year: "numeric"}).format(new Date()) }} <span class="underline decoration-2 underline-offset-8 decoration-red-500">{{ props.error == true ? ' Credenciales Incorrectas.' : ' ' }}</span> </small>
    </div>

</GuestLayout>

</template>
