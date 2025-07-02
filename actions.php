<?php
include 'db.php';

if (isset($_GET['id'])) {
	$action = $_GET['action'];
	$id = $_GET['id'];
	$username = $_GET['username'];
	$password = $_GET['password'];

	switch ($action) {
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

	header("Location: landing.php");
	exit;
}


?>