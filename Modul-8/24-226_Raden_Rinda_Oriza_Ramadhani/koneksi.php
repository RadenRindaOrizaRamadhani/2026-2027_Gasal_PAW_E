<?php

$host = "localhost";
$port = 3306;
$username = "root";
$password = "";
$database = "penjualan";

try {
  $conn = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  exit("Koneksi database gagal: " . $e->getMessage());
}