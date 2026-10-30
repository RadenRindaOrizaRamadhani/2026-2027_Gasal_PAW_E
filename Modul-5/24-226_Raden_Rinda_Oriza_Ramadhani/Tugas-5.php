<?php
include 'koneksi.php';

$id = $_GET['id'];

$stmt = $DBH->prepare("SELECT * FROM supplier WHERE supplier_id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $sql = "UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE supplier_id = ?";
    $stmtUpdate = $DBH->prepare($sql);
    $stmtUpdate->execute([$nama, $telp, $alamat, $id]);

    header("Location: Tugas-3.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas 5 - Edit Data Supplier</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form { width: 300px; margin-top: 20px; }
        div { margin-bottom: 10px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], textarea { width: 100%; padding: 6px; box-sizing: border-box; }
        .btn-update { background: orange; color: white; border: none; padding: 8px 12px; cursor: pointer; }
        .btn-batal { background: red; color: white; padding: 8px 12px; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>

    <h3>Edit Data Supplier</h3>
    <form method="POST">
        <div>
            <label>Nama Supplier:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" required>
        </div>
        <div>
            <label>No Telp:</label>
            <input type="text" name="telp" value="<?= htmlspecialchars($row['telp']); ?>" required>
        </div>
        <div>
            <label>Alamat:</label>
            <textarea name="alamat" required><?= htmlspecialchars($row['alamat']); ?></textarea>
        </div>
        <button type="submit" name="update" class="btn-update">Update</button>
        <a href="Tugas-3.php" class="btn-batal">Batal</a>
    </form>

</body>
</html>