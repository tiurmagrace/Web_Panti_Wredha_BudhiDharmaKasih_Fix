<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1a5c7a;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #1a5c7a;
            margin: 0;
            font-size: 24px;
        }
        .content {
            text-align: center;
        }
        .code-box {
            background: #1a5c7a;
            color: white;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            padding: 20px 30px;
            border-radius: 8px;
            margin: 20px 0;
            display: inline-block;
        }
        .message {
            color: #666;
            line-height: 1.6;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Panti Wredha Budi Dharma Kasih</h1>
        </div>
        
        <div class="content">
            <p class="message">Halo <strong>{{ $userName }}</strong>,</p>
            <p class="message">Anda telah meminta untuk mereset password akun Anda. Gunakan kode verifikasi berikut:</p>
            
            <div class="code-box">{{ $code }}</div>
            
            <p class="message">Masukkan kode ini di halaman verifikasi untuk melanjutkan proses reset password.</p>
            
            <div class="warning">
                ⚠️ Kode ini hanya berlaku selama <strong>15 menit</strong>. Jangan bagikan kode ini kepada siapapun.
            </div>
        </div>
        
        <div class="footer">
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            <p>&copy; {{ date('Y') }} Panti Wredha Budi Dharma Kasih</p>
        </div>
    </div>
</body>
</html>
