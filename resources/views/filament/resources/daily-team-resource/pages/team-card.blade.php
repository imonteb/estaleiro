<x-filament-panels::page>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    ])
    {{-- Barra superior: Seleção de datas + Botão de geração manual --}}
    <div class="grid-flow-row gap-8 mb-4">
        <div class="mb-3 flex items-center gap-4">
            <label class="block text-base font-semibold text-blue-900 dark:text-white mr-2">
                <x-filament::icon name="heroicon-o-calendar" class="w-5 h-7 inline-block mr-1 text-blue-600" />
                Seleciona o dia de trabalho
            </label>
            <input type="date" wire:model.lazy="sourceDate"
                class="block w-48 px-3 py-2 border border-blue-300 rounded-lg text-base shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white dark:bg-gray-800 transition"
                placeholder="Escolhe a data" />
            {{-- resources/views/filament/resources/daily-team-resource/pages/team-card.blade.php --}}
        </div>
    </div>
    {{-- Modal de edição (partial) --}}
    {{-- @if($showEditModal)
    @include('filament.resources.daily-team-resource.pages.edit-team-modal')
    @endif --}}

    {{-- Modal de confirmação de eliminação (Blade/Livewire) --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="text-lg font-bold text-red-600 mb-2">Eliminar equipa?</div>
            <div class="mb-4 text-gray-700 dark:text-gray-200">
                Esta ação não pode ser desfeita. Tem a certeza que pretende eliminar esta equipa?
            </div>
            <div class="flex justify-end gap-2">
                <button wire:click="$set('showDeleteModal', false)"
                    class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                    Cancelar
                </button>
                <button wire:click="deleteTeam" class="px-4 py-2 rounded bg-red-600 text-white font-bold">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Grid de cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-3 gap-6">
        @forelse($this->teams as $team)
        <div
            class="flex flex-col justify-between rounded-lg border border-blue-900 shadow-xl hover:shadow-2xl shadow-blue-900 min-h-60 bg-white dark:bg-gray-900 p-4 transition h-full">

            {{-- Cabecera azul --}}
            <div class="grid grid-cols-4 bg-blue-900 rounded-t-md text-white p-2">
                <div class="col-span-3 text-left border-b">
                    <h3 class="text-lg font-bold text-yellow-400">
                        @if($team->teamname)
                        {{ $team->teamname->name }}
                        @elseif($team->status === 'estaleiro')
                        Estaleiro
                        @elseif($team->status === 'ausentes')
                        Ausentes
                        @else
                        —
                        @endif
                    </h3>
                </div>
                <div class="col-span-1 text-center border-b">
                    <p class="text-sm">
                        <strong>Tipo:</strong>
                        {{ $team->work_type ?? '–' }}
                    </p>
                </div>
                <div class="col-span-2 px-2 mt-2">
                    <strong class="text-xs">PEP:</strong>
                    <p class="text-sm">{{ $team->pep->code ?? '–' }}</p>
                </div>
                <div class="col-span-2 px-2 mt-2">
                    <strong class="text-xs">Localização:</strong>
                    <p class="text-sm">{{ $team->location ?? '–' }}</p>
                </div>
            </div>

            {{-- Dados principais --}}
            <div class="grid grid-cols-5 gap-2 mt-2">
                <div class="col-span-5 bg-blue-600 px-1">
                    <p class="text-xs truncate">
                        <strong>Líder:</strong> {{ $team->leader->full_name ?? '–' }}
                    </p>
                </div>
                <div class="col-span-3">
                    <strong class="text-xs">Colaboradores:</strong>
                    @foreach($team->dailyTeamMembers as $emp)
                    <p class="text-xs truncate">{{ $emp->employee->full_name }}</p>
                    @endforeach
                </div>
                <div class="col-span-2">
                    <strong class="text-xs">Veículos:</strong>
                    @foreach($team->dailyTeamVehicles as $v)
                    <p class="text-xs truncate">{{ $v->vehicle ? $v->vehicle->getLabelParaSelect($team->work_date) : ''
                        }}</p>
                    @endforeach
                </div>
            </div>

            {{-- SubEquipas --}}
            @foreach($team->subTeams as $sub)
            <div class="grid grid-cols-5 gap-2 mt-4">
                <div class="col-span-5 bg-blue-500 px-1">
                    <p class="text-xs truncate">
                        <strong>Sub-equipa: {{ $sub->subTeamName->name ?? '' }}</strong><br>
                        <span class="ml-2"><strong>Líder:</strong> {{ $sub->leader->full_name ?? '–' }}</span>
                    </p>
                </div>
                <div class="col-span-3">
                    <strong class="text-xs">Colaboradores:</strong>
                    @forelse($sub->members as $member)
                    <p class="text-xs truncate">{{ $member->employee->full_name }}</p>
                    @empty
                    <p class="text-xs text-gray-400">Sem membros</p>
                    @endforelse
                </div>
                <div class="col-span-2">
                    <strong class="text-xs">Veículos:</strong>
                    @forelse($sub->vehicles as $sv)
                    <p class="text-xs truncate">{{ $sv->vehicle ? $sv->vehicle->getLabelParaSelect($team->work_date) :
                        '' }}</p>
                    @empty
                    <p class="text-xs text-gray-400">Sem veículos</p>
                    @endforelse
                </div>
            </div>
            @endforeach

            {{-- Footer con ação de editar --}}
            <div class="mt-3 flex justify-between items-center bg-blue-900 text-white text-xs px-2 py-1 rounded">
                <span><strong>Data:</strong> {{ \Carbon\Carbon::parse($team->work_date)->format('d-m-Y') }}</span>
                <div class="flex gap-2">
                    {{-- Ação Filament para editar --}}
                    
                    <a href="#" wire:click.prevent="openEditModal({{ $team->id }})"
                        class="inline-block px-2 py-1 text-xs rounded bg-yellow-400 text-blue-900 hover:bg-yellow-300 font-bold transition"
                        title="Editar equipa">
                        <x-filament::icon name="heroicon-o-pencil-square" />
                        Editar
                    </a>
                    {{-- Ação Filament para eliminar --}}
                    <button wire:click.prevent="confirmDeleteTeam({{ $team->id }})"
                        class="inline-block px-2 py-1 text-xs rounded bg-red-500 text-white hover:bg-red-400 font-bold transition"
                        title="Eliminar equipa">
                        <x-filament::icon name="heroicon-o-trash" />
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-gray-500 dark:text-gray-400 text-sm">
            Nenhuma equipa para {{ $sourceDate }}.
        </div>
        @endforelse
    </div>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
</x-filament-panels::page>
