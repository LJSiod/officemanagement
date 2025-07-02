<?php
error_reporting(0);
require '../db.php';

$result = $conn->query("SELECT * FROM rcmchannel");
$channels = [];
while ($row = $result->fetch_assoc()) {
    $channels[] = $row;
}
header('Content-Type: application/json');
echo json_encode($channels);
mysqli_close($conn);
