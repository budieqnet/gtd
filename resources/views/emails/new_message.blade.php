<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .message-box { background: #f8fafc; border-left: 5px solid #3b82f6; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .footer { background: #f1f5f9; padding: 20px; text-align: center; color: #6b7280; font-size: 14px; }
        .btn { display: inline-block; background: #3b82f6; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; margin-top: 20px; font-weight: bold; }
        .message { color: #4b5563; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Pesan Baru!</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">Anda menerima pesan dari {{ $senderName }}</p>
        </div>

        <div class="content">
            <p class="message">Halo!</p>
            <p><strong>{{ $senderName }}</strong> telah mengirim pesan kepada Anda melalui aplikasi GTD:</p>

            <div class="message-box">
                <p style="color: #374151; font-style: italic; margin: 0;">
                    {{ $messageText ?: '(Mengirim file/lampiran)' }}
                </p>
            </div>

            <p style="margin-top: 25px;">Silakan login ke aplikasi GTD untuk membalas pesan.</p>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/messenger?conversation=' . $conversationId) }}" class="btn">💬 Buka Pesan</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} GTD App - Get Things Done</p>
            <p style="margin: 5px 0 0;">Jangan balas email ini. Balas melalui aplikasi GTD.</p>
        </div>
    </div>
</body>
</html>
