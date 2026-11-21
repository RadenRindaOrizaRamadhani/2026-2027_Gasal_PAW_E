<?php
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
require 'Koneksi.php';

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function flash_set($tipe, $pesan) { $_SESSION['flash'] = [$tipe, $pesan]; }
function flash_tampil() {
    if (empty($_SESSION['flash'])) return '';
    [$t, $m] = $_SESSION['flash']; unset($_SESSION['flash']);
    return '<div class="alert alert-' . e($t) . ' alert-dismissible fade show">' . e($m) .
           '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}
function csrf_field() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}
function csrf_cek() {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); exit('Token tidak valid. Muat ulang halaman.');
    }
}

header('Cache-Control: no-store, no-cache, must-revalidate');
if (empty($_SESSION['id_user'])) { header('Location: Tugas-1.php'); exit; }   // belum login -> ke login

$level = (int)$_SESSION['level'];                 // 1 = owner, 2 = kasir
$hal   = $_GET['hal'] ?? 'home';
if (!in_array($hal, ['home', 'master', 'transaksi', 'laporan'], true)) $hal = 'home';
$aksi  = $_GET['aksi'] ?? 'list';

// HAK AKSES: Data Master hanya untuk level 1 (owner)
if ($hal === 'master' && $level !== 1) {
    flash_set('danger', 'Anda tidak memiliki hak akses ke halaman tersebut.');
    header('Location: Tugas-2.php'); exit;
}

$cfg = [
  'barang' => [
    'judul' => 'Data Barang', 'pk' => 'id',
    'kolom' => [
      'kode_barang' => ['Kode Barang', 'text', null, true],
      'nama_barang' => ['Nama Barang', 'text', null, true],
      'harga'       => ['Harga', 'number'],
      'stok'        => ['Stok', 'number'],
      'supplier_id' => ['Supplier', 'select', 'SELECT id, nama FROM supplier ORDER BY nama'],
    ],
    'list' => 'SELECT b.id, b.kode_barang, b.nama_barang, b.harga, b.stok, s.nama AS supplier_id
               FROM barang b LEFT JOIN supplier s ON s.id = b.supplier_id ORDER BY b.id',
  ],
  'supplier' => [
    'judul' => 'Data Supplier', 'pk' => 'id',
    'kolom' => [
      'nama'   => ['Nama', 'text', null, true],
      'telp'   => ['Telp', 'text'],
      'alamat' => ['Alamat', 'textarea'],
    ],
  ],
  'pelanggan' => [
    'judul' => 'Data Pelanggan', 'pk' => 'id', 'pk_manual' => true,
    'kolom' => [
      'nama'          => ['Nama', 'text', null, true],
      'jenis_kelamin' => ['Jenis Kelamin', 'enum', ['L' => 'Laki-laki', 'P' => 'Perempuan']],
      'telp'          => ['Telp', 'text'],
      'alamat'        => ['Alamat', 'textarea'],
    ],
  ],
  'user' => [
    'judul' => 'Data User', 'pk' => 'id_user', 'unik' => 'username',
    'kolom' => [
      'username' => ['Username', 'text', null, true],
      'password' => ['Password', 'password'],
      'nama'     => ['Nama', 'text'],
      'alamat'   => ['Alamat', 'text'],
      'hp'       => ['HP', 'text'],
      'level'    => ['Level', 'enum', [1 => 'Owner', 2 => 'Kasir']],
    ],
  ],
];
$tabel = $_GET['t'] ?? 'barang';
if (!isset($cfg[$tabel])) $tabel = 'barang';
$c = $cfg[$tabel];
$pk = $c['pk'];
$kembali = 'Tugas-2.php?hal=master&t=' . $tabel;


