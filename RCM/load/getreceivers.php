<?php
error_reporting(0);
require '../db.php';

$result = $conn->query("SELECT * FROM rcmreceiver");
$receiver = [];
while ($row = $result->fetch_assoc()) {
    $receiver[] = $row;
}
header('Content-Type: application/json');
echo json_encode($receiver);
mysqli_close($conn);
