<?php
include 'koneksi.php';
?>

<?php 
include 'header.php'; 
?>

<h2>Daftar Penghuni Kost</h2>
<a href="penghuni_tambah.php">+ Tambah Penghuni Baru</a>
<br><br>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID Penghuni</th>
        <th>Nama Penghuni</th>
        <th>No HP</th>
        <th>Kamar (Fasilitas)</th>
        <th>Aksi</th>
    </tr>
    
    <?php
    $query = "SELECT penghuni.id_penghuni, penghuni.nama_penghuni, penghuni.no_hp, kamar.id_kamar, kamar.fasilitas 
              FROM penghuni 
              JOIN kamar ON penghuni.id_kamar = kamar.id_kamar";
    $result = mysqli_query($koneksi, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id_penghuni'] . "</td>";
        echo "<td>" . $row['nama_penghuni'] . "</td>";
        echo "<td>" . $row['no_hp'] . "</td>";
        echo "<td>Kamar " . $row['id_kamar'] . " - " . $row['fasilitas'] . "</td>";
        echo "<td>
                <a href='penghuni_edit.php?id=" . $row['id_penghuni'] . "'>Edit</a> | 
                <a href='penghuni_hapus.php?id=" . $row['id_penghuni'] . "'>Hapus</a>
              </td>";
        echo "</tr>";
    }
    ?>
</table>

<?php 
include 'footer.php'; 
?>