if ($hal === 'master' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_cek();
    try {
        if ($aksi === 'hapus') {
            if ($tabel === 'user' && (int)$_POST['id'] === (int)$_SESSION['id_user'])
                throw new Exception('Anda tidak bisa menghapus akun yang sedang dipakai.');
            $conn->prepare("DELETE FROM $tabel WHERE $pk = ?")->execute([$_POST['id']]);
            flash_set('success', 'Data dihapus.');
        } else {
            $edit = ($_POST['mode'] ?? '') === 'edit';
            $id   = trim($_POST['id'] ?? '');
            $data = [];

            foreach ($c['kolom'] as $k => $d) {
                $label = $d[0]; $tipe = $d[1]; $v = $_POST[$k] ?? '';
                if ($tipe === 'password') {            // kosong saat edit = password tidak diubah
                    if ($v !== '') $data[$k] = md5($v);
                    elseif (!$edit) throw new Exception('Password wajib diisi untuk user baru.');
                    continue;
                }
                if ($tipe === 'number') {
                    $v = (int)$v;
                    if ($v < 0) throw new Exception("$label tidak boleh negatif.");
                } elseif ($tipe === 'select') {
                    $v = $v !== '' ? (int)$v : null;
                } elseif ($tipe === 'enum') {
                    if (!array_key_exists($v, $d[2])) throw new Exception("$label tidak valid.");
                } else {
                    $v = trim($v);
                    if (!empty($d[3]) && $v === '') throw new Exception("$label wajib diisi.");
                }
                $data[$k] = $v;
            }

            if (!empty($c['unik'])) {                  // cek username unik
                $u = $c['unik'];
                $q = $conn->prepare("SELECT COUNT(*) FROM $tabel WHERE $u = ? AND $pk <> ?");
                $q->execute([$data[$u], $edit ? $id : 0]);
                if ($q->fetchColumn()) throw new Exception(ucfirst($u) . ' sudah dipakai.');
            }
            if (!empty($c['pk_manual']) && !$edit) {   // ID diinput manual (pelanggan)
                if ($id === '') throw new Exception('ID wajib diisi.');
                $q = $conn->prepare("SELECT COUNT(*) FROM $tabel WHERE $pk = ?");
                $q->execute([$id]);
                if ($q->fetchColumn()) throw new Exception('ID sudah dipakai.');
                $data = [$pk => $id] + $data;
            }

            if ($edit) {
                $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
                $conn->prepare("UPDATE $tabel SET $set WHERE $pk = ?")->execute([...array_values($data), $id]);
                if ($tabel === 'user' && (int)$id === (int)$_SESSION['id_user']) {  // sinkronkan session akun sendiri
                    $_SESSION['nama']     = $data['nama'] ?: $data['username'];
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['level']    = (int)$data['level'];
                }
            } else {
                $kol = implode(', ', array_keys($data));
                $ph  = implode(', ', array_fill(0, count($data), '?'));
                $conn->prepare("INSERT INTO $tabel ($kol) VALUES ($ph)")->execute(array_values($data));
            }
            flash_set('success', 'Data disimpan.');
        }
    } catch (Exception $ex) {
        flash_set('danger', $ex instanceof PDOException ? 'Gagal: data masih dipakai tabel lain atau tidak valid.' : $ex->getMessage());
    }
    header("Location: $kembali"); exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sistem Penjualan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="Tugas-2.php">Sistem Penjualan</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link <?= $hal === 'home' ? 'active' : '' ?>" href="Tugas-2.php">Home</a></li>

        <?php if ($level === 1):  ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= $hal === 'master' ? 'active' : '' ?>" href="#" data-bs-toggle="dropdown">Data Master</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="Tugas-2.php?hal=master&t=barang">Data Barang</a></li>
            <li><a class="dropdown-item" href="Tugas-2.php?hal=master&t=supplier">Data Supplier</a></li>
            <li><a class="dropdown-item" href="Tugas-2.php?hal=master&t=pelanggan">Data Pelanggan</a></li>
            <li><a class="dropdown-item" href="Tugas-2.php?hal=master&t=user">Data User</a></li>
          </ul>
        </li>
        <?php endif; ?>

        <li class="nav-item"><a class="nav-link <?= $hal === 'transaksi' ? 'active' : '' ?>" href="Tugas-2.php?hal=transaksi">Transaksi</a></li>
        <li class="nav-item"><a class="nav-link <?= $hal === 'laporan' ? 'active' : '' ?>" href="Tugas-2.php?hal=laporan">Laporan</a></li>
      </ul>

      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><?= e($_SESSION['nama']) ?></a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><span class="dropdown-item-text text-muted small"><?= $level === 1 ? 'Owner' : 'Kasir' ?></span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="Tugas-3.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
<?= flash_tampil() ?>

<?php if ($hal === 'home'): ?>
<h3>Selamat datang, <?= e($_SESSION['nama']) ?></h3>
<p class="text-muted">Anda login sebagai <?= $level === 1 ? 'Owner' : 'Kasir' ?>.</p>

<?php elseif ($hal === 'master'):
    if ($aksi === 'tambah' || $aksi === 'edit'):
        $edit = $aksi === 'edit';
        $r = [];
        if ($edit) {
            $st = $conn->prepare("SELECT * FROM $tabel WHERE $pk = ?");
            $st->execute([$_GET['id'] ?? '']);
            $r = $st->fetch() ?: [];
        }
?>
<h4><?= $edit ? 'Edit' : 'Tambah' ?> <?= e($c['judul']) ?></h4>
<form method="post" class="card card-body" style="max-width:600px">
  <?= csrf_field() ?>
  <?php if ($edit): ?>
    <input type="hidden" name="mode" value="edit">
    <input type="hidden" name="id" value="<?= e($r[$pk] ?? '') ?>">
  <?php endif; ?>

  <?php if (!empty($c['pk_manual'])): ?>
  <div class="mb-3"><label class="form-label">ID</label>
    <?php if ($edit): ?><input class="form-control" value="<?= e($r[$pk] ?? '') ?>" disabled>
    <?php else: ?><input name="id" class="form-control" maxlength="20" required><?php endif; ?>
  </div>
  <?php endif; ?>

  <?php foreach ($c['kolom'] as $k => $d):
      $label = $d[0]; $tipe = $d[1];
      $val = $r[$k] ?? ($k === 'level' ? 2 : ''); ?>
  <div class="mb-3">
    <label class="form-label"><?= e($label) ?>
      <?= ($tipe === 'password' && $edit) ? '<small class="text-muted">(kosongkan jika tidak diubah)</small>' : '' ?></label>

    <?php if ($tipe === 'textarea'): ?>
      <textarea name="<?= $k ?>" class="form-control" rows="3"><?= e($val) ?></textarea>

    <?php elseif ($tipe === 'enum'): ?>
      <select name="<?= $k ?>" class="form-select">
        <?php foreach ($d[2] as $kode => $teks): ?>
          <option value="<?= e($kode) ?>" <?= (string)$val === (string)$kode ? 'selected' : '' ?>><?= e($teks) ?></option>
        <?php endforeach; ?>
      </select>

    <?php elseif ($tipe === 'select'):
        $opsi = $conn->query($d[2])->fetchAll(PDO::FETCH_NUM); ?>
      <select name="<?= $k ?>" class="form-select">
        <option value="">- Pilih -</option>
        <?php foreach ($opsi as $o): ?>
          <option value="<?= e($o[0]) ?>" <?= (string)$val === (string)$o[0] ? 'selected' : '' ?>><?= e($o[1]) ?></option>
        <?php endforeach; ?>
      </select>

    <?php elseif ($tipe === 'password'): ?>
      <input type="password" name="<?= $k ?>" class="form-control" <?= $edit ? '' : 'required' ?>>

    <?php elseif ($tipe === 'number'): ?>
      <input type="number" min="0" name="<?= $k ?>" class="form-control" value="<?= e($val) ?>" required>

    <?php else: ?>
      <input name="<?= $k ?>" class="form-control" value="<?= e($val) ?>" <?= !empty($d[3]) ? 'required' : '' ?>>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>

  <div><button class="btn btn-primary">Simpan</button> <a href="<?= $kembali ?>" class="btn btn-secondary">Batal</a></div>
</form>

<?php else:
    $rows = $conn->query($c['list'] ?? "SELECT * FROM $tabel ORDER BY $pk")->fetchAll();
?>
<div class="d-flex justify-content-between mb-3">
  <h4><?= e($c['judul']) ?></h4>
  <a href="<?= $kembali ?>&aksi=tambah" class="btn btn-primary">+ Tambah</a>
</div>
<div class="table-responsive">
<table class="table table-striped table-bordered">
  <thead><tr>
    <th>No</th>
    <?php if (!empty($c['pk_manual'])): ?><th>ID</th><?php endif; ?>
    <?php foreach ($c['kolom'] as $k => $d): if ($d[1] === 'password') continue; ?><th><?= e($d[0]) ?></th><?php endforeach; ?>
    <th style="width:150px">Aksi</th>
  </tr></thead>
  <tbody>
  <?php foreach ($rows as $i => $r): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <?php if (!empty($c['pk_manual'])): ?><td><?= e($r[$pk]) ?></td><?php endif; ?>
      <?php foreach ($c['kolom'] as $k => $d): if ($d[1] === 'password') continue;
          $v = $r[$k] ?? '';
          if ($d[1] === 'enum') $v = $d[2][$v] ?? $v; ?>
        <td><?= e($v) ?></td>
      <?php endforeach; ?>
      <td>
        <a href="<?= $kembali ?>&aksi=edit&id=<?= urlencode($r[$pk]) ?>" class="btn btn-sm btn-warning">Edit</a>
        <form method="post" action="<?= $kembali ?>&aksi=hapus" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
          <?= csrf_field() ?><input type="hidden" name="id" value="<?= e($r[$pk]) ?>">
          <button class="btn btn-sm btn-danger">Hapus</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php endif; ?>

<?php elseif ($hal === 'transaksi'): ?>
<h4>Transaksi</h4>
<p class="text-muted">Halaman Transaksi (sesuai modul sebelumnya).</p>

<?php elseif ($hal === 'laporan'): ?>
<h4>Laporan</h4>
<p class="text-muted">Halaman Laporan (sesuai modul sebelumnya).</p>

<?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>