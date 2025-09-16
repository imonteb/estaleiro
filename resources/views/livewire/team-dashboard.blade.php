<div>

    @if(session()->has('message'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-2">
        {{ session('message') }}
    </div>
    @endif

    <div class="flex items-center gap-2 mb-4">
        <input type="date" wire:model="selectedDate" class="border rounded px-2 py-1" />
        <button wire:click="loadTeams" class="bg-blue-500 text-white px-3 py-1 rounded">Buscar</button>
    </div>

    @php
    $isAdmin = auth()->user()?->hasRole('admin');
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-1 border border-red-700">
        @foreach($teams as $team)
        <div
            class="max-w-sm bg-yellow-500 border border-blue-600 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="">
                <div class="grid grid-flow-col grid-rows-3 gap-2  bg-blue-600">
                    <div class="row-span-3 border border-white">
                        <h5 class="m-2 text-2xl font-bold tracking-tight text-yellow-500 dark:text-yellow-500
                            }}
                        </h5>
                        <div>
                            <p class=" mb-1 font-semibold text-yellow-500 dark:text-yellow-500">Tipo: <span
                                class="font-normal">{{ $team->type_of_work }}</span></p>
                    </div>
                </div>
                <div class="col-span-2 row-span-3">
                    <div class="border border-white">
                        <p class="mb-1  text-sm font-semibold text-yellow-500 dark:text-yellow-500">PEP: <span
                                class="font-normal">{{
                                $team->pep->code ?? '-' }}</span></p>
                    </div>

                    <div class="border border-white">
                        <p class="mb-1 text-sm font-semibold text-yellow-500 dark:text-yellow-500">Dirección: <span
                                class="font-normal">{{
                                $team->address_of_work }}</span></p>
                    </div>
                    <div class="border border-white">
                        <p class="mb-1 font-semibold text-yellow-500 dark:text-yellow-500">Líder: <span
                                class="font-normal">{{
                                $team->teamleader->full_name ?? '-' }}</span></p>
                    </div>

                </div>

            </div>



            <p class="mb-1 font-semibold text-gray-700 dark:text-gray-300">Empleados: <span class="font-normal">{{
                    $team->employees->pluck('full_name')->join(', ') }}</span></p>
            <p class="mb-3 font-semibold text-gray-700 dark:text-gray-300">Vehículos: <span class="font-normal">{{
                    $team->vehicles->pluck('name')->join(', ') }}</span></p>

            @if($isAdmin)
            <a href="{{ route('filament.resources.teams.edit', $team) }}"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Editar
            </a>
            @endif
        </div>
    </div>
    @endforeach


    @if($isAdmin)
    <div class="flex gap-2 mt-6">
        <a href="{{ route('filament.resources.teams.create') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Crear nuevo equipo
        </a>
        <button type="button" wire:click="$set('showTemplateModal', true)"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
            Crear desde plantilla
        </button>
    </div>
    @endif
    @if($showTemplateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-bold mb-4">Crear equipos desde plantilla</h2>
            <div class="mb-4">
                <label class="block mb-1">Selecciona la fecha de la plantilla:</label>
                <select wire:model="templateDate" class="border rounded px-2 py-1 w-full">
                    <option value="">-- Selecciona una fecha --</option>
                    @foreach($availableTemplateDates as $date)
                    <option value="{{ $date }}">{{ $date }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Nueva fecha para los equipos:</label>
                <input type="date" wire:model="newDateForTemplate" class="border rounded px-2 py-1 w-full" />
            </div>
            <div class="flex gap-2">
                <button wire:click="createFromTemplate"
                    class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50" @if(!$templateDate ||
                    !$newDateForTemplate) disabled @endif>Crear</button>
                <button wire:click="$set('showTemplateModal', false)"
                    class="bg-gray-400 text-white px-4 py-2 rounded">Cancelar</button>
            </div>
        </div>
    </div>
    @endif
</div>
