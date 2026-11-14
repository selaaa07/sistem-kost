<?php 
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// ==========================================================
// AUTO-BILLING SEMUA PENGHUNI AKTIF (TERBIT TIAP TANGGAL 1)
// ==========================================================
$daftar_bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
$bulan_sekarang = $daftar_bulan[(int)date('n')] . ' ' . date('Y');

$q_semua_penghuni = mysqli_query($koneksi, "SELECT p.id_penghuni, k.harga FROM penghuni p JOIN kamar k ON p.id_kamar = k.id_kamar");
while ($ph = mysqli_fetch_assoc($q_semua_penghuni)) {
    $id_p    = $ph['id_penghuni'];
    $harga_k = $ph['harga'];
    $cek     = mysqli_query($koneksi, "SELECT id_pembayaran FROM pembayaran WHERE id_penghuni='$id_p' AND bulan_tagihan='$bulan_sekarang'");
    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($koneksi, "INSERT INTO pembayaran (id_penghuni, bulan_tagihan, nominal_tagihan, status_pembayaran, tanggal_bayar, bukti_bayar) 
                                VALUES ('$id_p', '$bulan_sekarang', '$harga_k', 'belum lunas', NULL, NULL)");
    }
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'lunas' && isset($_GET['id'])) {
    $id_bayar = mysqli_real_escape_string($koneksi, $_GET['id']);
    $update_query = "UPDATE pembayaran SET status_pembayaran = 'lunas' WHERE id_pembayaran = '$id_bayar'";
    mysqli_query($koneksi, $update_query);
    
    echo "<script>alert('Pembayaran berhasil diverifikasi menjadi LUNAS!'); window.location='pembayaran_tampil.php';</script>";
    exit;
}

