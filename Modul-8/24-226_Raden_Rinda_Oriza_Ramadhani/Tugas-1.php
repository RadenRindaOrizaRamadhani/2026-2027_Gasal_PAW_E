<?php
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

if (!empty($_SESSION['id_user'])) { header('Location: Tugas-2.php'); exit; }

require 'Koneksi.php';

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));

$error = '';
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $error = 'Token tidak valid. Muat ulang halaman.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = 'Username dan password wajib diisi.';
        } else {
            $st = $conn->prepare('SELECT * FROM user WHERE username = ? LIMIT 1');
            $st->execute([$username]);
            $u = $st->fetch();

            if ($u && hash_equals($u['password'], md5($password))) {
                session_regenerate_id(true);               
                // SET SESSION
                $_SESSION['id_user']  = $u['id_user'];
                $_SESSION['username'] = $u['username'];
                $_SESSION['nama']     = $u['nama'] ?: $u['username'];
                $_SESSION['level']    = (int)$u['level'];  
                header('Location: Tugas-2.php'); exit;
            }
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login - Sistem Penjualan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width:400px; margin-top:12vh">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h4 class="text-center mb-4">Sistem Penjualan</h4>
      <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
      <form method="post" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= e($username) ?>" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>