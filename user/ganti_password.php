<?php
session_start();
if (!isset($_SESSION['user_kamar'])) { header("Location: login.php"); exit; }
include '../koneksi.php';
$id_kamar = $_SESSION['user_kamar'];

if (isset($_POST['ubah'])) {
    $password_lama = mysqli_real_escape_string($koneksi, $_POST['password_lama']);
    $password_baru = mysqli_real_escape_string($koneksi, $_POST['password_baru']);
    $cek = mysqli_query($koneksi, "SELECT * FROM kamar WHERE id_kamar='$id_kamar' AND password='$password_lama'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($koneksi, "UPDATE kamar SET password='$password_baru' WHERE id_kamar='$id_kamar'");
        $sukses = "Password berhasil diubah! Gunakan password baru untuk login selanjutnya.";
    } else {
        $error = "Password lama salah!";
    }
}
include 'header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm mt-3">
            <h4 class="fw-bold text-utama mb-1">Ganti Password Kamar</h4>
            <p class="text-secondary small mb-4">Pastikan password baru mudah diingat.</p>
            <?php if (isset($error)) echo "<div class='alert alert-danger small'>$error</div>"; ?>
            <?php if (isset($sukses)) echo "<div class='alert alert-success small'>$sukses</div>"; ?>
            <form method="POST">
                <div class="mb-3"><label class="form-label fw-semibold small">Password Lama</label><input type="password" name="password_lama" class="form-control" required></div>
                <div class="mb-4"><label class="form-label fw-semibold small">Password Baru</label><input type="password" name="password_baru" class="form-control" required></div>
                <div class="d-flex gap-2"><button type="submit" name="ubah" class="btn btn-utama px-4">Simpan</button><a href="dashboard.php" class="btn btn-outline-secondary px-4">Batal</a></div>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>