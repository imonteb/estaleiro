
<?php if (isset($component)) { $__componentOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App\Free::resolve(['title' => 'Informações Técnicas','header' => 'Informações Técnicas'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('layouts.app.free'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Layouts\App\Free::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="space-y-4">
        <p class="text-gray-700 dark:text-gray-300">
            Nesta seção você poderá consultar esquemas técnicos, diagramas, manuais operacionais e outros documentos essenciais.
        </p>

        <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
            <h2 class="text-lg font-semibold text-blue-700 dark:text-blue-300 mb-2">Documentação mais recente</h2>
            <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
                <li>Manual de segurança operacional (v2.1)</li>
                <li>Esquema elétrico da ponte grua</li>
                <li>Plano de evacuação e contingência</li>
            </ul>
        </div>
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



<?php /**PATH C:\laragon\www\estaleiro\resources\views/technicalinfo.blade.php ENDPATH**/ ?>