include 'header.php'; 
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="text-primary fw-semibold small mb-2">KELOLA DATA KOST</p>
        <h2 class="fw-bold mb-2" style="color: #243b64;">Riwayat Tagihan & Pembayaran</h2>
        <p class="text-secondary mb-0">Informasi Pembayaran</p>
    </div>

    <a href="pembayaran_tambah.php" class="btn btn-primary px-4 py-2" style="background-color: #5b9bd5; border:none;">
        + Catat Pembayaran Baru
    </a>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped align-middle mb-0">
        <thead class="align-middle">
            <tr>
                <th>ID Pembayaran</th>
                <th>Nama Penghuni</th>
                <th>Tanggal Bayar</th>
                <th>Bulan Tagihan</th>
                <th>Nominal Tagihan</th>
                <th>Bukti / Metode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT pembayaran.*, penghuni.nama_penghuni, penghuni.tanggal_masuk 
                    FROM pembayaran 
                    LEFT JOIN penghuni ON pembayaran.id_penghuni = penghuni.id_penghuni 
                    ORDER BY pembayaran.id_pembayaran DESC";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $nominal_rupiah = number_format($row['nominal_tagihan'], 0, ',', '.');
                $status_row = strtolower($row['status_pembayaran'] ?? '');
        ?>
            <tr>
                <td class="fw-semibold">
                    <?= htmlspecialchars($row['id_pembayaran']) ?>
                </td>

                <td>
                    <?php if ($row['nama_penghuni'] !== null) { ?>
                        <?= htmlspecialchars($row['nama_penghuni']) ?>
                    <?php } else { ?>
                        <span class="text-danger small">Penghuni Sudah Dihapus</span>
                    <?php } ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['tanggal_bayar'] ?? '-') ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['bulan_tagihan']) ?>
                </td>

                <td class="fw-semibold">
                    Rp <?= $nominal_rupiah ?>
                </td>

                <td>
                    <?php if (!empty($row['bukti_bayar'])) { ?>
                        <a href="uploads/<?= htmlspecialchars($row['bukti_bayar']) ?>" target="_blank" class="btn btn-sm btn-info text-white" style="background-color: #5b9bd5; border:none;">Lihat Bukti</a>
                    <?php } elseif ($status_row === 'menunggu' || $status_row === 'lunas') { ?>
                        <span class="badge bg-warning text-dark border px-3 py-2">Pembayaran Tunai</span>
                    <?php } else { ?>
                        <span class="text-secondary small">Belum ada data</span>
                    <?php } ?>
                </td>

                <td>
                    <?php 
                    $status_bayar = strtolower($row['status_pembayaran'] ?? '');
                    if ($status_bayar === 'lunas') { 
                    ?>
                        <span class="badge rounded-pill text-bg-success px-3 py-2">Lunas</span>
                    <?php 
                    } elseif ($status_bayar === 'menunggu') { 
                    ?>
                        <span class="badge rounded-pill text-bg-warning px-3 py-2">Menunggu</span>
                    <?php 
                    } else { 
                        // Hitung tenggat waktu per penghuni berdasarkan tanggal_masuk
                        $tgl_masuk_p        = $row['tanggal_masuk'] ?? '2026-10-01';
                        $hari_tenggat       = (int)date('d', strtotime($tgl_masuk_p));
                        $hari_tenggat_valid = min($hari_tenggat, (int)date('t'));
                        $tgl_jatuh_tempo    = date('Y-m-') . str_pad($hari_tenggat_valid, 2, '0', STR_PAD_LEFT);
                        $sisa_hari          = (int)round((strtotime($tgl_jatuh_tempo) - strtotime(date('Y-m-d'))) / 86400);
                    ?>
                        <span class="badge rounded-pill text-bg-danger px-3 py-2">Belum lunas</span>
                        <div class="mt-1 small">
                            <?php if ($row['bulan_tagihan'] == $bulan_sekarang) { ?>
                                <?php if ($sisa_hari > 0) { ?>
                                    <span class="text-primary fw-semibold">
                                        ⏳ Tenggat tgl <?= $hari_tenggat_valid ?> (<?= $sisa_hari ?> hari lagi)
                                    </span>
                                <?php } elseif ($sisa_hari == 0) { ?>
                                    <span class="text-warning fw-bold">
                                        ⚠️ Jatuh tempo hari ini (tgl <?= $hari_tenggat_valid ?>)
                                    </span>
                                <?php } else { ?>
                                    <span class="text-danger fw-bold">
                                        🚨 Lewat <?= abs($sisa_hari) ?> hari (tenggat tgl <?= $hari_tenggat_valid ?>)
                                    </span>
                                <?php } ?>
                            <?php } else { ?>
                                <span class="text-danger fw-bold">🚨 Menunggak bulan lalu</span>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </td>

                <td>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if ($status_row !== 'lunas') { ?>
                            <a href="pembayaran_tampil.php?aksi=lunas&id=<?= urlencode($row['id_pembayaran']) ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Ubah status menjadi Lunas?')">Verifikasi Lunas</a>
                        <?php } else { ?>
                            <a href="cetak_kwitansi.php?id=<?= urlencode($row['id_pembayaran']) ?>" target="_blank" class="btn btn-sm btn-dark">Cetak Kwitansi</a>
                        <?php } ?>
                            <a href="pembayaran_edit.php?id=<?= urlencode($row['id_pembayaran']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="pembayaran_hapus.php?id=<?= urlencode($row['id_pembayaran']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </div>
                </td>
            </tr>
        <?php
            }
        } elseif ($result) {
        ?>
            <tr>
                <td colspan="8" class="text-center py-5">
                    <h5 class="fw-semibold">Belum ada riwayat transaksi</h5>
                    <p class="text-secondary mb-3">Data pembayaran yang tercatat akan muncul di sini.</p>
                    <a href="pembayaran_tambah.php" class="btn btn-primary" style="background-color: #5b9bd5; border:none;">+ Catat Pembayaran</a>
                </td>
            </tr>
        <?php
        } else {
        ?>
            <tr>
                <td colspan="8" class="text-center text-danger py-4">Data pembayaran gagal dimuat.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<br>
<a href="index.php" class="btn btn-secondary">Kembali</a>

<?php include 'footer.php'; ?>