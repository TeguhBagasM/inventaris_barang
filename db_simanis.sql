-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2025 at 10:53 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_simanis`
--

-- --------------------------------------------------------

--
-- Table structure for table `alats`
--

CREATE TABLE `alats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_alat` varchar(255) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `spesifikasi` varchar(255) NOT NULL,
  `no_seri` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `tahun_pengadaan` year(4) NOT NULL,
  `sumber_dana` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barangs`
--

CREATE TABLE `barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `spesifikasi` varchar(255) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL,
  `tahun_pengadaan` int(11) NOT NULL,
  `sumber_dana` varchar(255) NOT NULL,
  `kondisi` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `ruang_id` bigint(20) UNSIGNED NOT NULL,
  `kategori_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangs`
--

INSERT INTO `barangs` (`id`, `nama`, `merk`, `spesifikasi`, `serial_number`, `stok`, `tahun_pengadaan`, `sumber_dana`, `kondisi`, `gambar`, `ruang_id`, `kategori_id`, `created_at`, `updated_at`) VALUES
(3, 'Infokus', 'Sony', 'warna putih', 'A001', 1, 2020, 'kepala sekolah', 'Baik', 'storage/images/infokus-30122024081158.jpg', 3, 1, '2024-12-30 06:12:00', '2025-01-05 13:40:54'),
(4, 'PC', 'asus', 'ssd 512 ram 16gb', '1010', 10, 2023, 'pemerintah', 'Baik', 'storage/images/pc-31122024074156.jpg', 4, 1, '2024-12-31 05:41:58', '2024-12-31 05:41:58'),
(7, 'laptop', 'asus', 'oke', '547hfhg', 10, 2020, 'test', 'Baik', 'storage/images/laptop-09012025094522.jpg', 3, 1, '2025-01-09 07:45:24', '2025-01-09 07:45:24');

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluars`
--

CREATE TABLE `barang_keluars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_minta` date NOT NULL,
  `status` enum('diajukan','disetujui','ditolak') NOT NULL DEFAULT 'diajukan',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barang_keluars`
--

INSERT INTO `barang_keluars` (`id`, `user_id`, `tanggal_minta`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 5, '2024-12-28', 'disetujui', 'maaf saya tolak', '2024-12-28 05:45:36', '2024-12-28 07:19:05'),
(2, 5, '2024-12-28', 'ditolak', 'maaf barang nya lg kosong', '2024-12-28 07:17:30', '2024-12-28 07:19:28'),
(3, 5, '2024-12-28', 'ditolak', 'maaf saya tolak', '2024-12-28 19:06:56', '2024-12-28 19:07:26'),
(4, 5, '2024-12-28', 'ditolak', 'maaf rusak', '2024-12-28 19:11:59', '2024-12-28 19:26:32'),
(5, 5, '2024-12-28', 'disetujui', 'pinjam 1', '2024-12-28 19:24:44', '2025-01-06 08:45:33'),
(6, 5, '2024-12-30', 'disetujui', 'minta dong', '2024-12-30 06:34:37', '2024-12-30 07:13:22'),
(7, 5, '2024-12-30', 'disetujui', NULL, '2024-12-30 07:14:19', '2025-01-06 08:49:18');

-- --------------------------------------------------------

--
-- Table structure for table `bhps`
--

CREATE TABLE `bhps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `spesifikasi` varchar(255) NOT NULL,
  `tahun_pengadaan` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `sumber_dana` varchar(110) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bhps`
--

INSERT INTO `bhps` (`id`, `nama`, `spesifikasi`, `tahun_pengadaan`, `stok`, `sumber_dana`, `created_at`, `updated_at`) VALUES
(3, 'Pulpen', 'warna hitam', 2020, 95, 'kepsek', '2024-12-11 06:42:46', '2025-01-06 08:45:33'),
(4, 'Spidol', 'warna hitam', 2023, 10, 'kepsek', '2024-12-30 06:15:58', '2025-01-06 08:49:18'),
(7, 'hvs', 'putih', 2022, 10, 'Pemerintah', '2025-01-09 07:46:10', '2025-01-09 07:46:10');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_barang_keluars`
--

