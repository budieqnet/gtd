<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Baru Ditugaskan</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .task-box { background: #f8fafc; border-left: 5px solid #667eea; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .priority-high { border-color: #ef4444 !important; background: #fef2f2 !important; }
        .priority-medium { border-color: #f59e0b !important; background: #fffbeb !important; }
        .priority-low { border-color: #10b981 !important; background: #f0fdf4 !important; }
        .label { font-weight: bold; color: #374151; margin-bottom: 5px; display: block; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-high { background: #ef4444; color: white; }
        .badge-medium { background: #f59e0b; color: white; }
        .badge-low { background: #10b981; color: white; }
        .footer { background: #f1f5f9; padding: 20px; text-align: center; color: #6b7280; font-size: 14px; }
        .btn { display: inline-block; background: #667eea; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; margin-top: 20px; font-weight: bold; }
        .message { color: #4b5563; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📋 Tugas Baru Ditugaskan!</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">Anda telah menerima tugas baru</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="message">Halo!</p>
            <p>Kamu telah ditugaskan oleh <strong><?php echo e($assignedFromName); ?></strong> untuk mengerjakan tugas berikut:</p>

            <div class="task-box <?php echo e('priority-' . strtolower($taskPriority)); ?>">
                <span class="label">🏷️ Judul Tugas:</span>
                <h2 style="margin: 10px 0; color: #1f2937;"><?php echo e($taskTitle); ?></h2>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($taskDescription): ?>
                    <span class="label">📝 Deskripsi:</span>
                    <p style="color: #4b5563; margin: 10px 0;"><?php echo e($taskDescription); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div style="margin-top: 20px;">
                    <span class="label">⚡ Prioritas:</span>
                    <span class="badge badge-<?php echo e(strtolower($taskPriority)); ?>"><?php echo e($taskPriority); ?></span>
                </div>

                <div style="margin-top: 15px;">
                    <span class="label">🕒 Deadline:</span>
                    <p style="color: #4b5563; margin: 5px 0 0;"><?php echo e($dueDate); ?></p>
                </div>
            </div>

            <p style="margin-top: 25px;">Silakan login ke aplikasi GTD untuk melihat detail lengkap dan memperbarui status tugas.</p>

            <div style="text-align: center; margin-top: 30px;">
                <a href="<?php echo e(url('/tasks')); ?>" class="btn">🚀 Buka Tugas Saya</a>
            </div>

            <p style="margin-top: 25px; font-size: 14px; color: #9ca3af;">*Tugas akan segera ditampilkan di dashboard Anda.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; <?php echo e(date('Y')); ?> GTD App - Get Things Done</p>
            <p style="margin: 5px 0 0;">Jangan balas email ini. Hubungi atasan Anda jika ada pertanyaan.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/gtd/resources/views/emails/task_assigned.blade.php ENDPATH**/ ?>