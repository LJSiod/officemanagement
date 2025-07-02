<?php
session_start();
date_default_timezone_set('Asia/Manila');
include '../db.php';
include '../includes/header.php';

$dateformat = date('F j, Y');
$role = $_SESSION['role'];
$branchid = $_SESSION['branchid'];
$positionid = $_SESSION['positionid'];
$admin = !in_array($positionid, [5, 6, 7]);
$currentdate = date('Y-m-d');

if ($admin) {
    include 'admin.php';
} else {
    include 'branch.php';
}
?>