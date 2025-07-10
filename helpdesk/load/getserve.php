<?php
error_reporting(0);
require '../../db.php';
require '../../maindb.php';
$id = $_GET['id'];

$result = $conn->query("SELECT ti.*, b.id as branchid, b.name as branchname FROM ticketinfo ti LEFT JOIN branch b ON ti.branchid = b.id WHERE updatedby = '$id' AND status = 'ONGOING'");
$activeStatresult = $connmain->query("SELECT activeStat FROM employee WHERE id = '$id'");
$activeStat = $activeStatresult->fetch_assoc()['activeStat'];
$serving = [];
while ($row = $result->fetch_assoc()) {
    $serving[] = $row;
}
header('Content-Type: application/json');
echo json_encode(['serving' => $serving, 'activeStat' => $activeStat]);
mysqli_close($conn);
mysqli_close($connmain);

?>