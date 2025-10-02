
@if($showModal)
    <x-filament::modal id="teamFormModal" width="4xl" :visible="true">
        <form wire:submit.prevent="save">
            <div class="p-6">
                {{ $this->form }}
                <div class="flex justify-end mt-6">
                    <button type="button" wire:click="$set('showModal', false)" class="btn btn-secondary mr-2">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Guardar
                    </button>
                </div>
            </div>
        </form>
    </x-filament::modal>
@endif
