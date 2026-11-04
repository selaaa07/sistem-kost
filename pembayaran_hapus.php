<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_pembayaran = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Cari nama file bukti transfer sebelum datanya dihapus
    $cek_data = mysqli_query($koneksi, "SELECT bukti_bayar FROM pembayaran WHERE id_pembayaran = '$id_pembayaran'");
    if ($cek_data && mysqli_num_rows($cek_data) > 0) {
        $row = mysqli_fetch_assoc($cek_data);
        // Hapus file fisik di dalam folder uploads jika ada
        if (!empty($row['bukti_bayar']) && file_exists('uploads/' . $row['bukti_bayar'])) {
            unlink('uploads/' . $row['bukti_bayar']);
        }
    }

    $query_hapus = "DELETE FROM pembayaran WHERE id_pembayaran = '$id_pembayaran'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        header("Location: pembayaran_tampil.php");
        exit;
    } else {
        echo "Gagal menghapus data transaksi: " . mysqli_error($koneksi);
    }
}
?>
