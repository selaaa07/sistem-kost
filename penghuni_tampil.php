<?php
include 'koneksi.php';
include 'header.php';
?>

<div class="content-panel">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-2">Daftar Penghuni Kost</h2>
            <p class="text-secondary mb-0">
                Kelola data penghuni dan informasi kamar yang ditempati.
            </p>
        </div>

        <a href="penghuni_tambah.php" class="btn btn-primary">
            + Tambah Penghuni
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID Penghuni</th>
                    <th>Nama Penghuni</th>
                    <th>No. HP</th>
                    <th>Kamar</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $query = "SELECT penghuni.id_penghuni, penghuni.nama_penghuni, penghuni.no_hp, kamar.id_kamar, kamar.fasilitas
                          FROM penghuni
                          JOIN kamar ON penghuni.id_kamar = kamar.id_kamar";

                $result = mysqli_query($koneksi, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id_penghuni'] . "</td>";
                    echo "<td class='fw-semibold'>" . $row['nama_penghuni'] . "</td>";
                    echo "<td>" . $row['no_hp'] . "</td>";
                    echo "<td>
                            <div class='fw-semibold'>Kamar " . $row['id_kamar'] . "</div>
                            <small class='text-secondary'>" . $row['fasilitas'] . "</small>
                          </td>";
                    echo "<td>
                            <div class='d-flex gap-2'>
                                <a href='penghuni_edit.php?id=" . $row['id_penghuni'] . "' class='btn btn-sm btn-outline-primary'>Edit</a>
                                <a href='penghuni_hapus.php?id=" . $row['id_penghuni'] . "' class='btn btn-sm btn-outline-danger'>Hapus</a>
                            </div>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'footer.php'; ?>