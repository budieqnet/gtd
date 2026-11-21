<?php
    $title = 'Kinerja Pegawai';
?>



<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-start justify-between gap-4 no-print">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Kinerja Pegawai</h2>
            <p class="text-sm text-slate-500 mt-1">Skor kinerja = persentase tugas selesai dari total tugas yang dibuat pada bulan yang dipilih (skala 0–100).</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-print"></i> Cetak
        </button>
    </div>

    <!-- Navigasi periode -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 px-4 py-3 flex flex-wrap items-center justify-between gap-3 no-print">
        <div>
            <h3 class="font-bold text-slate-800">Periode Skor Kinerja</h3>
            <p class="text-xs text-slate-500 mt-0.5"><?php echo e($monthLabel); ?></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('admin.performance', ['month' => $prevMonth])); ?>"
               class="px-2.5 py-1.5 border border-slate-300 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition" title="Bulan sebelumnya">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
            <form method="GET" action="<?php echo e(route('admin.performance')); ?>">
                <input type="month" name="month" value="<?php echo e($monthValue); ?>" onchange="this.form.submit()"
                       class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </form>
            <a href="<?php echo e(route('admin.performance', ['month' => $nextMonth])); ?>"
               class="px-2.5 py-1.5 border border-slate-300 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition" title="Bulan berikutnya">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($monthValue !== now()->format('Y-m')): ?>
                <a href="<?php echo e(route('admin.performance')); ?>"
                   class="px-3 py-1.5 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition whitespace-nowrap">
                    Bulan Ini
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <h2 class="text-2xl font-bold text-slate-800 print-only" style="display:none">Kinerja Pegawai</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-white text-sm overflow-hidden">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($emp->photo): ?>
                            <img src="<?php echo e(asset('storage/' . $emp->photo)); ?>" alt="foto" class="w-full h-full object-cover">
                        <?php else: ?>
                            <?php echo e(substr($emp->name, 0, 1)); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800"><?php echo e($emp->full_name); ?></p>
                        <p class="text-xs text-slate-500"><?php echo e($emp->positionData?->name ?? $emp->position ?? '-'); ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-center mb-4">
                    <div class="bg-blue-50 rounded-lg p-3">
                        <p class="text-xl font-bold text-blue-600"><?php echo e($emp->total_tasks); ?></p>
                        <p class="text-xs text-blue-500">Total Tugas</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-3">
                        <p class="text-xl font-bold text-yellow-600"><?php echo e($emp->pending_tasks); ?></p>
                        <p class="text-xs text-yellow-500">Pending</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3">
                        <p class="text-xl font-bold text-green-600"><?php echo e($emp->done_tasks); ?></p>
                        <p class="text-xs text-green-500">Selesai</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-3">
                        <p class="text-xl font-bold text-red-600"><?php echo e($emp->overdue_tasks); ?></p>
                        <p class="text-xs text-red-500">Terlambat</p>
                    </div>
                </div>

                <?php
                    $score = $emp->month_total_tasks > 0 ? round(($emp->month_done_tasks / $emp->month_total_tasks) * 100) : 0;
                    $scoreBar = $score >= 70 ? 'bg-green-500' : ($score >= 40 ? 'bg-yellow-500' : 'bg-red-500');
                    $scoreChip = $score >= 70 ? 'bg-green-100 text-green-700' : ($score >= 40 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                    $scoreLabel = $score >= 70 ? 'Baik' : ($score >= 40 ? 'Cukup' : 'Kurang');
                ?>
                <div class="border-t border-slate-100 pt-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-slate-500">Skor Kinerja (<?php echo e($monthLabel); ?>)</span>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-slate-800"><?php echo e($score); ?><span class="text-xs font-medium text-slate-400">/100</span></span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium <?php echo e($scoreChip); ?>"><?php echo e($scoreLabel); ?></span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="<?php echo e($scoreBar); ?> h-2 rounded-full transition-all" style="width: <?php echo e($score); ?>%"></div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($emp->month_total_tasks === 0): ?>
                        <p class="text-xs text-slate-400 text-center mt-2">Belum ada tugas pada <?php echo e($monthLabel); ?></p>
                    <?php else: ?>
                        <p class="text-xs text-slate-400 text-center mt-2"><?php echo e($emp->month_done_tasks); ?> dari <?php echo e($emp->month_total_tasks); ?> tugas selesai</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="col-span-full text-center py-8 text-slate-400">Belum ada pegawai.</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/gtd/resources/views/admin/performance.blade.php ENDPATH**/ ?>