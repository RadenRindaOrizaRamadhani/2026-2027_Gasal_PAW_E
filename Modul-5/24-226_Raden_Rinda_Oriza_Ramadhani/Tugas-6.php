<?php
include 'koneksi.php';

$id = $_GET['id'];

$stmt = $DBH->prepare("DELETE FROM supplier WHERE supplier_id = ?");
$stmt->execute([$id]);

header("Location: Tugas-3.php");
exit;
?>