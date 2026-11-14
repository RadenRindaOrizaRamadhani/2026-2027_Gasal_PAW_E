-- Database penjualan (sesuai ER diagram modul) + data contoh semua tabel
DROP DATABASE IF EXISTS penjualan;
CREATE DATABASE penjualan;
USE penjualan;

CREATE TABLE supplier (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nama varchar(100) NOT NULL,
  telp varchar(12),
  alamat text
);

CREATE TABLE barang (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  kode_barang varchar(10) NOT NULL,
  nama_barang varchar(100) NOT NULL,
  harga int NOT NULL,
  stok int NOT NULL,
  supplier_id int,
  FOREIGN KEY (supplier_id) REFERENCES supplier(id)
);

CREATE TABLE pelanggan (
  id varchar(20) NOT NULL PRIMARY KEY,
  nama varchar(100) NOT NULL,
  jenis_kelamin enum('L','P'),
  telp varchar(12),
  alamat text
);

CREATE TABLE transaksi (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  waktu_transaksi date NOT NULL,
  keterangan text,
  total int NOT NULL,
  pelanggan_id varchar(20),
  FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
);

CREATE TABLE transaksi_detail (
  transaksi_id int NOT NULL,
  barang_id int NOT NULL,
  harga int NOT NULL,
  qty int NOT NULL,
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id),
  FOREIGN KEY (barang_id) REFERENCES barang(id)
);

CREATE TABLE pembayaran (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  waktu_bayar datetime NOT NULL,
  total int NOT NULL,
  metode enum('TUNAI','TRANSFER','EDC'),
  transaksi_id int,
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
);

CREATE TABLE user (
  id_user tinyint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username varchar(30) NOT NULL,
  password varchar(35) NOT NULL,
  nama varchar(50),
  alamat varchar(150),
  hp varchar(20),
  level tinyint(1)
);

-- Data contoh
INSERT INTO supplier (id, nama, telp, alamat) VALUES
(1, 'PT Sumber Pangan', '031555001', 'Surabaya'),
(2, 'CV Maju Jaya', '031555002', 'Sidoarjo'),
(3, 'UD Berkah Abadi', '031555003', 'Gresik');

INSERT INTO barang (id, kode_barang, nama_barang, harga, stok, supplier_id) VALUES
(1, 'BRG001', 'Minyak Goreng 1L', 13000, 100, 1),
(2, 'BRG002', 'Gula Pasir 1kg', 6500, 150, 2),
(3, 'BRG003', 'Tepung Terigu 1kg', 6500, 120, 2),
(4, 'BRG004', 'Sabun Mandi', 3000, 200, 3);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('P001','Andi','L','081111111111','Surabaya'),
('P002','Budi','L','081222222222','Sidoarjo'),
('P003','Citra','P','081333333333','Surabaya'),
('P004','Dewi','P','081444444444','Gresik'),
('P005','Eka','P','081555555555','Surabaya'),
('P006','Fajar','L','081666666666','Malang'),
('P007','Gita','P','081777777777','Surabaya'),
('P008','Hadi','L','081888888888','Sidoarjo'),
('P009','Indra','L','081999999999','Surabaya');

INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2023-09-01','Penjualan harian',26000,'P001'),
('2023-09-01','Penjualan harian',13000,'P002'),
('2023-09-02','Penjualan harian',39000,'P003'),
('2023-09-02','Penjualan harian',13000,'P004'),
('2023-09-03','Penjualan harian',13000,'P005'),
('2023-09-04','Penjualan harian',13000,'P006'),
('2023-09-05','Penjualan harian',13000,'P007'),
('2023-09-05','Penjualan harian',13000,'P008'),
('2023-09-06','Penjualan harian',13000,'P009'),
('2023-09-09','Penjualan harian',13000,'P001');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 13000, 2),
(2, 1, 13000, 1),
(3, 1, 13000, 1),
(3, 2, 6500, 4),
(4, 2, 6500, 2),
(5, 1, 13000, 1),
(6, 2, 6500, 1),
(6, 3, 6500, 1),
(7, 3, 6500, 2),
(8, 1, 13000, 1),
(9, 2, 6500, 2),
(10, 1, 13000, 1);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2023-09-01 09:15:00', 26000, 'TUNAI', 1),
('2023-09-01 13:40:00', 13000, 'TRANSFER', 2),
('2023-09-02 10:05:00', 39000, 'EDC', 3),
('2023-09-02 15:20:00', 13000, 'TUNAI', 4),
('2023-09-03 11:00:00', 13000, 'TUNAI', 5),
('2023-09-04 14:30:00', 13000, 'TRANSFER', 6),
('2023-09-05 09:45:00', 13000, 'EDC', 7),
('2023-09-05 16:10:00', 13000, 'TUNAI', 8),
('2023-09-06 12:25:00', 13000, 'TRANSFER', 9),
('2023-09-09 10:50:00', 13000, 'TUNAI', 10);

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin', MD5('admin123'), 'Administrator', 'Surabaya', '081200000001', 1),
('kasir', MD5('kasir123'), 'Kasir Toko', 'Sidoarjo', '081200000002', 2);