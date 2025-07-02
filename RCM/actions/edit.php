<?php
error_reporting(0);
include('../db.php');
$id = $_POST['id'];
$firstname = $conn->real_escape_string($_POST['firstname']);
$middlename = $conn->real_escape_string($_POST['middlename']);
$lastname = $conn->real_escape_string($_POST['lastname']);
$transcode = $_POST['code'];
$receiverid = $_POST['receiver'];
$amount = $_POST['amount'];
$sender = $_POST['sender'];
$channel = $_POST['channel'];

$query = "UPDATE rcm SET 
    custfirstname = '$firstname',
    custmiddlename = '$middlename',
    custlastname = '$lastname',
    receiverid = '$receiverid',
    transamount = '$amount',
    sendername = '$sender',
    channelid = '$channel'
WHERE id = $id";

$result = $conn->query($query);
mysqli_close($conn);

echo json_encode(array("success" => true, "message" => "Data updated successfully"));
?>