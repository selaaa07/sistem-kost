-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: kost_logic
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin123'),(2,'admin','admin123');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kamar`
--

DROP TABLE IF EXISTS `kamar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kamar` (
  `id_kamar` int NOT NULL,
  `fasilitas` varchar(200) DEFAULT NULL,
  `harga` decimal(12,2) DEFAULT NULL,
  `status_kamar` enum('terisi','kosong') DEFAULT NULL,
  `password` varchar(50) DEFAULT 'kost123',
  PRIMARY KEY (`id_kamar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kamar`
--

LOCK TABLES `kamar` WRITE;
/*!40000 ALTER TABLE `kamar` DISABLE KEYS */;
INSERT INTO `kamar` VALUES (101,'Lantai 1 - Kipas Angin, Kasur, Lemari, Kamar Mandi Luar',600000.00,'terisi','kost123'),(102,'Lantai 1 - Kipas Angin, Kasur, Lemari, Kamar Mandi Luar',600000.00,'terisi','kost123'),(103,'Lantai 1 - Kipas Angin, Kasur, Lemari, Kamar Mandi Luar',600000.00,'terisi','kost123'),(104,'Lantai 1 - Kipas Angin, Kasur, Lemari, Kamar Mandi Luar',600000.00,'kosong','kost123'),(105,'Lantai 1 - Kipas Angin, Kasur, Lemari, Kamar Mandi Luar',600000.00,'kosong','kost123'),(201,'Lantai 2 - AC, Kasur, Lemari, Meja, Kamar Mandi Dalam',1200000.00,'terisi','kost123'),(202,'Lantai 2 - AC, Kasur, Lemari, Meja, Kamar Mandi Dalam',1200000.00,'terisi','kost123'),(203,'Lantai 2 - AC, Kasur, Lemari, Meja, Kamar Mandi Dalam',1200000.00,'terisi','kost123'),(204,'Lantai 2 - AC, Kasur, Lemari, Meja, Kamar Mandi Dalam',1200000.00,'kosong','kost123'),(205,'Lantai 2 - AC, Kasur, Lemari, Meja, Kamar Mandi Dalam',1200000.00,'kosong','kost123');
/*!40000 ALTER TABLE `kamar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaran`
--

DROP TABLE IF EXISTS `pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL AUTO_INCREMENT,
  `id_penghuni` int DEFAULT NULL,
  `bulan_tagihan` varchar(20) DEFAULT NULL,
  `nominal_tagihan` decimal(12,2) DEFAULT NULL,
  `status_pembayaran` enum('lunas','belum lunas') DEFAULT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `nama_bank` varchar(50) DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  KEY `id_penghuni` (`id_penghuni`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id_penghuni`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran`
--

LOCK TABLES `pembayaran` WRITE;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
INSERT INTO `pembayaran` VALUES (1,1,'Oktober 2026',600000.00,'lunas','2026-10-01',NULL,NULL,NULL),(2,2,'Oktober 2026',600000.00,'belum lunas',NULL,NULL,NULL,NULL),(3,3,'Oktober 2026',600000.00,'lunas','2026-10-05',NULL,NULL,NULL),(4,4,'Oktober 2026',1200000.00,'lunas','2026-10-02',NULL,NULL,NULL),(5,5,'Oktober 2026',1200000.00,'belum lunas',NULL,NULL,NULL,NULL),(6,6,'Oktober 2026',1200000.00,'lunas','2026-10-10',NULL,NULL,NULL);
/*!40000 ALTER TABLE `pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penghuni`
--

DROP TABLE IF EXISTS `penghuni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penghuni` (
  `id_penghuni` int NOT NULL AUTO_INCREMENT,
  `nama_penghuni` varchar(100) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `id_kamar` int DEFAULT NULL,
  PRIMARY KEY (`id_penghuni`),
  KEY `id_kamar` (`id_kamar`),
  CONSTRAINT `penghuni_ibfk_1` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penghuni`
--

LOCK TABLES `penghuni` WRITE;
/*!40000 ALTER TABLE `penghuni` DISABLE KEYS */;
INSERT INTO `penghuni` VALUES (1,'Budi Santoso','081234567890',101),(2,'Andi Wijaya','081298765432',102),(3,'Siti Aminah','085712341234',103),(4,'Rina Melati','087811223344',201),(5,'Doni Pratama','081399887766',202),(6,'Fajar Hidayat','089644556677',203);
/*!40000 ALTER TABLE `penghuni` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-04 14:08:44
