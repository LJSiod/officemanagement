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
    a.ID,
    b.Name AS BranchName,
    a.PreviousLoanAmount,
    a.AccountStatus,
    a.AccountAllocation,
    a.ApprovedLoanAmount,
    a.Borrower,
    DATE_FORMAT(a.DateAdded, '%b %d, %Y') AS DateAdded,
    a.ProposedLoanAmount,
    a.Status,
    a.Remarks

FROM
    ApprovalInfo AS a 
LEFT JOIN
    Branch AS b
ON
    a.BranchID = b.ID
WHERE
    a.DateAdded >= '$today'
ORDER BY a.ID DESC";

} else {

    $query = "SELECT 
    a.ID,
    b.Name AS BranchName,
    a.PreviousLoanAmount,
    a.AccountStatus,
    a.AccountAllocation,
    a.ApprovedLoanAmount,
    a.Borrower,
    DATE_FORMAT(a.DateAdded, '%b %d, %Y') AS DateAdded,
    a.ProposedLoanAmount,
    a.Status,
    a.Remarks

FROM
    ApprovalInfo AS a 
LEFT JOIN
    Branch AS b
ON
    a.BranchID = b.ID
WHERE
    a.DateAdded >= '$today'
AND a.BranchID = '$branchid'
ORDER BY a.ID DESC";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            'ID' => $row['ID'],
            'BranchName' => $row['BranchName'],
            'PreviousLoanAmount' => '<span class="label"> Previous: </span>' . number_format($row['PreviousLoanAmount'], 0),
            'AccountStatus' => '<span class="label">Account: </span>' . $row['AccountStatus'],
            'AccountAllocation' => '<span class="label">Allocation: </span>' . $row['AccountAllocation'],
            'ApprovedLoanAmount' => '<span class="label">Approved: </span>' . number_format($row['ApprovedLoanAmount'], 0),
            'Borrower' => '<span class="label">Name: </span>' . $row['Borrower'],
            'DateAdded' => $row['DateAdded'],
            'ProposedLoanAmount' => '<span class="label">Proposed: </span>' . number_format($row['ProposedLoanAmount'], 0),
            'Status' => $row['Status'],
            'Remarks' => '<span class="label">Remarks: </span>' . $row['Remarks']
        );
    }
    echo json_encode(array('data' => $data));
} else {
    echo json_encode(array('data' => array()));
}


