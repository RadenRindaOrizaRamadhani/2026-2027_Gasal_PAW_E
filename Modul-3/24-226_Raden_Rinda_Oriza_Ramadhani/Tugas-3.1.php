<?php
$height = array("Andy" => "176", "Barry" => "165", "Charlie" => "170");
$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

$output_arr = [];
foreach($height as $k => $v) { $output_arr[] = "\"$k\" => \"$v\""; }
echo "height(" . implode(", ", $output_arr) . ")<br>";

$keys = array_keys($height);
echo "Nilai dengan indeks terakhir: " . $height[end($keys)] . "<br><br>";

unset($height["Barry"]);
$output_arr_del = [];
foreach($height as $k => $v) { $output_arr_del[] = "\"$k\" => \"$v\""; }
echo "height(" . implode(", ", $output_arr_del) . ")<br>";

$keys_del = array_keys($height);
echo "Nilai dengan indeks terakhir setelah dihapus: " . $height[end($keys_del)] . "<br><br>";


?>