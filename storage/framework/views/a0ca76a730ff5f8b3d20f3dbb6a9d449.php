<div>
    <div class="flex items-end justify-end mb-3">
        <button class="bg-green-700 hover:bg-green-800 text-white font-semibold text-xs py-2 px-4
        rounded shadow transition" wire:click="createCard">
            Criar nova equipa modelo
        </button>
    </div>

    <div class="rounded-lg w-full h-full min-w-0 bg-slate-400 flex flex-col items-stretch p-2 pb-10">
        <!-- Slideover/modal para crear nova equipa modelo -->
        <!--[if BLOCK]><![endif]--><?php if($showSlideover): ?>
        <div class="fixed inset-0 z-50 flex justify-end bg-black bg-opacity-40" style="cursor:pointer"
            wire:click.self="closeSlideover">
            <div class="w-full max-w-md bg-white dark:bg-gray-800 h-full shadow-xl p-0 flex flex-col">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold">Nova equipa modelo</h3>
                    <button wire:click="closeSlideover"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-2xl">&times;</button>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('template-team-form', ['editingTeamId' => $editingTeamId]);

$__html = app('livewire')->mount($__name, $__params, 'lw-487242479-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div
                class="mt-4 ml-2 me-2 justify-between rounded-lg border border-green-900 shadow-xl hover:shadow-2xl shadow-blue-900 min-h-60 p-4 transition h-full bg-white relative">
                <!-- Botón editar -->
                <button
                    class="absolute top-2 right-2 bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs shadow"
                    wire:click="editTeam(<?php echo e($team->id); ?>)">Editar</button>
                <!-- Cabecera azul -->
                <div class="grid grid-cols-4 bg-green-900 rounded-t-md text-white p-2">
                    <div class="col-span-2 text-left border-b">
                        <h3 class="text-lg font-bold text-yellow-400">
                            <?php echo e($team->teamname->name ?? '-'); ?>

                        </h3>
                    </div>
                    <div class="col-span-2 text-center border-b">
                        <strong class="text-xs">PEP:</strong>
                        <span class="text-xs truncate">
                            <?php echo e(optional($team->pep)->code ?? '-'); ?>

                        </span>
                    </div>
                    <div class="col-span-2 px-2 mt-2">
                        <strong class="text-xs">Tipo:</strong>
                        <span class="text-xs truncate"><?php echo e($team->work_type ?? '-'); ?></span>
                    </div>
                    <div class="col-span-2 px-2 mt-2">
                        
                        <span class="text-xs truncate"><?php echo e($team->location ?? '-'); ?></span>
                    </div>
                </div>

                <!-- Datos principales -->
                <div class="grid grid-cols-5 gap-2 mt-2 text-white text-xs bg-green-700 p-2 rounded-md">
                    <div class="col-span-5 bg-green-500 rounded-md p-2">
                        <strong class="text-xs truncate">Líder:</strong>
                        <span><?php echo e(optional($team->leader)->full_name ?? '–'); ?></span>
                    </div>
                    <div class="col-span-3 bg-green-500 rounded-md p-2">
                        <strong>Colaboradores:</strong>
                        <!--[if BLOCK]><![endif]--><?php $__empty_2 = true; $__currentLoopData = $team->dailyTeamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <p class="truncate"><?php echo e($member->employee->full_name ?? '–'); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <p class="text-xs text-gray-400">Sem colaboradores</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="col-span-2 bg-green-500 rounded-md p-2">
                        <strong class="text-xs">Viaturas:</strong>
                        <!--[if BLOCK]><![endif]--><?php $__empty_2 = true; $__currentLoopData = $team->dailyTeamVehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <p class="truncate"><?php echo e($vehicle->vehicle->car_plate ?? '–'); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <p class="text-xs text-gray-400">Sem viaturas</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- Subequipas -->
                <!--[if BLOCK]><![endif]--><?php $__empty_2 = true; $__currentLoopData = $team->subTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                <div class="grid grid-cols-5 gap-2 mt-2 text-white text-xs bg-green-700 p-2 rounded-md">
                    <div class="col-span-5 bg-green-500 rounded-md p-2">
                        <p class="text-xs truncate">
                            <strong>Subequipa: <?php echo e($sub->subTeamName->name ?? ''); ?></strong><br>
                            <span class="ml-2"><strong>Líder:</strong> <?php echo e($sub->leader->full_name ?? '–'); ?></span>
                        </p>
                    </div>
                    <div class="col-span-3 bg-green-500 rounded-md p-2">
                        <strong class="text-xs">Colaboradores:</strong>
                        <!--[if BLOCK]><![endif]--><?php $__empty_3 = true; $__currentLoopData = $sub->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                        <p class="truncate"><?php echo e($member->employee->full_name ?? '–'); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                        <p class="text-xs text-gray-400">Sem membros</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="col-span-2 bg-green-500 rounded-md p-2">
                        <strong class="text-xs">Viaturas:</strong>
                        <!--[if BLOCK]><![endif]--><?php $__empty_3 = true; $__currentLoopData = $sub->vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                        <p class="truncate"><?php echo e($vehicle->vehicle->car_plate ?? '–'); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                        <p class="text-gray-400">Sem viaturas</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                <div class="col-span-full text-xs text-gray-400">Sem subequipas</div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-gray-500 dark:text-gray-400 text-sm">
                Nenhuma equipa disponível.
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
    </div>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/livewire/template-teams-index.blade.php ENDPATH**/ ?>