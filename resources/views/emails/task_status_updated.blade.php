<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Tugas Diperbarui</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .task-box { background: #f8fafc; border-left: 5px solid #667eea; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .label { font-weight: bold; color: #374151; margin-bottom: 5px; display: block; }
        .status-change { display: flex; align-items: center; justify-content: center; gap: 16px; margin: 20px 0; }
        .status-old { background: #f3f4f6; color: #6b7280; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: bold; }
        .status-new { background: #dbeafe; color: #2563eb; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: bold; }
        .arrow { color: #9ca3af; font-size: 20px; }
        .footer { background: #f1f5f9; padding: 20px; text-align: center; color: #6b7280; font-size: 14px; }
        .btn { display: inline-block; background: #667eea; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; margin-top: 20px; font-weight: bold; }
        .message { color: #4b5563; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Status Tugas Diperbarui</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">Anggota tim Anda telah memperbarui status tugas</p>
        </div>
        <div class="content">
            <p class="message">Halo!</p>
            <p><strong>{{ $updatedByName }}</strong> telah memperbarui status tugas:</p>
            <div class="task-box">
                <span class="label">Judul Tugas:</span>
                <h2 style="margin: 10px 0; color: #1f2937;">{{ $taskTitle }}</h2>
                <div class="status-change">
                    <span class="status-old">{{ $oldStatus }}</span>
                    <span class="arrow">&rarr;</span>
                    <span class="status-new">{{ $newStatus }}</span>
                </div>
            </div>
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/tasks') }}" class="btn">Lihat Tugas</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GTD App - Get Things Done</p>
        </div>
    </div>
</body>
</html>
