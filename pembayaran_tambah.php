<?php 
include 'koneksi.php'; 

if (isset($_POST['simpan'])) {
    $id_penghuni       = $_POST['id_penghuni'];
    $tanggal_bayar     = $_POST['tanggal_bayar'];
    $bulan_tagihan     = $_POST['bulan_tagihan'];
    $nominal_tagihan   = $_POST['nominal_tagihan'];
    $status_pembayaran = $_POST['status_pembayaran'];

    $query_insert = "INSERT INTO pembayaran (id_penghuni, tanggal_bayar, bulan_tagihan, nominal_tagihan, status_pembayaran) 
                     VALUES ('$id_penghuni', '$tanggal_bayar', '$bulan_tagihan', '$nominal_tagihan', '$status_pembayaran')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        header("Location: pembayaran_tampil.php");
        exit;
    } else {
        $error_msg = "Gagal menyimpan data: " . mysqli_error($koneksi);
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
        Masukkan informasi transaksi pembayaran penghuni Kost Logic.
    </p>
</div>

<div class="content-panel">
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Informasi Pembayaran</h5>
        <p class="text-secondary small mb-0">
            Lengkapi formulir berikut untuk mencatat transaksi baru.
        </p>
    </div>

    <?php if (isset($error_msg)) { ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_msg) ?>
        </div>
    <?php } ?>

    <form action="" method="POST">

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
                <option value="lunas">Lunas</option>
            </select>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button
                type="submit"
                name="simpan"
                class="btn btn-primary px-4 py-2"
            >
                Simpan Transaksi
            </button>

            <a
                href="pembayaran_tampil.php"
                class="btn btn-outline-secondary px-4 py-2"
            >
                Kembali
            </a>
        </div>

    </form>
</div>

<?php include 'footer.php'; ?>