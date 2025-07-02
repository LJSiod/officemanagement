<?php
error_reporting(0);
session_start();
include '../db.php';

date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d H:i:s');
$rows = json_decode($_POST['rows'], true);
$receiver_id = $_POST['receiver_id'];
$branchid = $_POST['branchid'];
$status = 'PENDING';
$transby = $_SESSION['name'];

// Example: Insert each row and associate with receiver
foreach ($rows as $row) {
    $channel = $row['channel'];
    $code = $row['code'];
    $firstname = $row['firstname'];
    $middlename = $row['middlename'];
    $lastname = $row['lastname'];
    $amount = $row['amount'];
    $sender = $row['sender'];
    $proof = $row['proof'];

    // Insert into your table, e.g.:
    $stmt = $conn->prepare("INSERT INTO rcm (channelid, branchid, transactioncode, custfirstname, custmiddlename, custlastname, sendername, transamount, transdatetime, transby, receiverid, status, filepath) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssssssssss", $channel, $branchid, $code, $firstname, $middlename, $lastname, $sender, $amount, $today, $transby, $receiver_id, $status, $proof);
    $stmt->execute();
}
mysqli_close($conn);
echo json_encode(['success' => true]);
?>