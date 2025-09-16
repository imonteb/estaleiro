<div>
    <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
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
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $peps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b">
                                <td class="py-2"><?php echo e($pep->code); ?></td>
                                <td class="py-2"><?php echo e($pep->description); ?></td>
                                <td class="py-2 text-right">
                                    <button type="button" class="text-xs text-blue-600 hover:underline mr-2" wire:click="showEditModal(<?php echo e($pep->id); ?>)">Editar</button>
                                    <button type="button" class="text-xs text-red-600 hover:underline" wire:click="confirmDelete(<?php echo e($pep->id); ?>)">Apagar</button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
                <form wire:submit.prevent="save">
                    <input type="text" wire:model.defer="code" class="w-full rounded border-gray-300 mb-2" placeholder="Código PEP" />
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message == 'The code has already been taken.' ? 'O código já existe.' : $message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    <input type="text" wire:model.defer="description" class="w-full rounded border-gray-300 mb-2" placeholder="Descrição (opcional)" />
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-400 text-white rounded">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded"><?php echo e($editMode ? 'Guardar' : 'Criar'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if($showDeleteConfirmModal): ?>
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
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/livewire/pep-crud.blade.php ENDPATH**/ ?>