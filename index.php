<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Menghitung angka untuk ringkasan Dashboard
$kamar_total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM kamar"))['jml'];
$kamar_terisi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM kamar WHERE status_kamar='terisi'"))['jml'];
$kamar_kosong = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM kamar WHERE status_kamar='kosong'"))['jml'];
$menunggu_konfirmasi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pembayaran WHERE status_pembayaran='menunggu'"))['jml'];
$komplain_masuk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM komplain WHERE status_komplain='Menunggu Proses'"))['jml'];

include 'header.php';
?>

<div class="card welcome-card shadow-sm mb-4">
    <div class="card-body p-4 p-md-5">

        <p class="mb-3 text-white-50fw">
            INFORMASI DAN PENGELOLAAN KOST LOGIC (DASHBOARD)
        </p>

        <div class="row g-4 mb-4">
            
            <div class="col-md-3">
                <div class="card bg-primary text-white p-3 shadow-sm h-100" style="background-color: #5b9bd5 !important;">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Total Kamar</h5>
                        <h2 class="display-5 fw-bold"><?= $kamar_total ?></h2>
                        <a href="kamar_tampil.php" class="text-white text-decoration-none">Lihat Detail ></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white p-3 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Kamar Kosong</h5>
                        <h2 class="display-5 fw-bold"><?= $kamar_kosong ?></h2>
                        <a href="kamar_tampil.php" class="text-white text-decoration-none">Lihat Detail ></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-dark p-3 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Menunggu Verifikasi</h5>
                        <h2 class="display-5 fw-bold"><?= $menunggu_konfirmasi ?></h2>
                        <a href="pembayaran_tampil.php" class="text-dark text-decoration-none">Lihat Pembayaran ></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-danger text-white p-3 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Komplain Masuk</h5>
                        <h2 class="display-5 fw-bold"><?= $komplain_masuk ?></h2>
                        <a href="komplain_tampil.php" class="text-white text-decoration-none">Cek Komplain ></a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<?php include 'footer.php'; ?>