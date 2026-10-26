<?php
include 'koneksi.php';

// Ambil ID pembayaran dari URL
$id_pembayaran = $_GET['id'];
$query_tampil  = "SELECT * FROM pembayaran WHERE id_pembayaran = '$id_pembayaran'";
$result        = mysqli_query($koneksi, $query_tampil);
$data          = mysqli_fetch_assoc($result);

// Proses simpan perubahan data
if (isset($_POST['update'])) {
    $id_penghuni       = $_POST['id_penghuni'];
    $tanggal_bayar     = $_POST['tanggal_bayar'];
    $bulan_tagihan     = $_POST['bulan_tagihan'];
    $nominal_tagihan   = $_POST['nominal_tagihan'];
    $status_pembayaran = $_POST['status_pembayaran'];

    $query_update = "UPDATE pembayaran SET 
                        id_penghuni       = '$id_penghuni',
                        tanggal_bayar     = '$tanggal_bayar',
                        bulan_tagihan     = '$bulan_tagihan',
                        nominal_tagihan   = '$nominal_tagihan',
                        status_pembayaran = '$status_pembayaran'
                     WHERE id_pembayaran  = '$id_pembayaran'";

    if (mysqli_query($koneksi, $query_update)) {
        header("Location: pembayaran_tampil.php");
        exit;
    } else {
        $error_msg = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}

include 'header.php';
?>

<h2>Edit Data Pembayaran</h2>
<a href="pembayaran_tampil.php" style="color: #6c757d; text-decoration: none;">&laquo; Batal & Kembali</a>
<br><br>

<?php if(isset($error_msg)) { echo "<p style='color:red;'>$error_msg</p>"; } ?>

<form action="" method="POST" style="max-width: 500px; background: #fafafa; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
    <div style="margin-bottom: 15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Penghuni Kost:</label>
        <select name="id_penghuni" required style="width:100%; padding: 8px;">
            <?php
            $q_penghuni = mysqli_query($koneksi, "SELECT id_penghuni, nama_penghuni, id_kamar FROM penghuni");
            while ($p = mysqli_fetch_assoc($q_penghuni)) {
                $selected = ($p['id_penghuni'] == $data['id_penghuni']) ? 'selected' : '';
                echo "<option value='" . $p['id_penghuni'] . "' $selected>" . $p['nama_penghuni'] . " (Kamar " . $p['id_kamar'] . ")</option>";
            }
            ?>
        </select>
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Tanggal Bayar:</label>
        <input type="date" name="tanggal_bayar" value="<?php echo $data['tanggal_bayar']; ?>" required style="width:96%; padding: 8px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Bulan Tagihan:</label>
        <input type="text" name="bulan_tagihan" value="<?php echo $data['bulan_tagihan']; ?>" required style="width:96%; padding: 8px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Nominal Pembayaran (Rp):</label>
        <input type="number" name="nominal_tagihan" value="<?php echo $data['nominal_tagihan']; ?>" required style="width:96%; padding: 8px;">
    </div>

    <div style="margin-bottom: 20px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Status Pembayaran:</label>
        <select name="status_pembayaran" required style="width:100%; padding: 8px;">
            <option value="belum lunas" <?php if($data['status_pembayaran'] == 'belum lunas') echo 'selected'; ?>>Belum Lunas / Pending</option>
            <option value="lunas" <?php if($data['status_pembayaran'] == 'lunas') echo 'selected'; ?>>Lunas</option>
        </select>
    </div>

    <button type="submit" name="update" style="padding: 10px 15px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Simpan Perubahan</button>
</form>

<?php 
include 'footer.php'; 
?>
