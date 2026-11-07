<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kost Logic - Area Penghuni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f5f7fb; color: #243047; }
        .navbar { background-color: #243b64; }
        .text-utama { color: #243b64; }
        .btn-utama { background-color: #243b64; color: white; border: none; }
        .btn-utama:hover { background-color: #1a2a47; color: white; }
        .card { border: none; border-radius: 16px; }
    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<body>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Kost Logic</a>
        <div class="ms-auto">
            <?php if(isset($_SESSION['user_kamar'])) { ?>
                <a href="dashboard.php" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
                <!-- Tambahan Menu Lapor Kerusakan -->
                <a href="komplain.php" class="btn btn-outline-light btn-sm me-2">Lapor Kerusakan</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            <?php } else { ?>
                <a href="login.php" class="btn btn-outline-light btn-sm">Login Penghuni Kost</a>
            <?php } ?>
        </div>
    </div>
</nav>
<main class="container py-3">