<?php
error_reporting(0);
require '../db.php';

$result = $conn->query("SELECT * FROM rcmbranch");
$branch = [];
while ($row = $result->fetch_assoc()) {
    $branch[] = $row;
}
header('Content-Type: application/json');
echo json_encode($branch);
mysqli_close($conn);
