<?php
error_reporting(0);
session_start();
date_default_timezone_set('Asia/Manila');
include '../../db.php';

$positionid = $_SESSION['positionid'];
$admin = !in_array($positionid, [5, 6, 7]);
$branchid = $_SESSION['branchid'];

$currentdate = date('Y-m-d');
$querysum1 = "SELECT SUM(cashonhand) AS total1 FROM queueinfo WHERE servedby = 8 AND status = 'DONE' AND cashonhandstatus = 'RECEIVED' AND date = '$currentdate'";
$resultsum1 = mysqli_query($conn, $querysum1);
$rowsum1 = mysqli_fetch_assoc($resultsum1);
$total1 = $rowsum1['total1'];
$total1 = number_format($total1, 2);

$querysum2 = "SELECT SUM(cashonhand) AS total2 FROM queueinfo WHERE servedby = 288 AND status = 'DONE' AND cashonhandstatus = 'RECEIVED' AND date = '$currentdate'";
$resultsum2 = mysqli_query($conn, $querysum2);
$rowsum2 = mysqli_fetch_assoc($resultsum2);
$total2 = $rowsum2['total2'];
$total2 = number_format($total2, 2);

$querysum3 = "SELECT SUM(cashonhand) AS total3 FROM queueinfo WHERE servedby = 5 AND status = 'DONE' AND cashonhandstatus = 'RECEIVED' AND date = '$currentdate'";
$resultsum3 = mysqli_query($conn, $querysum3);
$rowsum3 = mysqli_fetch_assoc($resultsum3);
$total3 = $rowsum3['total3'];
$total3 = number_format($total3, 2);

$querysum4 = "SELECT SUM(cashonhand) AS total4 FROM queueinfo WHERE servedby = 73 AND status = 'DONE' AND cashonhandstatus = 'RECEIVED' AND date = '$currentdate'";
$resultsum4 = mysqli_query($conn, $querysum4);
$rowsum4 = mysqli_fetch_assoc($resultsum4);
$total4 = $rowsum4['total4'];
$total4 = number_format($total4, 2);

if ($admin) {
    $overalltotal = "SELECT SUM(cashonhand) AS total FROM queueinfo WHERE cashonhandstatus = 'RECEIVED' AND date = '$currentdate'";
} else {
    $overalltotal = "SELECT SUM(cashonhand) AS total FROM queueinfo WHERE cashonhandstatus = 'RECEIVED' AND date = '$currentdate' AND branchid = '$branchid'";
}
$resultoveralltotal = mysqli_query($conn, $overalltotal);
$rowoveralltotal = mysqli_fetch_assoc($resultoveralltotal);
$overalltotal = $rowoveralltotal['total'];
$overalltotal = number_format($overalltotal, 2);

header("Content-Type: application/json");
echo json_encode([
    'overalltotal' => $overalltotal,
    'total1' => $total1,
    'total2' => $total2,
    'total3' => $total3,
    'total4' => $total4
], JSON_UNESCAPED_UNICODE);
?>