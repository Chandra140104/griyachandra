<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kwitansi Pembayaran - Griya Chandra</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333 !important;
            background-color: #ffffff !important;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: #ffffff !important;
            border: 1px solid #eee;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #F53003;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #F53003;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #777;
        }
        .receipt-info {
            margin-bottom: 30px;
        }
        .receipt-info table {
            width: 100%;
        }
        .receipt-info td {
            padding: 5px 0;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table th {
            background: #f9f9f9;
            border: 1px solid #eee;
            padding: 12px;
            text-align: left;
        }
        .details-table td {
            border: 1px solid #eee;
            padding: 12px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .signature-space {
            margin-top: 60px;
            border-top: 1px solid #333;
            display: inline-block;
            width: 200px;
            text-align: center;
            padding-top: 5px;
        }
        .total-row {
            font-weight: bold;
            background: #fdfdfd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Griya Chandra</h1>
            <p>Hunian Nyaman & Eksklusif</p>
        </div>

        <div class="receipt-info">
            <table>
                <tr>
                    <td style="width: 150px;">No. Kwitansi</td>
                    <td>: #{{ date('Ymd') }}{{ $user->id }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ $user->contract_start ? $user->contract_start->format('d F Y') : date('d F Y') }}</td>
                </tr>
            </table>
        </div>

        <h3 style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Detail Pembayaran</h3>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nama Penyewa</td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td>Nomor Kamar</td>
                    <td>{{ $user->room->room_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tipe Kamar</td>
                    <td>{{ $user->room->type ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Periode Sewa</td>
                    <td>{{ $user->contract_start ? $user->contract_start->format('d M Y, H:i') : '-' }} s/d {{ $user->contract_end ? $user->contract_end->format('d M Y, H:i') : '-' }}</td>
                </tr>
                <tr>
                    <td>Status Pembayaran</td>
                    <td style="font-weight: bold; color: #10b981;">LUNAS</td>
                </tr>
                <tr class="total-row">
                    <td>Total Tagihan</td>
                    <td style="color: #F53003;">Rp {{ number_format($user->room->price ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 12px; color: #777; margin-top: 40px;">* Dokumen ini sah dan diterbitkan secara otomatis oleh sistem Griya Chandra.</p>

        <div class="footer">
            <p>Jember, {{ $user->contract_start ? $user->contract_start->format('d F Y') : date('d F Y') }}</p>
            <p style="margin-bottom: 0;">Pengelola,</p>
            <div class="signature-space">
                Griya Chandra Admin
            </div>
        </div>
    </div>
</body>
</html>
