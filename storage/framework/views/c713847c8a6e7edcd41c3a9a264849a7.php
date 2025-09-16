<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <?php echo $__env->make('partials.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title><?php echo e($title ?? config('app.name')); ?></title>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <?php if (isset($component)) { $__componentOriginalf3e2f0c1894be92477d74499f0acdeb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf3e2f0c1894be92477d74499f0acdeb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navppal','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('navppal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf3e2f0c1894be92477d74499f0acdeb7)): ?>
<?php $attributes = $__attributesOriginalf3e2f0c1894be92477d74499f0acdeb7; ?>
<?php unset($__attributesOriginalf3e2f0c1894be92477d74499f0acdeb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf3e2f0c1894be92477d74499f0acdeb7)): ?>
<?php $component = $__componentOriginalf3e2f0c1894be92477d74499f0acdeb7; ?>
<?php unset($__componentOriginalf3e2f0c1894be92477d74499f0acdeb7); ?>
<?php endif; ?>

    <main class=" mx-auto  px-4 sm:px-6 lg:px-8 py-6">
        <?php if(session('message')): ?>
        <div class="mb-4 px-4 py-3 text-sm text-green-700 bg-green-100 rounded-lg">
            <?php echo e(session('message')); ?>

        </div>
        <?php endif; ?>


        <?php if(isset($header)): ?>
        <div class="mb-6">
            <h1 class="text-center text-3xl font-bold text-blue-800 dark:text-blue-300">
                <?php echo e($header); ?>

            </h1>
        </div>
        <?php endif; ?>

        <?php echo e($slot); ?>

    </main>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/components/layouts/app/free.blade.php ENDPATH**/ ?>