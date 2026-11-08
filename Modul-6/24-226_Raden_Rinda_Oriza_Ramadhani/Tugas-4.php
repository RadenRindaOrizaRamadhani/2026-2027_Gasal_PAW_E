<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if (isset($_POST['simpan'])) {
        $barang_id = (int)$_POST['barang_id'];
        $qty       = (int)$_POST['qty'];
        
        $cek = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM transaksi_detail WHERE transaksi_id = $id AND barang_id = $barang_id");
        $hasil = mysqli_fetch_assoc($cek);

        if ($hasil['jumlah'] > 0) {
            echo "<script>alert('Barang sudah ada di transaksi ini!');</script>";
        } else {
            $brg = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT harga FROM barang WHERE id = $barang_id"));
            $harga = $brg['harga'];
            mysqli_query($koneksi, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES ($id, $barang_id, $harga, $qty)");

            mysqli_query($koneksi, "
                UPDATE transaksi
                SET total = (SELECT SUM(harga * qty) FROM transaksi_detail WHERE transaksi_id = $id)
                WHERE id = $id
            ");

            header("Location: Tugas-4.php?id=$id");
            exit;
        }
    }

    $master = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
        FROM transaksi
        JOIN pelanggan ON pelanggan.id = transaksi.pelanggan_id
        WHERE transaksi.id = $id
    "));

    if (!$master) {
        die("Transaksi tidak ditemukan.");
    }

    $detail = mysqli_query($koneksi, "
        SELECT barang.nama_barang, transaksi_detail.harga, transaksi_detail.qty
        FROM transaksi_detail
        JOIN barang ON barang.id = transaksi_detail.barang_id
        WHERE transaksi_detail.transaksi_id = $id
    ");

    $pilihan = mysqli_query($koneksi, "
        SELECT id, nama_barang, harga
        FROM barang
        WHERE id NOT IN (SELECT barang_id FROM transaksi_detail WHERE transaksi_id = $id)
    ");
    ?>
    <a href="Tugas-4.php">&laquo; Kembali</a>

    <h2>Transaksi #<?= $master['id'] ?></h2>
    <p>
      Tanggal: <?= $master['waktu_transaksi'] ?><br>
      Pelanggan: <?= $master['nama_pelanggan'] ?><br>
      Keterangan: <?= $master['keterangan'] ?><br>
      <b>Total: Rp <?= number_format($master['total'], 0, ',', '.') ?></b>
    </p>

    <h3>Detail Barang</h3>
    <table border="1" cellpadding="5" cellspacing="0">
      <tr>
        <th>Barang</th>
        <th>Harga</th>
        <th>Qty</th>
        <th>Subtotal</th>
      </tr>
      <?php while ($d = mysqli_fetch_assoc($detail)) { ?>
      <tr>
        <td><?= $d['nama_barang'] ?></td>
        <td><?= number_format($d['harga'], 0, ',', '.') ?></td>
        <td><?= $d['qty'] ?></td>
        <td><?= number_format($d['harga'] * $d['qty'], 0, ',', '.') ?></td>
      </tr>
      <?php } ?>
    </table>

    <h3>Tambah Detail</h3>
    <form method="post">
      Barang:
      <select name="barang_id" required>
        <option value="">-- pilih barang --</option>
        <?php while ($b = mysqli_fetch_assoc($pilihan)) { ?>
          <option value="<?= $b['id'] ?>"><?= $b['nama_barang'] ?> (<?= number_format($b['harga'], 0, ',', '.') ?>)</option>
        <?php } ?>
      </select>
      Qty: <input type="number" name="qty" min="1" value="1" required>
      <button type="submit" name="simpan">Simpan</button>
    </form>
    <?php
    exit;
}

$data = mysqli_query($koneksi, "
    SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
    FROM transaksi
    JOIN pelanggan ON pelanggan.id = transaksi.pelanggan_id
    ORDER BY transaksi.id
");
?>
<h2>Data Transaksi</h2>

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
    <td><a href="Tugas-4.php?id=<?= $r['id'] ?>">Detail</a></td>
  </tr>
  <?php } ?>
</table>