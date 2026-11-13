-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 13, 2026 at 03:14 AM
-- Server version: 8.0.30
-- PHP Version: 8.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_smkn1_bakam`
--

-- --------------------------------------------------------

--
-- Table structure for table `bkk_listings`
--

CREATE TABLE `bkk_listings` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('kerja_tetap','magang','magang_rekrutmen') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kerja_tetap',
  `description` text COLLATE utf8mb4_unicode_ci,
  `qualifications` text COLLATE utf8mb4_unicode_ci,
  `contact_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posted_at` date NOT NULL,
  `expires_at` date DEFAULT NULL,
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
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-settings.all', 'a:11:{s:11:\"ppdb_status\";s:4:\"open\";s:14:\"ppdb_open_date\";s:10:\"2027-05-27\";s:13:\"spmb_schedule\";s:209:\"[{\"label\":\"Pendaftaran\",\"value\":\"27 Mei - 12 Juni 2027\"},{\"label\":\"Verifikasi Data\",\"value\":\"17 juni 2027\"},{\"label\":\"Pengumuman\",\"value\":\"29 juni 2027\"},{\"label\":\"Daftar Ulang\",\"value\":\"1 juli -8 juli 2027\"}]\";s:15:\"social_facebook\";s:37:\"https://www.facebook.com/SkansaBakam/\";s:16:\"social_instagram\";s:45:\"https://www.instagram.com/smknegerisatubakam/\";s:14:\"social_youtube\";s:43:\"https://www.youtube.com/@smknegeri1bakam256\";s:11:\"school_name\";s:12:\"SMKN 1 Bakam\";s:14:\"school_address\";s:39:\"Jl. Raya Bakam, Kec. Bakam, Kab. Bangka\";s:12:\"school_phone\";s:16:\"+62 812-7393-587\";s:12:\"school_email\";s:22:\"info@smkn1bakam.sch.id\";s:10:\"erapor_url\";s:26:\"http://36.92.146.177:8154/\";}', 1794539808);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `concentrations`
--

CREATE TABLE `concentrations` (
  `id` bigint UNSIGNED NOT NULL,
  `program_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `overview` text COLLATE utf8mb4_unicode_ci,
  `advantages` json DEFAULT NULL,
  `further_studies` json DEFAULT NULL,
  `quota` int UNSIGNED NOT NULL DEFAULT '0',
  `competencies` json DEFAULT NULL,
  `career_prospects` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `concentrations`
--

INSERT INTO `concentrations` (`id`, `program_id`, `name`, `title`, `slug`, `description`, `image`, `overview`, `advantages`, `further_studies`, `quota`, `competencies`, `career_prospects`, `created_at`, `updated_at`) VALUES
(1, 1, 'TKJ', 'Teknik Komputer Jaringan', 'tkj', 'Fokus pada jaringan komputer lokal dan wide area, administrasi server, dan keamanan jaringan.', NULL, NULL, NULL, NULL, 70, '[\"Instalasi Jaringan LAN/WAN\", \"Konfigurasi Router & Switch (Cisco)\", \"Administrasi Server Linux\", \"Keamanan Jaringan & Firewall\", \"Cloud Computing Dasar\"]', '[\"Network Engineer\", \"IT Support Specialist\", \"System Administrator\", \"Wirausaha Jasa IT\"]', '2026-09-22 19:15:30', '2026-11-11 22:25:44'),
(2, 2, 'TSM', 'Teknik Sepeda Motor', 'tsm', 'Spesialisasi pada perawatan dan perbaikan sepeda motor berbahan bakar konvensional maupun injeksi.', NULL, NULL, NULL, NULL, 36, '[\"Tune-up & Servis Sepeda Motor\", \"Sistem Injeksi Bahan Bakar\", \"Sistem Kelistrikan Sepeda Motor\", \"Diagnosa Mesin\", \"K3 Bengkel\"]', '[\"Teknisi Bengkel Resmi\", \"Mekanik Otomotif\", \"Wirausaha Bengkel\", \"Inspektur Kendaraan\"]', '2026-09-22 19:15:30', '2026-09-22 19:15:30'),
(3, 2, 'TKR', 'Teknik Kendaraan Ringan', 'tkr', 'Spesialisasi pada perawatan dan perbaikan kendaraan ringan roda empat (mobil) berbahan bakar bensin dan diesel.', NULL, NULL, NULL, NULL, 36, '[\"Tune-up Mesin Konvensional & EFI\", \"Sistem Kelistrikan Kendaraan Ringan\", \"Sistem Rem & Suspensi\", \"Diagnosa Menggunakan Scan Tool\", \"K3 Bengkel\"]', '[\"Teknisi Bengkel Resmi\", \"Mekanik Otomotif\", \"Wirausaha Bengkel\", \"Quality Control Kendaraan\"]', '2026-09-22 19:15:30', '2026-09-22 19:15:30');

-- --------------------------------------------------------

--
-- Table structure for table `concentration_teacher`
--

