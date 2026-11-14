<?php
session_start();
if (!isset($_SESSION['user_kamar'])) { header("Location: login.php"); exit; }
include '../koneksi.php';
$id_kamar = mysqli_real_escape_string($koneksi, $_SESSION['user_kamar']);
$q_penghuni = mysqli_query($koneksi, "SELECT * FROM penghuni WHERE id_kamar='$id_kamar'");
$data_penghuni = mysqli_fetch_assoc($q_penghuni);
$id_penghuni = (int) ($data_penghuni['id_penghuni'] ?? 0);

if (isset($_POST['proses_bayar'])) {
    $id_pembayaran = (int) ($_POST['id_pembayaran'] ?? 0);
    $metode = $_POST['metode'] ?? '';
    $tanggal_sekarang = date('Y-m-d');

    $cek_milik = mysqli_query($koneksi, "SELECT id_pembayaran FROM pembayaran WHERE id_pembayaran='$id_pembayaran' AND id_penghuni='$id_penghuni' AND status_pembayaran='belum lunas'");

    if (mysqli_num_rows($cek_milik) == 0) {
        $error = "Tagihan tidak valid atau sudah diproses.";
    } elseif ($metode == 'Transfer') {
        $nama_file = $_FILES['bukti']['name'] ?? '';
        $tmp_name  = $_FILES['bukti']['tmp_name'] ?? '';

        if (!empty($nama_file)) {
            $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {
                $error = "Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.";
            } else {
                if (!is_dir("../uploads")) {
                    mkdir("../uploads", 0777, true);
                }

                $nama_baru = "bukti_" . $id_pembayaran . "_" . time() . "." . $ext;
                $destination = "../uploads/" . $nama_baru;

                if (move_uploaded_file($tmp_name, $destination)) {
                    mysqli_query($koneksi, "UPDATE pembayaran SET bukti_bayar='$nama_baru', status_pembayaran='menunggu', tanggal_bayar='$tanggal_sekarang' WHERE id_pembayaran='$id_pembayaran'");
                    $sukses = "Bukti transfer berhasil dikirim! Menunggu konfirmasi Lunas dari Admin.";
                } else {
                    $error = "Gagal mengupload file gambar.";
                }
            }
        } else {
            $error = "Bukti transfer wajib diupload jika memilih metode Transfer.";
        }
    } elseif ($metode == 'Tunai') {
        // Jika Tunai, set status menunggu dan kosongkan kolom bukti (NULL)
        mysqli_query($koneksi, "UPDATE pembayaran SET bukti_bayar=NULL, status_pembayaran='menunggu', tanggal_bayar='$tanggal_sekarang' WHERE id_pembayaran='$id_pembayaran'");
        $sukses = "Metode Tunai dipilih! Silakan serahkan uang fisik ke Admin Kost agar dapat diverifikasi Lunas.";
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

<?php if (isset($error)) echo "<div class='alert alert-danger small'>" . htmlspecialchars($error) . "</div>"; ?>
<?php if (isset($sukses)) echo "<div class='alert alert-success small'>" . htmlspecialchars($sukses) . "</div>"; ?>

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
                            <td><?= htmlspecialchars($b['tanggal_bayar'] ?? '-') ?></td>
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
                                    <button type="button" class="btn btn-sm btn-primary" style="background-color: #5b9bd5; border:none;" data-bs-toggle="modal" data-bs-target="#modalBayar<?= $b['id_pembayaran'] ?>">
                                        Bayar Kost
                                    </button>

                                    <div class="modal fade" id="modalBayar<?= $b['id_pembayaran'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header text-white" style="background-color: #5b9bd5;">
                                                    <h5 class="modal-title">Pembayaran <?= htmlspecialchars($b['bulan_tagihan']) ?></h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <div class="alert alert-info small mb-3">
                                                            Jumlah Tagihan: <b>Rp <?= number_format($b['nominal_tagihan'], 0, ',', '.') ?></b>
                                                        </div>
                                                        <input type="hidden" name="id_pembayaran" value="<?= $b['id_pembayaran'] ?>">
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small">Pilih Metode Pembayaran</label>
                                                            <select name="metode" class="form-select" id="pilihMetode<?= $b['id_pembayaran'] ?>" onchange="toggleUpload<?= $b['id_pembayaran'] ?>()" autocomplete="off" required>
                                                                <option value="Transfer">Transfer Bank</option>
                                                                <option value="Tunai">Uang Tunai (Cash)</option>
                                                            </select>
                                                        </div>

                                                        <div id="areaTransfer<?= $b['id_pembayaran'] ?>">
                                                            <div class="alert alert-secondary small p-2 mb-2">
                                                                Transfer ke: <b>BCA 1234567890</b> a.n Pemilik Kost
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold small">Upload Bukti Transfer (JPG/PNG)</label>
                                                                <input type="file" name="bukti" id="inputBukti<?= $b['id_pembayaran'] ?>" class="form-control" accept=".jpg,.jpeg,.png" required>
                                                            </div>
                                                        </div>

                                                        <div id="areaTunai<?= $b['id_pembayaran'] ?>" style="display:none;">
                                                            <div class="alert alert-warning small mb-0">
                                                                Anda memilih pembayaran Tunai. Silakan klik tombol <b>Konfirmasi Bayar</b> di bawah ini, lalu serahkan uang fisik sejumlah tagihan langsung ke Admin Kost.
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="proses_bayar" class="btn btn-primary" style="background-color: #5b9bd5; border:none;">Konfirmasi Bayar</button>
                                                    </div>
                                                </form>

                                                <!-- Script Toggle Dinamis -->
                                                <script>
                                                function toggleUpload<?= $b['id_pembayaran'] ?>() {
                                                    var metode = document.getElementById("pilihMetode<?= $b['id_pembayaran'] ?>").value;
                                                    var areaTransfer = document.getElementById("areaTransfer<?= $b['id_pembayaran'] ?>");
                                                    var areaTunai = document.getElementById("areaTunai<?= $b['id_pembayaran'] ?>");
                                                    var inputBukti = document.getElementById("inputBukti<?= $b['id_pembayaran'] ?>");
                                                    
                                                    if (metode === "Tunai") {
                                                        areaTransfer.style.display = "none";
                                                        areaTunai.style.display = "block";
                                                        inputBukti.removeAttribute("required"); // Tidak wajib upload jika tunai
                                                    } else {
                                                        areaTransfer.style.display = "block";
                                                        areaTunai.style.display = "none";
                                                        inputBukti.setAttribute("required", "required"); // Wajib upload jika transfer
                                                    }
                                                }

                                                document.getElementById("modalBayar<?= $b['id_pembayaran'] ?>").addEventListener("show.bs.modal", toggleUpload<?= $b['id_pembayaran'] ?>);
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                <?php } elseif ($b['status_pembayaran'] == 'menunggu') { ?>
                                    <span class="text-secondary small fw-bold">Diproses Admin</span>
                                <?php } elseif ($b['status_pembayaran'] == 'lunas') { ?>
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

<?php include 'footer.php'; ?>