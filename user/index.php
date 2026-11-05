
<?php
include __DIR__ . '/../koneksi.php';
include 'header.php';
?>

<div class="text-center mb-4 mt-4">
    <p class="text-primary fw-semibold small mb-2">
        SELAMAT DATANG DI KOST LOGIC
    </p>

    <h2 class="fw-bold text-utama">
        Informasi Kamar Tersedia
    </h2>
</div>

<div class="row g-3 justify-content-center">

    <?php
    $query = mysqli_query($koneksi, "SELECT * FROM kamar WHERE status_kamar = 'kosong'");

    if ($query && mysqli_num_rows($query) > 0) {

        while ($row = mysqli_fetch_assoc($query)) {

            $namaFoto = basename($row['foto'] ?? '');
            $namaFoto = preg_replace('/\.jpg$/i', '.jpeg', $namaFoto);
    ?>

    <div class="col-6 col-md-4 col-lg-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body text-center p-3">

                <div class="mb-2">
                    <span class="fs-3">🛏️</span>
                </div>

                <h5 class="fw-bold text-utama mb-2">
                    Kamar <?= htmlspecialchars($row['id_kamar']) ?>
                </h5>

                <h6 class="text-success fw-bold mb-2">
                    Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                    <span class="small text-secondary fw-normal">/ bln</span>
                </h6>

                <p class="text-secondary small mb-0">
                    <?= htmlspecialchars($row['fasilitas']) ?>
                </p>

                <button type="button"
                        class="btn btn-outline-primary btn-sm mt-3"
                        data-bs-toggle="modal"
                        data-bs-target="#foto<?= $row['id_kamar'] ?>">
                    🖼️ Lihat Foto Kamar
                </button>

                <div class="modal fade"
                     id="foto<?= $row['id_kamar'] ?>"
                     tabindex="-1">

                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Foto Kamar <?= htmlspecialchars($row['id_kamar']) ?>
                                </h5>

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                </button>
                            </div>

                            <div class="modal-body text-center">

                                <?php if (!empty($namaFoto)) { ?>

                                    <img src="../foto%20kamar/<?= htmlspecialchars($namaFoto) ?>"
                                         class="img-fluid rounded"
                                         alt="Foto Kamar <?= htmlspecialchars($row['id_kamar']) ?>">

                                <?php } else { ?>

                                    <p class="text-secondary">
                                        Foto kamar belum tersedia.
                                    </p>

                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php
        }

    } else {
    ?>

        <div class="col-12 text-center py-4">
            <h6 class="text-secondary">
                Maaf, saat ini semua kamar penuh.
            </h6>
        </div>

    <?php } ?>

</div>

<div class="container mt-5 mb-4">
    <div class="card shadow-sm border-0 text-center p-3">
        <div class="card-body">

            <h4 class="fw-bold text-utama mb-2">
                Tertarik buat kost disini??
            </h4>

            <p class="text-secondary small mb-3">
                langsung aja hubungi admin kost logic
            </p>

            <a href="https://wa.me/62877777777778?text=Halo saya mau pesan kamar di kost logic"
               target="_blank"
               class="btn btn-success btn-sm px-4">
                💬 admin kost logic
            </a>

        </div>
    </div>
</div>