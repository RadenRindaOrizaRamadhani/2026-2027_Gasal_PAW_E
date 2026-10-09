<?php
$fruits = array("Avocado", "Blueberry", "Cherry", "Durian", "Elderberry", "Fig", "Grape", "Honeydew");
unset($fruits[1]);
$fruits = array_values($fruits);

echo "Data Blueberry dihapus.<br>";
echo "fruits = (\"" . implode("\", \"", $fruits) . "\")<br>";
$highestIndex = count($fruits) - 1;
echo "Nilai dengan indeks tertinggi: " . $fruits[$highestIndex];
?>