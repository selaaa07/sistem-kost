<?php 
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; 

if (isset($_POST['simpan'])) {
    $id_penghuni       = mysqli_real_escape_string($koneksi, $_POST['id_penghuni']);
    $tanggal_bayar     = mysqli_real_escape_string($koneksi, $_POST['tanggal_bayar']);
    $bulan_tagihan     = mysqli_real_escape_string($koneksi, $_POST['bulan_tagihan']);
    $nominal_tagihan   = mysqli_real_escape_string($koneksi, $_POST['nominal_tagihan']);
    $status_pembayaran = mysqli_real_escape_string($koneksi, $_POST['status_pembayaran']);

    $nama_file_bukti = '';

    if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['bukti_bayar']['tmp_name'];
        $file_name = $_FILES['bukti_bayar']['name'];
        $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($ext, $allowed)) {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            $nama_file_baru = 'bukti_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $tujuan_upload  = 'uploads/' . $nama_file_baru;

            if (move_uploaded_file($file_tmp, $tujuan_upload)) {
                $nama_file_bukti = $nama_file_baru;
            } else {
                $error_msg = "Gagal mengunggah file bukti transfer ke folder uploads.";
            }
        } else {
            $error_msg = "Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau PDF.";
        }
    }

    if (!isset($error_msg)) {
        $bukti_sql = ($nama_file_bukti === '') ? "NULL" : "'$nama_file_bukti'";

        $query_insert = "INSERT INTO pembayaran (id_penghuni, tanggal_bayar, bulan_tagihan, nominal_tagihan, bukti_bayar, status_pembayaran) 
                         VALUES ('$id_penghuni', '$tanggal_bayar', '$bulan_tagihan', '$nominal_tagihan', $bukti_sql, '$status_pembayaran')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            header("Location: pembayaran_tampil.php");
            exit;
        } else {
            $error_msg = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    }
}

include 'header.php'; 
?>

<div class="mb-4">
    <p class="text-primary fw-semibold small mb-2">
        KELOLA DATA KOST
    </p>

    <h2 class="fw-bold mb-2" style="color: #243b64;">
        Catat Pembayaran Baru
    </h2>

    <p class="text-secondary mb-0">
        Informasi Pembayaran
    </p>
</div>

<?php if (isset($error_msg)) { ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error_msg) ?>
    </div>
<?php } ?>

<form action="" method="POST" enctype="multipart/form-data">

    <div class="mb-3">
        <label for="id_penghuni" class="form-label fw-semibold">
            Pilih Penghuni Kost
        </label>

        <select
            name="id_penghuni"
            id="id_penghuni"
            class="form-select"
            required
        >
            <option value="">-- Pilih Nama Penghuni --</option>

            <?php
            $q_penghuni = mysqli_query(
                $koneksi,
                "SELECT id_penghuni, nama_penghuni, id_kamar FROM penghuni"
            );

            while ($p = mysqli_fetch_assoc($q_penghuni)) {
                echo "<option value='" . htmlspecialchars($p['id_penghuni'], ENT_QUOTES, 'UTF-8') . "'>"
                    . htmlspecialchars($p['nama_penghuni'], ENT_QUOTES, 'UTF-8')
                    . " (Kamar "
                    . htmlspecialchars($p['id_kamar'], ENT_QUOTES, 'UTF-8')
                    . ")</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="tanggal_bayar" class="form-label fw-semibold">
            Tanggal Bayar
        </label>

        <input
            type="date"
            name="tanggal_bayar"
            id="tanggal_bayar"
            class="form-control"
            required
        >
    </div>

    <div class="mb-3">
        <label for="bulan_tagihan" class="form-label fw-semibold">
            Bulan Tagihan
        </label>

        <input
            type="text"
            name="bulan_tagihan"
            id="bulan_tagihan"
            class="form-control"
            placeholder="Contoh: Oktober 2026"
            required
        >
    </div>

    <div class="mb-3">
        <label for="nominal_tagihan" class="form-label fw-semibold">
            Nominal Pembayaran (Rp)
        </label>

        <input
            type="number"
            name="nominal_tagihan"
            id="nominal_tagihan"
            class="form-control"
            placeholder="Contoh: 1500000"
            min="0"
            required
        >
    </div>

    <div class="mb-3">
        <label for="bukti_bayar" class="form-label fw-semibold">
            Upload Bukti Transfer (JPG/PNG/PDF)
        </label>
        <input
            type="file"
            name="bukti_bayar"
            id="bukti_bayar"
            class="form-control"
            accept=".jpg,.jpeg,.png,.pdf"
        >
        <div class="form-text">Opsional. Kosongkan jika pembayaran tunai atau belum ada bukti.</div>
    </div>

    <div class="mb-4">
        <label for="status_pembayaran" class="form-label fw-semibold">
            Status Awal Pembayaran
        </label>

        <select
            name="status_pembayaran"
            id="status_pembayaran"
            class="form-select"
            required
        >
            <option value="belum lunas">Belum Lunas / Pending</option>
            <option value="menunggu">Menunggu Verifikasi</option>
            <option value="lunas">Lunas</option>
        </select>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <button
            type="submit"
            class="btn btn-primary px-4 py-2"
            name="simpan"
        >
            Simpan Transaksi
        </button>

        <a
            href="pembayaran_tampil.php"
            class="btn btn-outline-secondary px-4 py-2"
        >
            Batal & Kembali
        </a>
    </div>

</form>

<?php include 'footer.php'; ?>