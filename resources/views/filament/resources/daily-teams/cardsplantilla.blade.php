@php
use App\Helpers\EmployeeAssignmentHelper;
use App\Helpers\VehicleAssignmentHelper;
@endphp
@vite(['resources/css/app.css', 'resources/js/app.js'])


<div class="min-h-96 w-full m-0 justify-between rounded-lg border border-blue-900 shadow-xl
    hover:shadow-2xl shadow-blue-900 p-1 transition bg-blue-300 relative">

    <!-- Cabecera azul -->
    <div class="grid grid-cols-4 bg-blue-900 rounded-t-md text-white p-1">
        <div class="col-span-2 text-left border-b">
            <h3 class="text-lg font-bold text-yellow-600">
                {{ $getRecord()->teamname->name ?? '—' }}
            </h3>
        </div>
        <div class="col-span-2 text-center border-b">
            <strong class="text-xs">PEP:</strong>
            <span class="text-xs truncate">
                {{ optional($getRecord()->pep)->code ?? '-' }}
            </span>
        </div>
        <div class="col-span-2 px-2 mt-2">
            <strong class="text-xs">Tipo:</strong>
            <span class="text-xs truncate">{{ $getRecord()->work_type ?? '-' }}</span>
        </div>
        <div class="col-span-2 px-1 mt-2">
            {{-- <strong class="text-xs">Localização:</strong> --}}
            <span class="text-xs truncate">{{ $getRecord()->location ?? '-' }}</span>
        </div>
    </div>

    <!-- Datos principales -->
    <div class="grid grid-cols-5 gap-1 mt-2 text-white text-xs bg-blue-700 p-1 rounded-md ">
        <div class="col-span-5 bg-blue-500 rounded-md p-2 text-left  ">
            <strong class="text-xs truncate">Líder:</strong>
            <span>{{ optional($getRecord()->leader)->full_name ?? '–' }}</span>
        </div>
        <div class="col-span-3 bg-blue-500 rounded-md p-1 text-left  ">
            <strong>Colaboradores:</strong>
            @forelse($getRecord()->dailyTeamMembers as $member)
            <p class="truncate">{!! EmployeeAssignmentHelper::getLabelParaSelect($member->employee_id,
                $getRecord()->work_date, $getRecord()->id, $member->id) !!}</p>
            @empty
            <p class="text-xs text-gray-400">Sem colaboradores</p>
            @endforelse
        </div>
        <div class="col-span-2 bg-blue-500 rounded-md p-1 text-left ">
            <strong class="text-xs">Viaturas:</strong>
            @forelse($getRecord()->dailyTeamVehicles as $vehicle)
            <p class="truncate">{!! VehicleAssignmentHelper::getLabelParaSelect($vehicle->vehicle_id ??
                $vehicle->id,
                $getRecord()->work_date, $getRecord()->id, $vehicle->id) !!}</p>
            @empty
            <p class="text-xs text-gray-400">Sem viaturas</p>
            @endforelse
        </div>
    </div>

    <!-- Subequipas -->
    @forelse($getRecord()->subTeams as $sub)
    <div class="grid grid-cols-5 gap-2 mt-2 text-white text-xs bg-blue-700 p-1 rounded-md">
        <div class="col-span-5 bg-blue-500 rounded-md p-1 text-left ">
            <p class="text-xs truncate">
                <strong>Subequipa: {{ $sub->subTeamName->name ?? '' }}</strong><br>
                <span class="ml-1"><strong>Líder:</strong> {{ $sub->leader->full_name ?? '–' }}</span>
            </p>
        </div>
        <div class="col-span-3 bg-blue-500 rounded-md p-1 text-left ">
            <strong class="text-xs">Colaboradores:</strong>
            @forelse($sub->members as $member)
            <p class="truncate">{!! EmployeeAssignmentHelper::getLabelParaSelect($member->employee_id,
                $getRecord()->work_date, $getRecord()->id, $member->id) !!}</p>
            @empty
            <p class="text-xs text-gray-400">Sem membros</p>
            @endforelse
        </div>
        <div class="col-span-2 bg-blue-500 rounded-md p-1 text-left ">
            <strong class="text-xs">Viaturas:</strong>
            @forelse($sub->vehicles as $vehicle)
            <p class="truncate">{!! VehicleAssignmentHelper::getLabelParaSelect($vehicle->vehicle_id ??
                $vehicle->id,
                $getRecord()->work_date, $getRecord()->id, $vehicle->id) !!}</p>
            @empty
            <p class="text-gray-400">Sem viaturas</p>
            @endforelse
        </div>
    </div>
    @empty
    <div class="col-span-full text-xs text-gray-400">Sem subequipas</div>
    @endforelse
</div>

<!-- Fecha del día de trabalho siempre al final -->
<div class="w-full text-center  pb-2">
    <span class="text-xs text-gray-400 bg-gray-100 rounded px-2 py-1" style="font-size: 0.85em;">
        Data do Trabalho: {{ \Carbon\Carbon::parse($getRecord()->work_date)->format('d/m/Y') }}
    </span>
</div>
