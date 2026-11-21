<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Riwayat Tugas Selesai</h1>
        <a href="<?php echo e(route('tasks.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-4">
        <form method="GET" action="<?php echo e(route('tasks.history')); ?>" class="flex flex-wrap items-end gap-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subordinates->isNotEmpty()): ?>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Pegawai</label>
                <select name="employee_id" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua (Saya)</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $subordinates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($emp->id); ?>" <?php echo e($viewingEmployeeId == $emp->id ? 'selected' : ''); ?>>
                            <?php echo e($emp->full_name); ?> (<?php echo e($emp->positionData?->name ?? $emp->position ?? '-'); ?>)
                        </option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Bulan</label>
                <input type="month" name="month" value="<?php echo e($monthValue); ?>"
                       class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['employee_id', 'month'])): ?>
                <a href="<?php echo e(route('tasks.history')); ?>" class="px-4 py-2 bg-slate-200 text-slate-600 rounded-lg hover:bg-slate-300 transition text-sm">
                    <i class="fa-solid fa-times"></i> Reset
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($viewingEmployeeId): ?>
        <?php
            $viewedEmp = $subordinates->firstWhere('id', $viewingEmployeeId);
        ?>
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-user fa-lg text-blue-600"></i>
                <div>
                    <p class="text-sm font-semibold text-blue-900">
                        Menampilkan riwayat: <?php echo e($viewedEmp ? $viewedEmp->full_name : '#' . $viewingEmployeeId); ?>

                    </p>
                    <p class="text-xs text-blue-600">
                        <?php echo e($viewedEmp && $viewedEmp->positionData ? $viewedEmp->positionData->name : ($viewedEmp->position ?? '')); ?>

                    </p>
                </div>
            </div>
            <a href="<?php echo e(route('tasks.history')); ?>" class="text-sm font-medium text-blue-700 hover:text-blue-900">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Riwayat Saya
            </a>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Month Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('tasks.history', array_merge(request()->query(), ['month' => $prevMonth]))); ?>" class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition text-sm">
                <i class="fa-solid fa-chevron-left mr-1"></i> Bulan Lalu
            </a>
            <span class="text-lg font-semibold text-slate-800"><?php echo e($monthLabel); ?></span>
            <a href="<?php echo e(route('tasks.history', array_merge(request()->query(), ['month' => $nextMonth]))); ?>" class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition text-sm">
                Bulan Depan <i class="fa-solid fa-chevron-right ml-1"></i>
            </a>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tasks->isEmpty()): ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
            <i class="fa-solid fa-clock-rotate-left text-5xl text-slate-300 mb-4"></i>
            <p class="text-slate-500">Belum ada tugas yang selesai</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="p-3 text-left font-semibold text-slate-600">No</th>
                        <th class="p-3 text-left font-semibold text-slate-600">Judul</th>
                        <th class="p-3 text-left font-semibold text-slate-600 hidden md:table-cell">Kategori</th>
                        <th class="p-3 text-center font-semibold text-slate-600">Prioritas</th>
                        <th class="p-3 text-left font-semibold text-slate-600 hidden md:table-cell">Dikerjakan Oleh</th>
                        <th class="p-3 text-left font-semibold text-slate-600 hidden sm:table-cell">Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="p-3 text-center text-slate-400"><?php echo e($tasks->firstItem() + $i); ?></td>
                            <td class="p-3 font-medium text-slate-800"><?php echo e($task->title); ?></td>
                            <td class="p-3 hidden md:table-cell">
                                <span class="text-xs px-2 py-1 rounded bg-slate-100 text-slate-600" style="border-left: 3px solid <?php echo e($task->category?->color ?? '#ccc'); ?>">
                                    <?php echo e($task->category?->name ?? '-'); ?>

                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="text-xs font-medium px-2 py-1 rounded <?php echo e($task->priority == 'High' ? 'bg-red-100 text-red-700' : ($task->priority == 'Medium' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700')); ?>">
                                    <?php echo e($task->priority); ?>

                                </span>
                            </td>
                            <td class="p-3 hidden md:table-cell text-slate-500">
                                <?php echo e($task->assignedTo?->name ?? '-'); ?>

                            </td>
                            <td class="p-3 hidden sm:table-cell text-slate-400 text-xs">
                                <?php echo e($task->updated_at->translatedFormat('d M Y H:i')); ?>

                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <?php echo e($tasks->appends(request()->query())->links()); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/gtd/resources/views/tasks/history.blade.php ENDPATH**/ ?>