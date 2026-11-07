<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Proses ubah status menjadi Selesai
if (isset($_GET['aksi']) && $_GET['aksi'] == 'selesai' && isset($_GET['id'])) {
    $id_komplain = mysqli_real_escape_string($koneksi, $_GET['id']);
    mysqli_query($koneksi, "UPDATE komplain SET status_komplain='Selesai' WHERE id_komplain='$id_komplain'");
    echo "<script>alert('Status komplain berhasil ditandai Selesai!'); window.location='komplain_tampil.php';</script>";
    exit;
}

include 'header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 mt-3">
    <div>
        <p class="text-primary fw-semibold small mb-2" style="color: #5b9bd5 !important;">KELOLA DATA KOST</p>
        <h2 class="fw-bold mb-2" style="color: #243b64;">Data Komplain Penghuni</h2>
        <p class="text-secondary mb-0">Daftar laporan kerusakan dari penghuni kost</p>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th class="py-3">Tanggal Lapor</th>
                <th class="py-3">Kamar</th>
                <th class="py-3">Detail Keluhan</th>
                <th class="py-3">Status</th>
                <th class="py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Urutkan agar yang 'Menunggu Proses' tampil di paling atas
            $query = mysqli_query($koneksi, "SELECT * FROM komplain ORDER BY CASE WHEN status_komplain = 'Menunggu Proses' THEN 1 ELSE 2 END, id_komplain DESC");
            
            if (mysqli_num_rows($query) > 0) {
                while ($r = mysqli_fetch_assoc($query)) {
            ?>
                <tr>
                    <td><?= date('d F Y', strtotime($r['tanggal_lapor'])) ?></td>
                    <td class="fw-bold">Kamar <?= htmlspecialchars($r['id_kamar']) ?></td>
                    <td style="max-width: 300px;"><?= nl2br(htmlspecialchars($r['keluhan'])) ?></td>
                    <td>
                        <?php if ($r['status_komplain'] == 'Selesai') { ?>
                            <span class="badge rounded-pill text-bg-success px-3 py-2">Selesai</span>
                        <?php } else { ?>
                            <span class="badge rounded-pill text-bg-warning px-3 py-2">Menunggu Proses</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($r['status_komplain'] != 'Selesai') { ?>
                            <a href="komplain_tampil.php?aksi=selesai&id=<?= urlencode($r['id_komplain']) ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Tandai komplain kamar <?= $r['id_kamar'] ?> sudah selesai diperbaiki?')">
                                Tandai Selesai
                            </a>
                        <?php } else { ?>
                            <span class="text-success small fw-bold">✓ Sudah Diperbaiki</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } } else { ?>
                <tr><td colspan="5" class="text-center py-5 text-secondary">Belum ada data komplain masuk.</td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<br>
<a href="index.php" class="btn btn-secondary">Kembali ke Dashboard</a>

<?php include 'footer.php'; ?>