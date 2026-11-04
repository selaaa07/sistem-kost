<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek_admin = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($cek_admin) > 0) {
        $_SESSION['role'] = 'admin';
        header("Location: index.php"); 
        exit;
    } else {
        echo "<script>alert('Username atau Password Admin Salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head><title>Login Admin - Kos Logic</title></head>
<body>
    <h2>Login Admin Kos Logic</h2>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>