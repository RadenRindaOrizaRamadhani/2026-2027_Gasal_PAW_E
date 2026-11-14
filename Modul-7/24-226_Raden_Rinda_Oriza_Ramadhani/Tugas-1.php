<?php

require_once __DIR__ . "/Koneksi.php";

function getTransactions($conn, $mulai, $selesai)
{
  $sql = <<<SQL
    SELECT
      t.id,
      t.waktu_transaksi,
      p.nama AS pelanggan,
      t.keterangan,
      t.total
    FROM transaksi AS t
    LEFT JOIN pelanggan AS p ON t.pelanggan_id = p.id
    WHERE DATE(t.waktu_transaksi) BETWEEN ? AND ?
    ORDER BY t.waktu_transaksi ASC, t.id ASC;
  SQL;

  $res = $conn->prepare($sql);
  $res->execute([$mulai, $selesai]);

  return $res->fetchAll();
}

$mulai = parseDate($_GET["tanggal_mulai"] ?? "");
$selesai = parseDate($_GET["tanggal_selesai"] ?? "");
$rows = [];

if ($mulai && $selesai) {
  if ($mulai > $selesai) {
    [$mulai, $selesai] = [$selesai, $mulai];
  }
  $rows = getTransactions($conn, $mulai, $selesai);
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tugas 1 - Filter Laporan Penjualan</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 30px; }
    h1 small { font-size: 16px; color: #777; font-weight: normal; }
    table { border-collapse: collapse; width: 100%; margin-top: 15px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #d9edf7; }
    .btn { padding: 6px 14px; border: none; color: #fff; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; border-radius: 3px; background: #337ab7; }
    .form-filter { margin: 15px 0; }
  </style>
</head>

<body>
  <h1>
    Rekap laporan penjualan
    <?php if ($mulai && $selesai): ?>
      <small><?= sanitazion($mulai) ?> sampai <?= sanitazion($selesai) ?></small>
    <?php endif ?>
  </h1>

  <!-- Form filter rentang tanggal -->
  <form class="form-filter" method="GET">
    Dari:
    <input type="date" name="tanggal_mulai" value="<?= sanitazion($mulai ?? "") ?>" required>
    Sampai:
    <input type="date" name="tanggal_selesai" value="<?= sanitazion($selesai ?? "") ?>" required>
    <button type="submit" class="btn">Tampilkan</button>
  </form>

  <?php if (!$mulai || !$selesai): ?>
    <p>Pilih rentang tanggal lalu klik "Tampilkan".</p>
  <?php elseif (!$rows): ?>
    <p>Tidak ada transaksi pada rentang tanggal tersebut.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr><th>No</th><th>Tanggal</th><th>Pelanggan</th><th>Keterangan</th><th>Total</th></tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $i => $row): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= date("d M Y", strtotime($row["waktu_transaksi"])) ?></td>
            <td><?= sanitazion($row["pelanggan"] ?? "-") ?></td>
            <td><?= sanitazion($row["keterangan"] ?? "") ?></td>
            <td>Rp. <?= number_format($row["total"], 0, ",", ".") ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  <?php endif ?>

</body>

</html>