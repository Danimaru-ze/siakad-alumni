-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 26, 2025 at 03:04 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siakad`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `changes` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_conversation_logs`
--

CREATE TABLE `ai_conversation_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `context_summary` text COLLATE utf8mb4_unicode_ci,
  `response_time_ms` int NOT NULL,
  `model_used` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_applied` tinyint(1) NOT NULL DEFAULT '0',
  `guard_issues` json DEFAULT NULL,
  `was_retry` tinyint(1) NOT NULL DEFAULT '0',
  `feedback` enum('helpful','not_helpful','incorrect') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `feedback_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ai_conversation_logs`
--

INSERT INTO `ai_conversation_logs` (`id`, `user_id`, `mahasiswa_id`, `session_id`, `question`, `answer`, `context_summary`, `response_time_ms`, `model_used`, `provider`, `guard_applied`, `guard_issues`, `was_retry`, `feedback`, `feedback_note`, `created_at`, `updated_at`) VALUES
-- --------------------------------------------------------

--
-- Table structure for table `bimbingan_skripsi`
--

CREATE TABLE `bimbingan_skripsi` (
  `id` bigint UNSIGNED NOT NULL,
  `skripsi_id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `tanggal_bimbingan` date NOT NULL,
  `catatan_mahasiswa` text COLLATE utf8mb4_unicode_ci,
  `catatan_dosen` text COLLATE utf8mb4_unicode_ci,
  `file_dokumen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('menunggu','disetujui','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nidn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id`, `user_id`, `nidn`, `prodi_id`, `created_at`, `updated_at`) VALUES
(1, 3, '0012056701', 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `fakultas`
--

CREATE TABLE `fakultas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fakultas`
--

INSERT INTO `fakultas` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Fakultas Teknik dan Ilmu Komputer', '2025-12-26 07:19:59', '2025-12-26 07:19:59');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kuliah`
--

CREATE TABLE `jadwal_kuliah` (
  `id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_kuliah`
--

INSERT INTO `jadwal_kuliah` (`id`, `kelas_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `created_at`, `updated_at`) VALUES
(1, 1, 'Senin', '08:00:00', '09:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(2, 2, 'Selasa', '10:00:00', '11:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(3, 3, 'Rabu', '13:00:00', '14:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(4, 4, 'Kamis', '15:00:00', '16:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(5, 5, 'Jumat', '08:00:00', '09:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(6, 6, 'Senin', '10:00:00', '11:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(7, 7, 'Selasa', '13:00:00', '14:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(8, 8, 'Rabu', '15:00:00', '16:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(9, 9, 'Kamis', '08:00:00', '09:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(10, 10, 'Jumat', '10:00:00', '11:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(11, 11, 'Senin', '13:00:00', '14:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(12, 12, 'Selasa', '15:00:00', '16:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(13, 13, 'Rabu', '08:00:00', '09:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(14, 14, 'Kamis', '10:00:00', '11:30:00', 'Lab Komputer 1', '2025-12-26 07:20:00', '2025-12-26 07:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `kehadiran_dosen`
--

CREATE TABLE `kehadiran_dosen` (
  `id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `jadwal_kuliah_id` bigint UNSIGNED DEFAULT NULL,
  `pertemuan_id` bigint UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `status` enum('hadir','izin','sakit','tugas','alpa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `bukti_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `tahun_akademik_id` bigint UNSIGNED DEFAULT NULL,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas` int NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `mata_kuliah_id`, `dosen_id`, `tahun_akademik_id`, `nama_kelas`, `kapasitas`, `is_closed`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(2, 2, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(3, 3, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(4, 4, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(5, 5, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(6, 6, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(7, 7, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(8, 8, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(9, 9, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(10, 10, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(11, 11, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(12, 12, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(13, 13, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(14, 14, 1, 3, 'A', 40, 0, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(15, 15, 1, 1, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(16, 16, 1, 1, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(17, 17, 1, 1, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(18, 18, 1, 1, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(19, 19, 1, 1, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(20, 20, 1, 1, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(21, 21, 1, 1, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(22, 22, 1, 2, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(23, 23, 1, 2, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(24, 24, 1, 2, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(25, 25, 1, 2, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(26, 26, 1, 2, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(27, 27, 1, 2, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(28, 28, 1, 2, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(29, 29, 1, 3, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(30, 30, 1, 3, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(31, 31, 1, 3, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(32, 32, 1, 3, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(33, 33, 1, 3, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(34, 34, 1, 3, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(35, 35, 1, 3, 'A', 40, 0, '2025-12-26 07:20:01', '2025-12-26 07:20:01');

-- --------------------------------------------------------

--
-- Table structure for table `kerja_praktek`
--

CREATE TABLE `kerja_praktek` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `pembimbing_id` bigint UNSIGNED DEFAULT NULL,
  `nama_perusahaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_perusahaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bidang_usaha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_pembimbing_lapangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan_pembimbing_lapangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp_pembimbing` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `judul_laporan` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pengajuan','disetujui','ditolak','berlangsung','selesai_kp','penyusunan_laporan','seminar','revisi','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pengajuan',
  `tanggal_seminar` date DEFAULT NULL,
  `nilai_perusahaan` decimal(5,2) DEFAULT NULL,
  `nilai_pembimbing` decimal(5,2) DEFAULT NULL,
  `nilai_seminar` decimal(5,2) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `nilai_huruf` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `tahun_akademik_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id`, `mahasiswa_id`, `tahun_akademik_id`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'approved', NULL, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(2, 1, 5, 'approved', NULL, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(3, 1, 1, 'approved', NULL, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(4, 1, 2, 'approved', NULL, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(5, 1, 3, 'draft', NULL, '2025-12-26 07:20:01', '2025-12-26 07:20:01');

-- --------------------------------------------------------

--
-- Table structure for table `krs_detail`
--

CREATE TABLE `krs_detail` (
  `id` bigint UNSIGNED NOT NULL,
  `krs_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `krs_detail`
--

INSERT INTO `krs_detail` (`id`, `krs_id`, `kelas_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(2, 1, 2, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(3, 1, 3, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(4, 1, 4, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(5, 1, 5, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(6, 1, 6, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(7, 1, 7, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(8, 2, 8, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(9, 2, 9, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(10, 2, 10, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(11, 2, 11, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(12, 2, 12, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(13, 2, 13, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(14, 2, 14, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(15, 3, 15, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(16, 3, 16, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(17, 3, 17, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(18, 3, 18, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(19, 3, 19, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(20, 3, 20, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(21, 3, 21, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(22, 4, 22, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(23, 4, 23, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(24, 4, 24, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(25, 4, 25, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(26, 4, 26, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(27, 4, 27, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(28, 4, 28, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(29, 5, 29, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(30, 5, 30, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(31, 5, 31, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(32, 5, 32, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(33, 5, 33, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(34, 5, 34, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(35, 5, 35, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(36, 5, 22, '2025-12-26 07:25:56', '2025-12-26 07:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `logbook_kp`
--

CREATE TABLE `logbook_kp` (
  `id` bigint UNSIGNED NOT NULL,
  `kerja_praktek_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `kegiatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan_pembimbing` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','disetujui','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi_id` bigint UNSIGNED NOT NULL,
  `dosen_pa_id` bigint UNSIGNED DEFAULT NULL,
  `angkatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','cuti','do','lulus') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `user_id`, `nim`, `prodi_id`, `dosen_pa_id`, `angkatan`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '2022101001', 1, 1, '2022', 'aktif', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(2, 5, '2205102010', 1, 1, '2022', 'lulus', '2025-12-26 08:02:15', '2025-12-26 08:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_mk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks` int NOT NULL,
  `semester` int NOT NULL,
  `prodi_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode_mk`, `nama_mk`, `sks`, `semester`, `prodi_id`, `created_at`, `updated_at`) VALUES
(1, 'TI101', 'Algoritma dan Pemrograman I', 4, 1, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(2, 'TI102', 'Matematika Diskrit', 3, 1, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(3, 'TI103', 'Pengantar Teknologi Informasi', 3, 1, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(4, 'TI104', 'Kalkulus I', 3, 1, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(5, 'TI105', 'Fisika Dasar', 3, 1, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(6, 'TI106', 'Bahasa Inggris I', 2, 1, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(7, 'TI107', 'Pendidikan Pancasila', 2, 1, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(8, 'TI201', 'Algoritma dan Pemrograman II', 4, 2, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(9, 'TI202', 'Struktur Data', 4, 2, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(10, 'TI203', 'Kalkulus II', 3, 2, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(11, 'TI204', 'Aljabar Linear', 3, 2, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(12, 'TI205', 'Bahasa Inggris II', 2, 2, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(13, 'TI206', 'Pendidikan Kewarganegaraan', 2, 2, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(14, 'TI207', 'Praktikum Algoritma', 2, 2, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(15, 'TI301', 'Pemrograman Berorientasi Objek', 4, 3, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(16, 'TI302', 'Basis Data', 4, 3, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(17, 'TI303', 'Sistem Operasi', 3, 3, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(18, 'TI304', 'Statistika dan Probabilitas', 3, 3, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(19, 'TI305', 'Organisasi dan Arsitektur Komputer', 3, 3, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(20, 'TI306', 'Praktikum Basis Data', 2, 3, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(21, 'TI307', 'Agama', 2, 3, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(22, 'TI401', 'Pemrograman Web', 4, 4, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(23, 'TI402', 'Jaringan Komputer', 4, 4, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(24, 'TI403', 'Rekayasa Perangkat Lunak', 3, 4, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(25, 'TI404', 'Interaksi Manusia dan Komputer', 3, 4, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(26, 'TI405', 'Analisis dan Perancangan Sistem', 3, 4, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(27, 'TI406', 'Praktikum Jaringan', 2, 4, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(28, 'TI407', 'Etika Profesi', 2, 4, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(29, 'TI501', 'Pemrograman Mobile', 4, 5, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(30, 'TI502', 'Kecerdasan Buatan', 3, 5, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(31, 'TI503', 'Keamanan Sistem Informasi', 3, 5, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(32, 'TI504', 'Sistem Terdistribusi', 3, 5, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(33, 'TI505', 'Manajemen Proyek TI', 3, 5, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(34, 'TI506', 'Praktikum Mobile', 2, 5, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(35, 'TI507', 'Kewirausahaan', 2, 5, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(36, 'TI601', 'Machine Learning', 3, 6, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(37, 'TI602', 'Data Mining', 3, 6, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(38, 'TI603', 'Cloud Computing', 3, 6, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(39, 'TI604', 'Pengolahan Citra Digital', 3, 6, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(40, 'TI605', 'Metodologi Penelitian', 2, 6, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(41, 'TI606', 'Kerja Praktek', 4, 6, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(42, 'TI701', 'Internet of Things', 3, 7, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(43, 'TI702', 'Big Data Analytics', 3, 7, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(44, 'TI703', 'Natural Language Processing', 3, 7, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(45, 'TI704', 'Proyek 1 (Proposal Skripsi)', 2, 7, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(46, 'TI705', 'Pilihan 1', 3, 7, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(47, 'TI801', 'Deep Learning', 3, 8, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(48, 'TI802', 'Pilihan 2', 3, 8, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(49, 'TI803', 'Skripsi', 6, 8, 1, '2025-12-26 07:20:00', '2025-12-26 07:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id` bigint UNSIGNED NOT NULL,
  `pertemuan_id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_external` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '2025_12_10_020000_add_role_to_users_table', 1),
(4, '2025_12_10_020001_create_fakultas_table', 1),
(5, '2025_12_10_020002_create_prodi_table', 1),
(6, '2025_12_10_020003_create_mahasiswa_table', 1),
(7, '2025_12_10_020004_create_dosen_table', 1),
(8, '2025_12_10_020005_create_tahun_akademik_table', 1),
(9, '2025_12_10_020006_create_mata_kuliah_table', 1),
(10, '2025_12_10_020007_create_kelas_table', 1),
(11, '2025_12_10_020008_create_krs_table', 1),
(12, '2025_12_10_020009_create_krs_detail_table', 1),
(13, '2025_12_10_020010_create_nilai_table', 1),
(14, '2025_12_10_030001_create_jadwal_kuliah_table', 1),
(15, '2025_12_10_030002_create_activity_log_table', 1),
(16, '2025_12_10_030003_add_status_columns', 1),
(17, '2025_12_10_040001_add_catatan_to_krs_table', 1),
(18, '2025_12_10_040001_add_dosen_pa_to_mahasiswa', 1),
(19, '2025_12_10_050001_create_pertemuan_table', 1),
(20, '2025_12_10_050002_create_presensi_table', 1),
(21, '2025_12_10_124351_create_ruangan_table', 1),
(22, '2025_12_10_131009_create_notifications_table', 1),
(23, '2025_12_10_132207_create_skripsi_table', 1),
(24, '2025_12_10_132208_create_bimbingan_skripsi_table', 1),
(25, '2025_12_10_134022_create_kerja_praktek_table', 1),
(26, '2025_12_10_134023_create_logbook_kp_table', 1),
(27, '2025_12_10_135337_create_kehadiran_dosen_table', 1),
(28, '2025_12_16_134815_create_ai_conversation_logs_table', 1),
(29, '2025_12_21_114532_create_permission_tables', 1),
(30, '2025_12_21_180000_add_fakultas_id_to_users_table', 1),
(31, '2025_12_22_070221_add_prodi_id_to_mata_kuliah_table', 1),
(32, '2025_12_22_070222_add_fakultas_id_to_ruangan_table', 1),
(33, '2025_12_22_150900_create_materi_table', 1),
(34, '2025_12_22_151000_create_tugas_table', 1),
(35, '2025_12_22_151001_create_tugas_submission_table', 1),
(36, '2025_12_22_160300_add_tahun_akademik_id_to_kelas_table', 1),
(37, '2025_12_22_160301_add_date_range_to_tahun_akademik_table', 1),
(38, '2025_12_26_180400_add_performance_indexes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `nilai_angka` decimal(5,2) DEFAULT NULL,
  `nilai_huruf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id`, `mahasiswa_id`, `kelas_id`, `nilai_angka`, `nilai_huruf`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '83.00', 'A-', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(2, 1, 2, '89.00', 'A', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(3, 1, 3, '82.00', 'A-', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(4, 1, 4, '91.00', 'A', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(5, 1, 5, '79.00', 'B+', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(6, 1, 6, '76.00', 'B+', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(7, 1, 7, '83.00', 'A-', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(8, 1, 8, '79.00', 'B+', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(9, 1, 9, '77.00', 'B+', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(10, 1, 10, '78.00', 'B+', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(11, 1, 11, '80.00', 'A-', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(12, 1, 12, '78.00', 'B+', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(13, 1, 13, '80.00', 'A-', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(14, 1, 14, '84.00', 'A-', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(15, 1, 15, '72.00', 'B', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(16, 1, 16, '85.00', 'A', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(17, 1, 17, '77.00', 'B+', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(18, 1, 18, '81.00', 'A-', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(19, 1, 19, '77.00', 'B+', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(20, 1, 20, '73.00', 'B', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(21, 1, 21, '86.00', 'A', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(22, 1, 22, '79.00', 'B+', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(23, 1, 23, '87.00', 'A', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(24, 1, 24, '74.00', 'B', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(25, 1, 25, '85.00', 'A', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(26, 1, 26, '90.00', 'A', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(27, 1, 27, '89.00', 'A', '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(28, 1, 28, '84.00', 'A-', '2025-12-26 07:20:01', '2025-12-26 07:20:01');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view mahasiswa', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(2, 'create mahasiswa', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(3, 'edit mahasiswa', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(4, 'delete mahasiswa', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(5, 'view dosen', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(6, 'create dosen', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(7, 'edit dosen', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(8, 'delete dosen', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(9, 'view kelas', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(10, 'create kelas', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(11, 'edit kelas', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(12, 'delete kelas', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(13, 'view mata kuliah', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(14, 'create mata kuliah', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(15, 'edit mata kuliah', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(16, 'delete mata kuliah', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(17, 'view krs', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(18, 'approve krs', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(19, 'reject krs', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(20, 'view nilai', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(21, 'input nilai', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(22, 'view skripsi', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(23, 'manage skripsi', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(24, 'view kp', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(25, 'manage kp', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(26, 'view users', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(27, 'create users', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(28, 'edit users', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(29, 'delete users', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(30, 'manage fakultas', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(31, 'manage prodi', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(32, 'manage tahun akademik', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(33, 'manage ruangan', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(34, 'view reports', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(35, 'export reports', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(36, 'manage announcements', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59');

-- --------------------------------------------------------

--
-- Table structure for table `pertemuan`
--

CREATE TABLE `pertemuan` (
  `id` bigint UNSIGNED NOT NULL,
  `jadwal_kuliah_id` bigint UNSIGNED NOT NULL,
  `pertemuan_ke` int NOT NULL,
  `tanggal` date NOT NULL,
  `materi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('terlaksana','libur','pengganti') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'terlaksana',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` bigint UNSIGNED NOT NULL,
  `pertemuan_id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `status` enum('hadir','sakit','izin','alpa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'alpa',
  `waktu_presensi` time DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id` bigint UNSIGNED NOT NULL,
  `fakultas_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id`, `fakultas_id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 1, 'Teknik Informatika', '2025-12-26 07:20:00', '2025-12-26 07:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(2, 'admin_fakultas', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(3, 'dosen', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(4, 'mahasiswa', 'web', '2025-12-26 07:19:59', '2025-12-26 07:19:59');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(1, 2),
(2, 2),
(3, 2),
(5, 2),
(6, 2),
(7, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(31, 2),
(33, 2),
(34, 2),
(35, 2),
(36, 2),
(1, 3),
(9, 3),
(20, 3),
(21, 3),
(22, 3),
(24, 3);

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_ruangan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ruangan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas` int NOT NULL DEFAULT '40',
  `gedung` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lantai` int DEFAULT NULL,
  `fasilitas` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `fakultas_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id`, `kode_ruangan`, `nama_ruangan`, `kapasitas`, `gedung`, `lantai`, `fasilitas`, `is_active`, `fakultas_id`, `created_at`, `updated_at`) VALUES
(1, 'LC-01', 'Lab Komputer 1', 40, 'Gedung A', 1, NULL, 1, NULL, '2025-12-26 07:20:00', '2025-12-26 07:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('jsJgkCjYQZ4qAVDH6DodCjUNbQbuuzxMNzNfnPCW', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZGtTb3ROSU9aMWFJRVVjb3VKOFA5Y1pHWDloQjNUN3ZFaDlSeHBReiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9tYWhhc2lzd2EiO3M6NToicm91dGUiO3M6MjE6ImFkbWluLm1haGFzaXN3YS5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1766761351);

-- --------------------------------------------------------

--
-- Table structure for table `skripsi`
--

CREATE TABLE `skripsi` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `pembimbing1_id` bigint UNSIGNED DEFAULT NULL,
  `pembimbing2_id` bigint UNSIGNED DEFAULT NULL,
  `judul` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abstrak` text COLLATE utf8mb4_unicode_ci,
  `bidang_kajian` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pengajuan','review','ditolak','diterima','bimbingan','seminar_proposal','penelitian','seminar_hasil','sidang','revisi','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pengajuan',
  `tanggal_pengajuan` date DEFAULT NULL,
  `tanggal_acc_judul` date DEFAULT NULL,
  `tanggal_seminar_proposal` date DEFAULT NULL,
  `tanggal_seminar_hasil` date DEFAULT NULL,
  `tanggal_sidang` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `nilai_huruf` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tahun_akademik`
--

CREATE TABLE `tahun_akademik` (
  `id` bigint UNSIGNED NOT NULL,
  `tahun` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `tanggal_krs_mulai` date DEFAULT NULL,
  `tanggal_krs_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tahun_akademik`
--

INSERT INTO `tahun_akademik` (`id`, `tahun`, `semester`, `is_active`, `tanggal_mulai`, `tanggal_selesai`, `tanggal_krs_mulai`, `tanggal_krs_selesai`, `created_at`, `updated_at`) VALUES
(1, '2023/2024', 'Ganjil', 0, '2023-09-01', '2024-01-31', NULL, NULL, '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(2, '2023/2024', 'Genap', 0, '2024-02-01', '2024-06-30', NULL, NULL, '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(3, '2024/2025', 'Ganjil', 1, '2024-09-01', '2025-01-31', NULL, NULL, '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(4, '2022/2023', 'Ganjil', 0, '2022-09-01', '2023-01-31', NULL, NULL, '2025-12-26 07:20:01', '2025-12-26 07:20:01'),
(5, '2022/2023', 'Genap', 0, '2023-02-01', '2023-06-30', NULL, NULL, '2025-12-26 07:20:01', '2025-12-26 07:20:01');

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `file_tugas` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deadline` datetime NOT NULL,
  `max_file_size` int NOT NULL DEFAULT '10485760',
  `allowed_extensions` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pdf,doc,docx,zip,rar',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas_submission`
--

CREATE TABLE `tugas_submission` (
  `id` bigint UNSIGNED NOT NULL,
  `tugas_id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` datetime NOT NULL,
  `nilai` decimal(5,2) DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `graded_at` datetime DEFAULT NULL,
  `graded_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mahasiswa',
  `fakultas_id` bigint UNSIGNED DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `role`, `fakultas_id`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrator', 'superadmin@siakad.test', NULL, 'superadmin', NULL, '$2y$12$8SFs3/wUE9lVwEESj5tC8Or3Etp7ExtegvD06azF7rmM/IGDu8b8a', 'TTqdTKWQK7GW9YLEF2pqZJG2yWwEBzFvZu2bd9lFWfjJl0FwebXy3MBw6bPH', '2025-12-26 07:19:59', '2025-12-26 07:19:59'),
(2, 'Admin FTIK', 'admin.ftik@siakad.test', NULL, 'admin_fakultas', 1, '$2y$12$6D8p9qoK0FDFYSWdW4pU3ON6AdkY7ytn60pmvHN1Ts5ymSNPgj/Ai', NULL, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(3, 'Dr. Ahmad Fauzi, M.Kom.', 'dosen@siakad.test', NULL, 'dosen', NULL, '$2y$12$pUnjG8YWnmNSI/I6xvuAB.bC97WteyskQ0Eu4GA4okdzuO8qmBD7y', NULL, '2025-12-26 07:20:00', '2025-12-26 07:20:00'),
(4, 'Dany Setiyawan', 'mahasiswa@siakad.test', NULL, 'mahasiswa', NULL, '$2y$12$z4bESRf30NtuHNCYGtCo7uEC/W.mGJq8RDJybMKrx/Yyppj8zD9XO', NULL, '2025-12-26 07:20:01', '2025-12-26 07:24:39'),
(5, 'Iqbal', 'iqbal@siakad.tes', NULL, 'mahasiswa', NULL, '$2y$12$XNJvvTSoJ.KipBdPeAn/Je0vRoTvxzvkC6rdEGJu7DPOgvr0QwP6a', NULL, '2025-12-26 08:02:15', '2025-12-26 08:02:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_log_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `activity_log_user_id_index` (`user_id`),
  ADD KEY `activity_log_action_index` (`action`);

--
-- Indexes for table `ai_conversation_logs`
--
ALTER TABLE `ai_conversation_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ai_conversation_logs_user_id_foreign` (`user_id`),
  ADD KEY `ai_conversation_logs_created_at_index` (`created_at`),
  ADD KEY `ai_conversation_logs_mahasiswa_id_created_at_index` (`mahasiswa_id`,`created_at`),
  ADD KEY `ai_conversation_logs_session_id_index` (`session_id`),
  ADD KEY `ai_logs_mahasiswa_index` (`mahasiswa_id`),
  ADD KEY `ai_logs_created_at_index` (`created_at`);

--
-- Indexes for table `bimbingan_skripsi`
--
ALTER TABLE `bimbingan_skripsi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bimbingan_skripsi_dosen_id_foreign` (`dosen_id`),
  ADD KEY `bimbingan_skripsi_skripsi_id_tanggal_bimbingan_index` (`skripsi_id`,`tanggal_bimbingan`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_nidn_unique` (`nidn`),
  ADD KEY `dosen_user_id_foreign` (`user_id`),
  ADD KEY `dosen_prodi_index` (`prodi_id`);

--
-- Indexes for table `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_kuliah_kelas_index` (`kelas_id`),
  ADD KEY `jadwal_kuliah_hari_index` (`hari`);

--
-- Indexes for table `kehadiran_dosen`
--
ALTER TABLE `kehadiran_dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kehadiran_dosen_dosen_id_jadwal_kuliah_id_tanggal_unique` (`dosen_id`,`jadwal_kuliah_id`,`tanggal`),
  ADD KEY `kehadiran_dosen_jadwal_kuliah_id_foreign` (`jadwal_kuliah_id`),
  ADD KEY `kehadiran_dosen_pertemuan_id_foreign` (`pertemuan_id`),
  ADD KEY `kehadiran_dosen_dosen_id_tanggal_index` (`dosen_id`,`tanggal`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  ADD KEY `kelas_mata_kuliah_index` (`mata_kuliah_id`),
  ADD KEY `kelas_dosen_index` (`dosen_id`);

--
-- Indexes for table `kerja_praktek`
--
ALTER TABLE `kerja_praktek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kerja_praktek_pembimbing_id_foreign` (`pembimbing_id`),
  ADD KEY `kerja_praktek_status_index` (`status`),
  ADD KEY `kerja_praktek_mahasiswa_id_index` (`mahasiswa_id`);

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `krs_mahasiswa_tahun_unique` (`mahasiswa_id`,`tahun_akademik_id`),
  ADD KEY `krs_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  ADD KEY `krs_status_index` (`status`);

--
-- Indexes for table `krs_detail`
--
ALTER TABLE `krs_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `krs_detail_krs_kelas_unique` (`krs_id`,`kelas_id`),
  ADD KEY `krs_detail_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `logbook_kp`
--
ALTER TABLE `logbook_kp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logbook_kp_kerja_praktek_id_tanggal_index` (`kerja_praktek_id`,`tanggal`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mahasiswa_nim_unique` (`nim`),
  ADD KEY `mahasiswa_user_id_foreign` (`user_id`),
  ADD KEY `mahasiswa_dosen_pa_id_foreign` (`dosen_pa_id`),
  ADD KEY `mahasiswa_angkatan_index` (`angkatan`),
  ADD KEY `mahasiswa_prodi_index` (`prodi_id`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mata_kuliah_kode_mk_unique` (`kode_mk`),
  ADD KEY `mata_kuliah_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materi_pertemuan_id_foreign` (`pertemuan_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nilai_mahasiswa_kelas_unique` (`mahasiswa_id`,`kelas_id`),
  ADD KEY `nilai_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`),
  ADD KEY `notifications_type_index` (`type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `pertemuan`
--
ALTER TABLE `pertemuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pertemuan_jadwal_kuliah_id_pertemuan_ke_unique` (`jadwal_kuliah_id`,`pertemuan_ke`),
  ADD KEY `pertemuan_jadwal_index` (`jadwal_kuliah_id`),
  ADD KEY `pertemuan_tanggal_index` (`tanggal`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `presensi_pertemuan_id_mahasiswa_id_unique` (`pertemuan_id`,`mahasiswa_id`),
  ADD KEY `presensi_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prodi_fakultas_id_foreign` (`fakultas_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruangan_kode_ruangan_unique` (`kode_ruangan`),
  ADD KEY `ruangan_fakultas_id_foreign` (`fakultas_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `skripsi`
--
ALTER TABLE `skripsi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skripsi_pembimbing1_id_foreign` (`pembimbing1_id`),
  ADD KEY `skripsi_pembimbing2_id_foreign` (`pembimbing2_id`),
  ADD KEY `skripsi_status_index` (`status`),
  ADD KEY `skripsi_mahasiswa_id_index` (`mahasiswa_id`);

--
-- Indexes for table `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `tugas_submission`
--
ALTER TABLE `tugas_submission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tugas_submission_tugas_id_mahasiswa_id_unique` (`tugas_id`,`mahasiswa_id`),
  ADD KEY `tugas_submission_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `tugas_submission_graded_by_foreign` (`graded_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_fakultas_id_foreign` (`fakultas_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_conversation_logs`
--
ALTER TABLE `ai_conversation_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bimbingan_skripsi`
--
ALTER TABLE `bimbingan_skripsi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kehadiran_dosen`
--
ALTER TABLE `kehadiran_dosen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `kerja_praktek`
--
ALTER TABLE `kerja_praktek`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `krs_detail`
--
ALTER TABLE `krs_detail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `logbook_kp`
--
ALTER TABLE `logbook_kp`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `pertemuan`
--
ALTER TABLE `pertemuan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skripsi`
--
ALTER TABLE `skripsi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas_submission`
--
ALTER TABLE `tugas_submission`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ai_conversation_logs`
--
ALTER TABLE `ai_conversation_logs`
  ADD CONSTRAINT `ai_conversation_logs_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ai_conversation_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bimbingan_skripsi`
--
ALTER TABLE `bimbingan_skripsi`
  ADD CONSTRAINT `bimbingan_skripsi_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bimbingan_skripsi_skripsi_id_foreign` FOREIGN KEY (`skripsi_id`) REFERENCES `skripsi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `dosen_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD CONSTRAINT `jadwal_kuliah_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kehadiran_dosen`
--
ALTER TABLE `kehadiran_dosen`
  ADD CONSTRAINT `kehadiran_dosen_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kehadiran_dosen_jadwal_kuliah_id_foreign` FOREIGN KEY (`jadwal_kuliah_id`) REFERENCES `jadwal_kuliah` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kehadiran_dosen_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuan` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kerja_praktek`
--
ALTER TABLE `kerja_praktek`
  ADD CONSTRAINT `kerja_praktek_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kerja_praktek_pembimbing_id_foreign` FOREIGN KEY (`pembimbing_id`) REFERENCES `dosen` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `krs_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `krs_detail`
--
ALTER TABLE `krs_detail`
  ADD CONSTRAINT `krs_detail_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `krs_detail_krs_id_foreign` FOREIGN KEY (`krs_id`) REFERENCES `krs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logbook_kp`
--
ALTER TABLE `logbook_kp`
  ADD CONSTRAINT `logbook_kp_kerja_praktek_id_foreign` FOREIGN KEY (`kerja_praktek_id`) REFERENCES `kerja_praktek` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_dosen_pa_id_foreign` FOREIGN KEY (`dosen_pa_id`) REFERENCES `dosen` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mahasiswa_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mahasiswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pertemuan`
--
ALTER TABLE `pertemuan`
  ADD CONSTRAINT `pertemuan_jadwal_kuliah_id_foreign` FOREIGN KEY (`jadwal_kuliah_id`) REFERENCES `jadwal_kuliah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presensi_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prodi`
--
ALTER TABLE `prodi`
  ADD CONSTRAINT `prodi_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD CONSTRAINT `ruangan_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `skripsi`
--
ALTER TABLE `skripsi`
  ADD CONSTRAINT `skripsi_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `skripsi_pembimbing1_id_foreign` FOREIGN KEY (`pembimbing1_id`) REFERENCES `dosen` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `skripsi_pembimbing2_id_foreign` FOREIGN KEY (`pembimbing2_id`) REFERENCES `dosen` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tugas_submission`
--
ALTER TABLE `tugas_submission`
  ADD CONSTRAINT `tugas_submission_graded_by_foreign` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tugas_submission_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tugas_submission_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
