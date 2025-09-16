<div>
    @if (session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-400">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-400">
        {{ session('error') }}
    </div>
    @endif
    <div class="lg:flex lg:items-center mb-6 lg:justify-between">
        <div class="flex items-center gap-4 mb-2 lg:mb-0">
            <label class="block text-base font-semibold text-blue-600 mr-2">Seleciona o dia</label>
            <input type="date" wire:model.lazy="sourceDate"
                class="block w-48 px-3 py-2 border border-blue-300 rounded-lg text-base shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-100 text-blue-900 transition" />
        </div>

        <div class="flex items-center gap-2">
            <button type="button" wire:click="openImportModal" class="text-xs bg-green-600 bg-blue-600 text-white px-4 py-2 rounded">
                Importar modelo
            </button>
            <button type="button" wire:click="importLastWorkDay"
                class="text-xs bg-blue-600 text-white px-4 py-2 rounded">
                Importar último dia trabalhado
            </button>
            <button
                class="text-xs bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded shadow transition"
                wire:click="createCard">
                Criar equipa diária
            </button>
        </div>



    </div>
    <div class="rounded-lg w-full h-full min-w-0 bg-slate-400 flex flex-col items-stretch p-2 pb-10">

        <!-- Slideover/modal para crear nuevo equipo diario -->
        @if($showSlideover)
        <div class="fixed inset-0 z-50 flex justify-end bg-black bg-opacity-40" style="cursor:pointer"
            wire:click.self="closeSlideover">
            <div class="w-full max-w-md bg-white dark:bg-gray-800 h-full shadow-xl p-0 flex flex-col">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold">Novo equipa diária</h3>
                    <button wire:click="closeSlideover"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-2xl">&times;</button>
                </div>
                <div class="flex-1 overflow-y-auto">
                    @php
                    $selectedTeam = $teams->firstWhere('id', $teamId);
                    $params = ['editingTeamId' => $teamId];
                    if ($selectedTeam) {
                    $params['date'] = $selectedTeam->work_date;
                    }
                    @endphp
                    @livewire('daily-teams.daily-team-form', $params)
                </div>
            </div>
        </div>
        @endif
        @if($showImportModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-full max-w-md">
                <h2 class="text-lg font-bold mb-4">Importar equipas modelo</h2>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data de importação</label>
                <input type="date" wire:model.defer="importDate" class="w-full rounded border-gray-300 mb-4" />
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" wire:click="closeImportModal"
                        class="px-3 py-1 bg-gray-400 text-white rounded">Cancelar</button>
                    <button type="button" wire:click="confirmImportTemplate"
                        class="px-3 py-1 bg-green-600 text-white rounded">Importar</button>
                </div>
            </div>
        </div>
        @endif
        @if($showImportLastWorkDayModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-full max-w-md">
                <h2 class="text-lg font-bold mb-4">Importar equipas do último dia trabalhado</h2>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data de origem</label>
                <input type="date" wire:model.defer="importSourceDate" class="w-full rounded border-gray-300 mb-4" />
                <label class="block text-sm font-medium text-gray-700 mb-2">Data de destino</label>
                <input type="date" wire:model.defer="importDestDate" class="w-full rounded border-gray-300 mb-4" />
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" wire:click="closeImportLastWorkDayModal"
                        class="px-3 py-1 bg-gray-400 text-white rounded">Cancelar</button>
                    <button type="button" wire:click="confirmImportLastWorkDay"
                        class="px-3 py-1 bg-blue-600 text-white rounded">Importar</button>
                </div>
            </div>
        </div>
        @endif
        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
            @forelse($teams as $team)
            <div
                class="mt-4 ml-2 me-2 justify-between rounded-lg border border-blue-900 shadow-xl hover:shadow-2xl shadow-blue-900 min-h-60 p-4 transition h-full bg-white relative">
                <!-- Botón editar -->
                <button
                    class="absolute top-2 right-2 bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs shadow"
                    wire:click="openSlideover({{ $team->id }})">Editar</button>
                <!-- Cabecera azul -->
                <div class="grid grid-cols-4 bg-blue-900 rounded-t-md text-white p-2">
                    <div class="col-span-2 text-left border-b">
                        <h3 class="text-lg font-bold text-yellow-400">
                            {{ $team->teamname->name ?? '-' }}
                        </h3>
                    </div>
                    <div class="col-span-2 text-center border-b">
                        <strong class="text-xs">PEP:</strong>
                        <span class="text-xs truncate">
                            {{ optional($team->pep)->code ?? '-' }}
                        </span>
                    </div>
                    <div class="col-span-2 px-2 mt-2">
                        <strong class="text-xs">Tipo:</strong>
                        <span class="text-xs truncate">{{ $team->work_type ?? '-' }}</span>
                    </div>
                    <div class="col-span-2 px-2 mt-2">
                        <span class="text-xs truncate">{{ $team->location ?? '-' }}</span>
                    </div>
                </div>
                <!-- Datos principales -->
                <div class="grid grid-cols-5 gap-2 mt-2 text-white text-xs bg-blue-700 p-2 rounded-md">
                    <div class="col-span-5 bg-blue-500 rounded-md p-2">
                        <strong class="text-xs truncate">Líder:</strong>
                        <span>{{ $team->leader->full_name ?? '–' }}</span>
                    </div>
                    <div class="col-span-3 bg-blue-500 rounded-md p-2">
                        <strong>Colaboradores:</strong>
                        @forelse($team->dailyTeamMembers as $emp)
                        <p class="truncate">{{ $emp->employee->full_name ?? '–' }}</p>
                        @empty
                        <p class="text-xs text-gray-400">Sem colaboradores</p>
                        @endforelse
                    </div>
                    <div class="col-span-2 bg-blue-500 rounded-md p-2">
                        <strong class="text-xs">Viaturas:</strong>
                        @forelse($team->dailyTeamVehicles as $veh)
                        <p class="truncate">{{
                            \App\Helpers\VehicleAssignmentHelper::getLabelParaSelect($veh->vehicle_id, $team->work_date,
                            $team->id, null, false) }}</p>
                        @empty
                        <p class="text-xs text-gray-400">Sem viaturas</p>
                        @endforelse
                    </div>
                </div>
                <!-- Subgrupos -->
                @forelse($team->subTeams as $sub)
                <div class="grid grid-cols-5 gap-2 mt-2 text-white text-xs bg-blue-700 p-2 rounded-md">
                    <div class="col-span-5 bg-blue-500 rounded-md p-2">
                        <p class="text-xs truncate">
                            <strong>Subequipa: {{ $sub->subTeamName->name ?? '' }}</strong><br>
                            <span class="ml-2"><strong>Líder:</strong> {{ $sub->leader->full_name ?? '–' }}</span>
                        </p>
                    </div>
                    <div class="col-span-3 bg-blue-500 rounded-md p-2">
                        <strong class="text-xs">Colaboradores:</strong>
                        @forelse($sub->members as $member)
                        <p class="truncate">{{ $member->employee->full_name ?? '–' }}</p>
                        @empty
                        <p class="text-xs text-gray-400">Sem membros</p>
                        @endforelse
                    </div>
                    <div class="col-span-2 bg-blue-500 rounded-md p-2">
                        <strong class="text-xs">Viaturas:</strong>
                        @forelse($sub->vehicles as $veh)
                        <p class="truncate">{{
                            \App\Helpers\VehicleAssignmentHelper::getLabelParaSelect($veh->vehicle_id, $team->work_date,
                            $team->id, null, false) }}</p>
                        @empty
                        <p class="text-gray-400">Sem viaturas</p>
                        @endforelse
                    </div>
                </div>
                @empty
                <div class="col-span-full text-xs text-gray-400">Sem subequipas</div>
                @endforelse
            </div>
            @empty
            <div class="col-span-5 text-center text-gray-700 font-semibold">No hay equipos diarios para esta data.</div>
            @endforelse
        </div>
    </div>
</div>
