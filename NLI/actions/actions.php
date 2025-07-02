<?php
error_reporting(0);
include '../../db.php';

if (isset($_GET['id'])) {
	$action = $_GET['action'];
	$id = $_GET['id'];
	$username = $_GET['username'];
	$password = $_GET['password'];

	switch ($action) {
		case 'disapprove':
			$sql = "UPDATE approvalinfo SET status = 'REJECTED' WHERE ID = $id";
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