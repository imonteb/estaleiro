<div class="rounded-lg w-full h-full min-w-0 bg-slate-400 flex flex-col items-stretch p-8">
    <h2 class="text-2xl font-bold mb-4">Equipas diárias publicadas para {{ $date }}</h2>
    @if($teams->isEmpty())
    <div class="text-gray-700">No hay equipas diárias publicadas para este día.</div>
    @else
    <ul class="space-y-4">
       {{--  @foreach($teams as $team) --}}
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
        {{-- @endforeach --}}
    </ul>
    @endif
</div>
