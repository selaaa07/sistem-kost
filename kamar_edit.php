<?php
include 'koneksi.php';

$id_kamar = $_GET['id'];
$query_tampil = "SELECT * FROM kamar WHERE id_kamar = '$id_kamar'";
$result = mysqli_query($koneksi, $query_tampil);
$data = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $fasilitas = $_POST['fasilitas'];
    $harga = $_POST['harga'];
    $status_kamar = $_POST['status_kamar'];

    $query_update = "UPDATE kamar SET fasilitas = '$fasilitas', harga = '$harga', status_kamar = '$status_kamar' WHERE id_kamar = '$id_kamar'";
    
    if (mysqli_query($koneksi, $query_update)) {
        header("Location: kamar_tampil.php");
        exit;
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Kamar</title>
</head>
<body>
    <h2>Edit Kamar: <?php echo $data['id_kamar']; ?></h2>
    <form method="POST" action="">
        <label>Fasilitas:</label><br>
        <textarea name="fasilitas" required rows="3" cols="30"><?php echo $data['fasilitas']; ?></textarea><br><br>

        <label>Harga:</label><br>
        <input type="number" name="harga" value="<?php echo $data['harga']; ?>" required><br><br>

        <label>Status Kamar:</label><br>
        <select name="status_kamar" required>
            <option value="kosong" <?php if($data['status_kamar'] == 'kosong') echo 'selected'; ?>>Kosong</option>
            <option value="terisi" <?php if($data['status_kamar'] == 'terisi') echo 'selected'; ?>>Terisi</option>
        </select><br><br>

        <button type="submit" name="update">Update Data</button>
        <a href="kamar_tampil.php">Batal</a>
    </form>
</body>
</html>