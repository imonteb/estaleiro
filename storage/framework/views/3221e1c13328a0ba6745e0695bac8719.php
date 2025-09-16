<div>


    <div x-init="initFlowbite();"></div> <!-- AlpineJS init -->

    <div id="default-carousel" class="relative  m-auto" data-carousel="slide">
        <!-- Carousel wrapper -->
        <div class="relative h-50 overflow-hidden rounded-lg md:h-96">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $carouselItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imagen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="<?php echo e($loop->first ? 'block' : 'hidden'); ?> duration-700 ease-in-out" data-carousel-item wire:key="imagen-<?php echo e($loop->index); ?>">
                <img src="<?php echo e($imagen['image']); ?>" class="absolute block w-full" alt="<?php echo e($imagen['title']); ?>">
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- Slider indicators -->
        <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $carouselItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imagen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button"
                    class="w-3 h-3 rounded-full bg-blue-500"
                    <?php if($loop->first): ?> aria-current="true" <?php endif; ?>
                    aria-label="Slide <?php echo e($index + 1); ?>"
                    data-carousel-slide-to="<?php echo e($index); ?>">
                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <!-- Slider controls -->
        <button type="button"
            class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg aria-hidden="true" class="w-5 h-5 text-blue-500 sm:w-6 sm:h-6 dark:text-gray-800" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                    </path>
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg aria-hidden="true" class="w-5 h-5 text-blue-400 sm:w-6 sm:h-6 dark:text-gray-800" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                    </path>
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>
<script>
    // Ensure initFlowbite is defined globally before using it
    window.initFlowbite = window.initFlowbite || function() {};
    if (window.Livewire && typeof window.Livewire.on === 'function') {
        Livewire.on('refresh-carousel', () => {
            setTimeout(() => {
                if (typeof initFlowbite === 'function') {
                    initFlowbite();
                }
            }, 300); // Pequeño retraso para esperar la actualización de Livewire
        });
    }
</script>
    
</div>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/livewire/home/carousel.blade.php ENDPATH**/ ?>