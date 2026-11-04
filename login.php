<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek_admin = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($cek_admin) > 0) {
    $_SESSION['role'] = 'admin';
    $_SESSION['login'] = true;

    header("Location: index.php");
    exit;
}
    {
        echo "<script>alert('Username atau Password Admin Salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Admin - Kos Logic</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #243b64;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 35px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn-login {
            background: #5f90eb;
            color: white;
        }

        .btn-login:hover {
            background: #646569;
            color: white;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">Kos Logic</h3>
            <p class="text-secondary">login admin</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username"
                    class="form-control" placeholder="Masukkan username" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password"
                    class="form-control" placeholder="Masukkan password" required>
            </div>

            <button type="submit" name="login"
                class="btn btn-login w-100">
                Login
            </button>

        </form>

    </div>

</body>
</html>