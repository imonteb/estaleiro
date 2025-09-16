<div>
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-full max-w-lg">
                <h4 class="text-lg font-bold mb-4">Gestão de PEPs</h4>
                <table class="w-full mb-4 text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Código</th>
                            <th class="text-left py-2">Descrição</th>
                            <th class="text-right py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peps as $pep)
                            <tr class="border-b">
                                <td class="py-2">{{ $pep->code }}</td>
                                <td class="py-2">{{ $pep->description }}</td>
                                <td class="py-2 text-right">
                                    <button type="button" class="text-xs text-blue-600 hover:underline mr-2" wire:click="showEditModal({{ $pep->id }})">Editar</button>
                                    <button type="button" class="text-xs text-red-600 hover:underline" wire:click="confirmDelete({{ $pep->id }})">Apagar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <form wire:submit.prevent="save">
                    <input type="text" wire:model.defer="code" class="w-full rounded border-gray-300 mb-2" placeholder="Código PEP" />
                    @error('code') <span class="text-red-500 text-xs">{{ $message == 'The code has already been taken.' ? 'O código já existe.' : $message }}</span> @enderror
                    <input type="text" wire:model.defer="description" class="w-full rounded border-gray-300 mb-2" placeholder="Descrição (opcional)" />
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                <h4 class="text-lg font-bold mb-4">Confirmação</h4>
                <p class="mb-4">Tem certeza que deseja apagar este PEP?</p>
                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="cancelDelete" class="px-4 py-2 bg-gray-400 text-white rounded">Cancelar</button>
                    <button type="button" wire:click="deleteConfirmed" class="px-4 py-2 bg-red-600 text-white rounded">Apagar</button>
                </div>
            </div>
        </div>
    @endif
</div>
