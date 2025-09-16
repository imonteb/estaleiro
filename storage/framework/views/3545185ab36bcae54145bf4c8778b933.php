<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Flowbite JS para dropdowns y componentes -->
    <script src="https://unpkg.com/flowbite@latest/dist/flowbite.min.js"></script>

</head>

<body class="font-sans antialiased">
    <div class="w-full h-full bg-gray-100 dark:bg-gray-900">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('layout.navigation', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2197797511-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

        <!-- Page Heading -->
        <?php if(isset($header)): ?>
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="w-full py-6 px-4 sm:px-6 lg:px-8">
                <?php echo e($header); ?>

            </div>
        </header>
        <?php endif; ?>

        <!-- Page Content -->
        <main>
            <?php echo e($slot); ?>

        </main>
    </div>


    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script>
    document.addEventListener('livewire:initialized', function () {
        // Inicializa Select2 en todos los selects con la clase select2 o select2-subveh
        $('.select2:visible, .select2-subveh:visible').select2();

        // Actualiza el valor de Livewire cuando cambia el select
        $('.select2:visible, .select2-subveh:visible').on('change', function (e) {
            var data = $(this).val();
            var wireModel = $(this).attr('wire:model.defer');
            if (wireModel) {
                window.livewire.find(this.closest('[wire\:id]').getAttribute('wire:id')).set(wireModel, data);
            }
        });

        // Re-inicializa Select2 después de cada actualización de Livewire
        Livewire.hook('message.processed', (message, component) => {
            setTimeout(function() {
                $('.select2:visible, .select2-subveh:visible').select2();
                $('.select2:visible, .select2-subveh:visible').off('change').on('change', function (e) {
                    var data = $(this).val();
                    var wireModel = $(this).attr('wire:model.defer');
                    if (wireModel) {
                        window.livewire.find(this.closest('[wire\\:id]').getAttribute('wire:id')).set(wireModel, data);
                    }
                });
            }, 100);
        });
    });

    // Inicializa Select2 tras abrir el modal/slideover
    document.addEventListener('openSlideover', function () {
        setTimeout(function() {
            $('.select2:visible, .select2-subveh:visible').select2();
        }, 100);
    });
    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/layouts/app.blade.php ENDPATH**/ ?>