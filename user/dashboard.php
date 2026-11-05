<?php
session_start();
if (!isset($_SESSION['user_kamar'])) { header("Location: login.php"); exit; }
include '../koneksi.php';
$id_kamar = $_SESSION['user_kamar'];
$q_penghuni = mysqli_query($koneksi, "SELECT * FROM penghuni WHERE id_kamar='$id_kamar'");
$data_penghuni = mysqli_fetch_assoc($q_penghuni);
$id_penghuni = $data_penghuni['id_penghuni'] ?? 0;
include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-utama mb-0">Halo, <?= htmlspecialchars($data_penghuni['nama_penghuni'] ?? 'Penghuni') ?>!</h3>
        <p class="text-secondary">Kamar <?= htmlspecialchars($id_kamar) ?></p>
    </div>
    <a href="ganti_password.php" class="btn btn-outline-primary">Ubah Password</a>
</div>
<div class="card shadow-sm p-2">
    <div class="card-body">
        <h5 class="fw-bold text-utama mb-4">Riwayat Tagihan & Pembayaran</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Bulan Tagihan</th><th>Nominal</th><th>Tanggal Bayar</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php
                    $q_bayar = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE id_penghuni='$id_penghuni' ORDER BY id_pembayaran DESC");
                    if (mysqli_num_rows($q_bayar) > 0) {
                        while ($b = mysqli_fetch_assoc($q_bayar)) {
                    ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($b['bulan_tagihan']) ?></td>
                            <td>Rp <?= number_format($b['nominal_tagihan'], 0, ',', '.') ?></td>
                            <td><?= ($b['tanggal_bayar'] ?? '-') ?></td>
                            <td>
                                <?php if ($b['status_pembayaran'] == 'lunas') { ?>
                                    <span class="badge bg-success px-3 py-2">Lunas</span>
                                <?php } else { ?>
                                    <span class="badge bg-warning text-dark px-3 py-2">Belum Lunas</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } } else { echo "<tr><td colspan='4' class='text-center py-4 text-secondary'>Belum ada riwayat tagihan.</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>