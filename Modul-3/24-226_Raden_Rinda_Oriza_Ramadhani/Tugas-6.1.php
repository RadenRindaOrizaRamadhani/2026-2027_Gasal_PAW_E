<?php
$arr1 = array("A");
array_push($arr1, "B");
echo "Array awal (\"A\")<br>Hasil array push: " . implode("", $arr1) . "<br><br>";

$arrA = array("A", "B"); $arrB = array("C");
$merged = array_merge($arrA, $arrB);
echo "Array awal (\"A\", \"B\") digabung dengan (\"C\")<br>Hasil array merge: " . implode("", $merged) . "<br><br>";

$assoc1 = array("x" => 1, "y" => 2);
echo "Array awal (\"x\" => 1, \"y\" => 2)<br>Hasil array values: " . implode(" ", array_values($assoc1)) . "<br><br>";

$arrSearch = array("A", "B", "C");
echo "Mencari \"B\" pada array: (\"A\", \"B\", \"C\")<br>Hasil array search: " . array_search("B", $arrSearch) . "<br><br>";

$arrFilter = array(0, 1, false, 2, 3, "array");
echo "Array awal: (0, 1, false, 2, 3, \"array\")<br>Hasil array filter: " . implode(" ", array_filter($arrFilter)) . "<br><br>";

$nums = array(3, 1, 2);
echo "Array awal: (3, 1, 2)<br>";
$n1 = $nums; sort($n1); echo "Hasil sort: " . implode(" ", $n1) . "<br>";
$n2 = $nums; rsort($n2); echo "Hasil rsort: " . implode(" ", $n2) . "<br><br>";

$ages = array("Peter" => 35, "Ben" => 37, "Joe" => 43);
echo "Array awal: (\"Peter\" => 35, \"Ben\" => 37, \"Joe\" => 43)<br>";

$a = $ages; asort($a); 
$res = []; foreach($a as $k=>$v) { $res[] = "$k => $v"; }
echo "Hasil asort: " . implode(", ", $res) . "<br>";

$k = $ages; ksort($k); 
$res = []; foreach($k as $k2=>$v2) { $res[] = "$k2 => $v2"; }
echo "Hasil ksort: " . implode(", ", $res) . "<br>";

$ar = $ages; arsort($ar); 
$res = []; foreach($ar as $k3=>$v3) { $res[] = "$k3 => $v3"; }
echo "Hasil arsort: " . implode(", ", $res) . "<br>";

$kr = $ages; krsort($kr); 
$res = []; foreach($kr as $k4=>$v4) { $res[] = "$k4 => $v4"; }
echo "Hasil krsort: " . implode(", ", $res);
?>