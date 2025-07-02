<?php
error_reporting(0);
session_start();
include '../../db.php';
$positionid = $_SESSION['positionid'];
$admin = !in_array($positionid, [5, 6, 7]);
$branchid = $_SESSION['branchid'];
$date = date('Y-m-d');

if ($admin) {
    $gettotal = $conn->query("SELECT
SUM(CASE WHEN b.`AccountAllocation` = 'Neo' THEN CAST(b.`ApprovedLoanAmount` AS DECIMAL(18,2)) ELSE 0 END) AS TotalNeo, 
SUM(CASE WHEN b.`AccountAllocation` = 'Gen' THEN CAST(b.`ApprovedLoanAmount` AS DECIMAL(18,2)) ELSE 0 END) AS TotalGen,
SUM(CAST(b.`ApprovedLoanAmount` AS DECIMAL(18,2))) AS Total FROM ApprovalInfo AS b WHERE b.`Status` = 'APPROVED' AND b.DateAdded = '$date'") or die($conn->error);
    $row = $gettotal->fetch_array();
} else {
    $gettotal = $conn->query("SELECT
SUM(CASE WHEN b.`AccountAllocation` = 'Neo' THEN CAST(b.`ApprovedLoanAmount` AS DECIMAL(18,2)) ELSE 0 END) AS TotalNeo, 
SUM(CASE WHEN b.`AccountAllocation` = 'Gen' THEN CAST(b.`ApprovedLoanAmount` AS DECIMAL(18,2)) ELSE 0 END) AS TotalGen,
SUM(CAST(b.`ApprovedLoanAmount` AS DECIMAL(18,2))) AS Total FROM ApprovalInfo AS b WHERE b.`Status` = 'APPROVED' AND b.BranchID = '$branchid' AND b.DateAdded = '$date'") or die($conn->error);
    $row = $gettotal->fetch_array();
}
?>
<h6 class="text-center font-weight-bold small teal">NEO RELEASE</h6>
<h4 class="text-center"><b>₱<?php echo number_format($row['TotalNeo'], 2); ?></b></h4>
<h6 class="text-center font-weight-bold small teal">GEN RELEASE</h6>
<h4 class="text-center"><b>₱<?php echo number_format($row['TotalGen'], 2); ?></b></h4>
<h6 class="text-center font-weight-bold small teal">TOTAL RELEASE</h6>
<h4 class="text-center"><b>₱<?php echo number_format($row['Total'], 2); ?></b></h4>