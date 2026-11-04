<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<?php include 'header.php'; ?>
<div class="card welcome-card shadow-sm mb-4">
    <div class="card-body p-4 p-md-5">

     <p class="mb-3 text-white-50fw">
            INFORMASI DAN PENGELOLAAN KOST
        </p>

<div class="row g-4">

    <div class="col-md-4">
        <div class="card summary-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="mb-3 text-primary">
                    <span class="badge text-bg-primary p-2">01</span>
                </div>

                <h5 class="fw-bold">Informasi Kamar</h5>

                <a href="kamar_tampil.php"
                    class="btn btn-primary w-100">
                    Lihat Kamar
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card summary-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="mb-3 text-success">
                    <span class="badge text-bg-success p-2">02</span>
                </div>

                <h5 class="fw-bold">Informasi Penghuni</h5>

                <a href="penghuni_tampil.php"
                    class="btn btn-success w-100">
                    Lihat Penghuni
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card summary-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="mb-3 text-warning">
                    <span class="badge text-bg-warning p-2">03</span>
                </div>

                <h5 class="fw-bold">Informasi Pembayaran</h5>

                <a href="pembayaran_tampil.php"
                    class="btn btn-warning w-100">
                    Lihat Pembayaran
                </a>
            </div>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>
