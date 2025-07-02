<?php
error_reporting(0);
include '../../db.php';
$countApproved = "SELECT COUNT(ID) AS count FROM approvalinfo WHERE status = 'APPROVED'";
$result = $conn->query($countApproved);
$count = $result->fetch_assoc();
echo 'Number of Acct. Approved:' . ' ' . $count['count'];
