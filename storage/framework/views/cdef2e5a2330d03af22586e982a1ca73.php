

<?php $__env->startSection('title', 'Publikasi - Audio Statistik'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Publikasi</h1>
            <p class="text-gray-600">Publikasi terkini dari BPS Sulawesi Utara dalam format audio yang mudah diakses</p>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" action="<?php echo e(route('documents.publications')); ?>" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Cari Publikasi
                    </label>
                    <div class="relative">
                        <input type="text" id="search" name="search"
                            value="<?php echo e($query ?? ($originalQuery ?? request('search'))); ?>"
                            placeholder="Cari judul Publikasi..."
                            class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover-sound">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>

                <div class="w-32">
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select id="year" name="year"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover-sound">
                        <option value="">Semua</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($y); ?>"
                                <?php echo e((string) request('year') === (string) $y ? 'selected' : ''); ?>>
                                <?php echo e($y); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="w-48">
                    <label for="indicator" class="block text-sm font-medium text-gray-700 mb-2">Indikator</label>
                    <select id="indicator" name="indicator"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Indikator</option>
                        <?php $__currentLoopData = $indicators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indicatorOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($indicatorOption->id); ?>"
                                <?php echo e((request('indicator') ?? $indicator) == $indicatorOption->id ? 'selected' : ''); ?>>
                                <?php echo e($indicatorOption->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors focus:ring-2 focus:ring-green-500 focus:ring-offset-2 hover-sound">
                    <i class="fas fa-search mr-2" aria-hidden="true"></i>Filter
                </button>
            </form>
        </div>

        <!-- Results Count -->
        <?php if($documents->count() > 0): ?>
            <div class="mb-6">
                <p class="text-gray-600">
                    Menampilkan <?php echo e(number_format($documents->count())); ?> dari <?php echo e(number_format($documents->total())); ?>

                    Publikasi
                </p>
            </div>
        <?php endif; ?>

        <!-- Documents Grid -->
        <?php if($documents->count() > 0): ?>
            <div id="documents-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginalaed2295c58826c452004d4536fe0e2a1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaed2295c58826c452004d4536fe0e2a1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.document_card','data' => ['document' => $document,'index' => $index]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('document_card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['document' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($document),'index' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($index)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaed2295c58826c452004d4536fe0e2a1)): ?>
<?php $attributes = $__attributesOriginalaed2295c58826c452004d4536fe0e2a1; ?>
<?php unset($__attributesOriginalaed2295c58826c452004d4536fe0e2a1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaed2295c58826c452004d4536fe0e2a1)): ?>
<?php $component = $__componentOriginalaed2295c58826c452004d4536fe0e2a1; ?>
<?php unset($__componentOriginalaed2295c58826c452004d4536fe0e2a1); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <?php if($documents->hasPages()): ?>
                <div class="flex justify-center">
                    <?php echo e($documents->withQueryString()->links()); ?>

                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- No Results -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-newspaper text-3xl text-gray-400" aria-hidden="true"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada Publikasi ditemukan</h2>
                <p class="text-gray-600 mb-6">
                    <?php if(request()->hasAny(['search', 'year', 'indicator'])): ?>
                        Coba ubah filter pencarian atau hapus beberapa filter.
                    <?php else: ?>
                        Belum ada Publikasi yang tersedia saat ini.
                    <?php endif; ?>
                </p>
                <?php if(request()->hasAny(['search', 'year', 'indicator'])): ?>
                    <a href="<?php echo e(route('documents.publications')); ?>"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors hover-sound">
                        <i class="fas fa-refresh mr-2" aria-hidden="true"></i>Reset Filter
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    
    <div id="voice-navigation-controls" class="fixed bottom-4 right-4 z-40">
        <div class="flex flex-col space-y-2">
            
            <button id="voice-activation-btn"
                class="w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110"
                aria-label="Aktivasi Voice Navigation"
                title="Tekan untuk mengaktifkan/menonaktifkan voice navigation (Ctrl+P)">
                <i class="fas fa-microphone text-xl"></i>
            </button>

            
            <button id="voice-help-btn"
                class="w-14 h-14 bg-green-600 hover:bg-green-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110"
                aria-label="Bantuan Voice Navigation" title="Bantuan voice navigation (Ctrl+H)">
                <i class="fas fa-question text-xl"></i>
            </button>
        </div>
    </div>

    
    <div id="voice-status" class="fixed top-4 left-4 z-50 hidden">
        <div class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2">
            <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
            <span>Voice Navigation Aktif</span>
        </div>
    </div>

    
    <div id="voice-commands-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Perintah Voice Navigation - Publikasi</h3>
                    <button id="close-voice-modal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">📄 Perintah Dokumen</h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Baca dokumen"</code> - Bacakan daftar dokumen
                            </li>
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Pilih dokumen 1"</code> - Buka dokumen nomor 1
                            </li>
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Putar dokumen 2"</code> - Putar audio dokumen
                                2</li>
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Berapa dokumen"</code> - Jumlah dokumen</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">🔍 Perintah Filter</h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Filter tahun 2023"</code> - Filter berdasarkan
                                tahun</li>
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Filter indikator inflasi"</code> - Filter
                                indikator</li>
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Cari penduduk"</code> - Pencarian kata kunci
                            </li>
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Hapus filter"</code> - Reset semua filter</li>
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Persempit pencarian"</code> - Saran filter
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">🧭 Perintah Navigasi</h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Halaman selanjutnya"</code> - Halaman berikut
                            </li>
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Halaman sebelumnya"</code> - Halaman sebelum
                            </li>
                            <li><code class="bg-gray-100 px-2 py-1 rounded">"Bantuan"</code> - Tampilkan bantuan</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">⌨️ Keyboard Shortcuts</h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li><kbd class="bg-gray-200 px-2 py-1 rounded">Ctrl + P</kbd> - Toggle voice navigation</li>
                            <li><kbd class="bg-gray-200 px-2 py-1 rounded">Ctrl + H</kbd> - Bantuan voice</li>
                            <li><kbd class="bg-gray-200 px-2 py-1 rounded">Ctrl + R</kbd> - Baca dokumen</li>
                            <li><kbd class="bg-gray-200 px-2 py-1 rounded">Esc</kbd> - Tutup modal</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h5 class="font-medium text-blue-900 mb-2">💡 Tips Penggunaan:</h5>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Jika dokumen ≤ 5, akan dibacakan otomatis</li>
                        <li>• Jika dokumen > 5, gunakan filter untuk mempersempit</li>
                        <li>• Pastikan microphone diizinkan oleh browser</li>
                        <li>• Ucapkan perintah dengan jelas dalam bahasa Indonesia</li>
                    </ul>
                </div>

                <div class="mt-4 flex justify-end">
                    <button id="close-voice-modal-btn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\MAGANGHUB\BPS - SULUT\IPDS\PROJECT\austats–web\resources\views/documents/publications.blade.php ENDPATH**/ ?>