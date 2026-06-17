-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Agu 2025 pada 17.54
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wuling`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id` int(11) NOT NULL,
  `model_name` varchar(255) NOT NULL,
  `category` enum('SUV','Electric','MPV','Pickup','Sedan','Hatchback') NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `kendaraan`
--

INSERT INTO `kendaraan` (`id`, `model_name`, `category`, `price`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Wuling Almaz RS', 'SUV', 355800000.00, 'SUV premium dengan fitur lengkap dan performa tangguh untuk keluarga modern.', '2025-08-03 12:44:42', '2025-08-06 23:02:41'),
(2, 'Wuling Almaz RS Pro', 'SUV', 398800000.00, 'Varian tertinggi Almaz dengan fitur ADAS dan teknologi terdepan', '2025-08-03 12:44:42', '2025-08-03 12:44:42'),
(3, 'Wuling Air ev', 'Electric', 238000000.00, 'Mobil listrik kompak untuk mobilitas urban yang efisien dan ramah lingkungan', '2025-08-03 12:44:42', '2025-08-03 12:44:42'),
(4, 'Wuling Binguo EV', 'Electric', 189000000.00, 'City car listrik dengan desain modern dan ramah lingkungan', '2025-08-03 12:44:42', '2025-08-03 12:44:42'),
(5, 'Wuling Cortez', 'MPV', 218800000.00, 'MPV 7 seater dengan kenyamanan dan ruang yang luas untuk keluarga besar', '2025-08-03 12:44:42', '2025-08-03 12:44:42'),
(6, 'Wuling Cortez CT', 'MPV', 268800000.00, 'Varian premium Cortez dengan fitur dan material berkualitas tinggi', '2025-08-03 12:44:42', '2025-08-03 12:44:42'),
(7, 'Wuling Confero S', 'MPV', 178800000.00, 'MPV compact yang ekonomis dan praktis untuk keluarga kecil', '2025-08-03 12:44:42', '2025-08-03 12:44:42'),
(8, 'Wuling Victory', 'Pickup', 158800000.00, 'Pickup tangguh untuk kebutuhan bisnis dan komersial dengan daya angkut besar', '2025-08-03 12:44:42', '2025-08-03 12:44:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `sl_date` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` varchar(20) NOT NULL,
  `hari` int(11) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `cust_phone` varchar(50) DEFAULT NULL,
  `sales_name` varchar(255) DEFAULT NULL,
  `spv` varchar(255) DEFAULT NULL,
  `leasing` varchar(255) DEFAULT NULL,
  `insurance` varchar(255) DEFAULT NULL,
  `inv_no` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `type` varchar(100) NOT NULL,
  `chassis` varchar(50) NOT NULL,
  `price_list` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) DEFAULT 0.00,
  `price_net` decimal(15,2) NOT NULL,
  `dp_amt` decimal(15,2) DEFAULT 0.00,
  `leasing_amt` decimal(15,2) DEFAULT 0.00,
  `description_2` text DEFAULT NULL,
  `tenor` int(11) DEFAULT 0,
  `ktp_no` varchar(50) DEFAULT NULL,
  `do_status` int(11) DEFAULT 0,
  `tunai_kredit` enum('Tunai','Kredit') NOT NULL,
  `model` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `sl_date`, `tahun`, `bulan`, `hari`, `customer`, `alamat`, `cust_phone`, `sales_name`, `spv`, `leasing`, `insurance`, `inv_no`, `code`, `type`, `chassis`, `price_list`, `discount`, `price_net`, `dp_amt`, `leasing_amt`, `description_2`, `tenor`, `ktp_no`, `do_status`, `tunai_kredit`, `model`, `created_at`) VALUES
