CREATE DATABASE IF NOT EXISTS penjualan CHARACTER SET utf8mb4;
USE penjualan;
 
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pembayaran, transaksi_detail, transaksi, barang, supplier, pelanggan, user;
SET FOREIGN_KEY_CHECKS = 1;
 
CREATE TABLE supplier (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  telp VARCHAR(12),
  alamat TEXT
) ENGINE=InnoDB;
 
CREATE TABLE barang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_barang VARCHAR(10) NOT NULL UNIQUE,
  nama_barang VARCHAR(100) NOT NULL,
  harga INT NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  supplier_id INT NOT NULL,
  CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id)
    REFERENCES supplier(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;
 
CREATE TABLE pelanggan (
  id VARCHAR(20) PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp VARCHAR(12),
  alamat TEXT
) ENGINE=InnoDB;
 
CREATE TABLE transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_transaksi DATE NOT NULL,
  keterangan TEXT,
  total INT NOT NULL DEFAULT 0,
  pelanggan_id VARCHAR(20) NOT NULL,
  CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id)
    REFERENCES pelanggan(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;
 
CREATE TABLE transaksi_detail (
  transaksi_id INT NOT NULL,
  barang_id INT NOT NULL,
  harga INT NOT NULL,
  qty INT NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),  -- satu barang hanya sekali per transaksi
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id)
    REFERENCES transaksi(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_detail_barang FOREIGN KEY (barang_id)
    REFERENCES barang(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;
 
CREATE TABLE pembayaran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_bayar DATETIME NOT NULL,
  total INT NOT NULL,
  metode ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT NOT NULL,
  CONSTRAINT fk_bayar_transaksi FOREIGN KEY (transaksi_id)
    REFERENCES transaksi(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
 
CREATE TABLE user (
  id_user TINYINT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(35) NOT NULL,
  nama VARCHAR(50),
  alamat VARCHAR(150),
  hp VARCHAR(20),
  level TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;
 
-- ===== DATA MASTER (minimal 5 per tabel) =====
INSERT INTO supplier (nama, telp, alamat) VALUES
('PT Sinar Jaya',     '031555101', 'Jl. Rungkut No. 10, Surabaya'),
('CV Maju Bersama',   '031555102', 'Jl. Darmo No. 22, Surabaya'),
('PT Aneka Pangan',   '031555103', 'Jl. Gubeng No. 5, Surabaya'),
('UD Sumber Rejeki',  '031555104', 'Jl. Wonokromo No. 8, Surabaya'),
('PT Global Distribusi','031555105','Jl. Ahmad Yani No. 99, Surabaya');
 
INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001', 'Beras 5 kg',        65000, 50, 3),
('BRG002', 'Minyak Goreng 2 L', 38000, 40, 3),
('BRG003', 'Gula Pasir 1 kg',   16000, 60, 1),
('BRG004', 'Mie Instan Dus',   110000, 25, 2),
('BRG005', 'Kopi Bubuk 200 g',  22000, 35, 4),
('BRG006', 'Sabun Mandi',        4500, 80, 5),
('BRG007', 'Deterjen 800 g',    19000, 30, 5);  -- belum dipakai transaksi (untuk uji hapus)
 
INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('PLG001', 'Budi Santoso',  'L', '08123450001', 'Surabaya'),
('PLG002', 'Siti Aminah',   'P', '08123450002', 'Sidoarjo'),
('PLG003', 'Agus Prasetyo', 'L', '08123450003', 'Gresik'),
('PLG004', 'Dewi Lestari',  'P', '08123450004', 'Surabaya'),
('PLG005', 'Rina Wulandari','P', '08123450005', 'Malang');
 
INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin', MD5('admin123'), 'Administrator', 'Surabaya', '081200000001', 1),
('kasir', MD5('kasir123'), 'Kasir Toko',    'Surabaya', '081200000002', 2);
 
-- ===== TRANSAKSI (10 data; total dihitung ulang dari detail di bawah) =====
INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-01', 'Belanja bulanan',     0, 'PLG001'),
('2026-10-03', 'Pembelian sembako',   0, 'PLG002'),
('2026-10-05', 'Belanja mingguan',    0, 'PLG003'),
('2026-10-07', 'Pesanan kantor',      0, 'PLG004'),
('2026-10-10', 'Belanja harian',      0, 'PLG005'),
('2026-10-12', 'Stok warung',         0, 'PLG001'),
('2026-10-15', 'Pembelian kopi',      0, 'PLG002'),
('2026-10-18', 'Belanja bulanan',     0, 'PLG003'),
('2026-10-22', 'Pesanan acara',       0, 'PLG004'),
('2026-10-28', 'Belanja akhir bulan', 0, 'PLG005');
 
INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 65000, 2), (1, 2, 38000, 1), (1, 3, 16000, 3),
(2, 1, 65000, 1), (2, 5, 22000, 2),
(3, 4, 110000, 1), (3, 3, 16000, 2),
(4, 4, 110000, 3), (4, 2, 38000, 2),
(5, 6, 4500, 5),  (5, 5, 22000, 1),
(6, 1, 65000, 4), (6, 3, 16000, 5),
(7, 5, 22000, 6),
(8, 2, 38000, 3), (8, 1, 65000, 1), (8, 6, 4500, 2),
(9, 4, 110000, 2), (9, 5, 22000, 4),
(10, 3, 16000, 4), (10, 6, 4500, 10);
 
-- Hitung total tiap transaksi dari detailnya
UPDATE transaksi t
SET t.total = (SELECT COALESCE(SUM(d.harga * d.qty), 0)
               FROM transaksi_detail d WHERE d.transaksi_id = t.id);
 
INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id)
SELECT CONCAT(waktu_transaksi, ' 10:00:00'), total, 'TUNAI', id FROM transaksi WHERE id <= 5;
 