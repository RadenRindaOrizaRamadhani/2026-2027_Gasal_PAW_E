<?php
include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas 3 - Data Master Supplier</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-tambah { background: green; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; }
        .btn-edit { background: orange; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-hapus { background: red; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>

    <h2>Data Master Supplier</h2>
    <a href="Tugas-4.php" class="btn-tambah">Tambah Data</a>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Alamat</th>
            <th>Tindakan</th>
        </tr>
        <?php
        $no = 1;
        $query = $DBH->query("SELECT * FROM supplier");
        while ($row = $query->fetch()) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td><?= htmlspecialchars($row['telp']); ?></td>
            <td><?= htmlspecialchars($row['alamat']); ?></td>
            <td>
                <a href="Tugas-5.php?id=<?= $row['supplier_id']; ?>" class="btn-edit">Edit</a>
                <a href="Tugas-6.php?id=<?= $row['supplier_id']; ?>" class="btn-hapus" onclick="return confirm('Anda yakin akan menghapus supplier ini?');">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>