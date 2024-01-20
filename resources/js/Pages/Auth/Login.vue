<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Inicio de sesion" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-20 gap-y-5">

            <div class="flex flex-col items-center justify-center max-w-xs">
                <img src="/img/gora.png" class="object-contain w-16 mb-2" alt="Logo Gora">
                <h1 class="text-3xl font-bold tracking-wider">GORA</h1>
                <h3 class="text-sm tracking-wider font-semibold">SOLUCIONES INTEGRALES</h3>
                <small class="my-2 text-neutral-400 tracking-widest font-semibold">plataforma</small>
            </div>

            <form @submit.prevent="submit" class=" relative max-w-xs w-full p-4">
                <!-- <div class="absolute w-fit truncate -bottom-10">
                    <InputError class="mt-2 text-xs " :message="form.errors.email" />
                    <InputError class="mt-2 w-fit text-xs" :message="form.errors.password" />
                </div> -->


                <div>
                    <InputLabel for="email" value="Correo Electronico" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="ventas@goramexico.com"
                    />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Contraseña" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center cursor-pointer">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ml-2 text-xs text-neutral-900 font-semibold">Recordar sesión.</span>
                    </label>
                </div>

                <div class="flex items-center justify-start mt-4">
                    <PrimaryButton class=" w-fit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        <span>Acceder a la plataforma</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" class="fill-white ml-4"><path d="M10.061 19.061 17.121 12l-7.06-7.061-2.122 2.122L12.879 12l-4.94 4.939z"></path></svg>
                    </PrimaryButton>
                </div>


            </form>
        </div>
    </GuestLayout>
</template>
