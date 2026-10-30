<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)";
    $stmt = $DBH->prepare($sql);
    $stmt->execute([$nama, $telp, $alamat]);

    header("Location: Tugas-3.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas 4 - Tambah Data Supplier</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form { width: 300px; margin-top: 20px; }
        div { margin-bottom: 10px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], textarea { width: 100%; padding: 6px; box-sizing: border-box; }
        .btn-simpan { background: green; color: white; border: none; padding: 8px 12px; cursor: pointer; }
        .btn-batal { background: red; color: white; padding: 8px 12px; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>

    <h3>Tambah Data Supplier Baru</h3>
    <form method="POST">
        <div>
            <label>Nama Supplier:</label>
            <input type="text" name="nama" required>
        </div>
        <div>
            <label>No Telp:</label>
            <input type="text" name="telp" required>
        </div>
        <div>
            <label>Alamat:</label>
            <textarea name="alamat" required></textarea>
        </div>
        <button type="submit" name="simpan" class="btn-simpan">Simpan</button>
        <a href="Tugas-3.php" class="btn-batal">Batal</a>
    </form>

</body>
</html>