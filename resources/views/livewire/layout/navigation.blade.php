<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <nav x-data="{ open: false }" class="bg-gray-300 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('home') }}" wire:navigate>
                            <x-application-logo
                                class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-8 sm:-my-px sm:ms-10">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            <span class="inline-flex items-center gap-2">
                                <svg class="h-5 w-5 text-blue-700" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8h6V5z" />
                                </svg>
                                Painel
                            </span>
                        </x-nav-link>
                        @role('admin')
                        <div x-data="{ open: false }" class="relative flex items-center">
                            <button @click="open = !open" type="button"
                                class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                <svg class="h-5 w-5 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 00-3-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Equipas</span>
                                <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute left-0 top-full w-44 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 z-50"
                                style="display: none;" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">

                                    <li>
                                        <a href="{{ route('dashboard.daily-teams') }}"
                                            class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                            <svg class="h-4 w-4 text-yellow-700" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path
                                                    d="M17 21v-2a4 4 0 00-3-3.87V13a4 4 0 10-8 0v2.13A4 4 0 003 19v2" />
                                            </svg>
                                            Gestão de equipas
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('dashboard.publish-work-day') }}"
                                            class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                            <svg class="h-5 w-5 text-blue-700" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M9 12l2 2l4-4" />
                                            </svg>
                                            Publicar tarefas
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('dashboard.template-teams') }}"
                                            class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                            <svg class="h-4 w-4 text-green-700" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path
                                                    d="M7 7V3a1 1 0 011-1h8a1 1 0 011 1v18a1 1 0 01-1 1H8a1 1 0 01-1-1v-4" />
                                            </svg>
                                            Criar modelos
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        @endrole
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                    x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                Perfil
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    Cerrar sesión
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 py-2 px-3 text-white bg-blue-700 rounded w-full md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500"
                    aria-current="page">
                    <svg class="h-5 w-5 text-blue-700" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8h6V5z" />
                    </svg>
                    <span>Painel</span>
                </a>
                @role('admin')
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button"
                        class="inline-flex items-center gap-2 py-2 px-3 text-gray-900 rounded hover:bg-gray-100 w-full">
                        <svg class="h-5 w-5 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 00-3-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Equipas</span>
                        <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute left-0 mt-2 w-44 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 z-50"
                        style="display: none;" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                            <li>
                                <a href="{{ route('dashboard.template-teams') }}"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <svg class="h-4 w-4 text-green-700" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M7 7V3a1 1 0 011-1h8a1 1 0 011 1v18a1 1 0 01-1 1H8a1 1 0 01-1-1v-4" />
                                    </svg>
                                    Criar modelos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard.daily-teams') }}"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <svg class="h-4 w-4 text-yellow-700" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M17 21v-2a4 4 0 00-3-3.87V13a4 4 0 10-8 0v2.13A4 4 0 003 19v2" />
                                    </svg>
                                    Gestão de equipas
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                @endrole
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200"
                        x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile')" wire:navigate>
                        Perfil
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            Cerrar sesión
                        </x-responsive-nav-link>
                    </button>
                </div>
            </div>
        </div>
    </nav>

</div>