(1, 2025, 2021, 'January', 6, 'Zulfa Yulianti', 'Jl. Monginsidi No. 01, Madiun, BA 06121', '+62 (006) 628-5545', 'AGUS WAHYUDI', '', 'PT. MANDIRI TUNAS FINANCE', '', 'F.UN/2350/0121/0014', '21N.3954', 'FORMO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ858157', 189900000.00, 15000000.00, 174900000.00, 52470000.00, 122430000.00, '0', 1, '8870028641987302', 1, 'Kredit', 'FORMO', '2025-08-05 17:19:37'),
(2, 44223, 2021, 'January', 27, 'drg. Capa Iswahyudi, S.Ked', 'Jl. Rajawali Barat No. 7, Medan, KB 07365', '(0668) 755 5233', 'NOOR CAHYA SRI UNTARI', NULL, 'PT. MANDIRI TUNAS FINANCE', NULL, 'F.UN/2341/0121/0033', '22N.8138', 'CORTEZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ497533', 210500000.00, 20000000.00, 190500000.00, 57150000.00, 133350000.00, '0', 2, '574893336612829', 0, 'Kredit', 'CORTEZ', '2025-08-05 17:19:37'),
(3, 44223, 2021, 'January', 27, 'Rahmi Budiman', 'Gg. Astana Anyar No. 3, Padang Sidempuan, Aceh 91507', '+62-761-835-7585', 'NOOR CAHYA SRI UNTARI', NULL, NULL, NULL, 'F.UN/4125/0121/0050', '25N.5385', 'CONFERO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ340496', 210500000.00, 20000000.00, 190500000.00, 57150000.00, 0.00, '0', 0, '2994891568118452', 0, 'Tunai', 'CONFERO', '2025-08-05 17:19:37'),
(4, 44227, 2021, 'January', 31, 'Puti Gabriella Mandasari', 'Jalan PHH. Mustofa No. 19, Ambon, Lampung 46635', '0803458958', 'NOOR CAHYA SRI UNTARI', NULL, 'PT. BCA FINANCE', NULL, 'F.UN/4227/0121/0138', '23N.2234', 'ALMAZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ240248', 210500000.00, 15000000.00, 195500000.00, 58650000.00, 136850000.00, '0', 2, '2114884131222489', 1, 'Kredit', 'ALMAZ', '2025-08-05 17:19:37'),
(5, 44228, 2021, 'February', 1, 'Tri Wijaya', 'Jl. PHH. Mustofa No. 5, Pangkalpinang, SB 51551', '+62-824-273-1696', 'RIZKY RAMADHAN', NULL, NULL, NULL, 'F.UN/4810/0221/0010', '25N.8628', 'FORMO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ920699', 189900000.00, 27000000.00, 162900000.00, 48870000.00, 0.00, '0', 0, '2637983436822578', 1, 'Tunai', 'FORMO', '2025-08-05 17:19:37'),
(6, 44238, 2021, 'February', 11, 'Daruna Fujiati', 'Jalan HOS. Cokroaminoto No. 94, Tanjungpinang, Sulawesi Selatan 34747', '+62 (851) 773-5924', 'AGUS WAHYUDI', NULL, 'PT. FIF GROUP', NULL, 'F.UN/2358/0221/0021', '23N.2898', 'ALMAZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ118203', 189900000.00, 27000000.00, 162900000.00, 48870000.00, 114030000.00, '0', 1, '5909597430185129', 0, 'Kredit', 'ALMAZ', '2025-08-05 17:19:37'),
(7, 44240, 2021, 'February', 13, 'Drs. Jamalia Fujiati', 'Jalan M.H Thamrin No. 6, Magelang, Gorontalo 31840', '(044) 263-2527', 'NOOR CAHYA SRI UNTARI', NULL, NULL, NULL, 'F.UN/5752/0221/0148', '20N.9323', 'FORMO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ823069', 210500000.00, 27000000.00, 183500000.00, 55050000.00, 0.00, '0', 0, '6466918213379535', 1, 'Tunai', 'FORMO', '2025-08-05 17:19:37'),
(8, 44253, 2021, 'February', 26, 'drg. Muhammad Prayoga', 'Jalan Asia Afrika No. 0, Sungai Penuh, JT 03772', '(026) 231-2920', 'RIZKY RAMADHAN', NULL, NULL, NULL, 'F.UN/5379/0221/0068', '24N.3634', 'FORMO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ130453', 189900000.00, 27000000.00, 162900000.00, 48870000.00, 0.00, '0', 0, '8796730694370518', 0, 'Tunai', 'FORMO', '2025-08-05 17:19:37'),
(9, 44259, 2021, 'March', 4, 'Estiawan Permata', 'Gg. KH Amin Jasuta No. 0, Palu, Kalimantan Utara 31571', '+62-003-239-6093', 'RIZKY RAMADHAN', NULL, 'PT. FIF GROUP', NULL, 'F.UN/4450/0321/0129', '20N.4540', 'CORTEZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ984582', 189900000.00, 15000000.00, 174900000.00, 52470000.00, 122430000.00, '0', 3, '850359794324578', 0, 'Kredit', 'CORTEZ', '2025-08-05 17:19:37'),
(10, 44267, 2021, 'March', 12, 'Zelda Napitupulu, S.I.Kom', 'Gang M.T Haryono No. 842, Pekalongan, SU 11363', '+62 (364) 624-1418', 'NOOR CAHYA SRI UNTARI', NULL, 'PT. BCA FINANCE', NULL, 'F.UN/4728/0321/0039', '23N.1260', 'CORTEZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ321780', 189900000.00, 20000000.00, 169900000.00, 50970000.00, 118930000.00, '0', 2, '9076285207649252', 0, 'Kredit', 'CORTEZ', '2025-08-05 17:19:37'),
(11, 44273, 2021, 'March', 18, 'drg. Winda Maryadi', 'Jalan Moch. Ramdan No. 0, Kendari, Maluku Utara 36231', '(0590) 996-4708', 'NOOR CAHYA SRI UNTARI', NULL, 'PT. BCA FINANCE', NULL, 'F.UN/4635/0321/0007', '23N.7267', 'ALMAZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ254174', 159900000.00, 15000000.00, 144900000.00, 43470000.00, 101430000.00, '0', 1, '9468365283572382', 0, 'Kredit', 'ALMAZ', '2025-08-05 17:19:37'),
(13, 2025, 2025, 'Desember', 12, 'Aang', 'Jalan', '086666767880', 'Kyla', '', 'PT. Mandiri', '', 'F.UN/2350/0121/00190', '21N.3957', 'Corteiz m65', 'MK3AAAGA2MJ858157', 45000000.00, 0.00, 45000000.00, 1000000.00, 1000000.00, '', 1, '623220013', NULL, 'Tunai', 'Corteiz', '2025-08-07 04:23:30'),
(14, 44202, 2021, 'January', 6, 'Zulfa Yulianti', 'Jl. Monginsidi No. 01, Madiun, BA 06121', '+62 (006) 628-5545', 'AGUS WAHYUDI', NULL, 'PT. MANDIRI TUNAS FINANCE', NULL, 'F.UN/2350/0121/0014', '21N.3954', 'FORMO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ858157', 189900000.00, 15000000.00, 174900000.00, 52470000.00, 122430000.00, '0', 1, '8870028641987302', 1, 'Kredit', 'FORMO', '2025-08-07 05:24:46'),
(15, 44223, 2021, 'January', 27, 'drg. Capa Iswahyudi, S.Ked', 'Jl. Rajawali Barat No. 7, Medan, KB 07365', '(0668) 755 5233', 'NOOR CAHYA SRI UNTARI', NULL, 'PT. MANDIRI TUNAS FINANCE', NULL, 'F.UN/2341/0121/0033', '22N.8138', 'CORTEZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ497533', 210500000.00, 20000000.00, 190500000.00, 57150000.00, 133350000.00, '0', 2, '574893336612829', 0, 'Kredit', 'CORTEZ', '2025-08-07 05:24:46'),
(16, 44223, 2021, 'January', 27, 'Rahmi Budiman', 'Gg. Astana Anyar No. 3, Padang Sidempuan, Aceh 91507', '+62-761-835-7585', 'NOOR CAHYA SRI UNTARI', NULL, NULL, NULL, 'F.UN/4125/0121/0050', '25N.5385', 'CONFERO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ340496', 210500000.00, 20000000.00, 190500000.00, 57150000.00, 0.00, '0', 0, '2994891568118452', 0, 'Tunai', 'CONFERO', '2025-08-07 05:24:46'),
(17, 44227, 2021, 'January', 31, 'Puti Gabriella Mandasari', 'Jalan PHH. Mustofa No. 19, Ambon, Lampung 46635', '0803458958', 'NOOR CAHYA SRI UNTARI', NULL, 'PT. BCA FINANCE', NULL, 'F.UN/4227/0121/0138', '23N.2234', 'ALMAZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ240248', 210500000.00, 15000000.00, 195500000.00, 58650000.00, 136850000.00, '0', 2, '2114884131222489', 1, 'Kredit', 'ALMAZ', '2025-08-07 05:24:46'),
(18, 44228, 2021, 'February', 1, 'Tri Wijaya', 'Jl. PHH. Mustofa No. 5, Pangkalpinang, SB 51551', '+62-824-273-1696', 'RIZKY RAMADHAN', NULL, NULL, NULL, 'F.UN/4810/0221/0010', '25N.8628', 'FORMO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ920699', 189900000.00, 27000000.00, 162900000.00, 48870000.00, 0.00, '0', 0, '2637983436822578', 1, 'Tunai', 'FORMO', '2025-08-07 05:24:46'),
(19, 44238, 2021, 'February', 11, 'Daruna Fujiati', 'Jalan HOS. Cokroaminoto No. 94, Tanjungpinang, Sulawesi Selatan 34747', '+62 (851) 773-5924', 'AGUS WAHYUDI', NULL, 'PT. FIF GROUP', NULL, 'F.UN/2358/0221/0021', '23N.2898', 'ALMAZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ118203', 189900000.00, 27000000.00, 162900000.00, 48870000.00, 114030000.00, '0', 1, '5909597430185129', 0, 'Kredit', 'ALMAZ', '2025-08-07 05:24:46'),
(20, 44240, 2021, 'February', 13, 'Drs. Jamalia Fujiati', 'Jalan M.H Thamrin No. 6, Magelang, Gorontalo 31840', '(044) 263-2527', 'NOOR CAHYA SRI UNTARI', NULL, NULL, NULL, 'F.UN/5752/0221/0148', '20N.9323', 'FORMO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ823069', 210500000.00, 27000000.00, 183500000.00, 55050000.00, 0.00, '0', 0, '6466918213379535', 1, 'Tunai', 'FORMO', '2025-08-07 05:24:46'),
(21, 44253, 2021, 'February', 26, 'drg. Muhammad Prayoga', 'Jalan Asia Afrika No. 0, Sungai Penuh, JT 03772', '(026) 231-2920', 'RIZKY RAMADHAN', NULL, NULL, NULL, 'F.UN/5379/0221/0068', '24N.3634', 'FORMO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ130453', 189900000.00, 27000000.00, 162900000.00, 48870000.00, 0.00, '0', 0, '8796730694370518', 0, 'Tunai', 'FORMO', '2025-08-07 05:24:46'),
(22, 44259, 2021, 'March', 4, 'Estiawan Permata', 'Gg. KH Amin Jasuta No. 0, Palu, Kalimantan Utara 31571', '+62-003-239-6093', 'RIZKY RAMADHAN', NULL, 'PT. FIF GROUP', NULL, 'F.UN/4450/0321/0129', '20N.4540', 'CORTEZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ984582', 189900000.00, 15000000.00, 174900000.00, 52470000.00, 122430000.00, '0', 3, '850359794324578', 0, 'Kredit', 'CORTEZ', '2025-08-07 05:24:46'),
(23, 44267, 2021, 'March', 12, 'Zelda Napitupulu, S.I.Kom', 'Gang M.T Haryono No. 842, Pekalongan, SU 11363', '+62 (364) 624-1418', 'NOOR CAHYA SRI UNTARI', NULL, 'PT. BCA FINANCE', NULL, 'F.UN/4728/0321/0039', '23N.1260', 'CORTEZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ321780', 189900000.00, 20000000.00, 169900000.00, 50970000.00, 118930000.00, '0', 2, '9076285207649252', 0, 'Kredit', 'CORTEZ', '2025-08-07 05:24:46'),
(24, 44273, 2021, 'March', 18, 'drg. Winda Maryadi', 'Jalan Moch. Ramdan No. 0, Kendari, Maluku Utara 36231', '(0590) 996-4708', 'NOOR CAHYA SRI UNTARI', NULL, 'PT. BCA FINANCE', NULL, 'F.UN/4635/0321/0007', '23N.7267', 'ALMAZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ254174', 159900000.00, 15000000.00, 144900000.00, 43470000.00, 101430000.00, '0', 1, '9468365283572382', 0, 'Kredit', 'ALMAZ', '2025-08-07 05:24:46'),
(25, 44273, 2021, 'March', 18, 'drg. Cemplunk Siregar, M.Farm', 'Gg. Erlangga No. 03, Pekalongan, Jawa Timur 58882', '(0529) 633 7420', 'RIZKY RAMADHAN', NULL, 'PT. MANDIRI TUNAS FINANCE', NULL, 'F.UN/6117/0321/0094', '22N.1637', 'ALMAZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ579493', 159900000.00, 20000000.00, 139900000.00, 41970000.00, 97930000.00, '0', 1, '3982672045814545', 0, 'Kredit', 'ALMAZ', '2025-08-07 05:24:46'),
(26, 44275, 2021, 'March', 20, 'Umi Sihombing, M.TI.', 'Jalan Lembong No. 7, Bukittinggi, Sulawesi Barat 69995', '+62 (093) 867 9165', 'LINA HERAWATI', NULL, 'PT. FIF GROUP', NULL, 'F.UN/9455/0321/0060', '23N.4237', 'CONFERO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ181641', 210500000.00, 20000000.00, 190500000.00, 57150000.00, 133350000.00, '0', 2, '8167641304648868', 1, 'Kredit', 'CONFERO', '2025-08-07 05:24:46'),
(27, 44277, 2021, 'March', 22, 'Zelaya Uyainah, S.IP', 'Gang Gardujati No. 570, Gorontalo, SN 20539', '+62-925-962-2308', 'LINA HERAWATI', NULL, NULL, NULL, 'F.UN/5702/0321/0071', '22N.4094', 'CONFERO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ189148', 210500000.00, 15000000.00, 195500000.00, 58650000.00, 0.00, '0', 0, '2611833727253179', 0, 'Tunai', 'CONFERO', '2025-08-07 05:24:46'),
(28, 44281, 2021, 'March', 26, 'R. Vera Siregar, S.I.Kom', 'Gang Surapati No. 0, Meulaboh, MU 40692', '+62 (147) 804 4094', 'AGUS WAHYUDI', NULL, NULL, NULL, 'F.UN/2146/0321/0072', '23N.1386', 'CONFERO 1.5 MT DB MY 8P', 'MK3AAAGA2MJ800187', 159900000.00, 15000000.00, 144900000.00, 43470000.00, 0.00, '0', 0, '5449988860231935', 0, 'Tunai', 'CONFERO', '2025-08-07 05:24:46'),
(29, 44283, 2021, 'March', 28, 'Olivia Yulianti', 'Gg. Pasir Koja No. 1, Sorong, PA 91343', '(0100) 155-7038', 'RIZKY RAMADHAN', NULL, 'PT. FIF GROUP', NULL, 'F.UN/7818/0321/0055', '24N.4966', 'CORTEZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ855758', 210500000.00, 20000000.00, 190500000.00, 57150000.00, 133350000.00, '0', 1, '9367057298069512', 1, 'Kredit', 'CORTEZ', '2025-08-07 05:24:46'),
(30, 44289, 2021, 'April', 3, 'Murti Yulianti', 'Gang Soekarno Hatta No. 30, Bengkulu, Jawa Timur 79481', '+62-44-334-7890', 'AGUS WAHYUDI', NULL, 'PT. BCA FINANCE', NULL, 'F.UN/8198/0421/0096', '22N.3147', 'ALMAZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ925612', 189900000.00, 27000000.00, 162900000.00, 48870000.00, 114030000.00, '0', 3, '7710960209894160', 0, 'Kredit', 'ALMAZ', '2025-08-07 05:24:46'),
(31, 44291, 2021, 'April', 5, 'Darimin Maryadi', 'Gang Asia Afrika No. 882, Kupang, Kepulauan Bangka Belitung 80932', '+62-800-120-5439', 'NOOR CAHYA SRI UNTARI', NULL, 'PT. ADIRA DINAMIKA MULTI FINANCE', NULL, 'F.UN/5249/0421/0120', '22N.5102', 'CORTEZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ240323', 210500000.00, 20000000.00, 190500000.00, 57150000.00, 133350000.00, '0', 3, '3683378583653058', 1, 'Kredit', 'CORTEZ', '2025-08-07 05:24:46'),
(32, 44302, 2021, 'April', 16, 'Ulva Kurniawan', 'Gg. Soekarno Hatta No. 0, Batu, PA 44092', '+62 (992) 450-6387', 'AGUS WAHYUDI', NULL, 'PT. BCA FINANCE', NULL, 'F.UN/4644/0421/0031', '25N.6339', 'CORTEZ 1.5 MT DB MY 8P', 'MK3AAAGA2MJ695656', 210500000.00, 27000000.00, 183500000.00, 55050000.00, 128450000.00, '0', 2, '496974415599003', 0, 'Kredit', 'CORTEZ', '2025-08-07 05:24:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('administration_head','admin_bpkb','admin_sales','operation_manager','c_level') NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `full_name`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin123', 'administration_head', 'Administrator Head', 'admin@company.com', 'active', '2025-07-30 17:51:51', '2025-07-30 17:51:51'),
(2, 'bpkb_admin', 'bpkb123', 'admin_bpkb', 'Admin BPKB', 'bpkb@company.com', 'active', '2025-07-30 17:51:51', '2025-07-30 17:51:51'),
(3, 'sales_admin', 'sales123', 'admin_sales', 'Admin Sales', 'sales@company.com', 'active', '2025-07-30 17:51:51', '2025-07-30 17:51:51'),
(4, 'ops_manager', 'ops123', 'operation_manager', 'Operation Manager', 'ops@company.com', 'active', '2025-07-30 17:51:51', '2025-07-30 17:51:51'),
(5, 'clevel', 'clevel123', 'c_level', 'C-Level Executive', 'clevel@company.com', 'active', '2025-07-30 17:51:51', '2025-07-30 17:51:51'),
(6, 'deni', 'm1887sgp', 'admin_bpkb', 'Deni hidayat', 'deni@gmail.com', 'active', '2025-07-31 11:46:13', '2025-07-31 16:46:13');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
