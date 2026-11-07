<?php
session_start();

// Keamanan: Hanya bisa diakses oleh admin atau penghuni yang sudah login
if (!isset($_SESSION['user_kamar']) && (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin')) {
    die("Akses ditolak. Anda belum login.");
}

include 'koneksi.php';

if (!isset($_GET['id'])) {
    die("ID Pembayaran tidak ditemukan.");
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data pembayaran beserta nama penghuni dan nomor kamar
$query = mysqli_query($koneksi, "
    SELECT p.*, h.nama_penghuni, h.id_kamar 
    FROM pembayaran p 
    JOIN penghuni h ON p.id_penghuni = h.id_penghuni 
    WHERE p.id_pembayaran = '$id' AND p.status_pembayaran = 'lunas'
");

$data = mysqli_fetch_assoc($query);

// Cegah cetak jika data tidak ada atau belum lunas
if (!$data) {
    die("Data kwitansi tidak ditemukan atau tagihan belum dikonfirmasi LUNAS oleh admin.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi - <?= htmlspecialchars($data['nama_penghuni']) ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #f4f7f6;
            padding: 20px;
        }
        .kwitansi-box {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-top: 8px solid #5b9bd5; /* Warna biru kesukaanmu */
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #ccc;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            color: #243b64;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            color: #777;
        }
        .row-data {
            display: flex;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .label {
            width: 180px;
            font-weight: bold;
        }
        .titikdua {
            width: 20px;
        }
        .value {
            flex: 1;
            border-bottom: 1px dotted #999;
        }
        .nominal {
            background-color: #f1f6fa;
            border-left: 5px solid #5b9bd5;
            padding: 15px 20px;
            font-size: 22px;
            font-weight: bold;
            margin: 30px 0;
            display: inline-block;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }
        .ttd {
            text-align: center;
            width: 250px;
        }
        .ttd-nama {
            margin-top: 80px;
            font-weight: bold;
            text-decoration: underline;
        }
        /* Penyesuaian khusus saat di-print (menghilangkan warna background) */
        @media print {
            body { background: #fff; padding: 0; }
            .kwitansi-box { box-shadow: none; border: 2px solid #000; padding: 30px; }
            .header h2 { color: #000; }
            .nominal { background: #fff; border: 2px solid #000; }
        }
    </style>
</head>
<!-- Fungsi onload="window.print()" akan otomatis membuka pop-up cetak PDF/Printer -->
<body onload="window.print()">
    <div class="kwitansi-box">
        <div class="header">
            <h2>KWITANSI PEMBAYARAN</h2>
            <p>Sistem Informasi Kost Logic</p>
        </div>
        
        <div class="row-data">
            <div class="label">No. Transaksi</div>
            <div class="titikdua">:</div>
            <div class="value">#INV-<?= htmlspecialchars($data['id_pembayaran']) ?></div>
        </div>
        
        <div class="row-data">
            <div class="label">Telah terima dari</div>
            <div class="titikdua">:</div>
            <div class="value" style="text-transform: uppercase;">
                <?= htmlspecialchars($data['nama_penghuni']) ?> (Kamar <?= htmlspecialchars($data['id_kamar']) ?>)
            </div>
        </div>

        <div class="row-data">
            <div class="label">Untuk Pembayaran</div>
            <div class="titikdua">:</div>
            <div class="value">Tagihan Kost Bulan <?= htmlspecialchars($data['bulan_tagihan']) ?></div>
        </div>

        <div class="row-data">
            <div class="label">Metode Pembayaran</div>
            <div class="titikdua">:</div>
            <div class="value">Transfer</div>
        </div>

        <div class="row-data">
            <div class="label">Tanggal Bayar</div>
            <div class="titikdua">:</div>
            <div class="value"><?= date('d F Y', strtotime($data['tanggal_bayar'])) ?></div>
        </div>

        <div class="nominal">
            Uang Sebesar: Rp <?= number_format($data['nominal_tagihan'], 0, ',', '.') ?>
        </div>

        <div class="footer">
            <div class="ttd">
                <p>Cilegon, <?= date('d F Y') ?></p>
                <div class="ttd-nama">Admin Kost Logic</div>
            </div>
        </div>
    </div>
</body>
</html>