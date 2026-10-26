<?php 
include 'koneksi.php'; 

if (isset($_GET['aksi']) && $_GET['aksi'] == 'lunas' && isset($_GET['id'])) {
    $id_bayar = $_GET['id'];
    $update_query = "UPDATE pembayaran SET status_pembayaran = 'lunas' WHERE id_pembayaran = '$id_bayar'";
    mysqli_query($koneksi, $update_query);
    header("Location: pembayaran_tampil.php");
    exit;
}

include 'header.php'; 
?>

<h2>Riwayat Tagihan & Pembayaran Kost</h2>
<p>Kelola pencatatan transaksi masuk bulanan penghuni kost di sini.</p>
<a href="pembayaran_tambah.php" style="padding: 8px 12px; background: #28a745; color: #fff; text-decoration: none; border-radius: 4px;">+ Catat Pembayaran Baru</a>
<br><br>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead style="background: #f4f4f4;">
        <tr>
            <th>ID Pembayaran</th>
            <th>Nama Penghuni</th>
            <th>Tanggal Bayar</th>
            <th>Bulan Tagihan</th>
            <th>Nominal Tagihan (Rp)</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT pembayaran.*, penghuni.nama_penghuni 
                FROM pembayaran 
                LEFT JOIN penghuni ON pembayaran.id_penghuni = penghuni.id_penghuni 
                ORDER BY pembayaran.id_pembayaran DESC";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $nominal_rupiah = number_format($row['nominal_tagihan'], 0, ',', '.');
                
            echo "<tr>";
            echo "<td>" . $row['id_pembayaran'] . "</td>";
            echo "<td>" . ($row['nama_penghuni'] ?? "<span style='color:red;'>Penghuni Sudah Dihapus</span>") . "</td>";
            echo "<td>" . ($row['tanggal_bayar'] ?? '-') . "</td>";
            echo "<td>" . $row['bulan_tagihan'] . "</td>";
            echo "<td>Rp " . $nominal_rupiah . "</td>";
            echo "<td><strong>" . ucfirst($row['status_pembayaran']) . "</strong></td>";
                
            
            echo "<td>";
            if (strtolower($row['status_pembayaran']) != 'lunas') {
                echo "<a href='pembayaran_tampil.php?aksi=lunas&id=" . $row['id_pembayaran'] . "' onclick='return confirm(\"Ubah status menjadi Lunas?\")' style='color: green;'>Set Lunas</a> | ";
            }
            echo "<a href='pembayaran_edit.php?id=" . $row['id_pembayaran'] . "' style='color: #007bff;'>Edit</a> | ";
            echo "<a href='pembayaran_hapus.php?id=" . $row['id_pembayaran'] . "' onclick='return confirm(\"Yakin ingin menghapus data ini?\")' style='color: red;'>Hapus</a>";
            echo "</td>";
                
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7' style='text-align:center;'>Belum ada riwayat transaksi.</td></tr>";
    }
    ?>

    </tbody>
</table>

<?php 
include 'footer.php'; 
?>
