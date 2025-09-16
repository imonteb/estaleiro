<?php if (isset($component)) { $__componentOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App\Free::resolve(['title' => 'Equipas Diárias','header' => 'Equipas Diárias'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('layouts.app.free'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Layouts\App\Free::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>


    <div class="mt-10">
        
        <?php if($publishedDay): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('operaciones.published-daily-teams', ['date' => $publishedDay->date]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1706644656-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        <?php else: ?>
            <div class="text-gray-500">No hay día publicado. Consulte con el administrador.</div>
        <?php endif; ?>
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
<?php /**PATH C:\laragon\www\estaleiro\resources\views/operaciones.blade.php ENDPATH**/ ?>