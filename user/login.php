<?php
session_start();
include '../koneksi.php';
if (isset($_POST['login'])) {
    $id_kamar = mysqli_real_escape_string($koneksi, $_POST['id_kamar']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $cek = mysqli_query($koneksi, "SELECT * FROM kamar WHERE id_kamar='$id_kamar' AND password='$password' AND status_kamar='terisi'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['user_kamar'] = $id_kamar;
        header("Location: dashboard.php"); exit;
    } else {
        $error = "ID Kamar atau Password salah, atau kamar kosong!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Penghuni - Kost Logic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #243b64; min-height: 100vh; display: flex; align-items: center; }
        .login-card { width: 100%; max-width: 400px; padding: 35px; background: white; border-radius: 15px; margin: auto; }
        .btn-utama { background: #5f90eb; color: white; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card shadow-lg">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color: #243b64;">Kost Logic</h3>
                <p class="text-secondary small">Login khusus penghuni kost</p>
            </div>
            <?php if (isset($error)) echo "<div class='alert alert-danger small'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Nomor Kamar</label>
                    <input type="number" name="id_kamar" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-utama w-100 mb-3 py-2">Masuk</button>
                <div class="text-center"><a href="index.php" class="text-decoration-none small text-secondary">Kembali ke Beranda</a></div>
            </form>
        </div>
    </div>
</body>
</html>