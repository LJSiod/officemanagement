<?php
error_reporting(0);
require '../../db.php';
$id = $_GET['id'];

$result = $conn->query("SELECT ti.*, b.name as branchname FROM ticketinfo ti LEFT JOIN branch b ON ti.branchid = b.id WHERE updatedby = '$id' AND status = 'ONGOING'");
$serving = [];
while ($row = $result->fetch_assoc()) {
    $serving[] = $row;
}
header('Content-Type: application/json');
echo json_encode($serving);
mysqli_close($conn);