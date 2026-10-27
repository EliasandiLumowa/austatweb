

<?php $__env->startSection('title', $document->title . ' - Audio Statistik'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Document Header -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:space-x-8">
                <!-- Document Cover -->
                <div class="shrink-0 mb-6 lg:mb-0">
                    <img src="<?php echo e($document->cover_url); ?>" alt="Cover <?php echo e($document->title); ?>"
                        class="w-48 h-64 object-cover rounded-lg shadow-md mx-auto lg:mx-0"
                        onerror="this.src='/images/default-document-cover.jpg'">
                </div>

                <!-- Document Info -->
                <div class="flex-1">
                    <!-- Badges -->
                    <div class="flex items-center space-x-2 mb-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                 <?php echo e($document->type === 'publication' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                            <i class="fas fa-<?php echo e($document->type === 'publication' ? 'book' : 'newspaper'); ?> mr-1"
                                aria-hidden="true"></i>
                            <?php echo e($document->type === 'publication' ? 'Publikasi' : 'BRS'); ?>

                        </span>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-calendar mr-1" aria-hidden="true"></i>
                            <?php echo e($document->year); ?>

                        </span>
                        <?php if($document->mp3_path): ?>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-headphones mr-1" aria-hidden="true"></i>
                                Audio Tersedia
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php echo e($document->title); ?></h1>

                    <!-- Meta Info -->
                    <div class="space-y-2 text-gray-600 mb-6">
                        <p class="flex items-center">
                            <i class="fas fa-tag w-4 mr-3 text-gray-400" aria-hidden="true"></i>
                            <?php if($document->indicator): ?>
                                <?php echo e($document->indicator->name); ?>

                            <?php endif; ?>
                        </p>
                        
                        <p class="flex items-center">
                            <i class="fas fa-clock w-4 mr-3 text-gray-400" aria-hidden="true"></i>
                            <?php echo e($document->created_at->format('d F Y')); ?>

                        </p>
                        <?php if($document->hasAudio()): ?>
                            <p class="flex items-center">
                                <i class="fas fa-volume-up w-4 mr-3 text-gray-400" aria-hidden="true"></i>
                                Durasi audio: <?php echo e($document->getAudioDurationFormatted()); ?>

                            </p>
                        <?php endif; ?>
                        <p class="flex items-center">
                            <i class="fas fa-download w-4 mr-3 text-gray-400" aria-hidden="true"></i>
                            <?php echo e(number_format($document->download_count)); ?> unduhan
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 mb-6">
                        <?php if($document->mp3_path): ?>
                            <button onclick="playDocumentAudio(<?php echo e($document->toJson()); ?>)"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors hover-sound">
                                <i class="fas fa-play mr-2" aria-hidden="true"></i>
                                Putar Audio
                            </button>

                            <a href="<?php echo e(Storage::disk('public')->url($document->mp3_path)); ?>"
                                class="inline-flex items-center px-6 py-3 border border-blue-600 text-blue-600 hover:bg-blue-50 font-medium rounded-lg transition-colors hover-sound">
                                <i class="fas fa-download mr-2" aria-hidden="true"></i>
                                Unduh MP3
                            </a>

                            <a href="<?php echo e(route('documents.audio.download', [$document, 'flac'])); ?>"
                                class="inline-flex items-center px-6 py-3 border border-green-600 text-green-600 hover:bg-green-50 font-medium rounded-lg transition-colors hover-sound">
                                <i class="fas fa-download mr-2" aria-hidden="true"></i>
                                Unduh FLAC
                            </a>
                        <?php else: ?>
                            <div
                                class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-500 font-medium rounded-lg">
                                <i class="fas fa-clock mr-2" aria-hidden="true"></i>
                                Audio sedang diproses
                            </div>
                        <?php endif; ?>




                        <!-- Description -->
                        <?php if($document->description): ?>
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-900 mb-2">Deskripsi</h3>
                                <p class="text-gray-600 leading-relaxed"><?php echo e($document->description); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-900 mb-2">Deskripsi</h3>
                                <p class="text-gray-400 italic">Deskripsi dokumen masih belum tersedia.</p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between items-center">
                <a href="<?php echo e($document->type === 'publication' ? route('documents.publications') : route('documents.brs')); ?>"
                    class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium hover-sound">
                    <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                    Kembali ke <?php echo e($document->type === 'publication' ? 'Publikasi' : 'BRS'); ?>

                </a>

                <!-- Share buttons could go here -->
            </div>
        </div>
    <?php $__env->stopSection(); ?>
    <script>
        function toggleExtractedText() {
            const section = document.getElementById('extractedTextSection');
            section.classList.toggle('hidden');
        }
    </script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\MAGANGHUB\BPS - SULUT\IPDS\PROJECT\austats–web\resources\views/documents/show.blade.php ENDPATH**/ ?>