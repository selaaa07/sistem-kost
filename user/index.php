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
            <a href="https://wa.me/62877777777789?text=Halo saya mau pesan kamar di kost logic"
               target="_blank"
               class="btn btn-success btn-sm px-4">
                💬 admin kost logic
            </a>

        </div>
    </div>
</div>

<!-- Card Informasi Lokasi Kost di Landing Page -->
<div class="container mt-4 mb-5">
    <div class="card shadow-sm p-4 border-0" style="border-radius: 15px; background-color: #f8f9fa;">
        <div class="card-body text-center">
            <h4 class="fw-bold mb-3" style="color: #243b64;">
                <i class="bi bi-geo-alt-fill text-danger"></i> Lokasi Kost Logic
            </h4>
            <p class="text-secondary mb-4">
                Jl. Jenderal Sudirman, Kec. Jombang, Kota Cilegon, Banten 42411<br>
                <i>(Patokan: Masuk gang samping Indomaret Jenderal Sudirman, pagar warna hitam)</i>
            </p>
            
            <!-- Embed Google Maps Interaktif (Sudah Fix Ukurannya) -->
            <div class="rounded overflow-hidden shadow-sm" style="border: 1px solid #dee2e6;">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126937.56846985437!2d105.9734125!3d-6.0232497!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e418e17812f6759%3A0x6a2b8e21711202e8!2sCilegon%2C%20Cilegon%20City%2C%20Banten!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                    width="100%" 
                    height="350" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>