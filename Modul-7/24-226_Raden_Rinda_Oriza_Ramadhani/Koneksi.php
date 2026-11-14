<?php

$host = "localhost";
$port = 3306;
$username = "root";
$password = "";
$database = "penjualan";

$conn = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

function sanitazion($input)
{
  return htmlspecialchars(trim($input));
}

// Validasi format tanggal Y-m-d, kembalikan null jika tidak valid
function parseDate($value)
{
  $date = DateTime::createFromFormat("Y-m-d", (string) $value);
  return ($date && $date->format("Y-m-d") === $value) ? $value : null;
}