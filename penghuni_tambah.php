<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama_penghuni = $_POST['nama_penghuni'];
    $no_hp = $_POST['no_hp'];
    $id_kamar = $_POST['id_kamar'];

    $query_insert = "INSERT INTO penghuni (nama_penghuni, no_hp, id_kamar) 
                     VALUES ('$nama_penghuni', '$no_hp', '$id_kamar')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'terisi' WHERE id_kamar = '$id_kamar'");
        
        header("Location: penghuni_tampil.php");
        exit;
    } else {
        $error = "Gagal: " . mysqli_error($koneksi);
    }
}

include 'header.php';
?>

<div class="mb-4">
    <p class="text-primary fw-semibold small mb-2">
        KELOLA DATA KOST
    </p>

    <h2 class="fw-bold mb-2" style="color: #243b64;">
        Tambah Penghuni
    </h2>

    <p class="text-secondary mb-0">
        Masukkan informasi penghuni baru ke dalam sistem Kost Logic.
    </p>
</div>

<div class="content-panel">
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Informasi Penghuni</h5>
        <p class="text-secondary small mb-0">
            Lengkapi formulir berikut dengan data penghuni.
        </p>
    </div>

    <?php if (isset($error)) { ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="nama_penghuni" class="form-label fw-semibold">
                Nama Penghuni
            </label>
            <input
                type="text"
                class="form-control"
                id="nama_penghuni"
                name="nama_penghuni"
                placeholder="Masukkan nama penghuni"
                required
            >
        </div>

        <div class="mb-3">
            <label for="no_hp" class="form-label fw-semibold">
                Nomor HP
            </label>
            <input
                type="text"
                class="form-control"
                id="no_hp"
                name="no_hp"
                placeholder="Masukkan nomor HP"
                required
            >
        </div>

        <div class="mb-4">
            <label for="id_kamar" class="form-label fw-semibold">
                Pilih Kamar
            </label>

            <select
                class="form-select"
                id="id_kamar"
                name="id_kamar"
                required
            >
                <option value="">-- Pilih Kamar Kosong --</option>

                <?php
                $query_kamar = "SELECT * FROM kamar WHERE status_kamar = 'kosong'";
                $result_kamar = mysqli_query($koneksi, $query_kamar);
                
                while ($k = mysqli_fetch_assoc($result_kamar)) {
                    echo "<option value='" . $k['id_kamar'] . "'>Kamar " . $k['id_kamar'] . " (Rp " . number_format($k['harga'], 0, ',', '.') . ")</option>";
                }
                ?>
            </select>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button
                type="submit"
                name="simpan"
                class="btn btn-primary px-4 py-2"
            >
                Simpan Data
            </button>

            <a
                href="penghuni_tampil.php"
                class="btn btn-outline-secondary px-4 py-2"
            >
                Batal
            </a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
