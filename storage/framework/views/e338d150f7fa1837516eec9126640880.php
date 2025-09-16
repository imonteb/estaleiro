<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/app.css',
    'resources/js/app.js',
    ]); ?>
    
    <div class="grid-flow-row gap-8 mb-4">
        <div class="mb-3 flex items-center gap-4">
            <label class="block text-base font-semibold text-blue-900 dark:text-white mr-2">
                <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['name' => 'heroicon-o-calendar','class' => 'w-5 h-7 inline-block mr-1 text-blue-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'heroicon-o-calendar','class' => 'w-5 h-7 inline-block mr-1 text-blue-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                Seleciona o dia de trabalho
            </label>
            <input type="date" wire:model.lazy="sourceDate"
                class="block w-48 px-3 py-2 border border-blue-300 rounded-lg text-base shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white dark:bg-gray-800 transition"
                placeholder="Escolhe a data" />
            
        </div>
    </div>
    
    

    
    <!--[if BLOCK]><![endif]--><?php if($showDeleteModal): ?>
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
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-3 gap-6">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $this->teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div
            class="flex flex-col justify-between rounded-lg border border-blue-900 shadow-xl hover:shadow-2xl shadow-blue-900 min-h-60 bg-white dark:bg-gray-900 p-4 transition h-full">

            
            <div class="grid grid-cols-4 bg-blue-900 rounded-t-md text-white p-2">
                <div class="col-span-3 text-left border-b">
                    <h3 class="text-lg font-bold text-yellow-400">
                        <!--[if BLOCK]><![endif]--><?php if($team->teamname): ?>
                        <?php echo e($team->teamname->name); ?>

                        <?php elseif($team->status === 'estaleiro'): ?>
                        Estaleiro
                        <?php elseif($team->status === 'ausentes'): ?>
                        Ausentes
                        <?php else: ?>
                        —
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </h3>
                </div>
                <div class="col-span-1 text-center border-b">
                    <p class="text-sm">
                        <strong>Tipo:</strong>
                        <?php echo e($team->work_type ?? '–'); ?>

                    </p>
                </div>
                <div class="col-span-2 px-2 mt-2">
                    <strong class="text-xs">PEP:</strong>
                    <p class="text-sm"><?php echo e($team->pep->code ?? '–'); ?></p>
                </div>
                <div class="col-span-2 px-2 mt-2">
                    <strong class="text-xs">Localização:</strong>
                    <p class="text-sm"><?php echo e($team->location ?? '–'); ?></p>
                </div>
            </div>

            
            <div class="grid grid-cols-5 gap-2 mt-2">
                <div class="col-span-5 bg-blue-600 px-1">
                    <p class="text-xs truncate">
                        <strong>Líder:</strong> <?php echo e($team->leader->full_name ?? '–'); ?>

                    </p>
                </div>
                <div class="col-span-3">
                    <strong class="text-xs">Colaboradores:</strong>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $team->dailyTeamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="text-xs truncate"><?php echo e($emp->employee->full_name); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-span-2">
                    <strong class="text-xs">Veículos:</strong>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $team->dailyTeamVehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="text-xs truncate"><?php echo e($v->vehicle ? $v->vehicle->getLabelParaSelect($team->work_date) : ''); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $team->subTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="grid grid-cols-5 gap-2 mt-4">
                <div class="col-span-5 bg-blue-500 px-1">
                    <p class="text-xs truncate">
                        <strong>Sub-equipa: <?php echo e($sub->subTeamName->name ?? ''); ?></strong><br>
                        <span class="ml-2"><strong>Líder:</strong> <?php echo e($sub->leader->full_name ?? '–'); ?></span>
                    </p>
                </div>
                <div class="col-span-3">
                    <strong class="text-xs">Colaboradores:</strong>
                    <!--[if BLOCK]><![endif]--><?php $__empty_2 = true; $__currentLoopData = $sub->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                    <p class="text-xs truncate"><?php echo e($member->employee->full_name); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                    <p class="text-xs text-gray-400">Sem membros</p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-span-2">
                    <strong class="text-xs">Veículos:</strong>
                    <!--[if BLOCK]><![endif]--><?php $__empty_2 = true; $__currentLoopData = $sub->vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                    <p class="text-xs truncate"><?php echo e($sv->vehicle ? $sv->vehicle->getLabelParaSelect($team->work_date) :
                        ''); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                    <p class="text-xs text-gray-400">Sem veículos</p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

            
            <div class="mt-3 flex justify-between items-center bg-blue-900 text-white text-xs px-2 py-1 rounded">
                <span><strong>Data:</strong> <?php echo e(\Carbon\Carbon::parse($team->work_date)->format('d-m-Y')); ?></span>
                <div class="flex gap-2">
                    
                    
                    <a href="#" wire:click.prevent="openEditModal(<?php echo e($team->id); ?>)"
                        class="inline-block px-2 py-1 text-xs rounded bg-yellow-400 text-blue-900 hover:bg-yellow-300 font-bold transition"
                        title="Editar equipa">
                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['name' => 'heroicon-o-pencil-square']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'heroicon-o-pencil-square']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                        Editar
                    </a>
                    
                    <button wire:click.prevent="confirmDeleteTeam(<?php echo e($team->id); ?>)"
                        class="inline-block px-2 py-1 text-xs rounded bg-red-500 text-white hover:bg-red-400 font-bold transition"
                        title="Eliminar equipa">
                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['name' => 'heroicon-o-trash']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'heroicon-o-trash']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full text-gray-500 dark:text-gray-400 text-sm">
            Nenhuma equipa para <?php echo e($sourceDate); ?>.
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!--[if BLOCK]><![endif]--><?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/filament/resources/daily-team-resource/pages/team-card.blade.php ENDPATH**/ ?>