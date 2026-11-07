<?php
session_start();
if (!isset($_SESSION['user_kamar'])) { header("Location: login.php"); exit; }
include '../koneksi.php';
$id_kamar = $_SESSION['user_kamar'];
$q_penghuni = mysqli_query($koneksi, "SELECT * FROM penghuni WHERE id_kamar='$id_kamar'");
$data_penghuni = mysqli_fetch_assoc($q_penghuni);
$id_penghuni = $data_penghuni['id_penghuni'] ?? 0;

// ==========================================
// LOGIKA UPLOAD BUKTI (STATUS JADI MENUNGGU)
// ==========================================
if (isset($_POST['upload_bukti'])) {
    $id_pembayaran = $_POST['id_pembayaran'];
    $nama_file = $_FILES['bukti']['name'];
    $tmp_name = $_FILES['bukti']['tmp_name'];
    $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
    
    // Buat nama file unik
    $nama_baru = "bukti_".$id_pembayaran."_".time().".".$ext;
    $destination = "../uploads/".$nama_baru;

    if (move_uploaded_file($tmp_name, $destination)) {
        $tanggal_sekarang = date('Y-m-d');
        
        // UPDATE: Status diubah menjadi 'menunggu', bukan 'lunas'
        mysqli_query($koneksi, "UPDATE pembayaran SET bukti_bayar='$nama_baru', status_pembayaran='menunggu', tanggal_bayar='$tanggal_sekarang' WHERE id_pembayaran='$id_pembayaran'");
        
        $sukses = "Bukti berhasil dikirim! Menunggu konfirmasi Lunas dari Admin.";
    } else {
        $error = "Gagal mengupload file gambar.";
    }
}

include 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <div>
        <h3 class="fw-bold text-utama mb-0" style="color: #5b9bd5;">Halo, <?= htmlspecialchars($data_penghuni['nama_penghuni'] ?? 'Penghuni') ?>!</h3>
        <p class="text-secondary">Kamar <?= htmlspecialchars($id_kamar) ?></p>
    </div>
    <a href="ganti_password.php" class="btn btn-outline-primary">Ubah Password</a>
</div>

<!-- Notifikasi Berhasil/Gagal Upload -->
<?php if (isset($error)) echo "<div class='alert alert-danger small'>$error</div>"; ?>
<?php if (isset($sukses)) echo "<div class='alert alert-success small'>$sukses</div>"; ?>

<!-- Logika Peringatan Merah Jika Belum Bayar -->
<?php
$cek_tunggakan = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE id_penghuni='$id_penghuni' AND status_pembayaran='belum lunas'");
if (mysqli_num_rows($cek_tunggakan) > 0) {
    echo '<div class="alert alert-danger border-0 border-start border-5 border-danger shadow-sm mb-4">
            <strong>Peringatan:</strong> Anda memiliki tagihan kost yang belum dilunasi. Silakan cek tabel di bawah untuk melakukan pembayaran.
          </div>';
}
?>

<div class="card shadow-sm p-2">
    <div class="card-body">
        <h5 class="fw-bold text-utama mb-4">Riwayat Tagihan & Pembayaran</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Bulan Tagihan</th>
                        <th>Nominal</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th> 
                    </tr>
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
                                <?php } elseif ($b['status_pembayaran'] == 'menunggu') { ?>
                                    <span class="badge bg-warning text-dark px-3 py-2">Menunggu Konfirmasi</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger px-3 py-2">Belum Lunas</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if ($b['status_pembayaran'] == 'belum lunas') { ?>
                                    <!-- Tombol Pemicu Pop-up Upload -->
                                    <button type="button" class="btn btn-sm btn-primary" style="background-color: #5b9bd5; border:none;" data-bs-toggle="modal" data-bs-target="#modalBayar<?= $b['id_pembayaran'] ?>">
                                        Bayar & Upload
                                    </button>

                                    <!-- Pop-up Modal Upload Bukti -->
                                    <div class="modal fade" id="modalBayar<?= $b['id_pembayaran'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header text-white" style="background-color: #5b9bd5;">
                                                    <h5 class="modal-title">Pembayaran Tagihan <?= htmlspecialchars($b['bulan_tagihan']) ?></h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <div class="alert alert-info small">
                                                            <strong>Informasi Rekening Pembayaran:</strong><br>
                                                            Silakan transfer sebesar <b>Rp <?= number_format($b['nominal_tagihan'], 0, ',', '.') ?></b> ke:<br>
                                                            <b>BCA: 1234567890</b> a.n Pemilik Kost Logic
                                                        </div>
                                                        <input type="hidden" name="id_pembayaran" value="<?= $b['id_pembayaran'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small">Upload Bukti Transfer (JPG/PNG)</label>
                                                            <input type="file" name="bukti" class="form-control" accept="image/*" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="upload_bukti" class="btn btn-primary" style="background-color: #5b9bd5; border:none;">Kirim Bukti</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php } elseif ($b['status_pembayaran'] == 'menunggu') { ?>
                                    <span class="text-secondary small fw-bold">Diproses Admin</span>
                                <?php } elseif ($b['status_pembayaran'] == 'lunas') { ?>
                                    <!-- Tombol Cetak Kwitansi Muncul Jika Lunas -->
                                    <a href="../cetak_kwitansi.php?id=<?= $b['id_pembayaran'] ?>" target="_blank" class="btn btn-sm btn-dark">Cetak Kwitansi</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } } else { echo "<tr><td colspan='5' class='text-center py-4 text-secondary'>Belum ada riwayat tagihan.</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>