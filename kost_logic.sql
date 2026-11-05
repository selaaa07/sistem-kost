DROP DATABASE IF EXISTS kost_logic;
CREATE DATABASE kost_logic;
USE kost_logic;
-- tolong klik petir di MySQL Workbench untuk menjalankan query ini agar database kost_logic dibuat dan digunakan.

-- 1. TABEL ADMIN (BARU)

CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(50)
);

-- 2. TABEL KAMAR (DITAMBAH KOLOM PASSWORD)

CREATE TABLE kamar (
    id_kamar INT PRIMARY KEY,
    fasilitas VARCHAR(200),
    harga DECIMAL(12, 2),
    status_kamar ENUM('terisi', 'kosong'),
    password VARCHAR(50) DEFAULT 'kost123'
);

-- 3. TABEL PENGHUNI (TETAP)

CREATE TABLE penghuni (
    id_penghuni INT AUTO_INCREMENT PRIMARY KEY,
    nama_penghuni VARCHAR(100),
    no_hp VARCHAR(15),
    id_kamar INT,
    FOREIGN KEY (id_kamar) REFERENCES kamar(id_kamar)
);

-- 4. TABEL PEMBAYARAN (DITAMBAH BANK & BUKTI BAYAR)

CREATE TABLE pembayaran (
    id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
    id_penghuni INT,
    bulan_tagihan VARCHAR(20),
    nominal_tagihan DECIMAL(12,2),
    status_pembayaran ENUM('lunas', 'belum lunas'),
    tanggal_bayar DATE,
    nama_bank VARCHAR(50) DEFAULT NULL,
    no_rekening VARCHAR(50) DEFAULT NULL,
    bukti_bayar VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (id_penghuni) REFERENCES penghuni(id_penghuni)
);


-- INSERT DATA DUMMY (DATA AWAL)

-- Data Admin
INSERT INTO admin (username, password) VALUES 
('admin', 'admin123');

-- Data Kamar
INSERT INTO kamar (id_kamar, fasilitas, harga, status_kamar) VALUES
(101, 'Lantai 1 - Kipas Angin, Kasur, Lemari, Kamar Mandi Luar', 600000.00, 'terisi'),
(102, 'Lantai 1 - Kipas Angin, Kasur, Lemari, Kamar Mandi Luar', 600000.00, 'terisi'),
(103, 'Lantai 1 - Kipas Angin, Kasur, Lemari, Kamar Mandi Luar', 600000.00, 'terisi'),
(104, 'Lantai 1 - Kipas Angin, Kasur, Lemari, Kamar Mandi Luar', 600000.00, 'kosong'),
(105, 'Lantai 1 - Kipas Angin, Kasur, Lemari, Kamar Mandi Luar', 600000.00, 'kosong'),
(201, 'Lantai 2 - AC, Kasur, Lemari, Meja, Kamar Mandi Dalam', 1200000.00, 'terisi'),
(202, 'Lantai 2 - AC, Kasur, Lemari, Meja, Kamar Mandi Dalam', 1200000.00, 'terisi'),
(203, 'Lantai 2 - AC, Kasur, Lemari, Meja, Kamar Mandi Dalam', 1200000.00, 'terisi'),
(204, 'Lantai 2 - AC, Kasur, Lemari, Meja, Kamar Mandi Dalam', 1200000.00, 'kosong'),
(205, 'Lantai 2 - AC, Kasur, Lemari, Meja, Kamar Mandi Dalam', 1200000.00, 'kosong');

-- Data Penghuni
INSERT INTO penghuni (nama_penghuni, no_hp, id_kamar) VALUES
('Budi Santoso', '081234567890', 101),
('Andi Wijaya', '081298765432', 102),
('Siti Aminah', '085712341234', 103),
('Rina Melati', '087811223344', 201),
('Doni Pratama', '081399887766', 202),
('Fajar Hidayat', '089644556677', 203);

-- Data Pembayaran
INSERT INTO pembayaran (id_penghuni, bulan_tagihan, nominal_tagihan, status_pembayaran, tanggal_bayar, nama_bank, no_rekening, bukti_bayar) VALUES
(1, 'Oktober 2026', 600000.00, 'lunas', '2026-10-01', 'BNI', '0882391045', 'bukti_1_1793813118.jpg'),
(2, 'Oktober 2026', 600000.00, 'belum lunas', NULL, NULL, NULL, NULL),
(3, 'Oktober 2026', 600000.00, 'lunas', '2026-10-05', 'BRI', '034101004829501', 'bukti_3_1793812490.jpg'),
(4, 'Oktober 2026', 1200000.00, 'lunas', '2026-10-02', 'Mandiri', '1370098234112', 'bukti_4_1793812440.jpg'),
(5, 'Oktober 2026', 1200000.00, 'belum lunas', NULL, NULL, NULL, NULL),
(6, 'Oktober 2026', 1200000.00, 'lunas', '2026-10-10', 'BCA', '8720451923', 'bukti_6_1793811717.jpg');