CREATE TABLE `concentration_teacher` (
  `teacher_id` bigint UNSIGNED NOT NULL,
  `concentration_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `concentration_teacher`
--

INSERT INTO `concentration_teacher` (`teacher_id`, `concentration_id`) VALUES
(2, 1),
(3, 1),
(5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'claude ai1', 'bariahmad6345@gmail.com', 'Pertanyaan Umum', 'siapa saya?', 1, '2026-10-20 00:06:40', '2026-10-22 17:56:34'),
(2, 'agus rifai', 'agus@gmail.com', 'Pertanyaan Umum', 'siapakah yang membuat website ini, bagus dan keren banget', 1, '2026-11-12 19:48:07', '2026-11-12 19:48:31');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `icon`, `name`, `qty`, `description`, `image`, `order`, `created_at`, `updated_at`) VALUES
(1, '🖥️', 'Laboratorium Komputer', '2 Lab', '60 unit PC dengan koneksi internet fiber optik dan software terbaru.', 'facilities/V3sFkVd4JFNxQBlItaL3TbhLTrntOElfWur12QaO.jpg', 1, '2026-10-20 05:40:36', '2026-10-20 21:13:27'),
(2, '🔌', 'Lab Jaringan (TKJ)', '1 Lab', 'Dilengkapi perangkat Cisco, switch manageable, dan server rack nyata.', NULL, 2, '2026-10-20 05:40:36', '2026-10-20 05:40:36'),
(3, '📚', 'Perpustakaan Digital', '1 Unit', 'Koleksi 3.000+ buku fisik dan akses ke ribuan e-book online.', NULL, 3, '2026-10-20 05:40:36', '2026-10-20 05:40:36'),
(4, '🔬', 'Lab IPA', '1 Lab', 'Peralatan laboratorium kimia, fisika, dan biologi yang memadai.', NULL, 4, '2026-10-20 05:40:36', '2026-10-20 05:40:36'),
(5, '🔧', 'Bengkel Otomotif', '1 Unit', 'Bengkel praktik TSM & TKR lengkap dengan unit kendaraan latih.', NULL, 5, '2026-10-20 05:40:36', '2026-10-20 05:40:36'),
(6, '⚽', 'Lapangan Olahraga', '2 Lapangan', 'Lapangan futsal & bola voli untuk kegiatan ekstrakurikuler.', NULL, 6, '2026-10-20 05:40:36', '2026-10-20 05:40:36'),
(7, '🍽️', 'Kantin Sekolah', '1 Area', 'Area kantin bersih dan nyaman dengan harga terjangkau bagi siswa.', NULL, 7, '2026-10-20 05:40:36', '2026-10-20 05:40:36'),
(8, '🕌', 'Musholla', '1 Unit', 'Tempat ibadah yang nyaman untuk seluruh warga sekolah.', NULL, 8, '2026-10-20 05:40:36', '2026-10-20 05:40:36'),
(9, NULL, 'Bengkel TSM', '1 ruangan', 'Bengkel TSM dilengkapi dengan peralatan yang memadai untuk menunjang proses praktek selama bersekolah di SMKN 1 Bakam', NULL, 9, '2026-10-20 05:43:40', '2026-10-20 05:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('photo','video') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'photo',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `file_path`, `video_url`, `type`, `category`, `event_date`, `order`, `created_at`, `updated_at`) VALUES
(1, 'akew', 'galeri/4JIHiNI3ADUEBFZS3KZ7tFDZEHvHZGaxFxM8ctAM.jpg', NULL, 'photo', 'Kegiatan', '2026-10-20', 1, '2026-10-19 21:27:30', '2026-10-19 21:27:30');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_books`
--

CREATE TABLE `library_books` (
  `id` bigint UNSIGNED NOT NULL,
  `no_induk_buku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_inventaris_buku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengarang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penerbit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_terbit` year DEFAULT NULL,
  `klasifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `library_books`
--

INSERT INTO `library_books` (`id`, `no_induk_buku`, `no_inventaris_buku`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `klasifikasi`, `jumlah`, `created_at`, `updated_at`) VALUES
(1, 'B001', NULL, 'Pemrograman Web Dasar', 'Andi Pratama', 'Informatika Bandung', '2022', '005.13', 5, '2026-09-22 19:15:30', '2026-09-22 19:15:30'),
(2, 'B002', NULL, 'Motor Bensin 4 Langkah', 'Budi Santoso', 'Erlangga', '2020', '629.25', 3, '2026-09-22 19:15:30', '2026-09-22 19:15:30');

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
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_12_074909_create_programs_table', 1),
(5, '2026_08_12_074929_create_concentrations_table', 1),
(6, '2026_08_12_074941_create_news_table', 1),
(7, '2026_08_12_074958_create_news_comments_table', 1),
(8, '2026_08_12_075024_create_teachers_table', 1),
(9, '2026_08_12_075044_create_ppdb_registrants_table', 1),
(10, '2026_08_12_075056_create_library_books_table', 1),
(11, '2026_08_12_075112_create_pages_table', 1),
(12, '2026_08_12_075133_create_settings_table', 1),
(13, '2026_08_13_000001_add_details_to_concentrations_table', 1),
(14, '2026_08_13_000001_add_excerpt_and_image_to_pages_table', 1),
(15, '2026_08_13_000002_create_bkk_listings_table', 1),
(16, '2026_08_13_000003_create_contact_messages_table', 1),
(17, '2026_08_13_000003_create_tracer_responses_table', 1),
(18, '2026_08_13_000004_create_galleries_table', 1),
(19, '2026_08_13_000006_add_username_to_users_table', 1),
(20, '2026_08_13_000007_add_subject_is_active_to_teachers_table', 1),
(21, '2026_08_14_000001_add_management_and_tmt_fields_to_teachers_table', 1),
(22, '2026_10_08_000001_change_role_to_string_on_users_table', 2),
(23, '2026_10_20_000001_create_facilities_table', 3),
(24, '2026_11_12_000000_add_detail_columns_to_concentrations_table', 4),
(25, '2026_11_13_000001_add_structure_fields_to_teachers', 5);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `user_id`, `title`, `slug`, `content`, `thumbnail`, `category`, `published_at`, `created_at`, `updated_at`) VALUES
(5, 3, 'Polisi Menjadi Pembina Upacara di SMKN 1 Bakam, Sampaikan Pesan tentang Narkoba, Perkelahian, dan Keselamatan Berlalu Lintas', 'polisi-menjadi-pembina-upacara-di-smkn-1-bakam-sampaikan-pesan-tentang-narkoba-perkelahian-dan-keselamatan-berlalu-lintas-wQJeF', '<p>SMKN 1 Bakam melaksanakan kegiatan upacara bendera yang diisi dengan penyampaian amanat dari pihak kepolisian sebagai pembina upacara. Kegiatan ini bertujuan untuk meningkatkan kesadaran siswa terhadap pentingnya menjaga kedisiplinan, menaati peraturan, serta menghindari perilaku yang dapat merugikan diri sendiri maupun orang lain.</p>\n<p>Dalam amanatnya, pihak kepolisian menyampaikan beberapa hal penting yang perlu diperhatikan oleh seluruh siswa, di antaranya bahaya penyalahgunaan narkoba, larangan melakukan perkelahian antarsiswa, serta pentingnya menggunakan helm saat berkendara ke sekolah.</p>\n<p>Terkait penyalahgunaan narkoba, siswa diimbau untuk menjauhi segala bentuk narkoba karena dapat merusak kesehatan, mengganggu proses belajar, dan berdampak buruk terhadap masa depan. Para siswa juga diingatkan agar mampu memilih pergaulan yang positif serta tidak mudah terpengaruh oleh ajakan yang mengarah pada perilaku negatif.</p>\n<p>Selain itu, pihak kepolisian menegaskan pentingnya menjaga ketertiban dan menghindari perkelahian antarsiswa. Setiap permasalahan hendaknya diselesaikan dengan cara yang baik melalui komunikasi dan musyawarah, bukan dengan kekerasan. Sikap saling menghormati dan menjaga persaudaraan di lingkungan sekolah menjadi hal penting dalam menciptakan suasana belajar yang aman dan nyaman.</p>\n<p>Dalam hal keselamatan berlalu lintas, siswa yang menggunakan sepeda motor untuk berangkat ke sekolah diingatkan agar selalu memakai helm sesuai standar, baik saat perjalanan pergi maupun pulang sekolah. Penggunaan helm merupakan salah satu upaya untuk melindungi diri dan mengurangi risiko cedera apabila terjadi kecelakaan.</p>\n<p>Melalui kegiatan ini, diharapkan seluruh siswa SMKN 1 Bakam dapat memahami dan menerapkan pesan yang disampaikan, baik di lingkungan sekolah maupun dalam kehidupan sehari-hari. Kegiatan tersebut juga menjadi bentuk edukasi dan upaya membangun kesadaran generasi muda agar lebih disiplin, bertanggung jawab, serta taat terhadap hukum dan peraturan yang berlaku.</p>', 'news/SqBRUPwcfqr0KUylKr5nIqO0CwXDJ4AuvZiSQnSa.jpg', 'Kegiatan', '2026-10-25 04:35:00', '2026-10-25 04:35:00', '2026-10-25 04:44:58'),
(6, 3, 'SMKN 1 Bakam Akan Melaksanakan Tes Kemampuan Akademik (TKA) Minggu Depan', 'smkn-1-bakam-akan-melaksanakan-tes-kemampuan-akademik-tka-minggu-depan-MUHCQ', '<p>SMKN 1 Bakam akan melaksanakan kegiatan Tes Kemampuan Akademik (TKA) pada minggu depan. Kegiatan ini merupakan salah satu agenda pendidikan yang perlu dipersiapkan dengan baik oleh peserta didik yang mengikuti pelaksanaannya.</p>\n<p>Sehubungan dengan kegiatan tersebut, seluruh siswa yang menjadi peserta TKA diimbau untuk mempersiapkan diri dengan belajar secara teratur, menjaga kesehatan, serta memastikan kelengkapan yang diperlukan sebelum pelaksanaan tes.</p>\n<p>Pihak sekolah juga mengimbau seluruh peserta untuk memperhatikan informasi dan arahan yang disampaikan oleh pihak sekolah, khususnya terkait jadwal, pembagian sesi, ruang pelaksanaan, serta tata tertib yang harus dipatuhi selama kegiatan berlangsung.</p>\n<p>Diharapkan seluruh peserta dapat mengikuti Tes Kemampuan Akademik dengan tertib, disiplin, dan penuh tanggung jawab sehingga kegiatan dapat berjalan dengan lancar.</p>\n<p>Informasi lebih lanjut mengenai tanggal, waktu, dan teknis pelaksanaan TKA akan disampaikan melalui pengumuman resmi sekolah. Seluruh siswa diharapkan terus memantau informasi dari pihak sekolah agar tidak melewatkan informasi penting terkait kegiatan tersebut.</p>', 'news/zwwClB85bCZzUtc0kNkcWl7fOFKUQXEry7t5v5ST.jpg', 'Pengumuman', '2026-10-25 04:48:47', '2026-10-25 04:48:47', '2026-10-25 04:48:47'),
(7, 3, 'Siswa SMKN 1 Bakam Raih Juara 2 dalam Kompetisi Karate', 'siswa-smkn-1-bakam-raih-juara-2-dalam-kompetisi-karate-EzgMm', '<p>Kabar membanggakan datang dari SMKN 1 Bakam. Salah satu siswa berhasil meraih juara 2 dalam kompetisi karate. Prestasi ini menjadi kebanggaan bagi sekolah sekaligus bukti bahwa siswa SMKN 1 Bakam mampu mengembangkan potensi dan kemampuan melalui kegiatan olahraga.</p>\n<p>Keberhasilan tersebut merupakan hasil dari usaha, latihan, kedisiplinan, dan semangat juang yang ditunjukkan dalam mengikuti kompetisi. Karate tidak hanya melatih kemampuan fisik dan teknik bela diri, tetapi juga membentuk karakter, meningkatkan kedisiplinan, serta menanamkan nilai sportivitas dan rasa tanggung jawab.</p>\n<p>Pencapaian ini diharapkan dapat menjadi motivasi bagi seluruh siswa SMKN 1 Bakam untuk terus mengembangkan bakat dan minat masing-masing, baik di bidang akademik maupun nonakademik. Prestasi yang diraih juga menjadi dorongan bagi siswa lainnya untuk berani mengikuti berbagai kompetisi dan membawa nama baik sekolah.</p>\n<p>SMKN 1 Bakam mengapresiasi pencapaian yang telah diraih dan berharap prestasi ini dapat menjadi langkah awal untuk meraih keberhasilan yang lebih tinggi pada kompetisi berikutnya.</p>\n<p>Selamat atas prestasi juara 2 yang diraih! Semoga keberhasilan ini menjadi inspirasi bagi seluruh warga sekolah untuk terus berprestasi dan mengharumkan nama SMKN 1 Bakam.</p>', 'news/6EQTFxaPBgHVnIMRKUeNA0Zda1YHTCpHLoxkt9zJ.jpg', 'Prestasi', '2026-10-25 05:00:38', '2026-10-25 05:00:38', '2026-10-25 05:00:38');

-- --------------------------------------------------------

--
-- Table structure for table `news_comments`
--

CREATE TABLE `news_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `news_id` bigint UNSIGNED NOT NULL,
  `nama_pengirim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi_komentar` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_comments`
--

INSERT INTO `news_comments` (`id`, `news_id`, `nama_pengirim`, `isi_komentar`, `status`, `created_at`, `updated_at`) VALUES
(3, 7, 'anomali', 'semoga mendapatkan juara pertama dilomba selanjut', 'approved', '2026-11-12 19:46:29', '2026-11-12 19:48:54');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `excerpt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `excerpt`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Visi dan Misi', 'visi-misi', 'Menyelenggarakan pendidikan yang menumbuhkan nilai religius dan akhlak mulia dalam kehidupan sehari-hari.\nMelaksanakan program pembiasaan yang membangun karakter peserta didik yang disiplin, bertanggung jawab, kreatif, dan peduli lingkungan.\nMeningkatkan kompetensi siswa sesuai dengan bidang keahlian melalui pembelajaran mendalam berbasis industri dan teknologi.\nMenyiapkan lulusan agar mampu bekerja secara profesional dan berdaya saing di dunia kerja.\nMemberikan bekal keterampilan dan pengetahuan untuk melanjutkan pendidikan ke jenjang yang lebih tinggi.\nMengembangkan jiwa wirausaha melalui praktik kewirausahaan, dan kerja sama dengan dunia usaha/dunia industri.', 'Terwujudnya tenaga terdidik yang religius, berkarakter, Kompeten, siap bekerja, melanjutkan pendidikan dan berwirausaha.', NULL, '2026-10-19 23:26:11', '2026-10-19 23:27:50'),
(2, 'Tri Haryati, S.Pd.', 'sambutan-kepala', '<p>Salam sejahtera bagi kita semua.</p>\n<p>Puji syukur ke hadirat Allah Swt., Tuhan Yang Maha Esa, atas segala rahmat dan karunia-Nya sehingga kita senantiasa diberikan kesempatan untuk belajar, berkarya, dan memberikan yang terbaik bagi dunia pendidikan.</p>\n<p>Selamat datang di website resmi SMK Negeri 1 Bakam, ruang informasi, komunikasi, dan inspirasi yang kami hadirkan sebagai jembatan antara sekolah, peserta didik, orang tua, dunia kerja, dan masyarakat luas.</p>\n<p>Kami percaya bahwa setiap anak memiliki potensi, setiap potensi memiliki kesempatan untuk berkembang, dan setiap kesempatan dapat menjadi jalan menuju masa depan yang gemilang.</p>\n<p>SMK Negeri 1 Bakam merupakan salah satu lembaga pendidikan menengah kejuruan negeri yang terletak di Desa Bakam, Kecamatan Bakam, Kabupaten Bangka, Provinsi Kepulauan Bangka Belitung. Sejak berdiri pada tahun 2013, sekolah ini hadir dengan semangat memberikan layanan pendidikan kejuruan yang membekali generasi muda dengan pengetahuan, keterampilan, karakter, dan kesiapan menghadapi dunia kerja maupun kehidupan bermasyarakat.</p>\n<p>Melalui bidang keahlian Teknik Otomotif serta Teknik Jaringan Komputer dan Telekomunikasi, kami berupaya menghadirkan pendidikan yang relevan dengan perkembangan teknologi, kebutuhan dunia industri, dan tantangan zaman yang terus berubah.</p>\n<p>Namun, bagi kami, pendidikan kejuruan bukan semata-mata tentang kemampuan mengoperasikan peralatan, menguasai teknologi, atau memperoleh pekerjaan. Lebih dari itu, pendidikan adalah proses membentuk manusia yang memiliki integritas, tanggung jawab, kemandirian, kepedulian, dan keberanian untuk terus belajar.</p>\n<p>Kami tidak hanya ingin melahirkan lulusan yang terampil bekerja, tetapi juga pribadi yang mampu berpikir, berkarakter, beradaptasi, dan memberi manfaat bagi sesama.</p>\n<p>Di tengah pesatnya perkembangan ilmu pengetahuan dan teknologi, kami menyadari bahwa sekolah harus terus bergerak dan berbenah. Oleh karena itu, SMK Negeri 1 Bakam berkomitmen membangun lingkungan pembelajaran yang berkesadaran, bermakna, dan menggembirakan, dengan memberikan ruang bagi setiap peserta didik untuk mengenali potensi dirinya, mengembangkan kreativitas, serta belajar melalui pengalaman nyata.</p>\n<p>Kami juga meyakini bahwa sekolah yang baik bukan hanya sekolah yang mengajarkan cara meraih keberhasilan, tetapi juga sekolah yang mengajarkan kejujuran dalam berproses, ketangguhan menghadapi tantangan, penghargaan terhadap sesama, dan kepedulian terhadap lingkungan.</p>\n<p>Melalui penguatan pembelajaran praktik, pemanfaatan teknologi, pengembangan budaya positif, serta kepedulian terhadap kelestarian lingkungan, kami ingin menjadikan SMK Negeri 1 Bakam sebagai tempat bertumbuhnya generasi yang kompeten sekaligus memiliki kepekaan sosial dan tanggung jawab terhadap alam sekitarnya.</p>\n<p>Perjalanan menuju sekolah yang unggul tentu tidak dapat diwujudkan oleh sekolah sendiri. Diperlukan sinergi yang kuat antara guru, tenaga kependidikan, peserta didik, orang tua, dunia usaha dan dunia industri, pemerintah, serta seluruh elemen masyarakat. Kami membuka ruang kolaborasi, menerima gagasan, dan menyambut setiap bentuk dukungan yang membawa kemajuan bagi pendidikan anak-anak kita.</p>\n<p>Website ini menjadi bagian dari komitmen kami untuk menghadirkan sekolah yang terbuka, informatif, inovatif, dan terus berkembang. Melalui media ini, kami berharap masyarakat dapat mengenal lebih dekat berbagai program, kegiatan, karya, prestasi, dan langkah perubahan yang sedang kami bangun bersama.</p>\n<p>Akhirnya, izinkan saya menyampaikan sebuah keyakinan:</p>\n<p>“Sekolah yang hebat bukan hanya diukur dari megahnya bangunan atau banyaknya prestasi, melainkan dari seberapa besar kemampuannya menumbuhkan harapan, membuka kesempatan, dan mengubah kehidupan anak-anak menjadi lebih baik.”</p>\n<p>Dari Bakam, sebuah tempat yang menjadi bagian dari kekayaan Bumi Serumpun Sebalai, kami ingin menumbuhkan mimpi-mimpi besar. Karena kami percaya, masa depan yang hebat tidak ditentukan oleh dari mana seseorang berasal, melainkan oleh nilai, ilmu, keterampilan, dan semangat yang dibawanya untuk terus melangkah.</p>\n<p>Bersama kita tumbuh, bersama kita berkarya, dan bersama kita wujudkan SMK Negeri 1 Bakam yang semakin maju, berkarakter, kompeten, dan membanggakan.</p>\n<p>Terima kasih atas kunjungan dan kepercayaan Anda.</p>\n<p>Wassalamu’alaikum warahmatullahi wabarakatuh.</p>', NULL, NULL, '2026-10-19 23:30:20', '2026-10-22 17:47:37'),
(3, 'SEJARAH SINGKAT SMK NEGERI 1 BAKAM', 'sejarah', '<p>SMK Negeri 1 Bakam merupakan satu-satunya Sekolah Menengah Kejuruan Negeri yang berada di Kecamatan Bakam, Kabupaten Bangka, Provinsi Kepulauan Bangka Belitung. Sekolah ini berlokasi di Desa Bakam, berjarak sekitar 38 kilometer dari ibu kota Kabupaten Bangka dan Kota Pangkalpinang sebagai ibu kota Provinsi Kepulauan Bangka Belitung.</p>\n<p>SMK Negeri 1 Bakam didirikan pada tahun 2012 melalui bantuan dana Anggaran Pendapatan dan Belanja Negara Perubahan (APBN-P) melalui Direktorat Pembinaan Sekolah Menengah Kejuruan. Pada tahap awal pembangunan, sekolah memiliki lima ruang kelas, tiga ruang praktik, serta fasilitas sanitasi berupa tiga unit toilet.</p>\n<p>Pada awal pendiriannya, SMK Negeri 1 Bakam membuka dua program keahlian, yaitu Teknik Kendaraan Ringan (TKR) dan Teknik Komputer dan Jaringan (TKJ). Kehadiran sekolah ini menjadi bagian dari upaya memperluas akses pendidikan kejuruan bagi masyarakat Kecamatan Bakam dan sekitarnya.</p>\n<p>SMK Negeri 1 Bakam secara resmi diresmikan pada 22 Mei 2013 oleh Bupati Bangka saat itu, H. Yusroni Yazid, S.E. Tanggal tersebut kemudian ditetapkan sebagai hari lahir SMK Negeri 1 Bakam dan diperingati setiap tahun.</p>\n<p>Penerimaan peserta didik baru pertama kali dilaksanakan pada 17 Juni 2013. Pada tahun pertama, sekolah memperoleh antusiasme masyarakat yang cukup tinggi dengan jumlah pendaftar sebanyak 256 calon peserta didik. Dari jumlah tersebut, sebanyak 104 peserta didik diterima dan terbagi ke dalam tiga rombongan belajar pada dua program keahlian yang tersedia.</p>\n<p>Sejak awal berdirinya, SMK Negeri 1 Bakam hadir dengan semangat memberikan layanan pendidikan kejuruan yang membekali peserta didik dengan pengetahuan, keterampilan, dan karakter sebagai bekal untuk melanjutkan pendidikan, memasuki dunia kerja, maupun berwirausaha.</p>\n<p>Hingga saat ini, SMK Negeri 1 Bakam terus berupaya mengembangkan kualitas pembelajaran, sarana dan prasarana, serta kompetensi peserta didik agar mampu beradaptasi dengan perkembangan teknologi dan kebutuhan dunia kerja.</p>', NULL, 'pages/Lf9nsRY3jBXzYi6vUhiNJZqw0K1rW6yB5R5Umej1.jpg', '2026-10-20 00:24:03', '2026-10-22 17:46:41');

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
-- Table structure for table `ppdb_registrants`
--

CREATE TABLE `ppdb_registrants` (
  `id` bigint UNSIGNED NOT NULL,
  `registration_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_kk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_id` bigint UNSIGNED NOT NULL,
  `concentration_id` bigint UNSIGNED DEFAULT NULL,
  `has_kip` tinyint(1) NOT NULL DEFAULT '0',
  `has_kis` tinyint(1) NOT NULL DEFAULT '0',
  `has_pkh` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('pending','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ppdb_registrants`
--

INSERT INTO `ppdb_registrants` (`id`, `registration_number`, `full_name`, `gender`, `nik`, `nisn`, `no_kk`, `address`, `program_id`, `concentration_id`, `has_kip`, `has_kis`, `has_pkh`, `status`, `created_at`, `updated_at`) VALUES
(1, '2026001', 'Sabrina', 'P', '0123456789145678', '2638469240', '9876543920109801', 'Desa Neknang, Kecamatan Bakam, Kabupaten Bangka Kepulauan Bangka Belitung', 2, 3, 0, 0, 0, 'verified', '2026-10-20 00:48:28', '2026-10-20 00:53:57'),
(2, '2026002', 'Ahmad Sobari', 'L', '1212233456789876', '1234567890', '1232123423436572', 'bakam', 2, 2, 0, 0, 0, 'pending', '2026-11-12 19:55:50', '2026-11-12 19:55:50');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `accreditation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `name`, `slug`, `description`, `accreditation`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Teknik Jaringan Komputer dan Telekomunikasi', 'teknik-jaringan-komputer-dan-telekomunikasi', 'Program keahlian yang mempersiapkan lulusan terampil di bidang instalasi, konfigurasi, dan pemeliharaan jaringan komputer serta infrastruktur telekomunikasi modern.', 'A', NULL, '2026-09-22 19:15:30', '2026-09-22 19:15:30'),
(2, 'Teknik Otomotif', 'teknik-otomotif', 'Program keahlian yang mencetak teknisi otomotif profesional dengan kompetensi perawatan, perbaikan, dan diagnosa kendaraan bermotor sesuai standar industri.', 'B', NULL, '2026-09-22 19:15:30', '2026-09-22 19:15:30');

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
('eIOnnmRS8LbwOodAw6IyKGxptxicgIZLWRDNQqF4', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ0c0tNVUJ4Q3dQejJyMWJCZXFycWlBaWRuYTl0RXJoTVI3cm9xVldxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbiIsInJvdXRlIjoiYWRtaW4uZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjN9', 1794496789),
('WbHlbETBg7FFWitHf2bspiq1GppwZ7FtI1pmIujW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJEYTZ4SHdNbFdTOEpKR0lhM1ZqSFh6ZGp6N2E5bXdiWUtETnRlWGY1IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==', 1794539633);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'ppdb_status', 'open', '2026-10-19 19:00:57', '2026-10-19 20:17:13'),
(2, 'ppdb_open_date', '2027-05-27', '2026-10-19 19:00:57', '2026-10-19 19:47:41'),
(3, 'spmb_schedule', '[{\"label\":\"Pendaftaran\",\"value\":\"27 Mei - 12 Juni 2027\"},{\"label\":\"Verifikasi Data\",\"value\":\"17 juni 2027\"},{\"label\":\"Pengumuman\",\"value\":\"29 juni 2027\"},{\"label\":\"Daftar Ulang\",\"value\":\"1 juli -8 juli 2027\"}]', '2026-10-19 19:48:54', '2026-10-19 19:48:54'),
(4, 'social_facebook', 'https://www.facebook.com/SkansaBakam/', '2026-10-20 01:03:37', '2026-10-20 01:03:37'),
(5, 'social_instagram', 'https://www.instagram.com/smknegerisatubakam/', '2026-10-20 01:03:37', '2026-10-20 06:21:18'),
(6, 'social_youtube', 'https://www.youtube.com/@smknegeri1bakam256', '2026-10-20 01:03:37', '2026-10-20 06:21:18'),
(7, 'school_name', 'SMKN 1 Bakam', '2026-10-20 06:22:03', '2026-10-20 06:22:03'),
(8, 'school_address', 'Jl. Raya Bakam, Kec. Bakam, Kab. Bangka', '2026-10-20 06:22:03', '2026-10-20 23:49:16'),
(9, 'school_phone', '+62 812-7393-587', '2026-10-20 06:22:03', '2026-10-20 06:22:03'),
(10, 'school_email', 'info@smkn1bakam.sch.id', '2026-10-20 06:22:03', '2026-10-20 06:22:03'),
(11, 'erapor_url', 'http://36.92.146.177:8154/', '2026-11-01 23:31:30', '2026-11-01 23:31:30');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tmt` date DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subjects` json DEFAULT NULL,
  `teaches_all_programs` tinyint(1) NOT NULL DEFAULT '0',
  `program_id` bigint UNSIGNED DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_management` tinyint(1) NOT NULL DEFAULT '0',
  `order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `nip`, `tmt`, `position`, `subject`, `subjects`, `teaches_all_programs`, `program_id`, `photo`, `is_active`, `is_management`, `order`, `created_at`, `updated_at`) VALUES
(2, 'tohir S.T., M.M', '199004042019031013', '2026-09-28', 'kepala program keahlian TKJ', 'konsentrasi keahlian TKJ', '[\"konsentrasi keahlian TKJ\"]', 0, 1, NULL, 1, 1, 3, '2026-10-20 05:45:37', '2026-10-22 17:52:57'),
(3, 'Tri Hayati', '188375967694758447', '2024-07-09', 'Kepala Sekolah', 'matematika', '[\"matematika\"]', 0, 1, 'guru/aiaxVB59jJ2a1M7XojjjfDA8tZbNb0LH896Nq8Kw.heic', 1, 1, 1, '2026-10-20 21:11:55', '2026-10-20 21:11:55'),
(5, 'Muhammad Yusuf, S.Pd', '13767646394373', '2026-10-23', 'wakil kurikulum', 'IPAS', '[\"IPAS\"]', 0, 1, NULL, 1, 1, 2, '2026-10-22 17:50:17', '2026-10-22 17:50:17'),
(7, 'Dora Prastadinata', '10388406496489', '2026-11-13', 'Wakasek kesiswaan', 'penjaskes', '[\"penjaskes\"]', 1, NULL, NULL, 1, 1, 0, '2026-11-12 19:34:50', '2026-11-12 19:34:50');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_positions`
--

CREATE TABLE `teacher_positions` (
  `id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `position_key` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_label` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_positions`
--

INSERT INTO `teacher_positions` (`id`, `teacher_id`, `position_key`, `class_label`, `created_at`, `updated_at`) VALUES
(1, 3, 'kepsek', NULL, '2026-11-12 19:16:24', '2026-11-12 19:16:24'),
(2, 7, 'wk_kesiswaan', NULL, '2026-11-12 19:34:50', '2026-11-12 19:34:50');

-- --------------------------------------------------------

--
-- Table structure for table `tracer_responses`
--

CREATE TABLE `tracer_responses` (
  `id` bigint UNSIGNED NOT NULL,
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_lulus` year NOT NULL,
  `alumni_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_id` bigint UNSIGNED DEFAULT NULL,
  `concentration_id` bigint UNSIGNED DEFAULT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domisili` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_kegiatan` enum('bekerja','kuliah','wiraswasta','bekerja_kuliah','belum') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_instansi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bidang_instansi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posisi_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kesesuaian_kerja` enum('sangat_sesuai','sesuai','kurang_sesuai','tidak_sesuai') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lama_tunggu_kerja` enum('kurang_1_bulan','1_3_bulan','3_6_bulan','lebih_6_bulan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `via_bkk` tinyint(1) NOT NULL DEFAULT '0',
  `nama_kampus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_studi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenjang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kuliah_linier` tinyint(1) DEFAULT NULL,
  `jenis_usaha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bidang_usaha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lama_usaha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_tenaga_kerja` int UNSIGNED DEFAULT NULL,
  `alasan_belum` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evaluasi_kesiapan` tinyint UNSIGNED DEFAULT NULL,
  `saran` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tracer_responses`
--

INSERT INTO `tracer_responses` (`id`, `nisn`, `tahun_lulus`, `alumni_nama`, `program_id`, `concentration_id`, `no_hp`, `domisili`, `status_kegiatan`, `nama_instansi`, `bidang_instansi`, `posisi_jabatan`, `kesesuaian_kerja`, `lama_tunggu_kerja`, `via_bkk`, `nama_kampus`, `program_studi`, `jenjang`, `kuliah_linier`, `jenis_usaha`, `bidang_usaha`, `lama_usaha`, `jumlah_tenaga_kerja`, `alasan_belum`, `evaluasi_kesiapan`, `saran`, `created_at`, `updated_at`) VALUES
(1, '1234567890', '2024', 'ahmad sobari', NULL, NULL, '083801144688', 'bakam', 'kuliah', NULL, NULL, NULL, NULL, NULL, 0, 'politeknik manufaktur negeri bangka belitung', 'D4 teknologi rekayasa perangkat lunak', 'D4', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'perdalam softskill seperti perbanyak presentasi untuk meningkatkan public speaking', '2026-10-27 18:38:44', '2026-10-27 18:38:44'),
(2, '3858676694', '2023', 'hisyam farhan', NULL, NULL, '3857604374', 'bakam', 'bekerja', 'PT GML', 'keamanan', 'satpam', 'tidak_sesuai', 'kurang_1_bulan', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'semoga smk semakin berkembang lebih bagus', '2026-10-27 18:41:17', '2026-10-27 18:41:17'),
(3, '576063838', '2024', 'jodi', NULL, NULL, '756993', 'kelapa', 'bekerja', 'CV yamaha sumber jadi', 'bengkel', 'mekanik', 'sangat_sesuai', '1_3_bulan', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'gacor', '2026-10-27 18:43:14', '2026-10-27 18:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'humas',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Administrator', 'admin', 'admin@smkn1bakam.sch.id', '2026-09-22 19:19:56', '$2y$12$yfMxOPKDBFxn5VLOlw3QhO2jbOQkEas2UlDf4/YG36KjlolUCn1US', 'superadmin', NULL, '2026-09-22 19:19:56', '2026-09-22 19:19:56'),
(4, 'Ahmad Sobari', 'bari', 'bariahmad6345@gmail.com', NULL, '$2y$12$S9JPkYE27.kEm0sLl5mow.RjdS5JYVIRXoVdYx7czv9hNKUvYao1C', 'humas', NULL, '2026-10-07 19:02:06', '2026-11-12 19:59:38'),
(5, 'Tiasil Haviana, S.T', 'Ibu sil', 'tiasil123@gmail.com', NULL, '$2y$12$YzuAvQkFp5gf8mxpe0Hlwu1FEMX1KHGkT66sQLXwcfvhYeuxbj492', 'panitia_ppdb', NULL, '2026-10-07 19:30:05', '2026-11-12 19:58:20'),
(6, 'Nur Fatria Wita Atma', 'Ibu Wita', 'wiwit123@gmail.com', NULL, '$2y$12$Q2H34W6kGINXx2doG6Cnx.V5e9eOMg8XUxmn3o9fX55Gh12qpaZGi', 'admin_perpus', NULL, '2026-10-07 19:43:53', '2026-10-07 19:43:53'),
(7, 'Maysaroh', 'ibu may', 'maybk123@gmail.com', NULL, '$2y$12$okeDJ/96hjepZiImnsIc8u2sRncloeesgz1xZ5/YQ5tgDEI0FpT/.', 'admin_bk', NULL, '2026-10-07 19:44:40', '2026-10-07 19:44:40'),
(8, 'Iindriani Astuti', 'ibu iin', 'iindriani123@gmail.com', NULL, '$2y$12$8qm/IQH4eRP1xbPh8o9Q9Oj0/WakX155e65CIR/7D1KAP2LwjM1FW', 'guru', NULL, '2026-10-07 19:45:19', '2026-10-07 19:45:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bkk_listings`
--
ALTER TABLE `bkk_listings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `concentrations`
--
ALTER TABLE `concentrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `concentrations_slug_unique` (`slug`),
  ADD KEY `concentrations_program_id_foreign` (`program_id`);

--
-- Indexes for table `concentration_teacher`
--
ALTER TABLE `concentration_teacher`
  ADD PRIMARY KEY (`teacher_id`,`concentration_id`),
  ADD KEY `concentration_teacher_concentration_id_foreign` (`concentration_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
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
-- Indexes for table `library_books`
--
ALTER TABLE `library_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_slug_unique` (`slug`),
  ADD KEY `news_user_id_foreign` (`user_id`);

--
-- Indexes for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_comments_news_id_foreign` (`news_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `ppdb_registrants`
--
ALTER TABLE `ppdb_registrants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ppdb_registrants_registration_number_unique` (`registration_number`),
  ADD KEY `ppdb_registrants_program_id_foreign` (`program_id`),
  ADD KEY `ppdb_registrants_concentration_id_foreign` (`concentration_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `programs_slug_unique` (`slug`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teachers_program_id_foreign` (`program_id`);

--
-- Indexes for table `teacher_positions`
--
ALTER TABLE `teacher_positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_positions_teacher_id_position_key_unique` (`teacher_id`,`position_key`),
  ADD KEY `teacher_positions_position_key_index` (`position_key`);

--
-- Indexes for table `tracer_responses`
--
ALTER TABLE `tracer_responses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracer_responses_nisn_tahun_lulus_unique` (`nisn`,`tahun_lulus`),
  ADD KEY `tracer_responses_program_id_foreign` (`program_id`),
  ADD KEY `tracer_responses_concentration_id_foreign` (`concentration_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bkk_listings`
--
ALTER TABLE `bkk_listings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `concentrations`
--
ALTER TABLE `concentrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_books`
--
ALTER TABLE `library_books`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `news_comments`
--
ALTER TABLE `news_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ppdb_registrants`
--
ALTER TABLE `ppdb_registrants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `teacher_positions`
--
ALTER TABLE `teacher_positions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tracer_responses`
--
ALTER TABLE `tracer_responses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `concentrations`
--
ALTER TABLE `concentrations`
  ADD CONSTRAINT `concentrations_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `concentration_teacher`
--
ALTER TABLE `concentration_teacher`
  ADD CONSTRAINT `concentration_teacher_concentration_id_foreign` FOREIGN KEY (`concentration_id`) REFERENCES `concentrations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `concentration_teacher_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD CONSTRAINT `news_comments_news_id_foreign` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ppdb_registrants`
--
ALTER TABLE `ppdb_registrants`
  ADD CONSTRAINT `ppdb_registrants_concentration_id_foreign` FOREIGN KEY (`concentration_id`) REFERENCES `concentrations` (`id`),
  ADD CONSTRAINT `ppdb_registrants_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `teacher_positions`
--
ALTER TABLE `teacher_positions`
  ADD CONSTRAINT `teacher_positions_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tracer_responses`
--
ALTER TABLE `tracer_responses`
  ADD CONSTRAINT `tracer_responses_concentration_id_foreign` FOREIGN KEY (`concentration_id`) REFERENCES `concentrations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tracer_responses_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
