<?php
error_reporting(0);
session_start();
include('../db.php');

$branchid = $_SESSION['branchid'];
$positionid = $_SESSION['positionid'];
date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d');


$query = "
    SELECT
        rcm.id as rcmid,
        rcm.status as rcmstatus,
        rcm.*, 
        rcmbranch.*,
        rcmreceiver.id as rcmreceiverid,
        rcmreceiver.*,
        rcmchannel.*
    FROM
        rcm
    LEFT JOIN
        rcmbranch
    ON
        rcm.branchid = rcmbranch.id
    LEFT JOIN
        rcmchannel
    ON
        rcm.channelid = rcmchannel.id
    LEFT JOIN
        rcmreceiver
    ON
        rcm.receiverid = rcmreceiver.id
    WHERE
        rcm.branchid = $branchid
    AND
        rcm.status != 'CANCELLED'
    ORDER BY
        rcm.id DESC
    ";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            'ID' => $row['rcmid'],
            'branchname' => $row['name'],
            'fullname' => $row['custfirstname'] . ' ' . $row['custmiddlename'] . ' ' . $row['custlastname'],
            'firstname' => $row['custfirstname'],
            'middlename' => $row['custmiddlename'],
            'lastname' => $row['custlastname'],
            'sendername' => $row['sendername'],
            'transcode' => $row['transactioncode'],
            'receiverid' => $row['rcmreceiverid'],
            'receivername' => $row['receivername'],
            'receiverno' => $row['contactnumber'],
            'amount' => $row['transamount'],
            'channelid' => $row['channelid'],
            'channel' => $row['channel'],
            'transby' => $row['transby'],
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


