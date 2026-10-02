-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_surat_izin_smkn1
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `pengajuan_izin_id` bigint(20) unsigned DEFAULT NULL,
  `aksi` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_pengajuan_izin_id_foreign` (`pengajuan_izin_id`),
  KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `activity_logs_aksi_index` (`aksi`),
  CONSTRAINT `activity_logs_pengajuan_izin_id_foreign` FOREIGN KEY (`pengajuan_izin_id`) REFERENCES `pengajuan_izins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,1,'approve','Guru Administrator Utama menyetujui izin siswa Adinda Afifah Putri. Nomor surat: SIZIN-2026-000001','127.0.0.1','2026-08-27 19:09:03','2026-08-27 19:09:03'),(2,1,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 19:09:30','2026-08-27 19:09:30'),(3,1,NULL,'logout','User logout.','127.0.0.1','2026-08-27 19:09:51','2026-08-27 19:09:51'),(4,3,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 19:10:01','2026-08-27 19:10:01'),(5,3,1,'download_surat','Siswa mendownload surat izin.','127.0.0.1','2026-08-27 19:10:07','2026-08-27 19:10:07'),(6,3,1,'download_surat','Siswa mendownload surat izin.','127.0.0.1','2026-08-27 19:10:12','2026-08-27 19:10:12'),(7,3,2,'pengajuan_izin','Siswa Adinda Afifah Putri mengajukan surat izin dengan verifikasi wajah.','127.0.0.1','2026-08-27 19:10:47','2026-08-27 19:10:47'),(8,3,NULL,'logout','User logout.','127.0.0.1','2026-08-27 19:10:50','2026-08-27 19:10:50'),(9,2,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 19:11:01','2026-08-27 19:11:01'),(10,2,2,'approve','Guru Bapak Guru Piket menyetujui izin siswa Adinda Afifah Putri. Nomor surat: SIZIN-2026-000002','127.0.0.1','2026-08-27 19:11:10','2026-08-27 19:11:10'),(11,2,NULL,'logout','User logout.','127.0.0.1','2026-08-27 19:11:48','2026-08-27 19:11:48'),(12,3,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 19:11:57','2026-08-27 19:11:57'),(13,3,2,'download_surat','Siswa mendownload surat izin.','127.0.0.1','2026-08-27 19:11:59','2026-08-27 19:11:59'),(14,3,2,'download_surat','Siswa mendownload surat izin.','127.0.0.1','2026-08-27 19:20:58','2026-08-27 19:20:58'),(15,3,2,'download_surat','Siswa mendownload surat izin.','127.0.0.1','2026-08-27 19:21:00','2026-08-27 19:21:00'),(16,3,2,'download_surat','Siswa mendownload surat izin.','127.0.0.1','2026-08-27 19:21:01','2026-08-27 19:21:01'),(17,3,NULL,'logout','User logout.','127.0.0.1','2026-08-27 19:21:03','2026-08-27 19:21:03'),(18,2,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 19:21:21','2026-08-27 19:21:21'),(19,2,NULL,'logout','User logout.','127.0.0.1','2026-08-27 19:21:33','2026-08-27 19:21:33'),(20,3,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 19:21:43','2026-08-27 19:21:43'),(21,3,2,'download_surat','Siswa mendownload surat izin.','127.0.0.1','2026-08-27 19:21:46','2026-08-27 19:21:46'),(22,3,2,'download_surat','Siswa mendownload surat izin.','127.0.0.1','2026-08-27 19:21:50','2026-08-27 19:21:50'),(23,3,1,'download_surat','Siswa mendownload surat izin.','127.0.0.1','2026-08-27 19:23:48','2026-08-27 19:23:48'),(24,3,2,'download_surat','Siswa melihat/mendownload surat izin.','127.0.0.1','2026-08-27 19:24:47','2026-08-27 19:24:47'),(25,3,NULL,'logout','User logout.','127.0.0.1','2026-08-27 19:27:13','2026-08-27 19:27:13'),(26,3,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 19:27:35','2026-08-27 19:27:35'),(27,3,NULL,'logout','User logout.','127.0.0.1','2026-08-27 19:28:24','2026-08-27 19:28:24'),(28,2,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 19:28:34','2026-08-27 19:28:34'),(29,1,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 23:05:26','2026-08-27 23:05:26'),(30,1,NULL,'logout','User logout.','127.0.0.1','2026-08-27 23:05:38','2026-08-27 23:05:38'),(31,2,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 23:05:46','2026-08-27 23:05:46'),(32,2,NULL,'logout','User logout.','127.0.0.1','2026-08-27 23:31:42','2026-08-27 23:31:42'),(33,3,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 23:31:53','2026-08-27 23:31:53'),(34,3,NULL,'logout','User logout.','127.0.0.1','2026-08-27 23:32:10','2026-08-27 23:32:10'),(35,3,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 23:45:02','2026-08-27 23:45:02'),(36,3,3,'pengajuan_izin','Siswa Adinda Afifah Putri mengajukan surat izin dengan verifikasi wajah.','127.0.0.1','2026-08-27 23:45:21','2026-08-27 23:45:21'),(37,3,NULL,'logout','User logout.','127.0.0.1','2026-08-27 23:45:25','2026-08-27 23:45:25'),(38,2,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 23:45:35','2026-08-27 23:45:35'),(39,2,3,'approve','Guru Bapak Guru Piket menyetujui izin siswa Adinda Afifah Putri. Nomor surat: SIZIN-2026-000003','127.0.0.1','2026-08-27 23:45:50','2026-08-27 23:45:50'),(40,2,NULL,'logout','User logout.','127.0.0.1','2026-08-27 23:46:07','2026-08-27 23:46:07'),(41,1,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 23:46:18','2026-08-27 23:46:18'),(42,1,NULL,'logout','User logout.','127.0.0.1','2026-08-27 23:48:33','2026-08-27 23:48:33'),(43,2,NULL,'login','User berhasil login.','127.0.0.1','2026-08-27 23:48:51','2026-08-27 23:48:51'),(44,2,NULL,'login','User berhasil login.','127.0.0.1','2026-09-24 17:56:36','2026-09-24 17:56:36'),(45,2,NULL,'logout','User logout.','127.0.0.1','2026-09-24 18:05:38','2026-09-24 18:05:38'),(46,1,NULL,'login','User berhasil login.','127.0.0.1','2026-09-24 18:06:39','2026-09-24 18:06:39'),(47,1,NULL,'logout','User logout.','127.0.0.1','2026-09-24 18:09:50','2026-09-24 18:09:50'),(48,3,NULL,'login','User berhasil login.','127.0.0.1','2026-09-24 18:10:09','2026-09-24 18:10:09'),(49,3,4,'pengajuan_izin','Siswa Adinda Afifah Putri mengajukan surat izin dengan verifikasi wajah.','127.0.0.1','2026-09-24 18:10:58','2026-09-24 18:10:58'),(50,3,NULL,'logout','User logout.','127.0.0.1','2026-09-24 18:11:06','2026-09-24 18:11:06'),(51,2,NULL,'login','User berhasil login.','127.0.0.1','2026-09-24 18:11:16','2026-09-24 18:11:16'),(52,2,4,'approve','Guru Bapak Guru Piket menyetujui izin siswa Adinda Afifah Putri. Nomor surat: SIZIN-2026-000004','127.0.0.1','2026-09-24 18:11:47','2026-09-24 18:11:47'),(53,2,NULL,'logout','User logout.','127.0.0.1','2026-09-24 18:15:07','2026-09-24 18:15:07'),(54,3,NULL,'login','User berhasil login.','127.0.0.1','2026-09-24 18:15:17','2026-09-24 18:15:17'),(55,3,3,'download_surat','Siswa melihat/mendownload surat izin.','127.0.0.1','2026-09-24 18:15:19','2026-09-24 18:15:19'),(56,3,NULL,'logout','User logout.','127.0.0.1','2026-09-24 18:27:25','2026-09-24 18:27:25'),(57,1,NULL,'login','User berhasil login.','127.0.0.1','2026-09-24 18:27:36','2026-09-24 18:27:36'),(58,1,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 16:42:30','2026-10-01 16:42:30'),(59,1,NULL,'logout','User logout.','127.0.0.1','2026-10-01 16:59:02','2026-10-01 16:59:02'),(60,2,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:00:53','2026-10-01 17:00:53'),(61,1,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:03:07','2026-10-01 17:03:07'),(62,1,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:03:10','2026-10-01 17:03:10'),(63,3,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:03:20','2026-10-01 17:03:20'),(64,3,5,'pengajuan_izin','Siswa Adinda Afifah Putri mengajukan surat izin dengan verifikasi wajah.','127.0.0.1','2026-10-01 17:03:51','2026-10-01 17:03:51'),(65,3,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:03:57','2026-10-01 17:03:57'),(66,1,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:04:04','2026-10-01 17:04:04'),(67,1,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:04:08','2026-10-01 17:04:08'),(68,2,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:04:15','2026-10-01 17:04:15'),(69,2,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:06:30','2026-10-01 17:06:30'),(70,4,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:06:39','2026-10-01 17:06:39'),(71,3,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:11:06','2026-10-01 17:11:06'),(72,3,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:11:28','2026-10-01 17:11:28'),(73,3,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:16:01','2026-10-01 17:16:01'),(74,3,7,'pengajuan_izin','Siswa Adinda Afifah Putri mengajukan surat izin dengan verifikasi wajah.','127.0.0.1','2026-10-01 17:16:33','2026-10-01 17:16:33'),(75,3,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:16:41','2026-10-01 17:16:41'),(76,2,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:16:44','2026-10-01 17:16:44'),(77,4,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:19:15','2026-10-01 17:19:15'),(78,2,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:19:17','2026-10-01 17:19:17'),(79,2,NULL,'reject','Guru Bapak Guru Piket menolak izin siswa. Alasan: tidak ada foto wajahh','127.0.0.1','2026-10-01 17:19:44','2026-10-01 17:19:44'),(80,2,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:19:46','2026-10-01 17:19:46'),(81,3,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:19:48','2026-10-01 17:19:48'),(82,3,9,'pengajuan_izin','Siswa Adinda Afifah Putri mengajukan surat izin dengan verifikasi wajah.','127.0.0.1','2026-10-01 17:20:28','2026-10-01 17:20:28'),(83,3,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:20:30','2026-10-01 17:20:30'),(84,2,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:20:32','2026-10-01 17:20:32'),(85,2,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:21:49','2026-10-01 17:21:49'),(86,3,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:21:52','2026-10-01 17:21:52'),(87,3,11,'pengajuan_izin','Siswa Adinda Afifah Putri mengajukan surat izin dengan verifikasi wajah.','127.0.0.1','2026-10-01 17:22:11','2026-10-01 17:22:11'),(88,3,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:22:18','2026-10-01 17:22:18'),(89,2,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:22:20','2026-10-01 17:22:20'),(90,2,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:28:47','2026-10-01 17:28:47'),(91,1,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:28:51','2026-10-01 17:28:51'),(92,1,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:29:33','2026-10-01 17:29:33'),(93,3,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:29:36','2026-10-01 17:29:36'),(94,3,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:29:41','2026-10-01 17:29:41'),(95,1,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:29:43','2026-10-01 17:29:43'),(96,2,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:30:29','2026-10-01 17:30:29'),(97,3,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:30:32','2026-10-01 17:30:32'),(98,3,12,'pengajuan_izin','Siswa Adinda Afifah Putri mengajukan surat izin dengan verifikasi wajah.','127.0.0.1','2026-10-01 17:30:55','2026-10-01 17:30:55'),(99,1,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:31:06','2026-10-01 17:31:06'),(100,2,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:31:09','2026-10-01 17:31:09'),(101,3,NULL,'logout','User logout.','127.0.0.1','2026-10-01 17:31:18','2026-10-01 17:31:18'),(102,2,NULL,'login','User berhasil login.','127.0.0.1','2026-10-01 17:31:20','2026-10-01 17:31:20');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_add_role_to_users_table',1),(5,'2024_01_01_000002_create_siswas_table',1),(6,'2024_01_01_000003_create_pengajuan_izins_table',1),(7,'2024_01_01_000004_create_verifikasi_wajahs_table',1),(8,'2024_01_01_000005_create_surat_izins_table',1),(9,'2024_01_01_000006_create_activity_logs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengajuan_izins`
--

DROP TABLE IF EXISTS `pengajuan_izins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pengajuan_izins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint(20) unsigned NOT NULL,
  `guru_id` bigint(20) unsigned DEFAULT NULL,
  `alasan_izin` text NOT NULL,
  `tanggal_izin` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `durasi_menit` int(11) NOT NULL DEFAULT 0,
  `status` enum('menunggu','disetujui','ditolak','selesai') NOT NULL DEFAULT 'menunggu',
  `catatan_guru` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengajuan_izins_siswa_id_foreign` (`siswa_id`),
  KEY `pengajuan_izins_guru_id_foreign` (`guru_id`),
  KEY `pengajuan_izins_status_created_at_index` (`status`,`created_at`),
  KEY `pengajuan_izins_tanggal_izin_index` (`tanggal_izin`),
  CONSTRAINT `pengajuan_izins_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pengajuan_izins_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengajuan_izins`
--

LOCK TABLES `pengajuan_izins` WRITE;
/*!40000 ALTER TABLE `pengajuan_izins` DISABLE KEYS */;
INSERT INTO `pengajuan_izins` VALUES (1,1,1,'Test','2026-08-28','08:00:00','10:00:00',120,'disetujui','ok','2026-08-27 19:09:03','2026-08-27 19:09:03'),(2,1,2,'asfasdfsadsadasdasd','2026-08-28','12:10:00','13:10:00',60,'disetujui',NULL,'2026-08-27 19:10:44','2026-08-27 19:11:10'),(3,1,2,'sakit untuk berobat ker umah sakit','2026-08-28','13:46:00','15:45:00',119,'disetujui',NULL,'2026-08-27 23:45:16','2026-08-27 23:45:50'),(4,1,2,'saya sakit','2026-09-25','09:12:00','19:00:00',588,'disetujui',NULL,'2026-09-24 18:10:51','2026-09-24 18:11:47'),(5,1,NULL,'saya sakit kynya','2026-10-02','09:04:00','10:04:00',60,'menunggu',NULL,'2026-10-01 17:03:41','2026-10-01 17:03:41'),(7,1,NULL,'sakit dan sakitttt','2026-10-02','07:16:00','10:16:00',180,'menunggu',NULL,'2026-10-01 17:16:25','2026-10-01 17:16:25'),(9,1,NULL,'saktinasdasdasdasdasda','2026-10-02','09:21:00','11:20:00',119,'menunggu',NULL,'2026-10-01 17:20:25','2026-10-01 17:20:25'),(11,1,NULL,'gsvahavbsbabanaaaa','2026-10-02','09:21:00','12:22:00',181,'menunggu',NULL,'2026-10-01 17:22:06','2026-10-01 17:22:06'),(12,1,NULL,'vvavvabavvbb','2026-10-02','07:30:00','07:31:00',1,'menunggu',NULL,'2026-10-01 17:30:49','2026-10-01 17:30:49');
/*!40000 ALTER TABLE `pengajuan_izins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('3JNoAxRIBIkWGH76Z8EEdhS9HmJOBnlIvRiwKUK4',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiJuR2RPOGQ2ZFAzVFZSeDRDWVdVQVBhMXhmNGhiTHRza2ZrZ1NuM293IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDgwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790899756),('3yG82HNcLSpNuNYheQQrl72GqWniy0jIO0hLsN0H',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.4 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.4 facebookexternalhit/1.1 Facebot Twitterbot/1.0','eyJfdG9rZW4iOiJUazVQQTRXZkNSVTc3ZkxHdkt3RXUxVnJWMWY2OXZDS2hqNGgxejZrIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2luZHVjdGlvbi1wZW5jaWwtaW5kaWNhdGUtc3VzZS50cnljbG91ZGZsYXJlLmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1790900204),('7H5OmBTJfJOLs2jFvmRME3RgAmAzrpW2ic5AlD2g',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJFRVg2ckt1dnppQ08zaW1NYktZRXZQZWN1Uk9QeUJYSXFEQnQ3eG9vIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3N1cmF0aXppbnNtazEubG9jYS5sdFwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1790900044),('8lnPNlQKXU0OoJhQVTGevf4XwmL5QS7ldhrFWM1n',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiIyTXc4bElXMEREdFFsSXlJZTgzT29rbnVuUTlkT3lqOXNtbTRoTmlEIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790899743),('8lSZnu9oOtpGQHGZJW6teHhyOry2BMpvPt3yBXlK',2,'127.0.0.1','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/27.0.1 Mobile/15E148 Safari/604.1','eyJfdG9rZW4iOiIyM1FrWnRiWnVQVU9TNXlqZ29qWUs5akFIaTc1STlpRUx2aEQxRUI1IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvaW5kdWN0aW9uLXBlbmNpbC1pbmRpY2F0ZS1zdXNlLnRyeWNsb3VkZmxhcmUuY29tXC9ndXJ1XC9kZXRhaWxcLzEyIiwicm91dGUiOiJndXJ1LmRldGFpbCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Mn0=',1790901084),('cdZPk1HKBrlF68KciUoOhyphLehKX7GFqgFoCfoY',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiJ3cFNmTmtXekJXSENPelpUMXRLOXBQTjdYSU5vZlVYR21zcVljZDZqIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDgwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790899063),('duybLUrXGoDRcc5rVjnKG4q3EMAKEYc30HSka0fU',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJzZmR6YXJsRGpzMHlBSjg5NUZ0aEJhZ2tWbzhZbURFdGhuZFJCNzBxIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvaW5kdWN0aW9uLXBlbmNpbC1pbmRpY2F0ZS1zdXNlLnRyeWNsb3VkZmxhcmUuY29tXC9ndXJ1XC9kZXRhaWxcLzEyIiwicm91dGUiOiJndXJ1LmRldGFpbCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Mn0=',1790901196),('EzNMaPzXYoPbotRLowo5FQ6yKe8Jk674NOXaY4Pi',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJoaWt3dlRqSm5COFo0VHA5eFg2NmdxUHJMMXE1aG5tenJUUUl0Sk1KIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwODBcL2d1cnVcL2Rhc2hib2FyZCIsInJvdXRlIjoiZ3VydS5kYXNoYm9hcmQifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9',1790899497),('ioaXn1gzzpPKKo7S7GPrfR93jWmWrmJAs00nN0wO',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8106','eyJfdG9rZW4iOiJsRUpKMkVzQWVZSllnV0FuekNUWWgxM0tyU0NBZWh0V3BYNmVrblhKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2luZHVjdGlvbi1wZW5jaWwtaW5kaWNhdGUtc3VzZS50cnljbG91ZGZsYXJlLmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1790900888),('K793Ku41GUkhKM1WOJZTdxEDqQpnMinCixCq1qXL',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiJUejRqTU1zUURwSERGVXlINGpJYldZNzNRZFFLMWIzUmNsU1hUS0hSIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790899933),('lbrhRUJd9KSsaQ4gH54RGTzLlg29Q1ZobN4JGcen',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiI4NzFzc0ZXU3V2RlNJN3RpZHVxM3FyaG1ZSDh5SWNTcFIwUjBrTHVwIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790900080),('m3C9r6aD3KCsQ8AFByz5AkWn9p2K6dPMfpES9CIU',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiJQb1RqeWpmN0JncW1BbzRtUmRsMmVGejhXTmlkQVFVWDU1MUNqMzRUIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790900080),('MgEHNCdAU0kXBTuYQmikog5R6fi2RCCtGGY9af1B',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.4 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.4 facebookexternalhit/1.1 Facebot Twitterbot/1.0','eyJfdG9rZW4iOiJHUjVyTENEeG1icTVZNzlBckpQOGF6MWV3eDJrbTlQcVFCcGdyOVEwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2luZHVjdGlvbi1wZW5jaWwtaW5kaWNhdGUtc3VzZS50cnljbG91ZGZsYXJlLmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1790901032),('Mu9gEcycGU1GhvkoA8ga0hdzaoPWYSqd9R0z0xNr',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJvWGtyRk9IV015ZmVuUHRWaWNya3lITkRSb29zTW1VbjlNczZnaG9HIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvaXppbi1zbWtuMS5sb2NhLmx0XC9sb2dpbiIsInJvdXRlIjoibG9naW4ifX0=',1790899889),('nSCUQbRLeQ1fm3Ob1Tl1csP9rvINApYC4Dzg1mA2',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.4 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.4 facebookexternalhit/1.1 Facebot Twitterbot/1.0','eyJfdG9rZW4iOiJTSlM1Z3UyUjhOMGdpbld2TjJPVUNsempYb3Z3OXpTajNiMjI5ZnpKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2luZHVjdGlvbi1wZW5jaWwtaW5kaWNhdGUtc3VzZS50cnljbG91ZGZsYXJlLmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1790900540),('nt9ZRHthogLYHaXOgnNoD3AJuY8j0gPTIqQncoWE',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiI4RXprZ2tJM1VObGNTNU85ZEFUTm9nQkhKcUYzc09NZHdvYzBsa2I1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDgwXC92ZXJpZmlrYXNpXC9BQkNERUZHSCIsInJvdXRlIjoidmVyaWZpa2FzaS5zdXJhdCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1790899067),('s0X5oW4sLDebkUVXUtCAWy2sbVK6TjpcKT9z5vDs',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiJQMFl5ckR3N2w4WTJVdXU1eDNianBEbnBna2hHdGFKWldjRTNtTmdmIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790899353),('VyVIjpi8GkdrjHbmq3cQzP5jmemvtFXf2J4npnok',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiJaSlljSWE3UXpXUXVPajRxU2h0aDhXQzBONEhvVThadVZ5UTBJM3E2IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790899739),('W6gPKNl7yioCPN9vd6NXrvlcFxbIq4cMxe1W6N5e',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiJXOTJzMjhrWmRaWG9Tc1FKdHZXQXBBeXRySXlKQjFOSHJHSDJrR2QxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2luZHVjdGlvbi1wZW5jaWwtaW5kaWNhdGUtc3VzZS50cnljbG91ZGZsYXJlLmNvbSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790899357),('Xga7GPlcqfVaybP6rFuthe2sPBsl4qF2aaVdvHBd',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiJjeFJYTTVOUGtVYUc2blFIQTNmRXlZRTY5dzZucm5DQmFFTWp2cFFEIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790899939),('ZzjnjZoq2qnBEC3nTrYffeQuZVrKvv31YeuFy8V1',NULL,'127.0.0.1','curl/8.18.0','eyJfdG9rZW4iOiI5TEltVnFSUVRISkU1dThkTDZMcHphZjVBaXMyYTZuRVlZR256bkFNIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1790899020);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `siswas`
--

