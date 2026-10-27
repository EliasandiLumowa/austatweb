<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Audio Statistik - Audio Statistik Sulawesi Utara'); ?></title>
    <meta name="description"
        content="Layanan audio untuk publikasi dan berita resmi statistik Sulawesi Utara yang mendukung aksesibilitas pengguna dengan gangguan penglihatan.">

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="bg-gray-50 font-inter" data-route-name="<?php echo e(Route::currentRouteName()); ?>"
    data-search-url="<?php echo e(route('search')); ?>"
    data-enable-voice="<?php if (! (request()->routeIs('admin.login') ||
            request()->routeIs('register') ||
            request()->routeIs('brs.') ||
            request()->routeIs('publications.'))): ?> 1 <?php else: ?> 0 <?php endif; ?>">
    
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-md z-50">
        Lewati ke konten utama
    </a>

    
    <?php echo $__env->make('components.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main id="main-content" class="min-h-screen pb-32 pt-0" role="main">
        <?php if(auth()->guard()->check()): ?>
            <div class="hidden">Akun ini : <span id="role-user"><?php echo e(auth()->user()->role); ?></span></div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php if (isset($component)) { $__componentOriginal4a2ffc5a062146494a2c65e267267106 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4a2ffc5a062146494a2c65e267267106 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.audio-player','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('audio-player'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4a2ffc5a062146494a2c65e267267106)): ?>
<?php $attributes = $__attributesOriginal4a2ffc5a062146494a2c65e267267106; ?>
<?php unset($__attributesOriginal4a2ffc5a062146494a2c65e267267106); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4a2ffc5a062146494a2c65e267267106)): ?>
<?php $component = $__componentOriginal4a2ffc5a062146494a2c65e267267106; ?>
<?php unset($__componentOriginal4a2ffc5a062146494a2c65e267267106); ?>
<?php endif; ?>

    
    <div id="right-sidebar"
        class="fixed top-16 right-0 w-80 h-full bg-gray-100 border-l border-gray-300 transform translate-x-full transition-transform duration-300 z-30 overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900 text-sound">Detail Dokumen</h3>
                <button id="close-sidebar-btn" class="text-gray-400 hover:text-gray-600 hover-sound">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="sidebar-content">
                <div class="mb-6">
                    <img id="sidebar-doc-cover" src="" alt=""
                        class="w-full aspect-[3/4] object-cover rounded-lg bg-gray-200 mb-4">
                    <h4 id="sidebar-doc-title" class="font-semibold text-gray-900 mb-2 text-sound"></h4>
                    <p id="sidebar-doc-indicator" class="text-sm text-gray-600 mb-2 text-sound"></p>
                    <p id="sidebar-doc-date" class="text-sm text-gray-500 mb-4 text-sound"></p>
                </div>

                <div class="mb-6">
                    <h5 class="font-medium text-gray-900 mb-2 text-sound">Deskripsi</h5>
                    <p id="sidebar-doc-description" class="text-sm text-gray-600 leading-relaxed text-sound"></p>
                </div>

                <div class="mb-6">
                    <h5 class="font-medium text-gray-900 mb-2 text-sound">Informasi Audio</h5>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between"><span class="text-sound">Durasi:</span><span
                                id="sidebar-audio-duration" class="text-sound">-</span></div>
                        <div class="flex justify-between"><span class="text-sound">Format:</span><span
                                id="sidebar-audio-format" class="text-sound">MP3</span></div>
                        <div class="flex justify-between"><span class="text-sound">Ukuran:</span><span
                                id="sidebar-file-size" class="text-sound">-</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php if (isset($component)) { $__componentOriginale375c06a7fceb0cee24f4b1baac80f57 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale375c06a7fceb0cee24f4b1baac80f57 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.voice-search','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('voice-search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale375c06a7fceb0cee24f4b1baac80f57)): ?>
<?php $attributes = $__attributesOriginale375c06a7fceb0cee24f4b1baac80f57; ?>
<?php unset($__attributesOriginale375c06a7fceb0cee24f4b1baac80f57); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale375c06a7fceb0cee24f4b1baac80f57)): ?>
<?php $component = $__componentOriginale375c06a7fceb0cee24f4b1baac80f57; ?>
<?php unset($__componentOriginale375c06a7fceb0cee24f4b1baac80f57); ?>
<?php endif; ?>

    
    <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <button id="scroll-to-top"
        class="fixed bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 hidden hover-sound z-50"
        aria-label="Kembali ke atas">
        <i class="fas fa-arrow-up" aria-hidden="true"></i>
    </button>

    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700 text-sound">Memuat...</span>
        </div>
    </div>

    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

</body>

</html>
<?php /**PATH C:\PROJECT\MAGANGHUB\BPS - SULUT\IPDS\PROJECT\austats–web\resources\views/layouts/app.blade.php ENDPATH**/ ?>