-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 30, 2026 at 02:43 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `barang_id` int NOT NULL,
  `kode_barang` varchar(10) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga` int NOT NULL,
  `stok` int NOT NULL,
  `supplier_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`barang_id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG001', 'Buku Tulis Sinar Dunia', 5000, 100, 1),
(2, 'BRG002', 'Pulpen Standard AE7', 3000, 150, 1),
(3, 'BRG003', 'Pensil 2B Faber Castell', 4000, 80, 2),
(4, 'BRG004', 'Penggaris 30cm', 2500, 60, 2),
(5, 'BRG005', 'Penghapus Karet', 1500, 200, 3),
(6, 'BRG006', 'Buku Gambar A4', 7000, 50, 3),
(7, 'BRG007', 'Spidol Hitam Permanent', 8000, 40, 4),
(8, 'BRG008', 'Tipe-x Roll', 6000, 90, 4),
(9, 'BRG009', 'Buku Kotak-kotak', 5500, 70, 5),
(10, 'BRG010', 'Stapler Kecil', 12000, 30, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `pelanggan_id` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `telp` varchar(12) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`pelanggan_id`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
('PLG001', 'Ahmad Dani', 'L', '0811111111', 'Surabaya'),
('PLG002', 'Siti Rahma', 'P', '0822222222', 'Madura'),
('PLG003', 'Budi Santoso', 'L', '0833333333', 'Malang'),
('PLG004', 'Dewi Lestari', 'P', '0844444444', 'Sidoarjo'),
('PLG005', 'Eko Prasetyo', 'L', '0855555555', 'Gresik'),
('PLG006', 'Fitri Handayani', 'P', '0866666666', 'Jakarta'),
('PLG007', 'Gilang Ramadhan', 'L', '0877777777', 'Bandung'),
('PLG008', 'Hana Maharani', 'P', '0888888888', 'Semarang'),
('PLG009', 'Irfan Hakim', 'L', '0899999999', 'Yogyakarta'),
('PLG010', 'Joko Widodo', 'L', '0810101010', 'Solo');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `pembayaran_id` int NOT NULL,
  `waktu_bayar` datetime NOT NULL,
  `metode` enum('TUNAI','TRANSFER','EDC') NOT NULL,
  `transaksi_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`pembayaran_id`, `waktu_bayar`, `metode`, `transaksi_id`) VALUES
(1, '2026-10-01 10:00:00', 'TUNAI', 1),
(2, '2026-10-02 11:30:00', 'TRANSFER', 2),
(3, '2026-10-03 09:15:00', 'EDC', 3),
(4, '2026-10-04 14:20:00', 'TUNAI', 4),
(5, '2026-10-05 16:45:00', 'TRANSFER', 5),
(6, '2026-10-06 13:10:00', 'TUNAI', 6),
(7, '2026-10-07 15:00:00', 'EDC', 7),
(8, '2026-10-08 10:25:00', 'TUNAI', 8),
(9, '2026-10-09 12:00:00', 'TRANSFER', 9),
(10, '2026-10-10 17:50:00', 'EDC', 10);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telp` varchar(12) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `nama`, `telp`, `alamat`) VALUES
(1, 'PT Maju Bersama', '08123456', 'Surabaya'),
(2, 'PT Senang Sekali', '081515563', 'Bangkalan'),
(3, 'PT Seger Seger', '0845454663', 'Surabaya'),
(4, 'PT Berkah Jaya', '0811223344', 'Jakarta'),
(5, 'PT Sinar Abadi', '0822334455', 'Malang'),
(6, 'PT Makmur Sentosa', '0833445566', 'Sidoarjo'),
(7, 'PT Sumber Rezeki', '0844556677', 'Gresik'),
(8, 'PT Mitra Usaha', '0855667788', 'Bandung'),
(9, 'PT Karya Mandiri', '0866778899', 'Semarang'),
(10, 'PT Sejahtera Bersama', '0877889900', 'Yogyakarta');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksi_id` int NOT NULL,
  `waktu_transaksi` date NOT NULL,
  `keterangan` text NOT NULL,
  `total` int NOT NULL,
  `pelanggan_id` varchar(20) NOT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`transaksi_id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`, `id_user`) VALUES
(1, '2026-10-01', 'Lunas', 15000, 'PLG001', 2),
(2, '2026-10-02', 'Lunas', 25000, 'PLG002', 2),
(3, '2026-10-03', 'Lunas', 12000, 'PLG003', 3),
(4, '2026-10-04', 'Lunas', 8000, 'PLG004', 3),
(5, '2026-10-05', 'Lunas', 30000, 'PLG005', 2),
(6, '2026-10-06', 'Lunas', 14000, 'PLG006', 2),
(7, '2026-10-07', 'Lunas', 40000, 'PLG007', 3),
(8, '2026-10-08', 'Lunas', 9500, 'PLG008', 3),
(9, '2026-10-09', 'Lunas', 18000, 'PLG009', 2),
(10, '2026-10-10', 'Lunas', 22000, 'PLG010', 2);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `transaksi_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `harga` int NOT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`transaksi_id`, `barang_id`, `harga`, `qty`) VALUES
(1, 1, 5000, 3),
(2, 2, 3000, 5),
(3, 3, 4000, 3),
(4, 4, 2500, 3),
(5, 5, 1500, 20),
(6, 6, 7000, 2),
(7, 7, 8000, 5),
(8, 8, 6000, 1),
(9, 9, 5500, 3),
(10, 10, 12000, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(35) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `hp` varchar(20) NOT NULL,
  `level` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin1', '202cb962ac59075b964b07152d234b70', 'Admin Utama', 'Surabaya', '081234567890', 1),
(2, 'kasir1', '202cb962ac59075b964b07152d234b70', 'Kasir Pagi', 'Sidoarjo', '081234567891', 2),
(3, 'kasir2', '202cb962ac59075b964b07152d234b70', 'Kasir Sore', 'Malang', '081234567892', 2),
(4, 'gudang1', '202cb962ac59075b964b07152d234b70', 'Staff Gudang A', 'Gresik', '081234567893', 3),
(5, 'gudang2', '202cb962ac59075b964b07152d234b70', 'Staff Gudang B', 'Surabaya', '081234567894', 3),
(6, 'manager', '202cb962ac59075b964b07152d234b70', 'Manager Toko', 'Jakarta', '081234567895', 1),
(7, 'owner', '202cb962ac59075b964b07152d234b70', 'Pemilik Toko', 'Bandung', '081234567896', 1),
(8, 'staff3', '202cb962ac59075b964b07152d234b70', 'Staff Umum', 'Semarang', '081234567897', 3),
(9, 'kasir3', '202cb962ac59075b964b07152d234b70', 'Kasir Malam', 'Yogyakarta', '081234567898', 2),
(10, 'admin2', '202cb962ac59075b964b07152d234b70', 'Admin Backup', 'Surabaya', '081234567899', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`barang_id`),
  ADD KEY `fk_barang_supplier` (`supplier_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`pelanggan_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`pembayaran_id`),
  ADD KEY `fk_pembayaran_transaksi` (`transaksi_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`transaksi_id`),
  ADD KEY `fk_transaksi_pelanggan` (`pelanggan_id`),
  ADD KEY `fk_transaksi_user` (`id_user`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`transaksi_id`,`barang_id`),
  ADD KEY `fk_detail_barang` (`barang_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `barang_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `pembayaran_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`transaksi_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`pelanggan_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `fk_detail_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`transaksi_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
