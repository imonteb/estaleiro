<div>
    
<div class="max-w-xl mx-auto mt-8">
    <form wire:submit.prevent="publishDay" class="flex items-center gap-4">
        <label for="published_day" class="font-semibold">Dia de trabalho a publicar:</label>
        <input type="date" id="published_day" wire:model="published_day" class="border rounded px-2 py-1" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">Publicar</button>
    </form>
    <!--[if BLOCK]><![endif]--><?php if($success): ?>
        <div class="mt-2 text-green-600"><?php echo e($success); ?></div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\laragon\www\estaleiro\resources\views/livewire/equipas/publish-work-day.blade.php ENDPATH**/ ?>