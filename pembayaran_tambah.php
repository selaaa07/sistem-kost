<?php 
include 'koneksi.php'; 

if (isset($_POST['simpan'])) {
    $id_penghuni       = $_POST['id_penghuni'];
    $tanggal_bayar     = $_POST['tanggal_bayar'];
    $bulan_tagihan     = $_POST['bulan_tagihan'];
    $nominal_tagihan   = $_POST['nominal_tagihan'];
    $status_pembayaran = $_POST['status_pembayaran'];

    $query_insert = "INSERT INTO pembayaran (id_penghuni, tanggal_bayar, bulan_tagihan, nominal_tagihan, status_pembayaran) 
                     VALUES ('$id_penghuni', '$tanggal_bayar', '$bulan_tagihan', '$nominal_tagihan', '$status_pembayaran')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        header("Location: pembayaran_tampil.php");
        exit;
    } else {
        $error_msg = "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}

include 'header.php'; 
?>

<h2>Catat Pembayaran Baru</h2>
<a href="pembayaran_tampil.php" style="color: #6c757d; text-decoration: none;">&laquo; Kembali ke Riwayat</a>
<br><br>

<?php if(isset($error_msg)) { echo "<p style='color:red;'>$error_msg</p>"; } ?>

<form action="" method="POST" style="max-width: 500px; background: #fafafa; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
    <div style="margin-bottom: 15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Pilih Penghuni Kost:</label>
        <select name="id_penghuni" required style="width:100%; padding: 8px;">
            <option value="">-- Pilih Nama Penghuni --</option>
            <?php
            //Mengambil data dari tabel penghuni 
            $q_penghuni = mysqli_query($koneksi, "SELECT id_penghuni, nama_penghuni, id_kamar FROM penghuni");
            while ($p = mysqli_fetch_assoc($q_penghuni)) {
                // Menampilkan nama_penghuni dan id_kamar yang valid
                echo "<option value='" . $p['id_penghuni'] . "'>" . $p['nama_penghuni'] . " (Kamar " . $p['id_kamar'] . ")</option>";
            }
            ?>
        </select>
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Tanggal Bayar:</label>
        <input type="date" name="tanggal_bayar" required style="width:96%; padding: 8px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Bulan Tagihan:</label>
        <input type="text" name="bulan_tagihan" placeholder="Contoh: Oktober 2026" required style="width:96%; padding: 8px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Nominal Pembayaran (Rp):</label>
        <input type="number" name="nominal_tagihan" placeholder="Contoh: 1500000" required style="width:96%; padding: 8px;">
    </div>

    <div style="margin-bottom: 20px;">
        <label style="display:block; font-weight:bold; margin-bottom:5px;">Status Awal Pembayaran:</label>
        <select name="status_pembayaran" required style="width:100%; padding: 8px;">
            <option value="belum lunas">Belum Lunas / Pending</option>
            <option value="lunas">Lunas</option>
        </select>
    </div>

    <button type="submit" name="simpan" style="padding: 10px 15px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Simpan Transaksi</button>
</form>

<?php 
include 'footer.php'; 
?>