CREATE TABLE `detail_barang_keluars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `barang_keluar_id` bigint(20) UNSIGNED NOT NULL,
  `bhp_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_barang_keluars`
--

INSERT INTO `detail_barang_keluars` (`id`, `barang_keluar_id`, `bhp_id`, `jumlah`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 2, '2024-12-28 05:45:36', '2024-12-28 05:45:36'),
(2, 2, 3, 1, '2024-12-28 07:17:30', '2024-12-28 07:17:30'),
(3, 3, 3, 1, '2024-12-28 19:06:56', '2024-12-28 19:06:56'),
(4, 4, 3, 1, '2024-12-28 19:11:59', '2024-12-28 19:11:59'),
(5, 5, 3, 1, '2024-12-28 19:24:44', '2024-12-28 19:24:44'),
(6, 6, 3, 2, '2024-12-30 06:34:37', '2024-12-30 06:34:37'),
(7, 7, 4, 20, '2024-12-30 07:14:19', '2024-12-30 07:14:19');

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjamans`
--

CREATE TABLE `detail_peminjamans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `peminjaman_id` bigint(20) UNSIGNED NOT NULL,
  `barang_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('menunggu konfirmasi','dipinjam','ditolak','kembali') NOT NULL DEFAULT 'menunggu konfirmasi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_peminjamans`
--

INSERT INTO `detail_peminjamans` (`id`, `peminjaman_id`, `barang_id`, `jumlah`, `tanggal_kembali`, `status`, `created_at`, `updated_at`) VALUES
(26, 24, 3, 1, NULL, 'dipinjam', '2025-01-05 13:40:54', '2025-01-05 14:26:20');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gedungs`
--

CREATE TABLE `gedungs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_gedung` varchar(255) NOT NULL,
  `luas_gedung` decimal(10,2) NOT NULL,
  `tahun_perolehan` year(4) NOT NULL,
  `nilai_bangunan` decimal(15,2) NOT NULL,
  `jumlah_ruang` int(11) NOT NULL DEFAULT 0,
  `peruntukkan` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gedungs`
--

