<x-filament::modal slide-over id="editTeamModal" width="4xl" :wire-ignore="false">

    <x-slot name="heading">
        <div class="flex items-center gap-2">
            <x-heroicon-o-pencil-square class="w-6 h-6 text-orange-400" />
            <span class="text-orange-400">Editar equipa</span>
        </div>
    </x-slot>
    <x-slot name="description">
        Atualize os dados da equipa selecionada.
    </x-slot>
    <div class="p1 bg-blue-800 text-white rounded-lg m-0 p-0">
        <form wire:submit.prevent="saveEditTeam">
            <div class="bg-blue-700 p-4 rounded-xl mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label class="text-sm text-zinc-400">Nome da Equipa</label>
                        <div class="form-input w-full bg-blue-700 text-white rounded-lg p-2 cursor-not-allowed ">
                            @if(isset($editTeamData['team_name_id']))
                            {{ \App\Models\TeamName::find($editTeamData['team_name_id'])->name ?? '' }}
                            @else
                            <span class="text-gray-400">Sem nome</span>
                            @endif
                        </div>
                        @error('editTeamData.team_name_id') <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="text-sm text-zinc-400">Código PEP</label>
                        <select wire:model.defer="editTeamData.pep_id"
                            class="form-input w-full bg-blue-700 text-white rounded-lg">
                            <option value="">Seleciona</option>
                            @foreach(\App\Models\Pep::all() as $pep)
                            <option value="{{ $pep->id }}">{{ $pep->code }}</option>
                            @endforeach
                        </select>
                        @error('editTeamData.pep_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="text-sm text-zinc-400">Tipo de Trabalho</label>
                        <input type="text" wire:model.defer="editTeamData.work_type"
                            class="form-input w-full bg-blue-700 text-white rounded-lg" />
                        @error('editTeamData.work_type') <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label class="text-sm text-zinc-400">Localização</label>
                        <input type="text" wire:model.defer="editTeamData.location"
                            class="form-input w-full bg-blue-700 text-white rounded-lg" />
                        @error('editTeamData.location') <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-span-2 mb-4">
                        <label class="text-sm text-zinc-400">Líder</label>
                        <select wire:model.defer="editTeamData.leader_id"
                            class="form-input bg-blue-600 text-white rounded-lg p-2 truncate w-full">
                            <option value="">Seleciona</option>
                            @foreach(\App\Models\Employee::all() as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                            @endforeach
                        </select>
                        @error('editTeamData.leader_id') <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-blue-700 p-4 rounded-xl mb-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Membros Diretos --}}
                    <div class="mb-4 col-span-1 md:col-span-3 border border-blue-300 p-4 rounded-lg">
                        <label class="text-sm text-zinc-400">Membros Diretos</label>
                        @foreach($editTeamData['dailyTeamMembers'] ?? [] as $i => $member)
                        <div class="flex gap-2 mb-2" wire:key="member-{{ $i }}-{{ $member['employee_id'] ?? 'novo' }}">
                            <select wire:model.defer="editTeamData.dailyTeamMembers.{{ $i }}.employee_id"
                                class="form-input bg-blue-600 text-white rounded-lg p-2 truncate w-full">
                                <option value="">Seleciona</option>
                                @foreach(\App\Models\Employee::all() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                                @endforeach
                            </select>
                            <div class="flex items-center">
                                <button type="button" wire:click="removeMember({{ $i }})" class="text-red-600">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </div>

                        </div>
                        @endforeach
                        <div class="relative min-h-[60px]">
                            <button type="button" wire:click="addMember" class="bg-blue-800 hover:bg-blue-900 rounded-lg border border-zinc-600
                                p-2 text-sm text-zinc-400 absolute bottom-0 left-0">
                                Adicionar membro
                            </button>
                        </div>
                    </div>

                    {{-- Veículos Diretos --}}
                    <div class="mb-4 col-span-1 border border-blue-300 p-4 rounded-lg">
                        <div>
                            <label class="text-sm text-zinc-400">Veículos Diretos</label>
                            @foreach($editTeamData['dailyTeamVehicles'] ?? [] as $i => $vehicle)
                            <div class="flex gap-2 mb-2">
                                <select wire:model.defer="editTeamData.dailyTeamVehicles.{{ $i }}.vehicle_id"
                                    class="form-input bg-blue-600 text-white rounded-lg p-2 truncate w-full">
                                    <option value="">Seleciona</option>
                                    @foreach(\App\Models\Vehicle::all() as $v)
                                    <option value="{{ $v->id }}">{{ $v->car_plate }}</option>
                                    @endforeach
                                </select>
                                <div class="flex items-center">
                                    <button type="button" wire:click="removeVehicle({{ $i }})" class="text-red-600">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="relative min-h-[60px]">
                            <button type="button" wire:click="addVehicle" class="bg-blue-800 hover:bg-blue-900 rounded-lg border border-zinc-600
                                p-2 text-sm text-zinc-400 absolute bottom-0 left-0">
                                Adicionar veículo
                            </button>
                        </div>

                    </div>

                </div>
            </div>



            {{-- Subgrupos --}}
            <div class="bg-blue-700 p-4 rounded-xl mb-4">
                <label class="block text-sm text-zinc-400">Subgrupos</label>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 border border-blue-300 p-4 rounded-lg">
                    @foreach($editTeamData['subTeams'] ?? [] as $s => $sub)
                    <div class="mb-4 col-span-1 ">
                        <label class="block text-sm text-zinc-400">Nome do Subgrupo</label>
                        <div class="flex gap-2 mb-2">
                            <select wire:model.defer="editTeamData.subTeams.{{ $s }}.sub_team_name_id"
                                class="form-input w-full bg-blue-700 text-white rounded-lg">
                                <option value="">Seleciona</option>
                                @foreach(\App\Models\SubTeamName::all() as $stn)
                                <option value="{{ $stn->id }}">{{ $stn->name }}</option>
                                @endforeach
                            </select>
                            <div class="flex items-center">
                                <button type="button" wire:click="removeSubTeam({{ $s }})" class="text-red-600 ml-2">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                        @error('editTeamData.subTeams.' . $s . '.sub_team_name_id')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4 col-span-1 md:col-span-2 ">
                        <label class="text-sm text-zinc-400">Líder do Subgrupo</label>
                        <div class="flex gap-2 mb-2">
                            <select wire:model.defer="editTeamData.subTeams.{{ $s }}.leader_id"
                                class="form-input w-full bg-blue-700 text-white rounded-lg">
                                <option value="">Seleciona</option>
                                @foreach(\App\Models\Employee::all() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                                @endforeach
                            </select>
                            <div class="flex items-center">
                                <button type="button"
                                    wire:click="$set('editTeamData.subTeams.{{ $s }}.leader_id', null)"
                                    class="text-red-600" title="Remover líder">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                        @error('editTeamData.subTeams.' . $s . '.leader_id')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="bg-blue-700 p-4 rounded-xl mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Membros do subgrupo --}}
                        <div class="mb-4 col-span-1 border border-blue-300 p-4 rounded-lg">
                            <label class="block text-sm text-zinc-400">Membros do Subgrupo</label>
                            @foreach($sub['members'] ?? [] as $m => $member)
                            <div class="flex gap-2 mb-1">
                                <select wire:model.defer="editTeamData.subTeams.{{ $s }}.members.{{ $m }}.employee_id"
                                    class="form-input w-full bg-blue-700 text-white rounded-lg">
                                    <option value="">Seleciona</option>
                                    @foreach(\App\Models\Employee::all() as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" wire:click="removeSubMember({{ $s }}, {{ $m }})"
                                    class="text-red-600">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </div>
                            @endforeach
                            <button type="button" wire:click="addSubMember({{ $s }})" class="text-blue-600">Adicionar membro</button>
                        </div>
                        {{-- Veículos do subgrupo --}}
                        <div class="mb-4 col-span-1 border border-blue-300 p-4 rounded-lg">
                            <label>Veículos do Subgrupo</label>
                            @foreach($sub['vehicles'] ?? [] as $v => $vehicle)
                            <div class="flex gap-2 mb-1">
                                <select wire:model.defer="editTeamData.subTeams.{{ $s }}.vehicles.{{ $v }}.vehicle_id"
                                    class="form-input">
                                    <option value="">Seleciona</option>
                                    @foreach(\App\Models\Vehicle::all() as $veh)
                                    <option value="{{ $veh->id }}">{{ $veh->car_plate }}</option>
                                    @endforeach
                                </select>
                                <button type="button" wire:click="removeSubVehicle({{ $s }}, {{ $v }})"
                                    class="text-red-600">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </div>
                            @endforeach
                            <button type="button" wire:click="addSubVehicle({{ $s }})" class="text-blue-600">Adicionar veículo</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <button type="button" wire:click="addSubTeam" class="text-blue-600">Adicionar subgrupo</button>
    </div>
    <input type="hidden" wire:model.defer="editTeamData.work_date" />
    <div class="flex justify-end gap-2">
    <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white font-semibold">Guardar</button>
    </div>
    </form>
    </div>
</x-filament::modal>
