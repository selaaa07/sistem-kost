<?php
include 'koneksi.php';

$id_kamar = $_GET['id'];
$query = "DELETE FROM kamar WHERE id_kamar = '$id_kamar'";

if (mysqli_query($koneksi, $query)) {
    header("Location: kamar_tampil.php");
} else {
    echo "Gagal menghapus: " . mysqli_error($koneksi);
}
?>