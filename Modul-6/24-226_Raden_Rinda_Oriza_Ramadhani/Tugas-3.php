<?php
include 'koneksi.php';

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];

    $cek = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM transaksi_detail WHERE barang_id = $id");
    $hasil = mysqli_fetch_assoc($cek);

    if ($hasil['jumlah'] > 0) {
        echo "<script>
                alert('Barang tidak dapat dihapus karena masih digunakan pada transaksi!');
                window.location = 'Tugas-3.php';
              </script>";
    } else {
        mysqli_query($koneksi, "DELETE FROM barang WHERE id = $id");
        echo "<script>
                alert('Barang berhasil dihapus.');
                window.location = 'Tugas-3.php';
              </script>";
    }
    exit;
}

$data = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY kode_barang");
?>
<h2>Data Barang</h2>

<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <th>ID</th>
    <th>Kode</th>
    <th>Nama Barang</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Aksi</th>
  </tr>
  <?php while ($r = mysqli_fetch_assoc($data)) { ?>
  <tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['kode_barang'] ?></td>
    <td><?= $r['nama_barang'] ?></td>
    <td><?= number_format($r['harga'], 0, ',', '.') ?></td>
    <td><?= $r['stok'] ?></td>
    <td>
      <a href="Tugas-3.php?hapus=<?= $r['id'] ?>" onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a>
    </td>
  </tr>
  <?php } ?>
</table>
