<?php
error_reporting(0);
session_start();
include '../../db.php';

$positionid = $_SESSION['positionid'];
$admin = in_array($positionid, [1, 2, 3, 13]);
$branchid = $_SESSION['branchid'];
$id = $_POST['id'];

$query = "
    SELECT 
        t.*,
        t.id as TID,
        b.name AS branchname ,
        CONCAT(p.FirstName, ' ', p.MiddleName, '. ', p.LastName) AS updatedby
    FROM 
        officemanagementdb.ticketinfo t 
    LEFT JOIN 
        officemanagementdb.branch b ON t.branchid = b.ID
    LEFT JOIN
        payroll.employee p ON t.updatedby = p.ID
    WHERE 
        t.id = '$id'
";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $attquery = "SELECT * FROM attachments WHERE ticketid = '" . $row['TID'] . "'";
        $attresult = mysqli_query($conn, $attquery);
        $att = array();
        while ($attrow = mysqli_fetch_assoc($attresult)) {
            $att[] = array(
                'filepath' => $attrow['filepath']
            );
        }
        $data = array(
            'ID' => $row['TID'],
            'branchid' => $row['branchid'],
            'branchname' => $row['branchname'],
            'type' => $row['type'],
            'concern' => $row['concern'],
            'sourceofdoc' => $row['sourceofdoc'],
            'reason' => $row['reason'],
            'borrower' => $row['borrower'],
            'requestedby' => $row['requestedby'],
            'position' => $row['position'],
            'approvedby' => $row['approvedby'],
            'updatedby' => $row['updatedby'],
            'datecreated' => $row['datecreated'],
            'dateupdated' => $row['dateupdated'],
            'status' => $row['status'],
            'attachments' => $att
        );
    }
    mysqli_close($conn);
    echo json_encode($data);
} else {
    echo json_encode(array('data' => array()));
}


