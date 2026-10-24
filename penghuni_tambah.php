<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama_penghuni = $_POST['nama_penghuni'];
    $no_hp = $_POST['no_hp'];
    $id_kamar = $_POST['id_kamar'];

    $query_insert = "INSERT INTO penghuni (nama_penghuni, no_hp, id_kamar) 
                     VALUES ('$nama_penghuni', '$no_hp', '$id_kamar')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        // UPDATE status kamar menjadi terisi agar tidak bentrok
        mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'terisi' WHERE id_kamar = '$id_kamar'");
        
        header("Location: penghuni_tampil.php");
        exit;
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Penghuni</title>
</head>
<body>
    <h2>Tambah Penghuni Baru</h2>
    <form method="POST" action="">
        <label>Nama Penghuni:</label><br>
        <input type="text" name="nama_penghuni" required><br><br>

        <label>Nomor HP:</label><br>
        <input type="text" name="no_hp" required><br><br>

        <label>Pilih Kamar:</label><br>
        <select name="id_kamar" required>
            <option value="">-- Pilih Kamar Kosong --</option>
            <?php
            $query_kamar = "SELECT * FROM kamar WHERE status_kamar = 'kosong'";
            $result_kamar = mysqli_query($koneksi, $query_kamar);
            
            while ($k = mysqli_fetch_assoc($result_kamar)) {
                echo "<option value='" . $k['id_kamar'] . "'>Kamar " . $k['id_kamar'] . " (Rp " . number_format($k['harga'], 0, ',', '.') . ")</option>";
            }
            ?>
        </select><br><br>

        <button type="submit" name="simpan">Simpan Data</button>
        <a href="penghuni_tampil.php">Batal</a>
    </form>
</body>
</html>