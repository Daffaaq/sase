<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #4facfe;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .email-body {
            padding: 20px;
        }
        .email-body p {
            margin: 0 0 10px;
        }
        .email-footer {
            text-align: center;
            color: #888888;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Detail Pengiriman Surat</h1>
        </div>
        <div class="email-body">
            <p><strong>Nama Pengirim:</strong> {{ $nama_pengirim }}</p>
            <p><strong>Instansi Pengirim:</strong> {{ $instansi_pengirim }}</p>
            <p><strong>Nomor Telepon Pengirim:</strong> {{ $no_telp_pengirim }}</p>
            <p><strong>Nomor Surat:</strong> {{ $no_surat }}</p>
            <p><strong>Deskripsi Surat:</strong> {{ $deskripsi_surat }}</p>
            <p>Terima kasih telah menggunakan layanan kami.</p>
        </div>
        <div class="email-footer">
            <p>Mohon cek email secara berkala untuk melihat status surat.</p>
        </div>
    </div>
</body>
</html>
