<?php
session_start();
error_reporting(0);
include '../../db.php';
include '../../maindb.php';
$userid = $_SESSION['userid'];
$fullname = $_SESSION['name'];

if (isset($_POST['id'])) {
	$action = $_POST['action'];
	$note = filter_var($_POST['note'], FILTER_SANITIZE_STRING);
	$id = $_POST['id'];

	switch ($action) {
		case 'cancel':
			$sql = "UPDATE ticketinfo SET status = 'CANCELLED', updatedby = $userid, dateupdated = NOW() WHERE ID = $id";
			break;
		case 'receive':
			$sql = "UPDATE ticketinfo SET status = 'ONGOING', updatedby = $userid, dateupdated = NOW() WHERE ID = $id";
			break;
		case 'close':
			$sql = "UPDATE ticketinfo SET status = 'COMPLETED', updatedby = $userid, note = '$note', dateupdated = NOW() WHERE ID = $id";
			break;
		case 'online':
			$sql = "UPDATE employee SET activeStat = NOW() WHERE ID = $id";
			break;
		default:
			echo "Invalid action";
			break;
	}

	if ($action == 'online') {
		$stmt = $connmain->prepare($sql);
		$stmt->execute();
		$connmain->close();
	} else {
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$conn->close();
	}


	echo json_encode(["success" => true, "message" => "Success."]);
	exit;
} else {
	echo json_encode(["success" => false, "message" => "Failed."]);
}

?>