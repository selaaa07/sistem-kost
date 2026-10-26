<?php
include 'koneksi.php';

$id_penghuni = $_GET['id'];
$query_tampil = "SELECT * FROM penghuni WHERE id_penghuni = '$id_penghuni'";
$result = mysqli_query($koneksi, $query_tampil);
$data = mysqli_fetch_assoc($result);

$kamar_lama = $data['id_kamar'];

if (isset($_POST['update'])) {
    $nama_penghuni = $_POST['nama_penghuni'];
    $no_hp = $_POST['no_hp'];
    $kamar_baru = $_POST['id_kamar'];

    if ($kamar_lama != $kamar_baru) {
        mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'kosong' WHERE id_kamar = '$kamar_lama'");
        mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'terisi' WHERE id_kamar = '$kamar_baru'");
    }

    $query_update = "UPDATE penghuni SET nama_penghuni = '$nama_penghuni', no_hp = '$no_hp', id_kamar = '$kamar_baru' WHERE id_penghuni = '$id_penghuni'";
    
    if (mysqli_query($koneksi, $query_update)) {
        header("Location: penghuni_tampil.php");
        exit;
    } else {
        $error = "Gagal update: " . mysqli_error($koneksi);
    }
}

include 'header.php';
?>

<div class="mb-4">
    <p class="text-primary fw-semibold small mb-2">
        KELOLA DATA KOST
    </p>

    <h2 class="fw-bold mb-2" style="color: #243b64;">
        Edit Penghuni
    </h2>

    <p class="text-secondary mb-0">
        Perbarui informasi penghuni Kost Logic.
    </p>
</div>

<div class="content-panel">
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Informasi Penghuni</h5>
        <p class="text-secondary small mb-0">
            Ubah data penghuni pada formulir berikut.
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
                value="<?= htmlspecialchars($data['nama_penghuni']) ?>"
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
                value="<?= htmlspecialchars($data['no_hp']) ?>"
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
                <?php
                $query_kamar = "SELECT * FROM kamar WHERE status_kamar = 'kosong' OR id_kamar = '$kamar_lama'";
                $result_kamar = mysqli_query($koneksi, $query_kamar);
                
                while ($k = mysqli_fetch_assoc($result_kamar)) {
                    $selected = ($k['id_kamar'] == $kamar_lama) ? 'selected' : '';
                    echo "<option value='" . $k['id_kamar'] . "' $selected>Kamar " . $k['id_kamar'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button
                type="submit"
                name="update"
                class="btn btn-primary px-4 py-2"
            >
                Update Data
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
