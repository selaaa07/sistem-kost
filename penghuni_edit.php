<?php
include 'koneksi.php';

$id_penghuni = $_GET['id'];
$query_tampil = "SELECT * FROM penghuni WHERE id_penghuni = '$id_penghuni'";
$result = mysqli_query($koneksi, $query_tampil);
$data = mysqli_fetch_assoc($result);

$kamar_lama = $data['id_kamar'];

if (isset($_POST['update'])) {
    $nama_penghuni = $_POST['nama_penghuni'];
    $no_hp = $_POST['no_hp'];
    $kamar_baru = $_POST['id_kamar'];

    if ($kamar_lama != $kamar_baru) {
        mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'kosong' WHERE id_kamar = '$kamar_lama'");
        mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'terisi' WHERE id_kamar = '$kamar_baru'");
    }

    $query_update = "UPDATE penghuni SET nama_penghuni = '$nama_penghuni', no_hp = '$no_hp', id_kamar = '$kamar_baru' WHERE id_penghuni = '$id_penghuni'";
    
    if (mysqli_query($koneksi, $query_update)) {
        header("Location: penghuni_tampil.php");
        exit;
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Penghuni</title>
</head>
<body>
    <h2>Edit Penghuni: <?php echo $data['nama_penghuni']; ?></h2>
    <form method="POST" action="">
        <label>Nama Penghuni:</label><br>
        <input type="text" name="nama_penghuni" value="<?php echo $data['nama_penghuni']; ?>" required><br><br>

        <label>Nomor HP:</label><br>
        <input type="text" name="no_hp" value="<?php echo $data['no_hp']; ?>" required><br><br>

        <label>Pilih Kamar:</label><br>
        <select name="id_kamar" required>
            <?php
            $query_kamar = "SELECT * FROM kamar WHERE status_kamar = 'kosong' OR id_kamar = '$kamar_lama'";
            $result_kamar = mysqli_query($koneksi, $query_kamar);
            
            while ($k = mysqli_fetch_assoc($result_kamar)) {
                $selected = ($k['id_kamar'] == $kamar_lama) ? 'selected' : '';
                echo "<option value='" . $k['id_kamar'] . "' $selected>Kamar " . $k['id_kamar'] . "</option>";
            }
            ?>
        </select><br><br>

        <button type="submit" name="update">Update Data</button>
        <a href="penghuni_tampil.php">Batal</a>
    </form>
</body>
</html>