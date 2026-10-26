<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_pembayaran = $_GET['id'];
    $query_hapus   = "DELETE FROM pembayaran WHERE id_pembayaran = '$id_pembayaran'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        header("Location: pembayaran_tampil.php");
        exit;
    } else {
        echo "Gagal menghapus data transaksi: " . mysqli_error($koneksi);
    }
}
?>
