<?php
include '../../db.php';

$id = $_POST['id'];

$result = $conn->query("SELECT updatedby FROM ticketinfo WHERE id = '$id'");
$row = $result->fetch_assoc();
if (is_null($row['updatedby'])) {
    echo json_encode(['vacant' => true]);
} else {
    echo json_encode(['vacant' => false, 'message' => 'Ticket is unavailable.']);
}