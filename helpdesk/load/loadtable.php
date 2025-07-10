<?php
error_reporting(0);
session_start();
include '../../db.php';

$branchid = $_SESSION['branchid'];

$query = "
    SELECT 
        t.*,
        t.id as TID,
        CONCAT(p.FirstName, ' ', p.MiddleName, '. ', p.LastName) AS updatedby,
        b.name AS branchname 
    FROM 
        officemanagementdb.ticketinfo t 
    LEFT JOIN 
        officemanagementdb.branch b ON t.branchid = b.ID
    LEFT JOIN
        payroll.employee p ON t.updatedby = p.ID
    WHERE 
        t.status = 'OPEN'
    ORDER BY 
        t.id ASC
";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $attquery = "SELECT * FROM attachments WHERE ticketid = '" . $row['TID'] . "'";
        $attresult = mysqli_query($conn, $attquery);
        $att = array();
        while ($attrow = mysqli_fetch_assoc($attresult)) {
            $att[] = array(
                'filepath' => $attrow['filepath']
            );
        }
        $updatedby = $row['updatedby'] ?? '';
        $data[] = array(
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
            'updatedby' => $updatedby,
            'datecreated' => $row['datecreated'],
            'dateupdated' => $row['dateupdated'],
            'status' => $row['status'],
            'attachments' => $att
        );
    }
    mysqli_close($conn);
    echo json_encode(array('data' => $data));
} else {
    echo json_encode(array('data' => array()));
}


