-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Bulan Mei 2025 pada 05.34
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
-- Database: `tiket`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_kereta`
--

CREATE TABLE `jadwal_kereta` (
  `id` int(11) NOT NULL,
  `nama_kereta` varchar(100) DEFAULT NULL,
  `rute` varchar(255) DEFAULT NULL,
  `waktu_berangkat` time DEFAULT NULL,
  `waktu_tiba` time DEFAULT NULL,
  `hari` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_kereta`
--

INSERT INTO `jadwal_kereta` (`id`, `nama_kereta`, `rute`, `waktu_berangkat`, `waktu_tiba`, `hari`) VALUES
(1, 'Argo Dwipangga', 'Solo - Jakarta', '07:00:00', '14:00:00', 'Senin - Jumat'),
(2, 'Prambanan Ekspres', 'Yogyakarta - Solo', '06:30:00', '07:30:00', 'Setiap Hari'),
(3, 'Taksaka', 'Yogyakarta - Jakarta', '20:00:00', '03:30:00', 'Setiap Hari');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keretaapi`
--

CREATE TABLE `keretaapi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(100) NOT NULL,
  `kapasitas` int(11) NOT NULL CHECK (`kapasitas` > 0),
  `jam_keberangkatan` time NOT NULL,
  `rute_perjalanan` varchar(100) NOT NULL,
  `harga` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `keretaapi`
--

INSERT INTO `keretaapi` (`id`, `nama`, `kelas`, `kapasitas`, `jam_keberangkatan`, `rute_perjalanan`, `harga`) VALUES
(123, 'Argo Lawu', 'Eksekutif', 90, '17:00:00', 'Jakarta - Surabaya', 250000),
(124, 'Taksaka', 'Eksekutif', 70, '09:00:00', 'Jakarta - Yogyakarta', 300000),
(125, 'Gajayana', 'Luxury', 26, '10:00:00', 'Jakarta - Malang', 550000),
(126, 'Manahan', 'Priority', 30, '13:00:00', 'Solo - Jakarta', 1250000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int(11) NOT NULL,
  `id_penumpang` varchar(50) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kontak` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu` varchar(20) DEFAULT NULL,
  `rute` varchar(255) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `kursi` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `id_penumpang`, `nama`, `kontak`, `tanggal`, `waktu`, `rute`, `jumlah`, `kursi`) VALUES
(1, 'ID-6832733f85c1c', 'Cindi Ayu Diah Pitaloka', '089999999123', '2025-05-13', '07:00', 'Solo Balapan - Bandung', 1, '6A');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penumpang`
--

CREATE TABLE `penumpang` (
  `id` int(11) NOT NULL,
  `id_penumpang` varchar(50) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kontak` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penumpang`
--

INSERT INTO `penumpang` (`id`, `id_penumpang`, `nama`, `kontak`) VALUES
(1, 'ID-6832733f85c1c', 'Cindi Ayu Diah Pitaloka', '089999999123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `Username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`Username`, `password`) VALUES
('cindi', '3333'),
('endang', '4444'),
('shafwa', '1111'),
('syifa', '2222');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `jadwal_kereta`
--
ALTER TABLE `jadwal_kereta`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keretaapi`
--
ALTER TABLE `keretaapi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penumpang`
--
ALTER TABLE `penumpang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_penumpang` (`id_penumpang`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `jadwal_kereta`
--
ALTER TABLE `jadwal_kereta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `penumpang`
--
ALTER TABLE `penumpang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
