<?php
require 'database.php';

// connect database
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=log_records.csv');

// open file pointer to output
$output = fopen('php://output', 'w');

// add column headers
fputcsv($output, ['ID', 'Card ID', 'Name', 'Date', 'Time']);

// fetch data
$sql = "SELECT * FROM log_records ORDER BY scan_date DESC";
$q = $pdo->query($sql);

while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
    $date = date("Y-m-d", strtotime($row['scan_date']));
    $time = date("H:i:s", strtotime($row['scan_date']));
    fputcsv($output, [$row['id'], $row['card_id'], $row['name'], $date, $time]);
}

fclose($output);
Database::disconnect();
?>
