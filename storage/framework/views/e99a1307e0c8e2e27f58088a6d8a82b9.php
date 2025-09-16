<div>
    <nav class="bg-blue-700 border-gray-200 dark:bg-gray-900 ">
        <!--   -->
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-1.5">
            <!-- Logo  -->
            <a href="<?php echo e(route('home')); ?>" class="flex items-center space-x-3 rtl:space-x-reverse ">

                <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'block h-9 w-auto fill-current text-gray-800 dark:text-gray-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'block h-9 w-auto fill-current text-gray-800 dark:text-gray-200']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
                <span class="text-amber-500 self-center text-2xl font-semibold whitespace-nowrap dark:text-white">
                    Estaleiro C016
                </span>
            </a>
            <div class="space-x-6 rtl:space-x-reverse ">

                <div class="text-white pt-1">
                    <div class="max-w-screen-xl  mx-auto">
                        <div class="flex items-center">
                            <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                                <li>
                                    <a href="<?php echo e(route('home')); ?>" class="text-white dark:text-white hover:underline"
                                        aria-current="page">Iniciar</a>
                                </li>

                                <li>
                                    <a href="<?php echo e(route('operaciones')); ?>" class="text-white dark:text-white hover:underline">Equipas Diárias</a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('technicalinfo')); ?>" class="text-white dark:text-white hover:underline">Informações</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white dark:text-white hover:underline">
                                        <a href="#" class="text-sm  text-blue-600 dark:text-blue-500 hover:underline">
                                            <div class="flex flex-wrap justify-between items-end mx-auto ">
                                                <div class="flex items-center">
                                                    <?php if(Route::has('login')): ?>
                                                    <div class="">
                                                        <?php if(auth()->guard()->check()): ?>
                                                        <a href="<?php echo e(url('/dashboard')); ?>"
                                                            class="font-semibold text-gray-100 hover:text-amber-500
                                                             dark:text-gray-400 dark:hover:text-amber-500 focus:outline
                                                            focus:outline-2 focus:rounded-sm focus:outline-red-500 text-sm">
                                                            Painel
                                                        </a>
                                                        <?php else: ?>
                                                        <a href="<?php echo e(route('login')); ?>"
                                                            class="font-semibold text-gray-100 hover:text-amber-500
                                                             dark:text-gray-400 dark:hover:text-amber-500 focus:outline
                                                             focus:outline-2 focus:rounded-sm focus:outline-red-500 text-sm">
                                                            Liga-te
                                                        </a>

                                                        
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </a></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </nav>
</div>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/components/navppal.blade.php ENDPATH**/ ?>