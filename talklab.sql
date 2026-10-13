-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jun 2026 pada 02.26
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `talklab`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ai_feedback_history`
--

CREATE TABLE `ai_feedback_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(6) NOT NULL,
  `source_type` varchar(40) NOT NULL,
  `duration_seconds` int(10) UNSIGNED NOT NULL,
  `clarity_score` int(10) UNSIGNED NOT NULL,
  `fluency_score` int(10) UNSIGNED NOT NULL,
  `confidence_score` int(10) UNSIGNED NOT NULL,
  `consistency_score` int(10) UNSIGNED NOT NULL,
  `filler_count` int(10) UNSIGNED NOT NULL,
  `speaking_speed` int(10) UNSIGNED NOT NULL,
  `feedback` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `camera_practice_history`
--

CREATE TABLE `camera_practice_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(6) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `simulation_mode` varchar(60) DEFAULT NULL,
  `duration_seconds` int(10) UNSIGNED NOT NULL,
  `video_path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ebooks`
--

CREATE TABLE `ebooks` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(180) NOT NULL,
  `author` varchar(120) NOT NULL,
  `pages` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `thumbnail_path` varchar(255) NOT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ebooks`
--

INSERT INTO `ebooks` (`id`, `title`, `author`, `pages`, `thumbnail_path`, `pdf_path`, `created_at`, `updated_at`) VALUES
(1, '3 Teknik Mahir Berbicara Di Depan Publik', 'Hebbie Agus Kurnia', 32, 'assets/ebook/ebook1.png', 'assets/ebook/ebook1.pdf', '2026-05-31 21:49:38', NULL),
(2, 'Public Speaking Untuk Pemula', 'Rinna Raflina, S.Sos., M.I.Kom', 88, 'assets/ebook/ebook2.png', 'assets/ebook/ebook2.pdf', '2026-05-31 21:49:38', NULL),
(3, 'My Public Speaking', 'Hilbram Dunar', 180, 'assets/ebook/ebook3.png', 'assets/ebook/ebook3.pdf', '2026-05-31 21:49:38', NULL),
(4, 'Dasar Public Speaking', 'Dr. Mohamed Sudi, S.E., M.Si.', 116, 'assets/ebook/ebook4.png', 'assets/ebook/ebook4.pdf', '2026-05-31 21:49:38', NULL),
(5, 'Tes', 'H.Selamet', 12, 'assets/ebook/ebook_thumb_20260531_165306_d528ac8f.png', 'assets/ebook/ebook_pdf_20260531_165306_0790844c.pdf', '2026-05-31 21:53:06', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ebook_activity`
--

CREATE TABLE `ebook_activity` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(6) NOT NULL,
  `ebook_id` int(10) UNSIGNED NOT NULL,
  `ebook_title` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ebook_activity`
--

INSERT INTO `ebook_activity` (`id`, `user_id`, `ebook_id`, `ebook_title`, `created_at`) VALUES
(1, 'AGBI29', 5, 'Tes', '2026-06-08 21:53:52'),
(2, 'AGBI29', 4, 'Dasar Public Speaking', '2026-06-08 21:56:54'),
(3, 'AGBI29', 2, 'Public Speaking Untuk Pemula', '2026-06-08 22:01:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `komunitas`
--

CREATE TABLE `komunitas` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Id_User` varchar(6) NOT NULL,
  `Isi` text NOT NULL,
  `Dibuat` datetime NOT NULL DEFAULT current_timestamp(),
  `Update_Post` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komunitas`
--

INSERT INTO `komunitas` (`Id`, `Id_User`, `Isi`, `Dibuat`, `Update_Post`) VALUES
(1, '7NA83J', 'Tes haha', '2026-05-09 15:59:59', '2026-05-09 16:02:03'),
(2, 'AGBI29', 'woy antek antek asheng', '2026-05-09 16:04:51', NULL),
(3, 'AGBI29', 'tesssss', '2026-05-22 15:18:50', NULL),
(4, 'AGBI29', 'ww', '2026-05-31 13:21:26', NULL),
(5, 'P4IL4M', 'hai', '2026-05-31 21:40:23', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `materials`
--

CREATE TABLE `materials` (
  `id` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `time_minutes` int(11) NOT NULL DEFAULT 10,
  `icon_file` varchar(50) NOT NULL,
  `color_class` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `materials`
--

INSERT INTO `materials` (`id`, `title`, `description`, `category`, `time_minutes`, `icon_file`, `color_class`, `created_at`) VALUES
('gestur_tangan', 'Gestur Tangan', 'Menggunakan gerakan tangan untuk memperkuat pesan', 'gerak tubuh', 10, 'icon/emot.svg', 'gestur-tangan', '2026-06-05 14:27:35'),
('intonasi_suara', 'Intonasi Suara', 'Mengatur naik turunnya suara saat berbicara', 'vokal', 10, 'icon/ear.svg', 'intonasi', '2026-06-05 14:27:35'),
('kontak_mata', 'Kontak Mata', 'Teknik menatap audiens dengan nyaman', 'gerak tubuh', 10, 'icon/mata.svg', 'kontak-mata', '2026-06-05 14:27:35'),
('media_presentasi', 'Media Presentasi', 'Tips menggunakan microphone dan panggung', 'lainnya', 15, 'icon/media.svg', 'media-presentasi', '2026-06-05 14:27:35'),
('mengatasi_grogi', 'Mengatasi Grogi', 'Tips menghilangkan rasa gugup di depan umum', 'lainnya', 25, 'icon/halo.svg', 'mengatasi-grogi', '2026-06-05 14:27:35'),
('penyusunan_materi', 'Penyusunan Materi', 'Penyampaian isi yang sistematis', 'lainnya', 15, 'icon/book.svg', 'penyusunan-materi', '2026-06-05 14:27:35'),
('postur_tubuh', 'Postur Tubuh', 'Cara berdiri dan bergerak yang percaya diri', 'gerak tubuh', 20, 'icon/badan.svg', 'gerak-tubuh', '2026-06-05 14:27:35'),
('vokal', 'Vokal yang Jelas', 'Belajar mengucapkan kata dengan jelas dan tegas', 'vokal', 15, 'icon/mic.svg', 'vokal', '2026-06-05 14:27:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_progress`
--

CREATE TABLE `material_progress` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(6) NOT NULL,
  `material_id` varchar(50) NOT NULL,
  `progress` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `material_progress`
--

INSERT INTO `material_progress` (`id`, `user_id`, `material_id`, `progress`, `updated_at`) VALUES
(1, 'AGBI29', 'vokal', 0, '2026-05-30 10:08:00'),
(2, 'AGBI29', 'gestur_tangan', 0, '2026-05-30 10:13:10'),
(3, 'AGBI29', 'mengatasi_grogi', 0, '2026-05-30 11:04:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_videos`
--

CREATE TABLE `material_videos` (
  `id` int(10) UNSIGNED NOT NULL,
  `material_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `script` text NOT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `material_videos`
--

INSERT INTO `material_videos` (`id`, `material_id`, `title`, `video_url`, `script`, `order_index`) VALUES
(1, 'vokal', '1. Artikulasi Dasar dan Resonansi', 'https://youtu.be/-2NnNomW68k?si=m_7WFa0C5o3_c5Yz', 'Video ini menjelaskan betapa pentingnya teknik vokal (cara penyampaian) saat berbicara di depan umum, karena elemen vokal dan visual jauh lebih berpengaruh daripada sekadar kata-kata (verbal) menurut teori psikologi komunikasi.\r\n\r\nBerikut adalah 5 teknik vokal utama yang dibahas untuk menjadi pembicara yang baik:\r\n\r\nVolume Suara: Bicaralah dengan lantang agar audiens bisa mendengarkan dengan jelas dan nyaman tanpa harus berusaha keras. Volume juga menunjukkan tingkat keyakinan pembicara.\r\n\r\nKecepatan (Pacing): Kendalikan kecepatan bicara, jangan terlalu cepat atau lambat (ideal: 120-150 kata per menit). Kecepatan juga bisa disesuaikan dengan jenis materi dan siapa audiensnya.\r\n\r\nNada (Pitch): Sesuaikan nada bicara dengan situasi. Nada rendah digunakan untuk situasi formal dan profesional (berwibawa), nada sedang untuk komunikasi sehari-hari, dan nada tinggi untuk mengekspresikan emosi.\r\n\r\nArtikulasi: Pengucapan kata-kata harus jelas dengan membuka mulut yang benar. Disarankan melakukan senam wajah, pemanasan lidah, dan latihan tongue twister sebelum tampil.\r\n\r\nIntonasi (Vocal Variety): Jangan berbicara dengan nada datar. Gunakan dinamika, penekanan pada kata-kata penting, serta penjiwaan agar penyampaian menjadi lebih menarik.\r\n\r\nBonus Teknik: Gunakan Pauses (Jeda) di antara kalimat untuk memberikan waktu audiens mencerna informasi dan memberikan efek dramatis atau penasaran pada poin penting yang ingin disampaikan.', 1),
(2, 'vokal', '2. Teknik Pernapasan', 'https://youtu.be/YhmbAxzxamo?si=C1EWqw5oIzZf-GjR', 'Video ini membahas tentang masalah pernapasan yang sering terjadi saat seseorang merasa gugup atau tertekan ketika berbicara di depan umum, dan bagaimana cara mengatasinya.\r\n\r\nBerikut adalah penjelasan singkat dari isi video tersebut:\r\n\r\nMasalah saat Gugup: Ketika merasa cemas atau stres, pernapasan kita cenderung menjadi dangkal, kacau, atau bahkan kita tanpa sadar menahan napas. Hal ini memotong suplai oksigen ke otak dan memicu pelepasan hormon stres (seperti kortisol dan adrenalin).\r\n\r\nDampaknya: Jantung berdetak kencang, berkeringat, pikiran menjadi kosong, dan kita merasa seolah otak dan lidah (kemampuan verbal) tidak terhubung (nge-blank). Tubuh masuk ke mode \"bertarung atau lari\" (fight or flight).\r\n\r\nSolusinya (Pernapasan Diafragma): Solusi untuk mengatasi rasa gugup tersebut adalah dengan melakukan pernapasan perut atau pernapasan diafragma.\r\n\r\nCara Melakukannya:\r\n\r\nTarik napas dalam-dalam hingga udara mengisi perut sepenuhnya (perut mengembang).\r\n\r\nTahan napas tersebut selama kurang lebih 3-5 detik.\r\n\r\nHembuskan udara secara perlahan hingga perut benar-benar kempis.\r\n\r\nTahan sebentar sebelum menarik napas kembali.\r\n\r\nManfaat Pernapasan Diafragma: Cara ini dapat mengaktifkan saraf vagus yang memberikan sinyal relaksasi pada tubuh. Selain itu, teknik ini meningkatkan suplai oksigen, membantu kita berpikir lebih jernih, dan menenangkan sistem saraf yang memicu kepanikan, sehingga kita bisa lebih fokus saat presentasi.', 2),
(3, 'vokal', '3. 8 Teknik Vokal', 'https://youtu.be/VGFVkRpv-SE?si=kDH2-PPod_9r27hT', 'video ini, ia membagikan 8 teknik vokal penting untuk menyempurnakan public speaking, melengkapi elemen komunikasi non-verbal yang sangat krusial saat tampil:\r\n\r\nBerikut adalah penjelasan ke-8 teknik tersebut:\r\n\r\nNapas / Pernapasan: Menggunakan teknik pernapasan diafragma sangat penting untuk kontrol suara. Seorang pembicara disarankan bisa menahan dan mengatur napas minimal 18 detik tanpa terputus untuk menghasilkan kualitas suara yang baik tanpa terengah-engah.\r\n\r\nVolume Suara: Mengatur besar-kecilnya suara. Harus disesuaikan dengan jumlah peserta, luas ruangan, dan kualitas perangkat audio (sound system). Suara harus terdengar jelas hingga ke peserta di kursi paling belakang.\r\n\r\nPace (Tempo): Kecepatan saat berbicara. Gunakan tempo yang dinamis (kadang cepat, kadang lambat). Perlambat tempo bicara Anda saat menyebutkan nama, istilah asing, atau poin yang sangat penting agar audiens lebih fokus.\r\n\r\nKekhasan (Irama / Ketukan): Berbicara layaknya bernyanyi yang memiliki irama. Memiliki ketukan dan keteraturan berbicara membuat penyampaian materi terasa memiliki \"bumbu\" yang enak didengar.\r\n\r\nPause (Jeda): Berhenti sejenak di tengah pembicaraan. Teknik ini sangat berguna untuk menciptakan rasa penasaran, efek dramatis, atau membiarkan audiens mencerna apa yang baru saja disampaikan sebelum lanjut ke poin berikutnya.\r\n\r\nAksentuasi (Stressing/Penekanan): Memberikan tekanan atau penonjolan pada kata atau kalimat tertentu. Hal ini berguna untuk meyakinkan audiens, menarik perhatian mereka kembali, dan mencegah kebosanan dari nada bicara yang terlalu datar.\r\n\r\nFrasering (Pemenggalan Kalimat): Memahami kapan harus berhenti mengambil napas sesuai tanda baca (koma dan titik). Mengambil napas secara teratur di titik yang tepat mencegah Anda berbicara terburu-buru atau kehabisan napas.\r\n\r\nInflection: Perubahan nada suara (dari tinggi ke rendah, atau rendah ke tinggi). Misalnya, sengaja merendahkan volume suara hingga setengah berbisik untuk menarik kembali perhatian penuh audiens saat membagikan suatu \"rahasia\" atau hal penting.', 3),
(5, 'kontak_mata', '1. Kuasai Kontak Mata', 'https://youtu.be/327_7IO9Mog?si=S_1ZUCUko5AO2xGf', 'Video ini menekankan bahwa 70% dari komunikasi kita bergantung pada bahasa tubuh (body language), termasuk kontak mata, bukan sekadar kata-kata yang keluar dari mulut.\r\n\r\nBerikut adalah 5 tips dasar menguasai kontak mata yang dijelaskan dalam video:\r\n\r\nGunakan Dahi dan Alis: Jangan hanya berbicara dengan mulut yang bergerak, tapi bicaralah dengan mata dan wajahmu. Mainkan alis dan kerutkan dahi agar ekspresi dan emosi saat berbicara lebih hidup dan terasa oleh lawan bicara.\r\n\r\nTatap Maksimal 5-7 Detik: Saat berbicara empat mata, tahan kontak mata sekitar 5 sampai 7 detik. Jika kurang dari 5 detik, kamu akan terkesan tidak peduli. Namun, jika menatap lebih dari 7-10 detik tanpa henti, lawan bicara bisa merasa terintimidasi dan tidak nyaman.\r\n\r\nTatap Semua Orang dalam Grup Diskusi: Saat kamu berbicara di dalam grup (misal 4-5 orang), bagikan pandangan matamu ke semua orang secara bergantian. Jangan hanya terpaku pada satu orang, karena anggota grup yang lain akan merasa diabaikan dan tidak dianggap penting.\r\n\r\nFokus pada Lawan Bicara: Ketika ada orang yang sedang berbicara denganmu, fokuslah padanya. Jangan sibuk bermain HP atau melihat ke arah lain, karena itu akan memberikan kesan bahwa orang tersebut tidak penting bagimu.\r\n\r\nBeri Jeda Saat Menatap: Sebagai pendengar, jangan terus-menerus menatap tajam tanpa henti. Berikan jeda sekitar 1-2 detik dengan membuang pandangan ke atas, bawah, atau samping. Sambil membuang pandangan, kamu bisa memberikan anggukan untuk menunjukkan bahwa kamu masih mendengarkan dan mengerti.', 1),
(6, 'postur_tubuh', '1. Anatomi & Posisi Artikulator', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Di bagian ini kita pelajari struktur mulut, lidah, bibir, dan rahang serta posisi artikulator yang memengaruhi pengucapan. Memahami anatomi membuat latihan artikulasi lebih efektif.', 1),
(7, 'postur_tubuh', '2. Sikap Berdiri yang Kokoh (Grounding)', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Teknik memposisikan kaki agar seimbang, menjaga bahu tetap tegap, dan menghindari postur tubuh yang membungkuk atau menumpu pada satu kaki saja.', 2),
(8, 'postur_tubuh', '3. Gestur Tangan yang Bermakna', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Panduan area ideal untuk menggerakkan tangan saat berbicara (biasanya di area dada hingga perut), cara menggunakan telapak tangan yang terbuka, serta cara menghilangkan kebiasaan fidgeting (memasukkan tangan ke saku, memainkan jari, atau meremas tangan)', 3),
(9, 'postur_tubuh', '4. Bahasa Tubuh Terbuka vs Tertutup', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Pemahaman tentang pentingnya menghindari gerakan yang terkesan defensif atau memblokir audiens (seperti bersedekap dada) dan cara mengadopsi postur yang lebih mengundang (open posture)', 4),
(10, 'postur_tubuh', '5. Pergerakan Berarah (Purposeful Movement)', '', 'Teknik memanfaatkan ruang atau panggung. Membahas kapan waktu yang tepat untuk melangkah (misalnya saat transisi ke poin baru) agar tidak terkesan mondar-mandir tanpa tujuan akibat rasa gugup', 5),
(11, 'kontak_mata', '2. Teknik Distribusi Pandangan (Scanning Ruangan)', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Mengajarkan cara membagi kontak mata secara merata ke seluruh audiens. Kamu bisa memasukkan teknik seperti membagi ruangan menjadi 3 zona (kiri, tengah, kanan) atau menggunakan pola \"Z\" atau \"W\" saat menyapu pandangan, agar semua audiens merasa dilibatkan.', 2),
(12, 'kontak_mata', '3. Trik Mengatasi Gugup Menatap Mata', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Panduan khusus bagi pemula yang masih canggung menatap mata audiens secara langsung. Misalnya, mengajarkan trik untuk menatap area dahi atas, pangkal hidung, atau area triangle zone di wajah audiens sebagai alternatif sementara yang tetap terlihat natural dari jauh.', 3),
(14, 'kontak_mata', '4. Durasi Menatap yang Ideal (Aturan 3-5 Detik)', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Membahas berapa lama kita harus menahan kontak mata dengan satu orang sebelum beralih ke orang lain. Ini penting agar tatapan tidak terkesan terlalu singkat (terlihat tidak peduli/gugup) atau terlalu lama (mengintimidasi audiens)', 4),
(15, 'intonasi_suara', '1. Menghindari Suara Monoton (Vocal Variety)', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Pengenalan dasar mengapa suara yang datar membuat audiens cepat bosan, dan cara menyuntikkan emosi (seperti antusiasme atau empati) ke dalam nada bicara agar terdengar lebih natural dan dinamis.', 1),
(16, 'intonasi_suara', '2. Seni Penekanan Kata (Aksentuasi)', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Teknik memberikan penekanan atau stressing pada kata-kata kunci dalam satu kalimat. Ini membantu audiens langsung menangkap poin terpenting tanpa harus menebak-nebak.', 2),
(17, 'vokal', '3. Mengatur Tempo Bicara (Pacing)', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Panduan tentang kapan harus berbicara lebih cepat (untuk membangun semangat dan energi) dan kapan harus melambat (untuk menjelaskan data, istilah baru, atau pesan yang krusial).', 3),
(18, 'intonasi_suara', '4. Kekuatan Jeda (The Power of Pause)', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Mengajarkan bahwa diam sejenak bukanlah sebuah kesalahan. Jeda (pause) sangat berguna untuk menciptakan efek dramatis, membiarkan audiens mencerna informasi, sekaligus memberi waktu bagi pembicara untuk mengambil napas.', 4),
(19, 'intonasi_suara', 'Bermain dengan Nada (Pitch):', 'https://www.w3schools.com/html/mov_bbb.mp4', 'Cara menyesuaikan tinggi rendahnya suara dengan konteks acara. Misalnya, menggunakan nada yang lebih rendah dan dalam untuk membangun wibawa di acara formal, atau nada yang sedikit lebih tinggi untuk menyapa audiens dengan ramah.', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_video_progress`
--

CREATE TABLE `material_video_progress` (
  `user_id` varchar(6) NOT NULL,
  `video_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `material_video_progress`
--

INSERT INTO `material_video_progress` (`user_id`, `video_id`, `created_at`) VALUES
('AGBI29', 1, '2026-06-09 07:24:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mentor_accounts`
--

CREATE TABLE `mentor_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `specialty` varchar(30) NOT NULL DEFAULT 'voice',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mentor_accounts`
--

INSERT INTO `mentor_accounts` (`id`, `name`, `username`, `password_hash`, `specialty`, `created_at`) VALUES
(1, 'Ilman Zuhry', 'Ilman0909', '$2y$10$fkWXokDZqIyayIccBdg8LOGcf4jdTVj9arThNWh9tYZagV8m/SoEq', 'voice', '2026-05-22 15:09:18'),
(2, 'Budi Santoso', 'Budzz', '$2y$10$yuZI99Lhm1JBf2ixrV6B0eg5PPiwCX3MX2oInZFlZ0up6B2clr/G6', 'camera', '2026-06-08 22:21:53'),
(3, 'Andi Wijaya', 'Wijaya', '$2y$10$0nefcgl0NvFkI3Csxh/HT.dmJcgYGZ5BEp7FwyR38Tqko8zFWxZqO', 'challenge', '2026-06-08 22:24:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mentor_reviews`
--

CREATE TABLE `mentor_reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `submission_id` int(10) UNSIGNED NOT NULL,
  `mentor_id` int(10) UNSIGNED NOT NULL,
  `articulation_score` int(10) UNSIGNED NOT NULL,
  `fluency_score` int(10) UNSIGNED NOT NULL,
  `confidence_score` int(10) UNSIGNED NOT NULL,
  `structure_score` int(10) UNSIGNED NOT NULL,
  `intonation_score` int(10) UNSIGNED NOT NULL,
  `final_score` int(10) UNSIGNED NOT NULL,
  `strengths` text NOT NULL,
  `improvements` text NOT NULL,
  `feedback` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mentor_submissions`
--

CREATE TABLE `mentor_submissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `practice_history_id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(6) NOT NULL,
  `mentor_id` int(10) UNSIGNED DEFAULT NULL,
  `feature_type` varchar(30) NOT NULL DEFAULT 'voice',
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `submitted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `reviewed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(6) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `post_comments`
--

INSERT INTO `post_comments` (`id`, `post_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 2, 'AGBI29', 'woyyyyyy', '2026-05-16 12:29:55', NULL),
(2, 2, '7NA83J', 'apa kau', '2026-05-16 12:30:11', NULL),
(3, 1, 'AGBI29', 'sssssss', '2026-05-17 09:50:23', NULL),
(4, 3, 'AGBI29', 'hahahaa', '2026-05-22 15:24:55', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(6) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `post_likes`
--

INSERT INTO `post_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(1, 2, '7NA83J', '2026-05-16 12:19:49'),
(2, 1, 'AGBI29', '2026-05-17 09:50:25'),
(4, 3, '7NA83J', '2026-05-22 15:19:14'),
(6, 3, 'AGBI29', '2026-05-25 20:51:52'),
(7, 4, 'AGBI29', '2026-05-31 21:38:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `practice_history`
--

CREATE TABLE `practice_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(6) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `script_title` varchar(255) DEFAULT NULL,
  `category` varchar(60) DEFAULT NULL,
  `level_name` varchar(30) DEFAULT NULL,
  `duration_seconds` int(10) UNSIGNED NOT NULL,
  `audio_path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `practice_history`
--

INSERT INTO `practice_history` (`id`, `user_id`, `topic`, `script_title`, `category`, `level_name`, `duration_seconds`, `audio_path`, `created_at`) VALUES
(1, '7NA83J', 'Perkenalkan diri kamu secara singkat dan percaya diri.', NULL, NULL, NULL, 4, 'uploads/practice_audio/7NA83J_20260517_054712_32899f41.webm', '2026-05-17 10:47:12'),
(2, 'AGBI29', 'Perkenalkan diri kamu secara singkat dan percaya diri.', NULL, NULL, NULL, 4, 'uploads/practice_audio/AGBI29_20260522_101049_48257a55.webm', '2026-05-22 15:10:49'),
(3, 'AGBI29', 'Pentingnya Berani Berbicara', 'Pentingnya Berani Berbicara', 'Pidato', 'Intermediate', 18, 'uploads/practice_audio/AGBI29_20260605_092345_0b7dbacc.webm', '2026-06-05 14:23:45'),
(4, 'AGBI29', 'Pidato Singkat Tentang Disiplin', 'Pidato Singkat Tentang Disiplin', 'Pidato', 'Beginner', 14, 'uploads/practice_audio/AGBI29_20260608_165827_9e29b9d6.webm', '2026-06-08 21:58:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `speaking_challenge_history`
--

CREATE TABLE `speaking_challenge_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(6) NOT NULL,
  `challenge_type` varchar(60) NOT NULL,
  `level_name` varchar(30) NOT NULL,
  `question_count` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `prompt` text NOT NULL,
  `prep_seconds` int(10) UNSIGNED NOT NULL,
  `speak_seconds` int(10) UNSIGNED NOT NULL,
  `actual_seconds` int(10) UNSIGNED NOT NULL,
  `score` int(10) UNSIGNED NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 1,
  `audio_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `Id_User` varchar(6) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `Tempat_Lahir` varchar(100) NOT NULL,
  `Tanggal_Lahir` date NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Google_Sub` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Foto` varchar(255) NOT NULL,
  `Bio` varchar(160) NOT NULL DEFAULT 'yang penting bicara aja dulu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`Id_User`, `Nama`, `Tempat_Lahir`, `Tanggal_Lahir`, `Username`, `Password`, `Foto`, `Bio`) VALUES
('7NA83J', 'Ilman Zuhry', 'Medan', '2007-10-26', 'LOL', '$2y$10$3ttwlV6cXL/JM.qWnWEiQu5yPbemGOmb8m1vALskfv2Furj7HISGC', '', 'yang penting bicara aja dulu'),
('AGBI29', 'Wawak Sitompul', 'Binjai', '1990-07-06', 'Sitoms', '$2y$10$vRTbM7Tw5JSVt.X4uohy5uAKlxW/sFEvdQ2FhNSRcFoJJNac24zMq', 'uploads/profile_photos/AGBI29_1780208208.png', 'hahaha'),
('P4IL4M', 'Tata ASep', 'Thailand', '2004-06-16', 'ASEP', '$2y$10$swIXtui7ZbiU.8W7fAm4qezbWLuMCDf9tj2dEctBOGcee42M91RM2', 'uploads/profile_photos/P4IL4M_1780238409.jpg', 'yang penting bicara aja dulu');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ai_feedback_history`
--
ALTER TABLE `ai_feedback_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ai_feedback_user` (`user_id`);

--
-- Indeks untuk tabel `camera_practice_history`
--
ALTER TABLE `camera_practice_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_camera_user` (`user_id`);

--
-- Indeks untuk tabel `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ebook_title` (`title`);

--
-- Indeks untuk tabel `ebook_activity`
--
ALTER TABLE `ebook_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ebook_activity_user` (`user_id`),
  ADD KEY `fk_ebook_activity_ebook` (`ebook_id`);

--
-- Indeks untuk tabel `komunitas`
--
ALTER TABLE `komunitas`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_user_id` (`Id_User`);

--
-- Indeks untuk tabel `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `material_progress`
--
ALTER TABLE `material_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_material` (`user_id`,`material_id`);

--
-- Indeks untuk tabel `material_videos`
--
ALTER TABLE `material_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_material` (`material_id`);

--
-- Indeks untuk tabel `material_video_progress`
--
ALTER TABLE `material_video_progress`
  ADD PRIMARY KEY (`user_id`,`video_id`),
  ADD KEY `fk_mvp_video` (`video_id`);

--
-- Indeks untuk tabel `mentor_accounts`
--
ALTER TABLE `mentor_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_mentor_username` (`username`);

--
-- Indeks untuk tabel `mentor_reviews`
--
ALTER TABLE `mentor_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_submission_review` (`submission_id`),
  ADD KEY `idx_mentor_review_mentor` (`mentor_id`);

--
-- Indeks untuk tabel `mentor_submissions`
--
ALTER TABLE `mentor_submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_practice_feature` (`practice_history_id`,`feature_type`),
  ADD KEY `idx_mentor_submission_user` (`user_id`),
  ADD KEY `idx_mentor_submission_mentor` (`mentor_id`),
  ADD KEY `idx_mentor_submission_status` (`status`);

--
-- Indeks untuk tabel `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post` (`post_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indeks untuk tabel `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `idx_post` (`post_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indeks untuk tabel `practice_history`
--
ALTER TABLE `practice_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_practice_user` (`user_id`);

--
-- Indeks untuk tabel `speaking_challenge_history`
--
ALTER TABLE `speaking_challenge_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_challenge_user` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id_User`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Google_Sub` (`Google_Sub`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `ai_feedback_history`
--
ALTER TABLE `ai_feedback_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `camera_practice_history`
--
ALTER TABLE `camera_practice_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `ebook_activity`
--
ALTER TABLE `ebook_activity`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `komunitas`
--
ALTER TABLE `komunitas`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `material_progress`
--
ALTER TABLE `material_progress`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `material_videos`
--
ALTER TABLE `material_videos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `mentor_accounts`
--
ALTER TABLE `mentor_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `mentor_reviews`
--
ALTER TABLE `mentor_reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `mentor_submissions`
--
ALTER TABLE `mentor_submissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `practice_history`
--
ALTER TABLE `practice_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `speaking_challenge_history`
--
ALTER TABLE `speaking_challenge_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `ai_feedback_history`
--
ALTER TABLE `ai_feedback_history`
  ADD CONSTRAINT `fk_ai_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `camera_practice_history`
--
ALTER TABLE `camera_practice_history`
  ADD CONSTRAINT `fk_camera_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ebook_activity`
--
ALTER TABLE `ebook_activity`
  ADD CONSTRAINT `fk_ebook_activity_ebook` FOREIGN KEY (`ebook_id`) REFERENCES `ebooks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ebook_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `komunitas`
--
ALTER TABLE `komunitas`
  ADD CONSTRAINT `fk_community_user` FOREIGN KEY (`Id_User`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `material_progress`
--
ALTER TABLE `material_progress`
  ADD CONSTRAINT `fk_progress_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `material_videos`
--
ALTER TABLE `material_videos`
  ADD CONSTRAINT `fk_mat_vid` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `material_video_progress`
--
ALTER TABLE `material_video_progress`
  ADD CONSTRAINT `fk_mvp_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_mvp_video` FOREIGN KEY (`video_id`) REFERENCES `material_videos` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mentor_reviews`
--
ALTER TABLE `mentor_reviews`
  ADD CONSTRAINT `fk_mentor_review_mentor` FOREIGN KEY (`mentor_id`) REFERENCES `mentor_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mentor_review_submission` FOREIGN KEY (`submission_id`) REFERENCES `mentor_submissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mentor_submissions`
--
ALTER TABLE `mentor_submissions`
  ADD CONSTRAINT `fk_mentor_submission_mentor` FOREIGN KEY (`mentor_id`) REFERENCES `mentor_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mentor_submission_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `fk_comment_post` FOREIGN KEY (`post_id`) REFERENCES `komunitas` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `fk_like_post` FOREIGN KEY (`post_id`) REFERENCES `komunitas` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_like_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `practice_history`
--
ALTER TABLE `practice_history`
  ADD CONSTRAINT `fk_practice_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `speaking_challenge_history`
--
ALTER TABLE `speaking_challenge_history`
  ADD CONSTRAINT `fk_challenge_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_User`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
