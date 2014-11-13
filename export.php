<?php

include('includes/config.php');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

$output = fopen('php://output', 'w');

$sql = 'SELECT * FROM visitor';
$rows = mysqli_query($con, $sql);

while ($row = mysqli_fetch_assoc($rows)) {
    fputcsv($output, $row);
}
