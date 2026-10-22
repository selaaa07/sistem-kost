CREATE DATABASE kost_logic;
USE kost_logic;

CREATE TABLE kamar (
    id_kamar INT PRIMARY KEY,
    fasilitas VARCHAR(200),
    harga DECIMAL(12, 2),
    status_kamar ENUM('terisi', 'kosong')
);

CREATE TABLE penghuni (
    id_penghuni INT AUTO_INCREMENT PRIMARY KEY,
    nama_penghuni VARCHAR(100),
    no_hp VARCHAR(15),
    id_kamar INT,
    FOREIGN KEY (id_kamar) REFERENCES kamar(id_kamar)
);

CREATE TABLE pembayaran (
    id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
    id_penghuni INT,
    bulan_tagihan VARCHAR(20),
    nominal_tagihan DECIMAL(12,2),
    status_pembayaran ENUM('lunas', 'belum lunas'),
    tanggal_bayar DATE,
    FOREIGN KEY (id_penghuni) REFERENCES penghuni(id_penghuni)
);

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

INSERT INTO penghuni (nama_penghuni, no_hp, id_kamar) VALUES
('Budi Santoso', '081234567890', 101),
('Andi Wijaya', '081298765432', 102),
('Siti Aminah', '085712341234', 103),
('Rina Melati', '087811223344', 201),
('Doni Pratama', '081399887766', 202),
('Fajar Hidayat', '089644556677', 203);

INSERT INTO pembayaran (id_penghuni, bulan_tagihan, nominal_tagihan, status_pembayaran, tanggal_bayar) VALUES
(1, 'Oktober 2026', 600000.00, 'lunas', '2026-10-01'),
(2, 'Oktober 2026', 600000.00, 'belum lunas', NULL),
(3, 'Oktober 2026', 600000.00, 'lunas', '2026-10-05'),
(4, 'Oktober 2026', 1200000.00, 'lunas', '2026-10-02'),
(5, 'Oktober 2026', 1200000.00, 'belum lunas', NULL),
(6, 'Oktober 2026', 1200000.00, 'lunas', '2026-10-10');