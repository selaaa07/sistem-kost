<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $cari = mysqli_query($koneksi, "SELECT id_kamar FROM penghuni WHERE id_penghuni='$id'");
    
    if (mysqli_num_rows($cari) > 0) {
        $data_kamar = mysqli_fetch_assoc($cari);
        $id_kamar = $data_kamar['id_kamar'];

        $hapus = mysqli_query($koneksi, "DELETE FROM penghuni WHERE id_penghuni='$id'");

        if ($hapus) {
            mysqli_query($koneksi, "UPDATE kamar SET status_kamar='kosong', password='kost123' WHERE id_kamar='$id_kamar'");
            
            echo "<script>
                    alert('Data penghuni berhasil dihapus dan kamar otomatis direset!'); 
                    window.location.href='penghuni_tampil.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus data penghuni!'); 
                    window.location.href='penghuni_tampil.php';
                  </script>";
        }
    } else {
        header("Location: penghuni_tampil.php");
    }
} else {
    header("Location: penghuni_tampil.php");
}
?>