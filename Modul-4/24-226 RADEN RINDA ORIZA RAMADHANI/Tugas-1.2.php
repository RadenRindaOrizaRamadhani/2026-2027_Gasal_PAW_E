<?php
echo "<h2>Hasil Pemrosesan Form (FOREACH)</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Field Name</th><th>Field Value</th></tr>";

foreach ($_GET as $key => $value) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($key) . "</td>";
    echo "<td>" . htmlspecialchars($value) . "</td>";
    echo "</tr>";
}

echo "</table>";
?>