<?php
error_reporting(0);
require '../db.php';

$channel = [];
$branch = [];
$receiver = [];

$channelResult = $conn->query("SELECT * FROM rcmchannel");
while ($channelRow = $channelResult->fetch_assoc()) {
    $channel[] = $channelRow;
}

$branchResult = $conn->query("SELECT * FROM rcmbranch");
while ($branchRow = $branchResult->fetch_assoc()) {
    $branch[] = $branchRow;
}

$receiverResult = $conn->query("SELECT * FROM rcmreceiver");
while ($receiverRow = $receiverResult->fetch_assoc()) {
    $receiver[] = $receiverRow;
}

$data = [
    'channel' => $channel,
    'branch' => $branch,
    'receiver' => $receiver
];

header('Content-Type: application/json');
echo json_encode($data);

