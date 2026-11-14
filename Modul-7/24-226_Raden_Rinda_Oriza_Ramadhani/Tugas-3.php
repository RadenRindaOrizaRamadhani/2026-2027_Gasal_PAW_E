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
$rows = [];
$summary = ["pelanggan" => 0, "total" => 0];

if ($mulai && $selesai) {
  if ($mulai > $selesai) {
    [$mulai, $selesai] = [$selesai, $mulai];
  }
  [$rows, $summary] = getSalesReport($conn, $mulai, $selesai);
}

$labels = array_column($rows, "tgl");
$data = array_map("intval", array_column($rows, "total"));

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tugas 3 - Rekapitulasi Penjualan</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: Arial, sans-serif; margin: 30px; }
    h1 small { font-size: 16px; color: #777; font-weight: normal; }
    table { border-collapse: collapse; width: 100%; margin-top: 15px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #d9edf7; }
    .btn { padding: 6px 14px; border: none; color: #fff; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; border-radius: 3px; background: #337ab7; }
    .form-filter { margin: 15px 0; }
    .chart-box { position: relative; width: 100%; max-width: 800px; height: 350px; margin-top: 15px; }
    .card { display: inline-block; margin-top: 15px; border: 1px solid #ccc; }
    .card table { margin: 0; }
    .card th, .card td { text-align: center; padding: 8px 25px; border: none; }
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
    <!-- Grafik batang (Chart.js) -->
    <div class="chart-box"><canvas id="chartSales"></canvas></div>
    <script>
      new Chart(document.getElementById("chartSales"), {
        type: "bar",
        data: {
          labels: <?= json_encode($labels) ?>,
          datasets: [{
            label: "Total",
            data: <?= json_encode($data) ?>,
            backgroundColor: "rgba(51, 122, 183, 0.6)",
            borderColor: "rgba(51, 122, 183, 1)",
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          animation: false,
          scales: {
            x: { title: { display: true, text: "Tanggal transaksi" } },
            y: { beginAtZero: true, title: { display: true, text: "Total pendapatan (Rp)" } }
          }
        }
      });
    </script>

    <table>
      <thead>
        <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $i => $row): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td>Rp. <?= number_format($row["total"], 0, ",", ".") ?></td>
            <td><?= date("d M Y", strtotime($row["tgl"])) ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>

    <div class="card">
      <table>
        <tr><th>Jumlah Pelanggan Unik</th><th>Total Pendapatan Keseluruhan</th></tr>
        <tr>
          <td><?= sanitazion($summary["pelanggan"]) ?> Orang</td>
          <td>Rp. <?= number_format($summary["total"], 0, ",", ".") ?></td>
        </tr>
      </table>
    </div>
  <?php endif ?>

</body>

</html>