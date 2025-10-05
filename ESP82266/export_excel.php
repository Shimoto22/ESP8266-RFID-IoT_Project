<?php
require 'database.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=log_records.xls");

$pdo = Database::connect();
$sql = "SELECT * FROM log_records ORDER BY scan_date DESC";
$q = $pdo->query($sql);

// Excel column headers
echo "ID\tCard ID\tName\tScan Date\n";

// Loop through logs
while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . "\t" . $row['card_id'] . "\t" . $row['name'] . "\t" . $row['scan_date'] . "\n";
}

Database::disconnect();
?>
