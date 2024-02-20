<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
                    <div class="flex justify-between h-12">
                        <div class="flex">
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-5 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Panel Administrador
                                </NavLink>
                            </div>

                            <!-- <div class="hidden space-x-8 sm:-my-px sm:ml-5 sm:flex">
                                <NavLink :href="route('gora.reports.counters')" :active="route().current('gora.reports.counters')">
                                    Reporte Contadores
                                </NavLink>
                            </div> -->

                            <div class="hidden space-x-8 sm:-my-px sm:ml-5 sm:flex">
                                <NavLink :href="route('gora.reports.countersaltern')" :active="route().current('gora.reports.counters')">
                                    Reporte Contadores
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ml-5 sm:flex">
                                <NavLink :href="route('gora.reports.problems')" :active="route().current('gora.reports.problems')">
                                    Reporte Tecnico
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-5 sm:flex">
                                <NavLink :href="route('gora.tags')" :active="route().current('gora.tags')">
                                    Administrador de etiquetas
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-5 sm:flex">
                                <NavLink :href="route('gora.settings')" :active="route().current('gora.settings')">
                                    Configuración de datos
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink> -->
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Cerrar Sesión
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Panel principal
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('gora.reports.counters')" :active="route().current('gora.reports.counters')">
                            Reporte Contadores
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('gora.reports.problems')" :active="route().current('gora.reports.problems')">
                            Reporte Tecnico
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('gora.tags')" :active="route().current('gora.tags')">
                            Administrador de etiquetas
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('gora.settings')" :active="route().current('gora.settings')">
                            Configuración de datos
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <!-- <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink> -->
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Cerrar Sesión
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <!-- <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header> -->

            <!-- Page Content -->
            <main>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8 px-5">
                    <div>
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
