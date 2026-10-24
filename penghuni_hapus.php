<?php
include 'koneksi.php';

$id_penghuni = $_GET['id'];

$query_cari = "SELECT id_kamar FROM penghuni WHERE id_penghuni = '$id_penghuni'";
$hasil_cari = mysqli_query($koneksi, $query_cari);
$data_kamar = mysqli_fetch_assoc($hasil_cari);
$id_kamar = $data_kamar['id_kamar'];

$query_hapus = "DELETE FROM penghuni WHERE id_penghuni = '$id_penghuni'";

if (mysqli_query($koneksi, $query_hapus)) {
    mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'kosong' WHERE id_kamar = '$id_kamar'");
    
    header("Location: penghuni_tampil.php");
} else {
    echo "Gagal menghapus: " . mysqli_error($koneksi);
}
?>