<?php 
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; 

// Notifikasi dan Update Status Lunas
if (isset($_GET['aksi']) && $_GET['aksi'] == 'lunas' && isset($_GET['id'])) {
    $id_bayar = mysqli_real_escape_string($koneksi, $_GET['id']);
    $update_query = "UPDATE pembayaran SET status_pembayaran = 'lunas' WHERE id_pembayaran = '$id_bayar'";
    mysqli_query($koneksi, $update_query);
    
    // Memunculkan pesan pop-up berhasil lalu me-refresh halaman
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
                <th>Bukti Transfer</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT pembayaran.*, penghuni.nama_penghuni 
                    FROM pembayaran 
                    LEFT JOIN penghuni ON pembayaran.id_penghuni = penghuni.id_penghuni 
                    ORDER BY pembayaran.id_pembayaran DESC";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $nominal_rupiah = number_format($row['nominal_tagihan'], 0, ',', '.');
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
                    <?php } else { ?>
                        <span class="text-secondary small">Belum ada bukti</span>
                    <?php } ?>
                </td>

                <td>
                    <?php if (strtolower($row['status_pembayaran']) === 'lunas') { ?>
                        <span class="badge rounded-pill text-bg-success px-3 py-2">Lunas</span>
                    <?php } else { ?>
                        <span class="badge rounded-pill text-bg-warning px-3 py-2">
                            <?= htmlspecialchars(ucfirst($row['status_pembayaran'])) ?>
                        </span>
                    <?php } ?>
                </td>

                <td>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (strtolower($row['status_pembayaran']) != 'lunas') { ?>
                            <!-- Tombol Verifikasi jika belum lunas -->
                            <a href="pembayaran_tampil.php?aksi=lunas&id=<?= urlencode($row['id_pembayaran']) ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Ubah status menjadi Lunas?')">Verifikasi Lunas</a>
                        <?php } else { ?>
                            <!-- Tombol Cetak Kwitansi Muncul Otomatis saat Lunas -->
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