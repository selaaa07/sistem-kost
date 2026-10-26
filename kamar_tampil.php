<?php
include 'koneksi.php';
?>

<?php 
include 'header.php'; 
?>

<h2>Daftar Kamar Kost</h2>
<a href="kamar_tambah.php">+ Tambah Kamar Baru</a>
<br><br>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID Kamar</th>
        <th>Fasilitas</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    
    <?php
    $query = "SELECT * FROM kamar";
    $result = mysqli_query($koneksi, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id_kamar'] . "</td>";
        echo "<td>" . $row['fasilitas'] . "</td>";
        echo "<td>" . $row['harga'] . "</td>";
        echo "<td>" . $row['status_kamar'] . "</td>";
        echo "<td>
                <a href='kamar_edit.php?id=" . $row['id_kamar'] . "'>Edit</a> | 
                <a href='kamar_hapus.php?id=" . $row['id_kamar'] . "'>Hapus</a>
              </td>";
        echo "</tr>";
    }
    ?>
</table>

<?php 
include 'footer.php'; 
?>