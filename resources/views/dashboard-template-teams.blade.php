<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestão de equipas modelo') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="w-full  mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 text-gray-900 dark:text-gray-100">
                    @livewire('template-teams-index')

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
