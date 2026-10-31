USE db_penjualan;
INSERT INTO supplier (nama, telp, alamat) VALUES
('PT Maju Bersama', '08123456', 'Surabaya'),
('PT Senang Sekali', '081515563', 'Bangkalan'),
('PT Seger Seger', '0845454663', 'Surabaya'),
('PT Berkah Jaya', '0811223344', 'Jakarta'),
('PT Sinar Abadi', '0822334455', 'Malang'),
('PT Makmur Sentosa', '0833445566', 'Sidoarjo'),
('PT Sumber Rezeki', '0844556677', 'Gresik'),
('PT Mitra Usaha', '0855667788', 'Bandung'),
('PT Karya Mandiri', '0866778899', 'Semarang'),
('PT Sejahtera Bersama', '0877889900', 'Yogyakarta');
INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id)
VALUES
('BRG001', 'Buku Tulis Sinar Dunia', 5000, 100, 1),
('BRG002', 'Pulpen Standard AE7', 3000, 150, 1),
('BRG003', 'Pensil 2B Faber Castell', 4000, 80, 2),
('BRG004', 'Penggaris 30cm', 2500, 60, 2),
('BRG005', 'Penghapus Karet', 1500, 200, 3),
('BRG006', 'Buku Gambar A4', 7000, 50, 3),
('BRG007', 'Spidol Hitam Permanent', 8000, 40, 4),
('BRG008', 'Tipe-x Roll', 6000, 90, 4),
('BRG009', 'Buku Kotak-kotak', 5500, 70, 5),
('BRG010', 'Stapler Kecil', 12000, 30, 5);
INSERT INTO pelanggan (pelanggan_id, nama, jenis_kelamin, telp, alamat)
VALUES
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
INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin1', md5('123'), 'Admin Utama', 'Surabaya', '081234567890', 1),
('kasir1', md5('123'), 'Kasir Pagi', 'Sidoarjo', '081234567891', 2),

('kasir2', md5('123'), 'Kasir Sore', 'Malang', '081234567892', 2),
('gudang1', md5('123'), 'Staff Gudang A', 'Gresik', '081234567893', 3),
('gudang2', md5('123'), 'Staff Gudang B', 'Surabaya', '081234567894',
3),
('manager', md5('123'), 'Manager Toko', 'Jakarta', '081234567895', 1),
('owner', md5('123'), 'Pemilik Toko', 'Bandung', '081234567896', 1),
('staff3', md5('123'), 'Staff Umum', 'Semarang', '081234567897', 3),
('kasir3', md5('123'), 'Kasir Malam', 'Yogyakarta', '081234567898', 2),
('admin2', md5('123'), 'Admin Backup', 'Surabaya', '081234567899', 1);
INSERT INTO transaksi (waktu_transaksi, keterangan, total,
pelanggan_id, id_user) VALUES
('2026-10-01', 'Lunas', 15000, 'PLG001', 2),
('2026-10-02', 'Lunas', 25000, 'PLG002', 2),
('2026-10-03', 'Lunas', 12000, 'PLG003', 3),
('2026-10-04', 'Lunas', 8000, 'PLG004', 3),
('2026-10-05', 'Lunas', 30000, 'PLG005', 2),
('2026-10-06', 'Lunas', 14000, 'PLG006', 2),
('2026-10-07', 'Lunas', 40000, 'PLG007', 3),
('2026-10-08', 'Lunas', 9500, 'PLG008', 3),
('2026-10-09', 'Lunas', 18000, 'PLG009', 2),
('2026-10-10', 'Lunas', 22000, 'PLG010', 2);
INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
VALUES
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
INSERT INTO pembayaran (waktu_bayar, metode, transaksi_id) VALUES
('2026-10-01 10:00:00', 'TUNAI', 1),
('2026-10-02 11:30:00', 'TRANSFER', 2),
('2026-10-03 09:15:00', 'EDC', 3),
('2026-10-04 14:20:00', 'TUNAI', 4),
('2026-10-05 16:45:00', 'TRANSFER', 5),
('2026-10-06 13:10:00', 'TUNAI', 6),
('2026-10-07 15:00:00', 'EDC', 7),
('2026-10-08 10:25:00', 'TUNAI', 8),
('2026-10-09 12:00:00', 'TRANSFER', 9),
('2026-10-10 17:50:00', 'EDC', 10);