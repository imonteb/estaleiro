<div class="rounded-lg w-full h-full min-w-0 bg-slate-400 flex flex-col items-stretch p-8">
    <h2 class="text-2xl font-bold mb-4">Equipas diárias publicadas para <?php echo e($date); ?></h2>
    <!--[if BLOCK]><![endif]--><?php if($teams->isEmpty()): ?>
    <div class="text-gray-700">No hay equipas diárias publicadas para este día.</div>
    <?php else: ?>
    <ul class="space-y-4">
       
        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
           <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div
                class="mt-4 ml-2 me-2 justify-between rounded-lg border border-blue-900 shadow-xl hover:shadow-2xl shadow-blue-900 min-h-60 p-4 transition h-full bg-white relative">
                <!-- Botón editar -->
                
                <!-- Cabecera azul -->
                <div class="grid grid-cols-4 bg-blue-900 rounded-t-md text-white p-2">
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
                <div class="grid grid-cols-5 gap-2 mt-2 text-white text-xs bg-blue-700 p-2 rounded-md">
                    <div class="col-span-5 bg-blue-500 rounded-md p-2">
                        <strong class="text-xs truncate">Líder:</strong>
                        <span><?php echo e($team->leader->full_name ?? '–'); ?></span>
                    </div>
                    <div class="col-span-3 bg-blue-500 rounded-md p-2">
                        <strong>Colaboradores:</strong>
                        <!--[if BLOCK]><![endif]--><?php $__empty_2 = true; $__currentLoopData = $team->dailyTeamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <p class="truncate"><?php echo e($emp->employee->full_name ?? '–'); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <p class="text-xs text-gray-400">Sem colaboradores</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="col-span-2 bg-blue-500 rounded-md p-2">
                        <strong class="text-xs">Viaturas:</strong>
                        <!--[if BLOCK]><![endif]--><?php $__empty_2 = true; $__currentLoopData = $team->dailyTeamVehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $veh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <p class="truncate"><?php echo e(\App\Helpers\VehicleAssignmentHelper::getLabelParaSelect($veh->vehicle_id, $team->work_date,
                            $team->id, null, false)); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <p class="text-xs text-gray-400">Sem viaturas</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <!-- Subgrupos -->
                <!--[if BLOCK]><![endif]--><?php $__empty_2 = true; $__currentLoopData = $team->subTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                <div class="grid grid-cols-5 gap-2 mt-2 text-white text-xs bg-blue-700 p-2 rounded-md">
                    <div class="col-span-5 bg-blue-500 rounded-md p-2">
                        <p class="text-xs truncate">
                            <strong>Subequipa: <?php echo e($sub->subTeamName->name ?? ''); ?></strong><br>
                            <span class="ml-2"><strong>Líder:</strong> <?php echo e($sub->leader->full_name ?? '–'); ?></span>
                        </p>
                    </div>
                    <div class="col-span-3 bg-blue-500 rounded-md p-2">
                        <strong class="text-xs">Colaboradores:</strong>
                        <!--[if BLOCK]><![endif]--><?php $__empty_3 = true; $__currentLoopData = $sub->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                        <p class="truncate"><?php echo e($member->employee->full_name ?? '–'); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                        <p class="text-xs text-gray-400">Sem membros</p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="col-span-2 bg-blue-500 rounded-md p-2">
                        <strong class="text-xs">Viaturas:</strong>
                        <!--[if BLOCK]><![endif]--><?php $__empty_3 = true; $__currentLoopData = $sub->vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $veh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                        <p class="truncate"><?php echo e(\App\Helpers\VehicleAssignmentHelper::getLabelParaSelect($veh->vehicle_id, $team->work_date,
                            $team->id, null, false)); ?></p>
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
            <div class="col-span-5 text-center text-gray-700 font-semibold">No hay equipos diarios para esta data.</div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        
    </ul>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/livewire/operaciones/published-daily-teams.blade.php ENDPATH**/ ?>