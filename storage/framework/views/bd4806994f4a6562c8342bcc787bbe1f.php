<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['document', 'index']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['document', 'index']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md group hover-sound text-sound
         h-full flex flex-col"
    data-document-index="<?php echo e($index + 1); ?>" data-document-title="<?php echo e($document->title); ?>"
    data-document-id="<?php echo e($document->id); ?>">

    <!-- Document Cover -->
    <div class="aspect-[3/4] bg-gray-200 relative overflow-hidden shrink-0"> <img src="<?php echo e($document->cover_url); ?>"
            alt="Cover <?php echo e($document->title); ?>"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy"
            >

        <!-- Play Overlay -->
        <div
            class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-300 flex items-center justify-center cursor-pointer">

            <?php if($document->mp3_path): ?>
                <button type="button"
                    class="play-document-btn w-16 h-16 bg-blue-600 hover:bg-blue-700 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center transform hover:scale-110 hover-sound"
                    data-document='<?php echo json_encode($document, 15, 512) ?>' aria-label="Putar audio <?php echo e($document->title); ?>">
                    <i class="fas fa-play text-xl ml-1" aria-hidden="true"></i>
                </button>
            <?php else: ?>
                <div
                    class="w-16 h-16 bg-gray-400 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <i class="fas fa-clock text-xl" aria-hidden="true"></i>
                </div>
            <?php endif; ?>
        </div>

        <!-- Type Badge -->
        <div class="absolute top-3 left-3">
            <span
                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    <?php echo e($document->type === 'publication' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                <i class="fas fa-<?php echo e($document->type === 'publication' ? 'book' : 'newspaper'); ?> mr-1"
                    aria-hidden="true"></i>
                <?php echo e($document->type === 'publication' ? 'Publikasi' : 'BRS'); ?>

            </span>
        </div>

        <!-- Document Number for Voice Navigation -->
        <div class="absolute top-3 right-3">
            <span
                class="inline-flex items-center justify-center w-8 h-8 bg-black bg-opacity-70 text-white text-sm font-bold rounded-full">
                <?php echo e($index + 1); ?>

            </span>
        </div>
    </div>

    <!-- Document Info -->
    <div class="p-4 flex-1 flex flex-col">
        <h3
            class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors text-sound hover-sound">
            <?php echo e($document->title); ?>

        </h3>

        <div class="space-y-2 text-sm text-gray-600">
            <div class="flex items-center">
                <i class="fas fa-calendar-alt mr-2 text-gray-400" aria-hidden="true"></i>
                <span class="text-sound"><?php echo e($document->year); ?></span>
            </div>

            <?php if($document->indicator): ?>
                <div class="flex items-center">
                    <i class="fas fa-tag mr-2 text-gray-400" aria-hidden="true"></i>
                    <span class="text-sound line-clamp-1"><?php echo e($document->indicator->name); ?></span>
                </div>
            <?php endif; ?>

            <?php if($document->description): ?>
                <p class="text-sound line-clamp-2 text-xs">
                    <?php echo e(Str::limit($document->description, 100)); ?></p>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-2 mt-auto pt-4">
            <?php if($document->mp3_path): ?>
                <button type="button"
                    class="flex-1 cursor-pointer px-3 py-2 bg-<?php echo e($document->type === 'publication' ? 'blue' : 'green'); ?>-600 hover:bg-<?php echo e($document->type === 'publication' ? 'blue' : 'green'); ?>-700 text-white text-xs font-medium rounded-md transition-colors hover-sound play-document-btn"
                    data-document='<?php echo json_encode($document, 15, 512) ?>'>
                    <i class="fas fa-play mr-1" aria-hidden="true"></i>
                    <span class="text-sound">Putar</span>
                </button>
            <?php else: ?>
                <div class="flex-1 px-3 py-2 bg-gray-300 text-gray-500 text-xs font-medium rounded-md text-center">
                    <i class="fas fa-clock mr-1" aria-hidden="true"></i>
                    <span class="text-sound">Diproses</span>
                </div>
            <?php endif; ?>

            <a href="<?php echo e(route('documents.uuid.show', $document->uuid)); ?>"
                class="px-3 py-2 border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 text-xs font-medium rounded-md transition-colors hover-sound">
                <i class="fas fa-eye" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>
<?php /**PATH C:\PROJECT\MAGANGHUB\BPS - SULUT\IPDS\PROJECT\austats–web\resources\views/components/document_card.blade.php ENDPATH**/ ?>