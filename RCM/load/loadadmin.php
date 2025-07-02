<?php
error_reporting(0);
session_start();
include('../db.php');

$status = $_GET['status'] ?? 'PENDING';
$branchid = $_SESSION['branchid'];
$positionid = $_SESSION['positionid'];
$admin = !in_array($positionid, [5, 6, 7]);
date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d');

if ($admin) {
    $query = "
    SELECT 
        rcm.id as rcmid,
        rcm.status as rcmstatus,
        rcm.*, 
        rcmbranch.*, 
        rcmreceiver.*, 
        rcmchannel.*
    FROM 
        rcm
    LEFT JOIN 
        rcmbranch ON rcm.branchid = rcmbranch.id
    LEFT JOIN 
        rcmchannel ON rcm.channelid = rcmchannel.id
    LEFT JOIN 
        rcmreceiver ON rcm.receiverid = rcmreceiver.id
    WHERE
        rcm.status = '$status'
    ORDER BY 
        rcm.id DESC
    ";
} else {
    $query = "
    SELECT
        rcm.id as rcmid,
        rcm.status as rcmstatus,
        rcm.*, 
        rcmbranch.*, 
        rcmreceiver.*, 
        rcmchannel.*
    FROM
        rcm
    LEFT JOIN
        rcmbranch ON rcm.branchid = rcmbranch.id
    LEFT JOIN
        rcmchannel ON rcm.channelid = rcmchannel.id
    LEFT JOIN
        rcmreceiver ON rcm.receiverid = rcmreceiver.id
    WHERE
        rcm.branchid = $branchid
        AND rcm.status = '$status'
    ORDER BY
        rcm.id DESC
    ";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            'ID' => $row['rcmid'],
            'branchname' => $row['name'],
            'sendername' => $row['sendername'],
            'fullname' => $row['custfirstname'] . ' ' . $row['custmiddlename'] . ' ' . $row['custlastname'],
            'firstname' => $row['custfirstname'],
            'middlename' => $row['custmiddlename'],
            'lastname' => $row['custlastname'],
            'transcode' => $row['transactioncode'],
            'receivername' => $row['receivername'],
            'receiverno' => $row['contactnumber'],
            'transby' => $row['transby'],
            'amount' => $row['transamount'],
            'channel' => $row['channel'],
            'date' => date('F j, Y h:i A', strtotime($row['transdatetime'])),
            'datereceived' => (is_null($row['datetimereceived'])) ? '' : date('F j, Y h:i A', strtotime($row['datetimereceived'])),
            'status' => $row['rcmstatus'],
            'filepath' => $row['filepath']
        );
    }
    echo json_encode(array('data' => $data));
    mysqli_close($conn);
} else {
    echo json_encode(array('data' => array()));
    mysqli_close($conn);
}