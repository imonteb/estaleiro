<div>
    <?php if (isset($component)) { $__componentOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App\Free::resolve(['title' => 'Página não encontrada','header' => 'Erro 404'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('layouts.app.free'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Layouts\App\Free::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <div class="text-center py-20">
            <h1 class="text-6xl font-bold text-blue-800 dark:text-blue-300">404</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                A página que você procura não foi encontrada.
            </p>
            <a href="<?php echo e(route('home')); ?>"
                class="mt-6 inline-block bg-blue-700 hover:bg-blue-800 text-white text-sm px-4 py-2 rounded">
                Voltar para o início
            </a>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a)): ?>
<?php $attributes = $__attributesOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a; ?>
<?php unset($__attributesOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a)): ?>
<?php $component = $__componentOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a; ?>
<?php unset($__componentOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a); ?>
<?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/errors/404.blade.php ENDPATH**/ ?>