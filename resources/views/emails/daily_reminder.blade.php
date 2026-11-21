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
            <h1>☀️ Selamat Pagi, {{ $employeeName }}!</h1>
            <p>Waktunya sikat habis tugas-tugasmu hari ini!</p>
        </div>
        
        <div class="content">
            @if($tasks->count() > 0)
                <p>Berikut adalah daftar tugas yang menunggu eksekusimu:</p>
                <div class="task-list">
                    @foreach($tasks as $task)
                        <div class="task-item">
                            <strong>{{ $task->title }}</strong> 
                            <span class="{{ $task->priority == 'High' ? 'priority-high' : '' }}">
                                [{{ $task->priority }}]
                            </span>
                            <br>
                            <small>{{ $task->description }}</small>
                        </div>
                    @endforeach
                </div>
            @else
                <p>Hore! Tidak ada tugas mendesak hari ini. Nikmati kopimu! ☕</p>
            @endif
        </div>

        <div class="footer">
            Kirim dengan kasih sayang oleh GTD Assistant 🤖
        </div>
    </div>
</body>
</html>
