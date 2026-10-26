<?php
include 'koneksi.php';

// Ambil ID pembayaran dari URL
$id_pembayaran = $_GET['id'] ?? '';

$query_tampil = "SELECT * FROM pembayaran WHERE id_pembayaran = '$id_pembayaran'";
$result = mysqli_query($koneksi, $query_tampil);
$data = mysqli_fetch_assoc($result);

// Proses simpan perubahan data
if (isset($_POST['update'])) {
    $id_penghuni       = $_POST['id_penghuni'];
    $tanggal_bayar     = $_POST['tanggal_bayar'];
    $bulan_tagihan     = $_POST['bulan_tagihan'];
    $nominal_tagihan   = $_POST['nominal_tagihan'];
    $status_pembayaran = $_POST['status_pembayaran'];

    $query_update = "UPDATE pembayaran SET
                        id_penghuni       = '$id_penghuni',
                        tanggal_bayar     = '$tanggal_bayar',
                        bulan_tagihan     = '$bulan_tagihan',
                        nominal_tagihan   = '$nominal_tagihan',
                        status_pembayaran = '$status_pembayaran'
                     WHERE id_pembayaran  = '$id_pembayaran'";

    if (mysqli_query($koneksi, $query_update)) {
        header("Location: pembayaran_tampil.php");
        exit;
    } else {
        $error_msg = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}

include 'header.php';
?>

<!-- Judul Halaman -->
<div class="mb-4">
    <p class="text-primary fw-semibold small mb-2">
        KELOLA DATA KOST
    </p>

    <h2 class="fw-bold mb-2" style="color: #243b64;">
        Edit Data Pembayaran
    </h2>

    <p class="text-secondary mb-0">
        Perbarui informasi transaksi pembayaran penghuni kost.
    </p>
</div>

<?php if (isset($error_msg)) { ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error_msg) ?>
    </div>
<?php } ?>

<?php if ($data) { ?>

<div class="content-panel">
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Informasi Pembayaran</h5>
        <p class="text-secondary small mb-0">
            Pastikan informasi yang diperbarui sudah benar.
        </p>
    </div>

    <form action="" method="POST">

        <div class="mb-3">
            <label for="id_penghuni" class="form-label fw-semibold">
                Penghuni Kost
            </label>

            <select name="id_penghuni" id="id_penghuni"
                    class="form-select" required>
                <?php
                $q_penghuni = mysqli_query(
                    $koneksi,
                    "SELECT id_penghuni, nama_penghuni, id_kamar FROM penghuni"
                );

                while ($p = mysqli_fetch_assoc($q_penghuni)) {
                    $selected = ($p['id_penghuni'] == $data['id_penghuni'])
                        ? 'selected'
                        : '';

                    echo "<option value='" .
                        htmlspecialchars($p['id_penghuni'], ENT_QUOTES, 'UTF-8') .
                        "' $selected>" .
                        htmlspecialchars($p['nama_penghuni'], ENT_QUOTES, 'UTF-8') .
                        " (Kamar " .
                        htmlspecialchars($p['id_kamar'], ENT_QUOTES, 'UTF-8') .
                        ")</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal_bayar" class="form-label fw-semibold">
                Tanggal Bayar
            </label>

            <input type="date" name="tanggal_bayar" id="tanggal_bayar"
                   class="form-control"
                   value="<?= htmlspecialchars($data['tanggal_bayar'] ?? '') ?>"
                   required>
        </div>

        <div class="mb-3">
            <label for="bulan_tagihan" class="form-label fw-semibold">
                Bulan Tagihan
            </label>

            <input type="text" name="bulan_tagihan" id="bulan_tagihan"
                   class="form-control"
                   value="<?= htmlspecialchars($data['bulan_tagihan'] ?? '') ?>"
                   required>
        </div>

        <div class="mb-3">
            <label for="nominal_tagihan" class="form-label fw-semibold">
                Nominal Pembayaran (Rp)
            </label>

            <input type="number" name="nominal_tagihan" id="nominal_tagihan"
                   class="form-control"
                   value="<?= htmlspecialchars($data['nominal_tagihan'] ?? '') ?>"
                   min="0" required>
        </div>

        <div class="mb-4">
            <label for="status_pembayaran" class="form-label fw-semibold">
                Status Pembayaran
            </label>

            <select name="status_pembayaran" id="status_pembayaran"
                    class="form-select" required>
                <option value="belum lunas"
                    <?= ($data['status_pembayaran'] == 'belum lunas') ? 'selected' : '' ?>>
                    Belum Lunas / Pending
                </option>

                <option value="lunas"
                    <?= ($data['status_pembayaran'] == 'lunas') ? 'selected' : '' ?>>
                    Lunas
                </option>
            </select>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button type="submit" name="update"
                    class="btn btn-primary px-4 py-2">
                Simpan Perubahan
            </button>

            <a href="pembayaran_tampil.php"
               class="btn btn-outline-secondary px-4 py-2">
                Batal & Kembali
            </a>
        </div>

    </form>
</div>

<?php } else { ?>

<div class="content-panel text-center py-5">
    <h5 class="fw-bold">Data pembayaran tidak ditemukan</h5>
    <p class="text-secondary">
        Data mungkin sudah dihapus atau ID pembayaran tidak valid.
    </p>
    <a href="pembayaran_tampil.php" class="btn btn-primary">
        Kembali ke Riwayat
    </a>
</div>

<?php } ?>

<?php include 'footer.php'; ?>