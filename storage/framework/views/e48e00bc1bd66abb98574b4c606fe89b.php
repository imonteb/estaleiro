<?php if (isset($component)) { $__componentOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fa42fd5850cb0cbd9ae1fcbc0771c2a = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App\Free::resolve(['title' => 'Home','header' => 'Bem-vindo ao Estaleiro'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('layouts.app.free'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Layouts\App\Free::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4">

        <div class="col-span-1 sm:col-span-2 lg:col-span-3">
            

            <!-- Carousel   -->
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('home.carousel', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2663981574-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <div class="text-center text-gray-600 dark:text-gray-400 mt-10">
                <p>Explore nossos serviços e descubra como podemos ajudar você a alcançar seus objetivos.</p>

            </div>
        </div>

        <div class="col-span-1 sm:col-span-2  lg:col-span-1 border-l border-gray-300 dark:border-gray-700 p-4">

            <div class="text-center text-blue-700 font-semibold sm:text-5xl mt-10">
                <h1>News</h1>
            </div>

            <div
                class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <a href="#">
                    <img src="<?php echo e($imageHome); ?>" class="rounded-t-lg w-fit" alt="">
                </a>
                <div class="p-5">
                    <a href="#">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Noteworthy
                            technology acquisitions 2021</h5>
                    </a>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Here are the biggest enterprise
                        technology acquisitions of 2021 so far, in reverse chronological order.</p>
                    <a href="#"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Read more
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <div
                class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <a href="#">
                    <img src="<?php echo e($imageHome); ?>" class="rounded-t-lg w-fit" alt="">
                </a>
                <div class="p-5">
                    <a href="#">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Noteworthy
                            technology acquisitions 2021</h5>
                    </a>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Here are the biggest enterprise
                        technology acquisitions of 2021 so far, in reverse chronological order.</p>
                    <a href="#"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Read more
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="text-center text-gray-600 dark:text-gray-400 mt-10">
                <p>Explore nossos serviços e descubra como podemos ajudar você a alcançar seus objetivos.</p>
            </div>
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
<?php /**PATH C:\laragon\www\estaleiro\resources\views/home.blade.php ENDPATH**/ ?>