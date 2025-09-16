<div>
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-full max-w-lg">
                <h4 class="text-lg font-bold mb-4">Gestão de nomes de equipa</h4>
                <table class="w-full mb-4 text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Nome</th>
                            <th class="text-right py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teamNames as $team)
                            <tr class="border-b">
                                <td class="py-2">{{ $team->name }}</td>
                                <td class="py-2 text-right">
                                    <button type="button" class="text-xs text-blue-600 hover:underline mr-2" wire:click="showEditModal({{ $team->id }})">Editar</button>
                                    <button type="button" class="text-xs text-red-600 hover:underline" wire:click="confirmDelete({{ $team->id }})">Apagar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <form wire:submit.prevent="save">
                    <input type="text" wire:model.defer="name" class="w-full rounded border-gray-300 mb-2" placeholder="Novo nome de equipa" />
                    @error('name') <span class="text-red-500 text-xs">{{ $message == 'The name has already been taken.' ? 'O nome já existe.' : $message }}</span> @enderror
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-400 text-white rounded">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ $editMode ? 'Guardar' : 'Criar' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    @if($showDeleteConfirmModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-full max-w-sm">
                <h4 class="text-lg font-bold mb-4">Confirmar exclusão</h4>
                <p class="mb-4">Tem certeza que deseja apagar este nome de equipa?</p>
                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="cancelDelete" class="px-4 py-2 bg-gray-400 text-white rounded">Cancelar</button>
                    <button type="button" wire:click="deleteConfirmed" class="px-4 py-2 bg-red-600 text-white rounded">Apagar</button>
                </div>
            </div>
        </div>
    @endif
</div>