INSERT INTO `gedungs` (`id`, `nama_gedung`, `luas_gedung`, `tahun_perolehan`, `nilai_bangunan`, `jumlah_ruang`, `peruntukkan`, `gambar`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'Gedung A', 150.25, '2020', 1000000000.00, 2, 'Kantor Ruang Guru', NULL, 'Gedung A digunakan untuk Ruang Rapat Guru.', '2024-11-27 08:18:25', '2024-12-28 19:36:54'),
(2, 'Gedung B', 200.00, '2012', 1500000000.00, 1, 'Laboratorium', NULL, 'Gedung B digunakan untuk laboratorium praktikum.', '2024-11-27 08:18:25', '2024-12-28 19:32:38'),
(3, 'Gedung C', 180.50, '2015', 1200000000.00, 0, 'Laboratorium', NULL, 'Gedung C digunakan untuk Ruang Praktikum Jurusan RPL.', '2024-11-27 08:18:25', '2024-12-28 19:41:16'),
(4, 'Lab Kimia', 220.75, '2018', 1700000000.00, 0, 'Praktikum', NULL, 'Lab Kimia digunakan untuk Praktikum jurusan Kimia Industri.', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(5, 'Gedung E', 250.30, '2020', 2000000000.00, 0, 'Teori', NULL, 'Gedung E digunakan untuk kelas teori kelas 11 dan 12.', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(6, 'Gedung F', 300.50, '2017', 2500000000.00, 0, 'Teori', NULL, 'Gedung F digunakan untuk ruang teori kelas 10.', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(7, 'Gedung G', 275.45, '2019', 2300000000.00, 0, 'Teori', NULL, 'Gedung G digunakan untuk teori.', '2024-11-27 08:18:25', '2024-11-27 08:18:25');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategoris`
--

CREATE TABLE `kategoris` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategoris`
--

INSERT INTO `kategoris` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Peralatan Elektronik', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(2, 'Peralatan Kantor', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(3, 'Buku dan Literatur', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(4, 'Peralatan Olahraga', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(5, 'Peralatan Kelas', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(6, 'Furnitur', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(7, 'Peralatan Laboratorium', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(8, 'Alat tulis kita', '2024-11-27 08:18:25', '2024-12-30 06:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_06_23_084555_create_kategoris_table', 1),
(5, '2024_11_02_082510_create_gedungs_table', 1),
(6, '2024_11_02_082849_create_ruangs_table', 1),
(7, '2024_11_03_084629_create_barangs_table', 1),
(8, '2024_11_03_092720_create_peminjamans_table', 1),
(9, '2024_11_03_153143_create_alats_table', 1),
(10, '2024_11_03_161001_create_to_do_lists_table', 1),
(11, '2024_11_27_101259_create_detail_peminjamans', 1),
(12, '2024_12_09_173253_create_bhps_table', 2),
(13, '2024_12_11_062934_create_barang_keluars_table', 2),
(14, '2024_12_11_063000_create_detail_barang_keluars_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjamans`
--

CREATE TABLE `peminjamans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `status` enum('menunggu konfirmasi','dipinjam','ditolak','selesai') NOT NULL DEFAULT 'menunggu konfirmasi',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjamans`
--

INSERT INTO `peminjamans` (`id`, `user_id`, `tanggal_peminjaman`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(24, 5, '2025-01-05', 'dipinjam', NULL, '2025-01-05 13:40:54', '2025-01-05 14:26:20');

-- --------------------------------------------------------

--
-- Table structure for table `ruangs`
--

CREATE TABLE `ruangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_ruang` varchar(255) NOT NULL,
  `gedung_id` bigint(20) UNSIGNED NOT NULL,
  `ukuran` decimal(10,2) NOT NULL,
  `kondisi` varchar(255) NOT NULL,
  `peruntukkan` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruangs`
--

INSERT INTO `ruangs` (`id`, `nama_ruang`, `gedung_id`, `ukuran`, `kondisi`, `peruntukkan`, `keterangan`, `created_at`, `updated_at`) VALUES
(2, 'Teori Mesin', 2, 300.00, 'Baik', 'teori', 'teori jurusan mesin', '2024-12-28 19:32:38', '2024-12-28 19:32:38'),
(3, 'Ruang Rapat', 1, 300.00, 'Baik', 'Rapat guru', 'rapat untuk guru', '2024-12-28 19:33:37', '2024-12-28 19:33:57'),
(4, 'Teori Animasi', 1, 100.00, 'Rusak Ringan', 'Teori mata pelajaran', 'untuk teori jurusan animasi', '2024-12-28 19:36:54', '2025-01-05 14:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('f11MevkurD2fKdXhJmeGiuchf8X1JC0oBGQxUCJB', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTVpFd3NkdXNuUk9PU1NTd0t5aFZ6NG5uRW1CSFoyNlBvTERRNDFTWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kZXRhaWxQZW1pbmphbWFuIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9', 1736416391);

-- --------------------------------------------------------

--
-- Table structure for table `to_do_lists`
--

CREATE TABLE `to_do_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `prioritas` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `to_do_lists`
--

INSERT INTO `to_do_lists` (`id`, `judul`, `deskripsi`, `prioritas`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(37, 'pc rusak', 'pc benerin di ruangan 201', 'Tinggi', 'selesai', 1, '2025-01-05 13:47:11', '2025-01-06 05:48:06'),
(38, 'test', 'test', 'Tinggi', 'selesai', 1, '2025-01-09 07:39:38', '2025-01-09 07:39:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `level` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `level`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Teguh Bagas M', 'admin@gmail.com', '2024-11-27 08:18:22', 'admin', '$2y$12$nBsuS9L7Q3hjJ/.Yq20KWeLn.0I38cmrr7SUCniHbCVfzgG1Xcoxq', 'KDCrn8Wv98iNjhrTjKY0oJyk78DodJRlGwUISMCqIbQnrUhAFZDxZWfOfHKe', '2024-11-27 08:18:23', '2024-11-27 08:18:23'),
(2, 'Nur Auliya Putri', 'petugas1@gmail.com', '2024-11-27 08:18:23', 'petugas 1', '$2y$12$yJ9A4yzXQ3crta4xyfZR5u10dbrdBjkOP6hJ/98yD70ye1Gncyj9S', 'YLwWSQUb92qIZO1jdOZBaLPjDoOTJBOfBBST5kexjVe8QwGDnescuBkdcdTp', '2024-11-27 08:18:23', '2024-11-27 08:18:23'),
(3, 'Rifki HY', 'petugas2@gmail.com', '2024-11-27 08:18:23', 'petugas 2', '$2y$12$8HtoucPBOQeQxIIZRreT3.FCApP06hfTtYo18I.IQ7gjez9HWLp3y', 'wXDn3oy1h6ZFwqxUX17iEVMjfDOyRlTqIg24HMpiYPC3nCCvymOLTGbtz9k4', '2024-11-27 08:18:24', '2024-11-27 08:18:24'),
(4, 'Amelia Chisha R', 'petugas3@gmail.com', '2024-11-27 08:18:24', 'petugas 3', '$2y$12$8tWmyClFysTIYix2vWSW6eKXAbO8LtDgaVKQgowvrYVurGiG2L6mW', 'I5dXzHmUjYgE9KBr1D0MsRrc8zFsmR06ULrcUFaGY6r5a2S8sBHnd1bai79G', '2024-11-27 08:18:24', '2024-11-27 08:18:24'),
(5, 'Annisa Nanda', 'guru@gmail.com', '2024-11-27 08:18:24', 'guru', '$2y$12$ZOymZUT4Mrgoc3UjATd9MeLeAy5QLsBo31mCAgxe1NQF3gQrfekJy', 'EaKhY0AyhegvaSP1Az0uXtWcWXnAIed38jb5gtTlJDED17pjiQCdov7GPFma', '2024-11-27 08:18:25', '2024-11-27 08:18:25'),
(6, 'Nurul Fatimah', 'siswa@gmail.com', '2024-11-27 08:18:25', 'siswa', '$2y$12$2.oWSMvFVV0aB2YHLvBxuO2sxfDF2HBBA0R0K9ZsD/R9.7frY6Bxq', 'dhDUQmoSuaZZtCnSvevhAwrSym4eKzACiqkiQ1QAUJcq3YagQ6G1oOeUTNUh', '2024-11-27 08:18:25', '2024-11-27 08:18:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alats`
--
ALTER TABLE `alats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barangs_ruang_id_foreign` (`ruang_id`),
  ADD KEY `barangs_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `barang_keluars`
--
ALTER TABLE `barang_keluars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_keluars_user_id_foreign` (`user_id`);

--
-- Indexes for table `bhps`
--
ALTER TABLE `bhps`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `detail_barang_keluars`
--
ALTER TABLE `detail_barang_keluars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_barang_keluars_barang_keluar_id_foreign` (`barang_keluar_id`),
  ADD KEY `detail_barang_keluars_bhp_id_foreign` (`bhp_id`);

--
-- Indexes for table `detail_peminjamans`
--
ALTER TABLE `detail_peminjamans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_peminjamans_peminjaman_id_foreign` (`peminjaman_id`),
  ADD KEY `detail_peminjamans_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gedungs`
--
ALTER TABLE `gedungs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategoris`
--
ALTER TABLE `kategoris`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `peminjamans`
--
ALTER TABLE `peminjamans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjamans_user_id_foreign` (`user_id`);

--
-- Indexes for table `ruangs`
--
ALTER TABLE `ruangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ruangs_gedung_id_foreign` (`gedung_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `to_do_lists`
--
ALTER TABLE `to_do_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to_do_lists_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alats`
--
ALTER TABLE `alats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `barang_keluars`
--
ALTER TABLE `barang_keluars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bhps`
--
ALTER TABLE `bhps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `detail_barang_keluars`
--
ALTER TABLE `detail_barang_keluars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `detail_peminjamans`
--
ALTER TABLE `detail_peminjamans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gedungs`
--
ALTER TABLE `gedungs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategoris`
--
ALTER TABLE `kategoris`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `peminjamans`
--
ALTER TABLE `peminjamans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ruangs`
--
ALTER TABLE `ruangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `to_do_lists`
--
ALTER TABLE `to_do_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barangs`
--
ALTER TABLE `barangs`
  ADD CONSTRAINT `barangs_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategoris` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `barangs_ruang_id_foreign` FOREIGN KEY (`ruang_id`) REFERENCES `ruangs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `barang_keluars`
--
ALTER TABLE `barang_keluars`
  ADD CONSTRAINT `barang_keluars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_barang_keluars`
--
ALTER TABLE `detail_barang_keluars`
  ADD CONSTRAINT `detail_barang_keluars_barang_keluar_id_foreign` FOREIGN KEY (`barang_keluar_id`) REFERENCES `barang_keluars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_barang_keluars_bhp_id_foreign` FOREIGN KEY (`bhp_id`) REFERENCES `bhps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_peminjamans`
--
ALTER TABLE `detail_peminjamans`
  ADD CONSTRAINT `detail_peminjamans_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_peminjamans_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjamans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peminjamans`
--
ALTER TABLE `peminjamans`
  ADD CONSTRAINT `peminjamans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ruangs`
--
ALTER TABLE `ruangs`
  ADD CONSTRAINT `ruangs_gedung_id_foreign` FOREIGN KEY (`gedung_id`) REFERENCES `gedungs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `to_do_lists`
--
ALTER TABLE `to_do_lists`
  ADD CONSTRAINT `to_do_lists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