DROP TABLE IF EXISTS `siswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siswas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `nomor_identitas` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `siswas_user_id_index` (`user_id`),
  CONSTRAINT `siswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `siswas`
--

LOCK TABLES `siswas` WRITE;
/*!40000 ALTER TABLE `siswas` DISABLE KEYS */;
INSERT INTO `siswas` VALUES (1,3,'Adinda Afifah Putri','XII','RPL','2024001','2026-08-27 19:08:19','2026-08-27 19:08:19'),(2,4,'Marcellino','XII','RPL','2024002','2026-08-27 19:08:19','2026-08-27 19:08:19');
/*!40000 ALTER TABLE `siswas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `surat_izins`
--

DROP TABLE IF EXISTS `surat_izins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surat_izins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pengajuan_izin_id` bigint(20) unsigned NOT NULL,
  `nomor_surat` varchar(255) NOT NULL,
  `kode_verifikasi` varchar(10) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_izins_nomor_surat_unique` (`nomor_surat`),
  UNIQUE KEY `surat_izins_kode_verifikasi_unique` (`kode_verifikasi`),
  KEY `surat_izins_pengajuan_izin_id_foreign` (`pengajuan_izin_id`),
  CONSTRAINT `surat_izins_pengajuan_izin_id_foreign` FOREIGN KEY (`pengajuan_izin_id`) REFERENCES `pengajuan_izins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `surat_izins`
--

LOCK TABLES `surat_izins` WRITE;
/*!40000 ALTER TABLE `surat_izins` DISABLE KEYS */;
INSERT INTO `surat_izins` VALUES (1,1,'SIZIN-2026-000001','2IIJHCGA','2026-08-28','pdf/SIZIN-2026-000001.pdf','2026-08-27 19:09:03','2026-08-27 19:09:03'),(2,2,'SIZIN-2026-000002','E2ZPZIO2','2026-08-28','pdf/SIZIN-2026-000002.pdf','2026-08-27 19:11:10','2026-08-27 19:11:10'),(3,3,'SIZIN-2026-000003','CEPEXASB','2026-08-28','pdf/SIZIN-2026-000003.pdf','2026-08-27 23:45:50','2026-08-27 23:45:50'),(4,4,'SIZIN-2026-000004','RTEWKSKN','2026-09-25','pdf/SIZIN-2026-000004.pdf','2026-09-24 18:11:47','2026-09-24 18:11:47');
/*!40000 ALTER TABLE `surat_izins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('siswa','guru_petugas','admin') NOT NULL DEFAULT 'siswa',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator Utama','admin@smkn1.sch.id',NULL,'$2y$12$TX8U/gJar33bNFhJnHht7ureqdOJmSeOb3mQH2PqximuHBrcYGvu.','admin',NULL,'2026-08-27 19:08:18','2026-08-27 19:08:18'),(2,'Bapak Guru Piket','guru@smkn1.sch.id',NULL,'$2y$12$6TC06QeK5cRmbcoGgns5Zun6dN1Le/6OHg3MsnqU7mbEjruQNKmCC','guru_petugas',NULL,'2026-08-27 19:08:18','2026-08-27 19:08:18'),(3,'Adinda Afifah Putri','adinda@smkn1.sch.id',NULL,'$2y$12$esIgzI6iF5cX0XTKPG1VqewH8D20EndvsxDGgV78F/SyR0CNMciqq','siswa',NULL,'2026-08-27 19:08:19','2026-08-27 19:08:19'),(4,'Marcellino','marcellino@smkn1.sch.id',NULL,'$2y$12$67.zfoJaEBx0LtEHf4BkROl3cOHJPFiVm2LigOxxYeBm1cvFdqg1G','siswa',NULL,'2026-08-27 19:08:19','2026-08-27 19:08:19');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verifikasi_wajahs`
--

DROP TABLE IF EXISTS `verifikasi_wajahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verifikasi_wajahs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pengajuan_izin_id` bigint(20) unsigned NOT NULL,
  `foto_wajah` varchar(255) NOT NULL,
  `hasil_verifikasi` enum('berhasil','gagal') NOT NULL DEFAULT 'berhasil',
  `waktu_verifikasi` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verifikasi_wajahs_pengajuan_izin_id_foreign` (`pengajuan_izin_id`),
  CONSTRAINT `verifikasi_wajahs_pengajuan_izin_id_foreign` FOREIGN KEY (`pengajuan_izin_id`) REFERENCES `pengajuan_izins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verifikasi_wajahs`
--

LOCK TABLES `verifikasi_wajahs` WRITE;
/*!40000 ALTER TABLE `verifikasi_wajahs` DISABLE KEYS */;
INSERT INTO `verifikasi_wajahs` VALUES (1,1,'test.png','berhasil','2026-08-28 02:09:03','2026-08-27 19:09:03','2026-08-27 19:09:03'),(2,2,'verifikasi/wajah_6a90ee27800c1.png','berhasil','2026-08-28 02:10:47','2026-08-27 19:10:47','2026-08-27 19:10:47'),(3,3,'verifikasi/wajah_6a912e81210c1.png','berhasil','2026-08-28 06:45:21','2026-08-27 23:45:21','2026-08-27 23:45:21'),(4,4,'verifikasi/wajah_6ab5ca21ebcdf.png','berhasil','2026-09-25 01:10:58','2026-09-24 18:10:58','2026-09-24 18:10:58'),(5,5,'verifikasi/wajah_6abef4e73e43d.png','berhasil','2026-10-02 00:03:51','2026-10-01 17:03:51','2026-10-01 17:03:51'),(6,7,'verifikasi/wajah_6abef7e1a173f.png','berhasil','2026-10-02 00:16:33','2026-10-01 17:16:33','2026-10-01 17:16:33'),(7,9,'verifikasi/wajah_6abef8cc67028.png','berhasil','2026-10-02 00:20:28','2026-10-01 17:20:28','2026-10-01 17:20:28'),(8,11,'verifikasi/wajah_6abef933536aa.png','berhasil','2026-10-02 00:22:11','2026-10-01 17:22:11','2026-10-01 17:22:11'),(9,12,'verifikasi/wajah_6abefb3f11a36.png','berhasil','2026-10-02 00:30:55','2026-10-01 17:30:55','2026-10-01 17:30:55');
/*!40000 ALTER TABLE `verifikasi_wajahs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-02  7:34:45
