<?php
error_reporting(0);
session_start();
include('../db.php');
date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d H:i:s');
$userid = $_SESSION['userid'];

if (isset($_GET['id'])) {
	$action = $_GET['action'];
	$id = $_GET['id'];
	$username = $_GET['username'];
	$password = $_GET['password'];

	switch ($action) {
		case 'receive':
			$sql = "UPDATE rcm SET status= 'RECEIVED', datetimereceived = '$today' WHERE id = $id";
			break;
		case 'encoded':
			$sql = "UPDATE rcm SET status= 'ENCODED' WHERE id = $id";
			break;
		case 'cancel':
			$sql = "UPDATE rcm SET status= 'CANCELLED' WHERE id = $id";
			break;
		case 'username':
			$sql = "UPDATE users SET username = '$username' WHERE id = $id";
			break;
		case 'password':
			$sql = "UPDATE users SET password = '$password' WHERE id = $id";
			break;
		default:
			echo "Invalid action";
			break;
	}

	$stmt = $conn->prepare($sql);
	$stmt->execute();
	mysqli_close($conn);

	header("Location: ../views/dashboard.php");
	exit;
}


?>