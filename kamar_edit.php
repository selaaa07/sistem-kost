<?php
include 'koneksi.php';

$id_kamar = $_GET['id'];
$query_tampil = "SELECT * FROM kamar WHERE id_kamar = '$id_kamar'";
$result = mysqli_query($koneksi, $query_tampil);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    include 'header.php';
    echo '<div class="alert alert-warning">Data kamar tidak ditemukan.</div>';
    echo '<a href="kamar_tampil.php" class="btn btn-primary">Kembali</a>';
    include 'footer.php';
    exit;
}

if (isset($_POST['update'])) {
    $fasilitas = $_POST['fasilitas'];
    $harga = $_POST['harga'];
    $status_kamar = $_POST['status_kamar'];

    $query_update = "UPDATE kamar
                     SET fasilitas = '$fasilitas',
                         harga = '$harga',
                         status_kamar = '$status_kamar'
                     WHERE id_kamar = '$id_kamar'";

    if (mysqli_query($koneksi, $query_update)) {
        header("Location: kamar_tampil.php");
        exit;
    } else {
        $error = "Gagal update: " . mysqli_error($koneksi);
    }
}

include 'header.php';
?>

<div class="content-panel">
    <div class="mb-4">
        <h2 class="fw-bold mb-2">Edit Kamar</h2>
        <p class="text-secondary mb-0">
            Perbarui informasi kamar nomor
            <strong><?php echo htmlspecialchars($data['id_kamar']); ?></strong>.
        </p>
    </div>

    <?php if (isset($error)) { ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label fw-semibold">ID Kamar</label>
            <input
                type="text"
                class="form-control"
                value="<?php echo htmlspecialchars($data['id_kamar']); ?>"
                disabled
            >
            <div class="form-text">ID kamar tidak dapat diubah.</div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Fasilitas</label>
            <textarea
                name="fasilitas"
                class="form-control"
                rows="4"
                required
            ><?php echo htmlspecialchars($data['fasilitas']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Harga per Bulan (Rp)</label>
            <input
                type="number"
                name="harga"
                class="form-control"
                min="0"
                value="<?php echo htmlspecialchars($data['harga']); ?>"
                required
            >
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Status Kamar</label>
            <select name="status_kamar" class="form-select" required>
                <option value="kosong" <?php if ($data['status_kamar'] == 'kosong') echo 'selected'; ?>>
                    Kosong
                </option>
                <option value="terisi" <?php if ($data['status_kamar'] == 'terisi') echo 'selected'; ?>>
                    Terisi
                </option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" name="update" class="btn btn-primary px-4">
                Simpan Perubahan
            </button>
            <a href="kamar_tampil.php" class="btn btn-outline-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>