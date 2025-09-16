<div class="p-6">

    <h3 class="text-lg font-bold mb-4">
        <!--[if BLOCK]><![endif]--><?php if($editingTeamId): ?>
        Editar equipa modelo
        <?php else: ?>
        Nova equipa modelo
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </h3>
    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
    <div class="mb-4 text-green-600 font-semibold"><?php echo e(session('success')); ?></div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <form wire:submit.prevent="save">
        <!-- Secção Equipa Principal -->
        <section class=" border border-yellow-600 p-4 rounded mb-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome da equipa</label>
                <!--[if BLOCK]><![endif]--><?php if(!$editingTeamId): ?>
                <!-- TeamName select -->
                <div class="flex items-center gap-2">
                    <select wire:model.defer="team_name_id" class="select2 w-full rounded border-gray-300">
                        <option value="">Seleciona nome de equipa...</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $teamNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($team['id'] ?? $team->id); ?>"><?php echo e($team['name'] ?? $team->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <button type="button" wire:click="$dispatch('openTeamNameModal')"
                        class="px-2 py-1 bg-green-500 text-white rounded text-xs" title="Novo nome de equipa">+</button>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['team_name_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                <?php else: ?>
                <div class="flex items-center gap-2">
                    <input type="text" value="<?php echo e(optional($teamNames->firstWhere('id', $team_name_id))->name); ?>"
                        class="w-full rounded border-gray-300 bg-gray-100" disabled />
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if($showNewTeamNameModal): ?>
                <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded shadow w-full max-w-md">
                        <h2 class="text-lg font-bold mb-4">Novo nome de equipa</h2>
                        <input type="text" wire:model.defer="newTeamName" class="w-full rounded border-gray-300 mb-2"
                            placeholder="Nome da equipa" />
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newTeamName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" wire:click="closeNewTeamNameModal"
                                class="px-3 py-1 bg-gray-400 text-white rounded">Cancelar</button>
                            <button type="button" wire:click="saveNewTeamName"
                                class="px-3 py-1 bg-blue-500 text-white rounded">Guardar</button>
                        </div>
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if($showEditTeamNameModal): ?>
                <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded shadow w-full max-w-md">
                        <h2 class="text-lg font-bold mb-4">Editar nome de equipa</h2>
                        <input type="text" wire:model.defer="editTeamNameValue"
                            class="w-full rounded border-gray-300 mb-2" placeholder="Nome da equipa" />
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['editTeamNameValue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" wire:click="closeEditTeamNameModal"
                                class="px-3 py-1 bg-gray-400 text-white rounded">Cancelar</button>
                            <button type="button" wire:click="saveEditTeamName"
                                class="px-3 py-1 bg-blue-500 text-white rounded">Guardar</button>
                        </div>
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if($showDeleteTeamNameModal): ?>
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
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>


            <!-- Secção Colaboradores e Veículos da Equipa -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de trabalho</label>
                <input type="text" wire:model.defer="work_type" class="w-full rounded border-gray-300" required />
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['work_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                <input type="text" wire:model.defer="location" class="w-full rounded border-gray-300" required />
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Líder</label>
                <select wire:model.defer="leader_id" class="select2 w-full rounded border-gray-300" required>
                    <option value="">Selecione...</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($emp->id); ?>">
                        <?php echo e(\App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect($emp->id, '2000-01-01',
                        $editingTeamId)); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['leader_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">PEP</label>
                <!-- Pep select -->
                <div class="flex items-center gap-2">
                    <select wire:model.defer="pep_id" class="select2 w-full rounded border-gray-300">
                        <option value="">Seleciona PEP...</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $peps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($pep['id'] ?? $pep->id); ?>"><?php echo e($pep['code'] ?? $pep->code); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <button type="button" wire:click="$dispatch('openPepModal')"
                        class="px-2 py-1 bg-green-500 text-white rounded text-xs" title="Novo PEP">+</button>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['pep_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                <!--[if BLOCK]><![endif]--><?php if($showNewPepInput): ?>
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
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newPepCode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newPepDescription'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Colaboradores (dailyTeamMembers) -->
            <div class="mt-2">
                <h4 class="text-md font-semibold mb-2">Colaboradores</h4>
                <div class="space-y-2">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $dailyTeamMembers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex gap-2 items-center">
                        <select wire:model.defer="dailyTeamMembers.<?php echo e($i); ?>.employee_id" class="select2 w-full rounded border-gray-300">
                            <option value="">Selecione colaborador...</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($emp->id); ?>"><?php echo e(\App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect($emp->id, '2000-01-01',
                                $editingTeamId)); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <button type="button" wire:click="removeDailyTeamMember(<?php echo e($i); ?>)"
                            class="text-red-500">&times;</button>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['dailyTeamMembers.' . $i . '.employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    <button type="button" wire:click="addDailyTeamMember"
                        class="bg-blue-500 text-white px-2 py-1 rounded">Adicionar colaborador</button>
                </div>
            </div>

            <!-- Veículos do equipa principal (dailyTeamVehicles) -->
            <div class="mt-6">
                <h4 class="text-md font-semibold mb-2">Veículos do equipa</h4>
                <div class="space-y-2">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $dailyTeamVehicles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex gap-2 items-center">
                        <select wire:model.defer="dailyTeamVehicles.<?php echo e($i); ?>.vehicle_id" class="select2 w-full rounded border-gray-300">
                            <option value="">Selecione veículo...</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $veh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($veh->id); ?>"> <?php echo e(\App\Helpers\VehicleAssignmentHelper::getLabelParaSelect($veh->id,'2000-01-01',
                                $editingTeamId, null, true )); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <button type="button" wire:click="removeDailyTeamVehicle(<?php echo e($i); ?>)"
                            class="text-red-500">&times;</button>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['dailyTeamVehicles.' . $i . '.vehicle_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
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
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $subTeams ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s => $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-2 border rounded bg-blue-50">
                        <div class="mb-2 flex gap-2 items-center">
                            <div class="flex items-center justify-between gap-2 w-full">
                                <!-- SubTeamName select (dentro de foreach de subTeams) -->
                                <select wire:model.defer="subTeams.<?php echo e($s); ?>.sub_team_name_id" class="select2 w-full rounded border-gray-300">
                                    <option value="">Seleciona subgrupo...</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $subTeamNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($stn['id'] ?? $stn->id); ?>"><?php echo e($stn['name'] ?? $stn->name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <button type="button" wire:click="$dispatch('openSubTeamNameModal')"
                                    class="px-2 py-1 bg-green-500 text-white rounded text-xs"
                                    title="Novo subgrupo">+</button>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['subTeams.' . $s . '.sub_team_name_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-2">
                            <label class="block text-xs">Líder do subgrupo</label>
                            <select wire:model.defer="subTeams.<?php echo e($s); ?>.leader_id" class="select2 w-full rounded border-gray-300">
                                <option value="">Seleciona líder...</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($emp->id); ?>"><?php echo e(\App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect($emp->id, '2000-01-01',
                                    $editingTeamId)); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['subTeams.' . $s . '.leader_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <!-- Vehículos del subgrupo (subTeamVehicles) -->
                        <div class="mb-2">
                            <label class="block text-xs">Veículos do subgrupo</label>

                            <div class="space-y-2">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sub['vehicles'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex gap-2 items-center">
                                    <select wire:model.defer="subTeams.<?php echo e($s); ?>.vehicles.<?php echo e($v); ?>.vehicle_id"
                                        class="select2 w-full rounded border-gray-300">
                                        <option value="">Selecione veículo...</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $veh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($veh->id); ?>"> <?php echo e(\App\Helpers\VehicleAssignmentHelper::getLabelParaSelect($veh->id,'2000-01-01',
                                            $editingTeamId, null, true )); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                    <button type="button" wire:click="removeSubTeamVehicle(<?php echo e($s); ?>, <?php echo e($v); ?>)"
                                        class="text-red-500">&times;</button>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['subTeams.' . $s . '.vehicles.' . $v . '.vehicle_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span
                                        class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <button type="button" wire:click="addSubTeamVehicle(<?php echo e($s); ?>)"
                                    class="bg-blue-500 text-white px-2 py-1 rounded">Adicionar veículo</button>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="block text-xs">Colaboradores do subgrupo</label>
                            <!--[if BLOCK]><![endif]--><?php if(is_array($sub['members'])): ?>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sub['members']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex gap-2 items-center mb-1">
                                <select wire:model.defer="subTeams.<?php echo e($s); ?>.members.<?php echo e($m); ?>.employee_id" class="select2 w-full rounded border-gray-300">
                                    <option value="">Seleciona colaborador...</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($emp->id); ?>"><?php echo e(\App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect($emp->id,
                                        '2000-01-01', $editingTeamId)); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <button type="button" wire:click="removeSubMember(<?php echo e($s); ?>, <?php echo e($m); ?>)"
                                    class="text-red-500">&times;</button>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['subTeams.' . $s . '.members.' . $m . '.employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span
                                    class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <button type="button" wire:click="addSubMember(<?php echo e($s); ?>)"
                                class="bg-blue-500 text-white px-2 py-1 rounded">Adicionar colaborador</button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
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
                <!--[if BLOCK]><![endif]--><?php if($editingTeamId): ?>
                Guardar cambios
                <?php else: ?>
                Criar equipa
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </button>
            <button type="button" wire:click="cancel"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded shadow transition">Cancelar</button>
            <!--[if BLOCK]><![endif]--><?php if($editingTeamId): ?>
            <button type="button" wire:click="openDeleteTemplateModal(<?php echo e($editingTeamId); ?>)"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded shadow transition">Eliminar
                plantilla</button>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!--[if BLOCK]><![endif]--><?php if($showDeleteTemplateModal): ?>
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
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </form>
    <!-- Incluye el componente CRUD de TeamName -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('team-name-crud', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2568720848-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <!-- Incluye el componente CRUD -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pep-crud', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2568720848-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <!-- Incluye el componente CRUD de suebteamname -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('sub-team-name-crud', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2568720848-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/livewire/template-team-form.blade.php ENDPATH**/ ?>