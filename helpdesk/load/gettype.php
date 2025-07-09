<?php
error_reporting(0);
require '../../db.php';

$result = $conn->query("SELECT * FROM tickettype");
$type = [];
while ($row = $result->fetch_assoc()) {
    $type[] = $row;
}
header('Content-Type: application/json');
echo json_encode($type);
mysqli_close($conn);
