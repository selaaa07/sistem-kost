<?php
session_start();
if (!isset($_SESSION['user_kamar'])) { header("Location: login.php"); exit; }
include '../koneksi.php';
$id_kamar = mysqli_real_escape_string($koneksi, $_SESSION['user_kamar']);

// Simpan komplain, lalu REDIRECT (Post/Redirect/Get)
if (isset($_POST['kirim_komplain'])) {
    $keluhan = mysqli_real_escape_string($koneksi, trim($_POST['keluhan']));
    $tgl = date('Y-m-d');

    if ($keluhan !== '') {
        $insert = mysqli_query($koneksi, "INSERT INTO komplain (id_kamar, keluhan, tanggal_lapor, status_komplain) VALUES ('$id_kamar', '$keluhan', '$tgl', 'Menunggu Proses')");
        $_SESSION['flash'] = $insert
            ? ['sukses', 'Laporan kerusakan berhasil dikirim ke Admin.']
            : ['error', 'Gagal mengirim laporan.'];
    }

    header("Location: komplain.php");
    exit;
}

// Ambil pesan sekali pakai setelah redirect
$sukses = $error = null;
if (isset($_SESSION['flash'])) {
    [$tipe, $pesan] = $_SESSION['flash'];
    if ($tipe === 'sukses') { $sukses = $pesan; } else { $error = $pesan; }
    unset($_SESSION['flash']);
}

include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <div>
        <h3 class="fw-bold mb-0" style="color: #5b9bd5;">Lapor Kerusakan / Komplain</h3>
        <p class="text-secondary">Kamar <?= htmlspecialchars($id_kamar) ?></p>
    </div>
</div>

<?php if ($sukses) echo "<div class='alert alert-success alert-dismissible fade show'>" . htmlspecialchars($sukses) . " <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>
<?php if ($error) echo "<div class='alert alert-danger alert-dismissible fade show'>" . htmlspecialchars($error) . " <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>

<div class="card shadow-sm p-3 mb-5 border-top border-4" style="border-color: #5b9bd5 !important;">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Form Laporan</h5>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Detail Keluhan / Kerusakan</label>
                <textarea name="keluhan" class="form-control" rows="4" placeholder="Contoh: AC bocor, lampu kamar mandi mati, atau keran air rusak..." required></textarea>
            </div>
            <button type="submit" name="kirim_komplain" class="btn btn-primary px-4" style="background-color: #5b9bd5; border:none;">Kirim Laporan</button>
        </form>
    </div>
</div>

<div class="card shadow-sm p-3">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Riwayat Komplain Kamar <?= htmlspecialchars($id_kamar) ?></h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q_komplain = mysqli_query($koneksi, "SELECT * FROM komplain WHERE id_kamar='$id_kamar' ORDER BY id_komplain DESC");
                    if (mysqli_num_rows($q_komplain) > 0) {
                        while ($k = mysqli_fetch_assoc($q_komplain)) {
                    ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($k['tanggal_lapor'])) ?></td>
                            <td><?= nl2br(htmlspecialchars($k['keluhan'])) ?></td>
                            <td>
                                <?php if ($k['status_komplain'] == 'Selesai') { ?>
                                    <span class="badge bg-success px-3 py-2">Selesai</span>
                                <?php } else { ?>
                                    <span class="badge bg-warning text-dark px-3 py-2">Menunggu Proses</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } } else { echo "<tr><td colspan='3' class='text-center py-4 text-secondary'>Belum ada riwayat komplain.</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>