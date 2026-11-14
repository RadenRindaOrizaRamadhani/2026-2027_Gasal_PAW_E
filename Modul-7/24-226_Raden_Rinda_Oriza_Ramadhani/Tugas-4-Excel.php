<?php

require_once __DIR__ . "/Koneksi.php";
function getSalesReport($conn, $mulai, $selesai)
{
  $rekapSQL = <<<SQL
    SELECT
      DATE(waktu_transaksi) AS tgl,
      SUM(total) AS total
    FROM transaksi
    WHERE DATE(waktu_transaksi) BETWEEN ? AND ?
    GROUP BY DATE(waktu_transaksi)
    ORDER BY tgl ASC;
  SQL;

  $summarySQL = <<<SQL
    SELECT
      COUNT(DISTINCT pelanggan_id) AS pelanggan,
      COALESCE(SUM(total), 0) AS total
    FROM transaksi
    WHERE DATE(waktu_transaksi) BETWEEN ? AND ?;
  SQL;

  $rekap = $conn->prepare($rekapSQL);
  $rekap->execute([$mulai, $selesai]);

  $summary = $conn->prepare($summarySQL);
  $summary->execute([$mulai, $selesai]);

  return [$rekap->fetchAll(), $summary->fetch()];
}

$mulai = parseDate($_GET["tanggal_mulai"] ?? "");
$selesai = parseDate($_GET["tanggal_selesai"] ?? "");

if (!$mulai || !$selesai) {
  header("Location: Tugas-4.php");
  exit();
}

if ($mulai > $selesai) {
  [$mulai, $selesai] = [$selesai, $mulai];
}

[$rows, $summary] = getSalesReport($conn, $mulai, $selesai);

// Header HTTP: browser mengunduh respons ini sebagai file Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=laporan_penjualan_{$mulai}_sampai_{$selesai}.xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<html>

<head>
  <meta charset="utf-8">
</head>

<body>
  <h3>Rekap Laporan Penjualan</h3>
  <p>Periode: <?= $mulai ?> sampai <?= $selesai ?></p>
  <table border="1">
    <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
    <?php foreach ($rows as $i => $row): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td>Rp. <?= number_format($row["total"], 0, ",", ".") ?></td>
        <td><?= $row["tgl"] ?></td>
      </tr>
    <?php endforeach ?>
  </table>
  <br>
  <table border="1">
    <tr><th>Jumlah Pelanggan Unik</th><th>Total Pendapatan Keseluruhan</th></tr>
    <tr><td><?= $summary["pelanggan"] ?> Orang</td><td>Rp. <?= number_format($summary["total"], 0, ",", ".") ?></td></tr>
  </table>
</body>

</html>