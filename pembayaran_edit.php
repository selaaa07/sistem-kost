<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Ambil ID pembayaran dari URL
$id_pembayaran = mysqli_real_escape_string($koneksi, $_GET['id'] ?? '');

$query_tampil = "SELECT * FROM pembayaran WHERE id_pembayaran = '$id_pembayaran'";
$result = mysqli_query($koneksi, $query_tampil);
$data = mysqli_fetch_assoc($result);

// Proses simpan perubahan data
if (isset($_POST['update'])) {
    $id_penghuni       = mysqli_real_escape_string($koneksi, $_POST['id_penghuni']);
    $tanggal_bayar     = mysqli_real_escape_string($koneksi, $_POST['tanggal_bayar']);
    $bulan_tagihan     = mysqli_real_escape_string($koneksi, $_POST['bulan_tagihan']);
    $nominal_tagihan   = mysqli_real_escape_string($koneksi, $_POST['nominal_tagihan']);
    $status_pembayaran = mysqli_real_escape_string($koneksi, $_POST['status_pembayaran']);

    // Default gunakan bukti bayar yang lama jika admin tidak upload file baru
    $nama_file_bukti = $data['bukti_bayar'] ?? '';

    // Cek apakah ada file bukti transfer baru yang diupload
    if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['bukti_bayar']['tmp_name'];
        $file_name = $_FILES['bukti_bayar']['name'];
        $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($ext, $allowed)) {
            // Buat folder uploads otomatis jika belum ada
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            // Buat nama file unik agar tidak bentrok
            $nama_file_baru = 'bukti_' . $id_pembayaran . '_' . time() . '.' . $ext;
            $tujuan_upload  = 'uploads/' . $nama_file_baru;

            if (move_uploaded_file($file_tmp, $tujuan_upload)) {
                // Hapus file bukti lama jika ada
                if (!empty($data['bukti_bayar']) && file_exists('uploads/' . $data['bukti_bayar'])) {
                    unlink('uploads/' . $data['bukti_bayar']);
                }
                $nama_file_bukti = $nama_file_baru;
            } else {
                $error_msg = "Gagal memindahkan file ke folder uploads.";
            }
        } else {
            $error_msg = "Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau PDF.";
        }
    }

    if (!isset($error_msg)) {
        $query_update = "UPDATE pembayaran SET
                            id_penghuni       = '$id_penghuni',
                            tanggal_bayar     = '$tanggal_bayar',
                            bulan_tagihan     = '$bulan_tagihan',
                            nominal_tagihan   = '$nominal_tagihan',
                            bukti_bayar       = '$nama_file_bukti',
                            status_pembayaran = '$status_pembayaran'
                         WHERE id_pembayaran  = '$id_pembayaran'";

        if (mysqli_query($koneksi, $query_update)) {
            header("Location: pembayaran_tampil.php");
            exit;
        } else {
            $error_msg = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
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
</div>

<?php if (isset($error_msg)) { ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error_msg) ?>
    </div>
<?php } ?>

<?php if ($data) { ?>

    <!-- Wajib menggunakan enctype="multipart/form-data" untuk upload file -->
    <form action="" method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="id_penghuni" class="form-label fw-semibold">
                Penghuni Kost
            </label>
            <select name="id_penghuni" id="id_penghuni" class="form-select" required>
                <?php
                $q_penghuni = mysqli_query(
                    $koneksi,
                    "SELECT id_penghuni, nama_penghuni, id_kamar FROM penghuni"
                );

                while ($p = mysqli_fetch_assoc($q_penghuni)) {
                    $selected = ($p['id_penghuni'] == $data['id_penghuni']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($p['id_penghuni']) . "' $selected>" 
                         . htmlspecialchars($p['nama_penghuni']) . " (Kamar: " . htmlspecialchars($p['id_kamar']) . ")"
                         . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal_bayar" class="form-label fw-semibold">Tanggal Bayar</label>
            <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control" 
                   value="<?= htmlspecialchars($data['tanggal_bayar'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="bulan_tagihan" class="form-label fw-semibold">Bulan Tagihan</label>
            <input type="text" name="bulan_tagihan" id="bulan_tagihan" class="form-control" 
                   placeholder="Contoh: November 2026"
                   value="<?= htmlspecialchars($data['bulan_tagihan'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="nominal_tagihan" class="form-label fw-semibold">Nominal Tagihan (Rp)</label>
            <input type="number" name="nominal_tagihan" id="nominal_tagihan" class="form-control" 
                   value="<?= htmlspecialchars($data['nominal_tagihan'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="bukti_bayar" class="form-label fw-semibold">Upload Bukti Transfer (JPG/PNG/PDF)</label>
            <?php if (!empty($data['bukti_bayar'])) { ?>
                <div class="mb-2">
                    <span class="small text-secondary">Bukti saat ini: </span>
                    <a href="uploads/<?= htmlspecialchars($data['bukti_bayar']) ?>" target="_blank" class="badge text-bg-info text-white text-decoration-none">
                        Lihat File Saat Ini
                    </a>
                </div>
            <?php } ?>
            <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <div class="form-text">Biarkan kosong jika tidak ingin mengubah bukti transfer.</div>
        </div>

        <div class="mb-4">
            <label for="status_pembayaran" class="form-label fw-semibold">Status Pembayaran</label>
            <select name="status_pembayaran" id="status_pembayaran" class="form-select" required>
                <?php $status_skrg = strtolower($data['status_pembayaran'] ?? ''); ?>
                <option value="belum lunas" <?= ($status_skrg == 'belum lunas' || $status_skrg == 'pending') ? 'selected' : '' ?>>Belum Lunas</option>
                <option value="lunas" <?= ($status_skrg == 'lunas') ? 'selected' : '' ?>>Lunas</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" name="update" class="btn btn-primary px-4">Simpan Perubahan</button>
            <a href="pembayaran_tampil.php" class="btn btn-secondary px-4">Batal</a>
        </div>

    </form>

<?php } else { ?>
    <div class="alert alert-warning">
        Data pembayaran tidak ditemukan. <a href="pembayaran_tampil.php">Kembali</a>
    </div>
<?php } ?>

<?php include 'footer.php'; ?>