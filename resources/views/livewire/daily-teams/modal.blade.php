<div>
    <div x-data="{ open: @entangle('showModal') }">
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">Editar Equipa Diária</h2>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                        <x-heroicon-o-x class="w-6 h-6"/>
                    </button>
                </div>

                <div class="mt-4">
                    @php
                        $teamModel = \App\Models\DailyTeam::find($teamId);
                        $params = ['team' => $teamId];
                        if ($teamModel) {
                            $params['date'] = $teamModel->work_date;
                        }
                    @endphp
                    @livewire('daily-teams.daily-team-form', $params)
                </div>
            </div>
        </div>
    </div>
</div>
