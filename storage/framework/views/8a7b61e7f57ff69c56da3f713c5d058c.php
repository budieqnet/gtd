<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 lg:px-6">
    <div class="flex items-center justify-between mb-5">
        <a href="<?php echo e(route('tasks.index')); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-blue-600 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tugas
        </a>
        <span class="text-xs px-2 py-1 rounded-full bg-slate-100 border border-slate-300 text-slate-500">#<?php echo e($task->id); ?></span>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <p><?php echo e($error); ?></p>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 md:p-8">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <?php
                    $statusColors = [
                        'Inbox' => 'bg-slate-200 text-slate-700',
                        'In Progress' => 'bg-blue-100 text-blue-700',
                        'Done' => 'bg-green-100 text-green-700',
                    ];
                    $priorityText = ['High' => 'text-red-600', 'Medium' => 'text-orange-600', 'Low' => 'text-green-600'];
                ?>
                <span class="text-xs uppercase tracking-wider font-bold px-3 py-1 rounded-full <?php echo e($statusColors[$task->status]); ?>">
                    <?php echo e($task->status); ?>

                </span>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-medium <?php echo e($priorityText[$task->priority]); ?>">
                        <i class="fa-solid fa-circle text-[6px] mr-1"></i> <?php echo e($task->priority); ?>

                    </span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->due_date): ?>
                        <span class="text-xs px-3 py-1 rounded-full border font-medium <?php echo e($task->due_date->isPast() && $task->status != 'Done' ? 'bg-red-50 border-red-200 text-red-600' : 'bg-slate-50 border-slate-200 text-slate-600'); ?>">
                            <i class="fa-regular fa-calendar mr-1"></i> <?php echo e($task->due_date->format('d M Y')); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->due_date->isPast() && $task->status != 'Done'): ?> <i class="fa-solid fa-triangle-exclamation"></i> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2"><?php echo e($task->title); ?></h1>

            <span class="inline-flex items-center text-xs px-3 py-1 rounded-lg bg-slate-100 border text-slate-600" style="border-left: 4px solid <?php echo e($task->category->color ?? '#ccc'); ?>">
                <?php echo e($task->category->icon ?? ''); ?> <?php echo e($task->category->name ?? 'No Category'); ?>

            </span>

            <div class="mt-6">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Deskripsi</h2>
                <div class="text-slate-700 leading-relaxed whitespace-pre-line">
                    <?php echo e($task->description ?: 'Tidak ada deskripsi.'); ?>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 border-t border-slate-100">
            <div class="p-4 text-center">
                <p class="text-xs text-slate-500 mb-1">Dibuat oleh</p>
                <p class="text-sm font-semibold text-slate-700"><?php echo e($task->createdBy?->full_name ?? $task->createdBy?->name ?? '-'); ?></p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-slate-500 mb-1">Ditugaskan ke</p>
                <p class="text-sm font-semibold text-slate-700"><?php echo e($task->assignedTo?->full_name ?? $task->assignedTo?->name ?? '-'); ?></p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-slate-500 mb-1">Dibuat</p>
                <p class="text-sm font-semibold text-slate-700"><?php echo e($task->created_at->format('d/m/Y H:i')); ?></p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-slate-500 mb-1">Terakhir diubah</p>
                <p class="text-sm font-semibold text-slate-700"><?php echo e($task->updated_at->format('d/m/Y H:i')); ?></p>
            </div>
        </div>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->status === 'Done'): ?>
            <div class="inline-flex items-center gap-2 bg-slate-100 border border-slate-200 text-slate-500 px-4 py-2 rounded-lg text-sm">
                <i class="fa-solid fa-lock"></i>
                <span>
                    Tugas selesai bersifat permanen — tidak dapat diubah atau dihapus.
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->created_by === Auth::id()): ?>
                        Jika hasil tugas kurang tepat, buat tugas baru untuk pegawai ini.
                    <?php elseif($task->assigned_to === Auth::id()): ?>
                        Hubungi atasan jika ada perbaikan.
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </span>
            </div>
        <?php else: ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->created_by === Auth::id()): ?>
                <button onclick="openEditModal(<?php echo e($task->toJson()); ?>)" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Tugas
                </button>
                <form action="<?php echo e(route('tasks.destroy', $task->id)); ?>" method="POST" class="confirm-delete" data-name="tugas <?php echo e($task->title); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg font-medium transition text-sm">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->assigned_to === Auth::id() && $task->created_by !== Auth::id()): ?>
                <form action="<?php echo e(route('tasks.update-status', $task->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <input type="hidden" name="status" value="Done">
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                        <i class="fa-solid fa-check"></i>
                        Tandai Selesai
                    </button>
                </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<?php echo $__env->make('tasks.edit-modal', ['categories' => $categories, 'task' => $task], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
function openEditModal(task) {
    document.getElementById('edit-title').value = task.title;
    document.getElementById('edit-description').value = task.description;
    document.getElementById('edit-category').value = task.category_id;
    document.getElementById('edit-priority').value = task.priority;
    document.getElementById('edit-status').value = task.status;
    document.getElementById('edit-due_date').value = task.due_date ? task.due_date.substring(0, 10) : '';
    document.getElementById('modal-edit').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('modal-edit').classList.add('hidden');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/gtd/resources/views/tasks/show.blade.php ENDPATH**/ ?>