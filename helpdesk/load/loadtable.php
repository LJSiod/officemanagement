<?php
error_reporting(0);
session_start();
include '../../db.php';

$admin = !in_array($positionid, [5, 6, 7]);
$branchid = $_SESSION['branchid'];

if ($admin) {
    $query = "
    SELECT 
        t.*,
        t.id as TID,
        IFNULL(t.updatedby, '') AS updatedby,
        b.name AS branchname 
    FROM 
        ticketinfo t 
    LEFT JOIN 
        branch b ON t.branchid = b.ID
    WHERE 
        t.status != 'CANCELLED'
";

} else {

    $query = "
        SELECT 
            t.*, 
            t.id as TID,
            IFNULL(t.updatedby, '') AS updatedby,
            b.name AS branchname
        FROM 
            ticketinfo t 
        LEFT JOIN 
            branch b ON t.branchid = b.ID 
        WHERE 
            t.branchid = $branchid 
            AND t.status != 'CANCELLED'
    ";
}

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
        $data[] = array(
            'ID' => $row['TID'],
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
    echo json_encode(array('data' => $data));
} else {
    echo json_encode(array('data' => array()));
}


