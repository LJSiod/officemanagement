<?php
error_reporting(0);
session_start();
include '../../db.php';

$positionid = $_SESSION['positionid'];
$admin = !in_array($positionid, [5, 6, 7]);
$branchid = $_SESSION['branchid'];
$date = new DateTimeImmutable();
$today = $date->format('Y-m-d');

if ($admin) {
    $query = "SELECT 
    e.*,
    b.name
FROM evaluationinfo e
LEFT JOIN branch b ON e.branchid = b.ID
WHERE e.status != 'CANCELLED'";

} else {

    $query = "SELECT 
    e.*,
    b.name
FROM evaluationinfo e
LEFT JOIN branch b ON e.branchid = b.ID
WHERE e.branchid = $branchid
AND e.status != 'CANCELLED'";
}

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $employeename = $row['firstname'] . ' ' . $row['middlename'] . '. ' . $row['lastname'];
        $data[] = array(
            'ID' => $row['id'],
            'BranchName' => $row['name'],
            'employeeName' => $employeename,
            'prID' => $row['prID'],
            'period' => $row['period'],
            'department' => $row['department'],
            'reviewdate' => date('F j, Y', strtotime($row['reviewdate'])),
            'status' => $row['status']
        );
    }
    mysqli_close($conn);
    echo json_encode(array('data' => $data));
} else {
    echo json_encode(array('data' => array()));
}


