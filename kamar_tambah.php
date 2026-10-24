<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $id_kamar = $_POST['id_kamar'];
    $fasilitas = $_POST['fasilitas'];
    $harga = $_POST['harga'];
    $status_kamar = $_POST['status_kamar'];

    $query = "INSERT INTO kamar (id_kamar, fasilitas, harga, status_kamar) 
              VALUES ('$id_kamar', '$fasilitas', '$harga', '$status_kamar')";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: kamar_tampil.php");
        exit;
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kamar</title>
</head>
<body>
    <h2>Tambah Kamar Baru</h2>
    <form method="POST" action="">
        <label>ID Kamar (Contoh: 104):</label><br>
        <input type="number" name="id_kamar" required><br><br>

        <label>Fasilitas:</label><br>
        <textarea name="fasilitas" required rows="3" cols="30"></textarea><br><br>

        <label>Harga (Tanpa titik, contoh: 1500000):</label><br>
        <input type="number" name="harga" required><br><br>

        <label>Status Kamar:</label><br>
        <select name="status_kamar" required>
            <option value="kosong">Kosong</option>
            <option value="terisi">Terisi</option>
        </select><br><br>

        <button type="submit" name="simpan">Simpan Data</button>
        <a href="kamar_tampil.php">Batal</a>
    </form>
</body>
</html>