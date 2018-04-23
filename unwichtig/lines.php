<?php

$num = $_GET['Anzahl'];
$lines = array();

for ($i=0; $i<$num; $i++){
	array_push($lines, $i);
}
$fp = fopen('temporary.csv', 'w');
$daten = $lines;
fputcsv($fp, $daten);
fclose($fp);
rename('temporary.csv', 'lines.csv');

?>