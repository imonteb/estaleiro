
<form wire:submit.prevent="save" class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Nombre del equipo</label>
            <select wire:model="team_name_id" class="w-full rounded border-gray-300">
                <option value="">Selecciona equipo</option>
                @foreach($teamNames as $id => $name)
                    <option value="{{ is_object($name) ? $name->id : $id }}">{{ is_object($name) ? $name->name : $name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">PEP</label>
            <select wire:model="pep_id" class="w-full rounded border-gray-300">
                <option value="">Selecciona PEP</option>
                @foreach($peps as $id => $pep)
                    <option value="{{ is_object($pep) ? $pep->id : $id }}">{{ is_object($pep) ? $pep->code : $pep }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Líder</label>
            <select wire:model="leader_id" class="w-full rounded border-gray-300">
                <option value="">Selecciona Líder</option>
                @foreach($employees as $id => $emp)
                    <option value="{{ is_object($emp) ? $emp->id : $id }}">{{ is_object($emp) ? $emp->name : $emp }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Tipo de trabajo</label>
            <input type="text" wire:model="work_type" class="w-full rounded border-gray-300" />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ubicación</label>
            <input type="text" wire:model="location" class="w-full rounded border-gray-300" />
        </div>
    </div>


    {{-- Membros --}}
    <div class="mt-4">
        <h4 class="font-bold mb-2">Membros</h4>
        @foreach($dailyTeamMembers as $i => $member)
            <div class="flex items-center gap-2 mb-1">
                <select wire:model="dailyTeamMembers.{{ $i }}.employee_id" class="rounded border-gray-300">
                    <option value="">Selecciona empleado</option>
                    @foreach($employees as $id => $emp)
                        <option value="{{ is_object($emp) ? $emp->id : $id }}">{{ is_object($emp) ? $emp->name : $emp }}</option>
                    @endforeach
                </select>
                <button type="button" class="px-2 py-1 bg-red-500 text-white rounded" wire:click.prevent="$unset('dailyTeamMembers.{$i}')">Eliminar</button>
            </div>
        @endforeach
        <button type="button" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded" wire:click.prevent="$push('dailyTeamMembers', ['employee_id'=>null])">Agregar miembro</button>
    </div>


    {{-- Vehículos --}}
    <div class="mt-4">
        <h4 class="font-bold mb-2">Vehículos</h4>
        @foreach($dailyTeamVehicles as $i => $vehicle)
            <div class="flex items-center gap-2 mb-1">
                <select wire:model="dailyTeamVehicles.{{ $i }}.vehicle_id" class="rounded border-gray-300">
                    <option value="">Selecciona vehículo</option>
                    @foreach($vehicles as $id => $veh)
                        <option value="{{ is_object($veh) ? $veh->id : $id }}">{{ is_object($veh) ? $veh->label : $veh }}</option>
                    @endforeach
                </select>
                <button type="button" class="px-2 py-1 bg-red-500 text-white rounded" wire:click.prevent="$unset('dailyTeamVehicles.{$i}')">Eliminar</button>
            </div>
        @endforeach
        <button type="button" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded" wire:click.prevent="$push('dailyTeamVehicles', ['vehicle_id'=>null])">Agregar vehículo</button>
    </div>


    {{-- Subgrupos --}}
    <div class="mt-4">
        <h4 class="font-bold mb-2">Subgrupos</h4>
        @foreach($subTeams as $i => $sub)
            <div class="border rounded p-2 mb-2">
                <label class="block text-sm font-medium mb-1">Nombre del Subgrupo</label>
                <select wire:model="subTeams.{{ $i }}.sub_team_name_id" class="w-full rounded border-gray-300 mb-2">
                    <option value="">Selecciona subgrupo</option>
                    @foreach($subTeamNames as $id => $stn)
                        <option value="{{ is_object($stn) ? $stn->id : $id }}">{{ is_object($stn) ? $stn->name : $stn }}</option>
                    @endforeach
                </select>
                <label class="block text-sm font-medium mb-1">Líder</label>
                <select wire:model="subTeams.{{ $i }}.leader_id" class="w-full rounded border-gray-300 mb-2">
                    <option value="">Selecciona Líder</option>
                    @foreach($employees as $id => $emp)
                        <option value="{{ is_object($emp) ? $emp->id : $id }}">{{ is_object($emp) ? $emp->name : $emp }}</option>
                    @endforeach
                </select>

                {{-- Membros --}}
                @foreach($sub['members'] as $j => $member)
                    <div class="flex items-center gap-2 mb-1">
                        <select wire:model="subTeams.{{ $i }}.members.{{ $j }}.employee_id" class="rounded border-gray-300">
                            <option value="">Selecciona empleado</option>
                            @foreach($employees as $id => $emp)
                                <option value="{{ is_object($emp) ? $emp->id : $id }}">{{ is_object($emp) ? $emp->name : $emp }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="px-2 py-1 bg-red-500 text-white rounded" wire:click.prevent="$unset('subTeams.{$i}.members.{$j}')">Eliminar</button>
                    </div>
                @endforeach
                <button type="button" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded" wire:click.prevent="$push('subTeams.{$i}.members', ['employee_id'=>null])">Agregar miembro</button>

                {{-- Vehículos --}}
                @foreach($sub['vehicles'] as $j => $vehicle)
                    <div class="flex items-center gap-2 mb-1">
                        <select wire:model="subTeams.{{ $i }}.vehicles.{{ $j }}.vehicle_id" class="rounded border-gray-300">
                            <option value="">Selecciona vehículo</option>
                            @foreach($vehicles as $id => $veh)
                                <option value="{{ is_object($veh) ? $veh->id : $id }}">{{ is_object($veh) ? $veh->label : $veh }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="px-2 py-1 bg-red-500 text-white rounded" wire:click.prevent="$unset('subTeams.{$i}.vehicles.{$j}')">Eliminar</button>
                    </div>
                @endforeach
                <button type="button" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded" wire:click.prevent="$push('subTeams.{$i}.vehicles', ['vehicle_id'=>null])">Agregar vehículo</button>

                <button type="button" class="mt-2 px-3 py-1 bg-red-600 text-white rounded" wire:click.prevent="$unset('subTeams.{$i}')">Eliminar subgrupo</button>
            </div>
        @endforeach
        <button type="button" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded" wire:click.prevent="$push('subTeams', ['sub_team_name_id'=>null,'leader_id'=>null,'members'=>[],'vehicles'=>[]])">Agregar subgrupo</button>
    </div>


    <div class="mt-4">
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Guardar</button>
    </div>
</form>
