<?php
include 'koneksi.php';

$data = mysqli_query($koneksi, "
    SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
    FROM transaksi
    JOIN pelanggan ON pelanggan.id = transaksi.pelanggan_id
    ORDER BY transaksi.id
");
?>
<h2>Data Transaksi (Master)</h2>

<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <th>ID</th>
    <th>Tanggal</th>
    <th>Pelanggan</th>
    <th>Keterangan</th>
    <th>Total</th>
    <th>Aksi</th>
  </tr>
  <?php while ($r = mysqli_fetch_assoc($data)) { ?>
  <tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['waktu_transaksi'] ?></td>
    <td><?= $r['nama_pelanggan'] ?></td>
    <td><?= $r['keterangan'] ?></td>
    <td>Rp <?= number_format($r['total'], 0, ',', '.') ?></td>
    <td><a href="detail.php?id=<?= $r['id'] ?>">Detail</a></td>
  </tr>
  <?php } ?>
</table>