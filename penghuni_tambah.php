<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama_penghuni = mysqli_real_escape_string($koneksi, $_POST['nama_penghuni']);
    $no_hp         = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $id_kamar      = mysqli_real_escape_string($koneksi, $_POST['id_kamar']);
    $tanggal_masuk = mysqli_real_escape_string($koneksi, $_POST['tanggal_masuk']);

    $query_insert = "INSERT INTO penghuni (nama_penghuni, no_hp, id_kamar, tanggal_masuk) 
                     VALUES ('$nama_penghuni', '$no_hp', '$id_kamar', '$tanggal_masuk')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        $id_penghuni_baru = mysqli_insert_id($koneksi);

        mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'terisi' WHERE id_kamar = '$id_kamar'");

        // Otomatis buat tagihan perdana sesuai bulan tanggal_masuk
        $daftar_bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulan_masuk = $daftar_bulan[(int)date('n', strtotime($tanggal_masuk))] . ' ' . date('Y', strtotime($tanggal_masuk));

        $q_kamar = mysqli_query($koneksi, "SELECT harga FROM kamar WHERE id_kamar = '$id_kamar'");
        $d_kamar = mysqli_fetch_assoc($q_kamar);
        $harga_kamar = $d_kamar['harga'] ?? 0;

        mysqli_query($koneksi, "INSERT INTO pembayaran (id_penghuni, bulan_tagihan, nominal_tagihan, status_pembayaran, tanggal_bayar, bukti_bayar) 
                                VALUES ('$id_penghuni_baru', '$bulan_masuk', '$harga_kamar', 'belum lunas', NULL, NULL)");
        
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

        <div class="mb-3">
            <label for="tanggal_masuk" class="form-label fw-semibold">
                Tanggal Masuk Sewa
            </label>
            <input
                type="date"
                class="form-control"
                id="tanggal_masuk"
                name="tanggal_masuk"
                value="<?= date('Y-m-d') ?>"
                required
            >
            <div class="form-text">Tanggal ini menjadi acuan tenggat tagihan setiap bulannya.</div>
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
                Batal & Kembali
            </a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>