<?php
$weight = array("Andy" => "70", "Barry" => "65", "Charlie" => "75");
$out_w4 = [];
foreach($weight as $k => $v) { $out_w4[] = "\"$k\" => \"$v\""; }
echo "weight = (" . implode(", ", $out_w4) . ")<br>";

$keys_w = array_keys($weight);
$values_w4 = array_values($weight);
for ($i = 0; $i < count($weight); $i++) {
    echo $keys_w[$i] . " is " . $values_w4[$i] . " kg.<br>";
}
?>