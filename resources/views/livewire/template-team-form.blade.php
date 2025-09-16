<div class="p-6">

    <h3 class="text-lg font-bold mb-4">
        @if($editingTeamId)
        Editar equipa modelo
        @else
        Nova equipa modelo
        @endif
    </h3>
    @if (session()->has('success'))
    <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="save">
        <!-- Secção Equipa Principal -->
        <section class=" border border-yellow-600 p-4 rounded mb-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome da equipa</label>
                @if(!$editingTeamId)
                <!-- TeamName select -->
                <div class="flex items-center gap-2">
                    <select wire:model.defer="team_name_id" class="select2 w-full rounded border-gray-300">
                        <option value="">Seleciona nome de equipa...</option>
                        @foreach($teamNames as $team)
                        <option value="{{ $team['id'] ?? $team->id }}">{{ $team['name'] ?? $team->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" wire:click="$dispatch('openTeamNameModal')"
                        class="px-2 py-1 bg-green-500 text-white rounded text-xs" title="Novo nome de equipa">+</button>
                </div>
                @error('team_name_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                @else
                <div class="flex items-center gap-2">
                    <input type="text" value="{{ optional($teamNames->firstWhere('id', $team_name_id))->name }}"
                        class="w-full rounded border-gray-300 bg-gray-100" disabled />
                </div>
                @endif

                {{-- Modal para crear nuevo nombre de equipo --}}
                @if($showNewTeamNameModal)
                <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded shadow w-full max-w-md">
                        <h2 class="text-lg font-bold mb-4">Novo nome de equipa</h2>
                        <input type="text" wire:model.defer="newTeamName" class="w-full rounded border-gray-300 mb-2"
                            placeholder="Nome da equipa" />
                        @error('newTeamName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" wire:click="closeNewTeamNameModal"
                                class="px-3 py-1 bg-gray-400 text-white rounded">Cancelar</button>
                            <button type="button" wire:click="saveNewTeamName"
                                class="px-3 py-1 bg-blue-500 text-white rounded">Guardar</button>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Modal para editar nombre de equipo --}}
                @if($showEditTeamNameModal)
                <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded shadow w-full max-w-md">
                        <h2 class="text-lg font-bold mb-4">Editar nome de equipa</h2>
                        <input type="text" wire:model.defer="editTeamNameValue"
                            class="w-full rounded border-gray-300 mb-2" placeholder="Nome da equipa" />
                        @error('editTeamNameValue') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" wire:click="closeEditTeamNameModal"
                                class="px-3 py-1 bg-gray-400 text-white rounded">Cancelar</button>
                            <button type="button" wire:click="saveEditTeamName"
                                class="px-3 py-1 bg-blue-500 text-white rounded">Guardar</button>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Modal para borrar nombre de equipo --}}
                @if($showDeleteTeamNameModal)
                <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded shadow w-full max-w-md">
                        <h2 class="text-lg font-bold mb-4 text-red-600">¿Borrar nome de equipa?</h2>
                        <p class="mb-4">Esta acción no se puede deshacer.</p>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" wire:click="closeDeleteTeamNameModal"
                                class="px-3 py-1 bg-gray-400 text-white rounded">Cancelar</button>
                            <button type="button" wire:click="deleteTeamName"
                                class="px-3 py-1 bg-red-500 text-white rounded">Borrar</button>
                        </div>
                    </div>
                </div>
                @endif
            </div>


            <!-- Secção Colaboradores e Veículos da Equipa -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de trabalho</label>
                <input type="text" wire:model.defer="work_type" class="w-full rounded border-gray-300" required />
                @error('work_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                <input type="text" wire:model.defer="location" class="w-full rounded border-gray-300" required />
                @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Líder</label>
                <select wire:model.defer="leader_id" class="select2 w-full rounded border-gray-300" required>
                    <option value="">Selecione...</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">
                        {{ \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect($emp->id, '2000-01-01',
                        $editingTeamId) }}
                    </option>
                    @endforeach
                </select>
                @error('leader_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">PEP</label>
                <!-- Pep select -->
                <div class="flex items-center gap-2">
                    <select wire:model.defer="pep_id" class="select2 w-full rounded border-gray-300">
                        <option value="">Seleciona PEP...</option>
                        @foreach($peps as $pep)
                        <option value="{{ $pep['id'] ?? $pep->id }}">{{ $pep['code'] ?? $pep->code }}</option>
                        @endforeach
                    </select>
                    <button type="button" wire:click="$dispatch('openPepModal')"
                        class="px-2 py-1 bg-green-500 text-white rounded text-xs" title="Novo PEP">+</button>
                </div>
                @error('pep_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                @if($showNewPepInput)
                <div class="mt-2 flex items-center">
                    <input type="text" wire:model.defer="newPepCode" class="rounded border-gray-300 mr-2"
                        placeholder="Código PEP" />
                    <input type="text" wire:model.defer="newPepDescription" class="rounded border-gray-300 mr-2"
                        placeholder="Descrição" />
                    <button type="button" wire:click="saveNewPep"
                        class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Guardar</button>
                    <button type="button" wire:click="hideNewPepInput"
                        class="px-2 py-1 bg-gray-400 text-white rounded text-xs ml-1">Cancelar</button>
                </div>
                @error('newPepCode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                @error('newPepDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                @endif
            </div>

            <!-- Colaboradores (dailyTeamMembers) -->
            <div class="mt-2">
                <h4 class="text-md font-semibold mb-2">Colaboradores</h4>
                <div class="space-y-2">
                    @foreach($dailyTeamMembers ?? [] as $i => $member)
                    <div class="flex gap-2 items-center">
                        <select wire:model.defer="dailyTeamMembers.{{ $i }}.employee_id" class="select2 w-full rounded border-gray-300">
                            <option value="">Selecione colaborador...</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{
                                \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect($emp->id, '2000-01-01',
                                $editingTeamId) }}
                            </option>
                            @endforeach
                        </select>
                        <button type="button" wire:click="removeDailyTeamMember({{ $i }})"
                            class="text-red-500">&times;</button>
                        @error('dailyTeamMembers.' . $i . '.employee_id') <span class="text-red-500 text-xs">{{ $message
                            }}</span> @enderror
                    </div>
                    @endforeach
                    <button type="button" wire:click="addDailyTeamMember"
                        class="bg-blue-500 text-white px-2 py-1 rounded">Adicionar colaborador</button>
                </div>
            </div>

            <!-- Veículos do equipa principal (dailyTeamVehicles) -->
            <div class="mt-6">
                <h4 class="text-md font-semibold mb-2">Veículos do equipa</h4>
                <div class="space-y-2">
                    @foreach($dailyTeamVehicles ?? [] as $i => $vehicle)
                    <div class="flex gap-2 items-center">
                        <select wire:model.defer="dailyTeamVehicles.{{ $i }}.vehicle_id" class="select2 w-full rounded border-gray-300">
                            <option value="">Selecione veículo...</option>
                            @foreach($vehicles as $veh)
                            <option value="{{ $veh->id }}"> {{
                                \App\Helpers\VehicleAssignmentHelper::getLabelParaSelect($veh->id,'2000-01-01',
                                $editingTeamId, null, true ) }}</option>
                            @endforeach
                        </select>
                        <button type="button" wire:click="removeDailyTeamVehicle({{ $i }})"
                            class="text-red-500">&times;</button>
                        @error('dailyTeamVehicles.' . $i . '.vehicle_id') <span class="text-red-500 text-xs">{{ $message
                            }}</span> @enderror
                    </div>
                    @endforeach
                    <button type="button" wire:click="addDailyTeamVehicle"
                        class="bg-blue-500 text-white px-2 py-1 rounded">Adicionar veículo</button>
                </div>
            </div>
        </section>

        <section class=" border border-yellow-600 p-4 rounded mb-6">
            <!-- Secção Subgrupos (inclui membros e veículos) -->
            <div class="mb-4">
                <h4 class="text-md font-semibold mb-2">Subgrupos</h4>
                <div class="space-y-4">
                    @foreach($subTeams ?? [] as $s => $sub)
                    <div class="p-2 border rounded bg-blue-50">
                        <div class="mb-2 flex gap-2 items-center">
                            <div class="flex items-center justify-between gap-2 w-full">
                                <!-- SubTeamName select (dentro de foreach de subTeams) -->
                                <select wire:model.defer="subTeams.{{ $s }}.sub_team_name_id" class="select2 w-full rounded border-gray-300">
                                    <option value="">Seleciona subgrupo...</option>
                                    @foreach($subTeamNames as $stn)
                                    <option value="{{ $stn['id'] ?? $stn->id }}">{{ $stn['name'] ?? $stn->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="button" wire:click="$dispatch('openSubTeamNameModal')"
                                    class="px-2 py-1 bg-green-500 text-white rounded text-xs"
                                    title="Novo subgrupo">+</button>
                            </div>
                            @error('subTeams.' . $s . '.sub_team_name_id') <span class="text-red-500 text-xs">{{
                                $message
                                }}</span> @enderror
                        </div>
                        <div class="mb-2">
                            <label class="block text-xs">Líder do subgrupo</label>
                            <select wire:model.defer="subTeams.{{ $s }}.leader_id" class="select2 w-full rounded border-gray-300">
                                <option value="">Seleciona líder...</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{
                                    \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect($emp->id, '2000-01-01',
                                    $editingTeamId) }}
                                </option>
                                @endforeach
                            </select>
                            @error('subTeams.' . $s . '.leader_id') <span class="text-red-500 text-xs">{{ $message
                                }}</span>
                            @enderror
                        </div>
                        <!-- Vehículos del subgrupo (subTeamVehicles) -->
                        <div class="mb-2">
                            <label class="block text-xs">Veículos do subgrupo</label>

                            <div class="space-y-2">
                                @foreach($sub['vehicles'] ?? [] as $v => $vehicle)
                                <div class="flex gap-2 items-center">
                                    <select wire:model.defer="subTeams.{{ $s }}.vehicles.{{ $v }}.vehicle_id"
                                        class="select2 w-full rounded border-gray-300">
                                        <option value="">Selecione veículo...</option>
                                        @foreach($vehicles as $veh)
                                        <option value="{{ $veh->id }}"> {{
                                            \App\Helpers\VehicleAssignmentHelper::getLabelParaSelect($veh->id,'2000-01-01',
                                            $editingTeamId, null, true ) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" wire:click="removeSubTeamVehicle({{ $s }}, {{ $v }})"
                                        class="text-red-500">&times;</button>
                                    @error('subTeams.' . $s . '.vehicles.' . $v . '.vehicle_id') <span
                                        class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                @endforeach
                                <button type="button" wire:click="addSubTeamVehicle({{ $s }})"
                                    class="bg-blue-500 text-white px-2 py-1 rounded">Adicionar veículo</button>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="block text-xs">Colaboradores do subgrupo</label>
                            @if(is_array($sub['members']))
                            @foreach($sub['members'] as $m => $member)
                            <div class="flex gap-2 items-center mb-1">
                                <select wire:model.defer="subTeams.{{ $s }}.members.{{ $m }}.employee_id" class="select2 w-full rounded border-gray-300">
                                    <option value="">Seleciona colaborador...</option>
                                    @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{
                                        \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect($emp->id,
                                        '2000-01-01', $editingTeamId) }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="button" wire:click="removeSubMember({{ $s }}, {{ $m }})"
                                    class="text-red-500">&times;</button>
                                @error('subTeams.' . $s . '.members.' . $m . '.employee_id') <span
                                    class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            @endforeach
                            @endif
                            <button type="button" wire:click="addSubMember({{ $s }})"
                                class="bg-blue-500 text-white px-2 py-1 rounded">Adicionar colaborador</button>
                        </div>
                    </div>
                    @endforeach
                    <button type="button" wire:click="addSubTeam"
                        class="bg-blue-700 text-white px-2 py-1 rounded">Adicionar
                        subgrupo</button>
                </div>
            </div>
        </section>
        <!-- Botões -->
        <div class="flex gap-2 mt-8 justify-end">
            <button type="submit"
                class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded shadow transition">
                @if($editingTeamId)
                Guardar cambios
                @else
                Criar equipa
                @endif
            </button>
            <button type="button" wire:click="cancel"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded shadow transition">Cancelar</button>
            @if($editingTeamId)
            <button type="button" wire:click="openDeleteTemplateModal({{ $editingTeamId }})"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded shadow transition">Eliminar
                plantilla</button>
            @endif
        </div>

        @if($showDeleteTemplateModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-full max-w-md">
                <h2 class="text-lg font-bold mb-4 text-red-600">Eliminar equipa modelo</h2>
                <p class="mb-4">Esta ação irá apagar permanentemente a equipa modelo e todos os seus membros, veículos e
                    subgrupos. Tem a certeza que deseja continuar?</p>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" wire:click="closeDeleteTemplateModal"
                        class="px-3 py-1 bg-gray-400 text-white rounded">Cancelar</button>
                    <button type="button" wire:click="deleteTemplate"
                        class="px-3 py-1 bg-red-600 text-white rounded">Eliminar definitivamente</button>
                </div>
            </div>
        </div>
        @endif
    </form>
    <!-- Incluye el componente CRUD de TeamName -->
    <livewire:team-name-crud />
    <!-- Incluye el componente CRUD -->
    <livewire:pep-crud />
    <!-- Incluye el componente CRUD de suebteamname -->
    <livewire:sub-team-name-crud />
</div>
