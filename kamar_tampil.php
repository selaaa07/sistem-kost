<?php
include 'koneksi.php';

include 'header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="text-primary fw-semibold small mb-2">
            KELOLA DATA KOST
        </p>
        <h2 class="fw-bold mb-2" style="color: #243b64;">
            Daftar Kamar Kost
        </h2>
        <p class="text-secondary mb-0">
            Kelola fasilitas, harga, dan status kamar Kost Logic.
        </p>
    </div>

    <a href="kamar_tambah.php" class="btn btn-primary px-4 py-2">
        + Tambah Kamar Baru
    </a>
</div>

<div class="content-panel">

    <div class="mb-3">
        <h5 class="fw-bold mb-1">Informasi Kamar</h5>
        <p class="text-secondary small mb-0">
            Daftar kamar yang terdaftar dalam sistem.
        </p>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle mb-0">

            <thead>
                <tr>
                    <th>ID Kamar</th>
                    <th>Fasilitas</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $query = "SELECT * FROM kamar";
                $result = mysqli_query($koneksi, $query);

                if (!$result) {
                    echo '<tr><td colspan="5" class="text-center text-danger">';
                    echo 'Data kamar gagal dimuat.';
                    echo '</td></tr>';
                } elseif (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>

                <tr>
                    <td class="fw-semibold">
                        <?= htmlspecialchars($row['id_kamar']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['fasilitas']) ?>
                    </td>

                    <td class="fw-semibold">
                        Rp <?= number_format((float)$row['harga'], 0, ',', '.') ?>
                    </td>

                    <td>
                        <?php if (strtolower($row['status_kamar']) === 'kosong') { ?>
                            <span class="badge rounded-pill text-bg-success px-3 py-2">
                                Kosong
                            </span>
                        <?php } elseif (strtolower($row['status_kamar']) === 'terisi') { ?>
                            <span class="badge rounded-pill text-bg-warning px-3 py-2">
                                Terisi
                            </span>
                        <?php } else { ?>
                            <span class="badge rounded-pill text-bg-secondary px-3 py-2">
                                <?= htmlspecialchars($row['status_kamar']) ?>
                            </span>
                        <?php } ?>
                    </td>

                    <td>
                        <div class="d-flex gap-2">
                            <a
                                href="kamar_edit.php?id=<?= urlencode($row['id_kamar']) ?>"
                                class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>

                            <a
                                href="kamar_hapus.php?id=<?= urlencode($row['id_kamar']) ?>"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                Hapus
                            </a>
                        </div>
                    </td>
                </tr>

                <?php
                    }
                } else {
                ?>

                <tr>
                    <td colspan="5" class="text-center py-5">
                        <h5 class="fw-semibold">Belum ada data kamar</h5>
                        <p class="text-secondary mb-3">
                            Yuk, tambahkan data kamar terlebih dahulu.
                        </p>
                        <a href="kamar_tambah.php" class="btn btn-primary">
                            + Tambah Kamar
                        </a>
                    </td>
                </tr>

                <?php } ?>
            </tbody>

        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
