<?php
include 'koneksi.php';

$error = '';

if (isset($_POST['simpan'])) {
    $id_kamar = mysqli_real_escape_string(
        $koneksi, $_POST['id_kamar']
    );
    $fasilitas = mysqli_real_escape_string(
        $koneksi, $_POST['fasilitas']
    );
    $harga = (int) $_POST['harga'];
    $status_kamar = mysqli_real_escape_string(
        $koneksi, $_POST['status_kamar']
    );

    $query = "INSERT INTO kamar
              (id_kamar, fasilitas, harga, status_kamar)
              VALUES
              ('$id_kamar', '$fasilitas', '$harga', '$status_kamar')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: kamar_tampil.php");
        exit;
    } else {
        $error = "Gagal menyimpan data. Pastikan ID kamar belum digunakan.";
    }
}

include 'header.php';
?>

<div class="mb-4">
    <a href="kamar_tampil.php"
       class="text-decoration-none text-secondary">
        &larr; Kembali ke Daftar Kamar
    </a>

    <p class="text-primary fw-semibold small mt-4 mb-2">
        KELOLA DATA KOST
    </p>

    <h2 class="fw-bold mb-2" style="color: #243b64;">
        Tambah Kamar Baru
    </h2>

    <p class="text-secondary mb-0">
        Isi informasi kamar yang ingin ditambahkan ke Kost Logic.
    </p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">

        <div class="content-panel">

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-3 p-3"
                     style="background: #e8effb; color: #243b64;">
                    <span class="fs-3">🛏️</span>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">Informasi Kamar</h5>
                    <p class="text-secondary small mb-0">
                        Lengkapi semua kolom berikut.
                    </p>
                </div>
            </div>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="mb-3">
                    <label for="id_kamar" class="form-label fw-semibold">
                        ID Kamar
                    </label>

                    <input
                        type="number"
                        class="form-control form-control-lg"
                        id="id_kamar"
                        name="id_kamar"
                        placeholder="Contoh: 104"
                        required
                        value="<?= htmlspecialchars($_POST['id_kamar'] ?? '') ?>">

                    <div class="form-text">
                        Masukkan ID unik untuk kamar ini.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="fasilitas" class="form-label fw-semibold">
                        Fasilitas Kamar
                    </label>

                    <textarea
                        class="form-control"
                        id="fasilitas"
                        name="fasilitas"
                        rows="4"
                        placeholder="Contoh: Kasur, lemari, kamar mandi dalam"
                        required><?= htmlspecialchars($_POST['fasilitas'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="harga" class="form-label fw-semibold">
                        Harga Kamar (Rp)
                    </label>

                    <div class="input-group input-group-lg">
                        <span class="input-group-text">Rp</span>
                        <input
                            type="number"
                            class="form-control"
                            id="harga"
                            name="harga"
                            min="0"
                            placeholder="1500000"
                            required
                            value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>">
                    </div>

                    <div class="form-text">
                        Masukkan harga tanpa titik atau tanda koma.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="status_kamar" class="form-label fw-semibold">
                        Status Kamar
                    </label>

                    <select
                        class="form-select form-select-lg"
                        id="status_kamar"
                        name="status_kamar"
                        required>

                        <option value="kosong"
                            <?= ($_POST['status_kamar'] ?? 'kosong') === 'kosong' ? 'selected' : '' ?>>
                            Kosong
                        </option>

                        <option value="terisi"
                            <?= ($_POST['status_kamar'] ?? '') === 'terisi' ? 'selected' : '' ?>>
                            Terisi
                        </option>
                    </select>
                </div>

                <hr class="my-4">

                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <a href="kamar_tampil.php"
                       class="btn btn-outline-secondary px-4 py-2">
                        Batal
                    </a>

                    <button type="submit"
                            name="simpan"
                            class="btn btn-primary px-4 py-2">
                        Simpan Data
                    </button>
                </div>

            </form>
        </div>

        <p class="text-secondary small mt-3">
            <span class="text-danger">*</span>
            Pastikan informasi kamar sudah benar sebelum disimpan.
        </p>

    </div>
</div>

<?php include 'footer.php'; ?>
