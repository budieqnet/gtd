<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; border: 1px solid #eee; padding: 20px; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; margin-bottom: 20px; }
        .task-item { padding: 10px; border-bottom: 1px solid #eee; }
        .priority-high { color: red; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>☀️ Selamat Pagi, <?php echo e($employeeName); ?>!</h1>
            <p>Waktunya sikat habis tugas-tugasmu hari ini!</p>
        </div>
        
        <div class="content">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tasks->count() > 0): ?>
                <p>Berikut adalah daftar tugas yang menunggu eksekusimu:</p>
                <div class="task-list">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="task-item">
                            <strong><?php echo e($task->title); ?></strong> 
                            <span class="<?php echo e($task->priority == 'High' ? 'priority-high' : ''); ?>">
                                [<?php echo e($task->priority); ?>]
                            </span>
                            <br>
                            <small><?php echo e($task->description); ?></small>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php else: ?>
                <p>Hore! Tidak ada tugas mendesak hari ini. Nikmati kopimu! ☕</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="footer">
            Kirim dengan kasih sayang oleh GTD Assistant 🤖
        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/gtd/resources/views/emails/daily_reminder.blade.php ENDPATH**/ ?>