<?php
error_reporting(0);
include '../../db.php';

if (isset($_GET['id'])) {
	$action = $_GET['action'];
	$id = $_GET['id'];

	switch ($action) {
		case 'cancel':
			$sql = "UPDATE evaluationinfo SET status = 'CANCELLED' WHERE ID = $id";
			break;
		case 'receive':
			$sql = "UPDATE evaluationinfo SET status = 'RECEIVED' WHERE ID = $id";
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