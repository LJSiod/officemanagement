<?php
session_start();
error_reporting(0);
include '../../db.php';
$fullname = $_SESSION['name'];

if (isset($_POST['id'])) {
	$action = $_POST['action'];
	$id = $_POST['id'];

	switch ($action) {
		case 'cancel':
			$sql = "UPDATE ticketinfo SET status = 'CANCELLED', dateupdated = NOW() WHERE ID = $id";
			break;
		case 'receive':
			$sql = "UPDATE ticketinfo SET status = 'ONGOING', updatedby = '" . $fullname . "', dateupdated = NOW() WHERE ID = $id";
			break;
		case 'close':
			$sql = "UPDATE ticketinfo SET status = 'COMPLETED', updatedby = '" . $fullname . "', dateupdated = NOW() WHERE ID = $id";
			break;
		default:
			echo "Invalid action";
			break;
	}

	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$conn->close();

	echo json_encode(["success" => true, "message" => "Ticket updated successfully."]);
	mysqli_close($conn);
	exit;
} else {
	echo json_encode(["success" => false, "message" => "Invalid request."]);
}

?>