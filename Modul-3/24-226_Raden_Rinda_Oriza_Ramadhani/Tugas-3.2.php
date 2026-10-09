<?php
$weight = array("Andy" => "70", "Barry" => "65", "Charlie" => "75");
$values = array_values($weight);

$output_w = [];
foreach($weight as $k => $v) { $output_w[] = "\"$k\" => \"$v\""; }
echo "weight = (" . implode(", ", $output_w) . ")<br>";
echo "Data kedua: " . $values[1];
?>