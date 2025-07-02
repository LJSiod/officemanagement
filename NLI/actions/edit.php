<?php
error_reporting(0);
session_start();
$branchid = $_SESSION['branchid'];
include '../../db.php';
if (isset($_POST['save'])) {
    $id = $_POST['rowids'];

    $compname = getenv('COMPUTERNAME');

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];
    }

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $previousloanamount = mysqli_real_escape_string($conn, $_POST['prevs']);
    $proposedloanamount = mysqli_real_escape_string($conn, $_POST['proposed']);
    $approvedloanamount = mysqli_real_escape_string($conn, $_POST['approved']);
    $requirmentpassed = mysqli_real_escape_string($conn, $_POST['requirement']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $fundsource = mysqli_real_escape_string($conn, $_POST['sourceoffund']);
    $monthlyincome = mysqli_real_escape_string($conn, $_POST['monthlyincome']);
    $loanpurpose = mysqli_real_escape_string($conn, $_POST['purposeofloan']);
    $ci = mysqli_real_escape_string($conn, $_POST['ci']);
    $accountallocation = mysqli_real_escape_string($conn, $_POST['allocation']);
    $stat = mysqli_real_escape_string($conn, $_POST['status']);
    $remark = mysqli_real_escape_string($conn, $_POST['remark']);
    $accstat = mysqli_real_escape_string($conn, $_POST['accountstatus']);
    $cycle = mysqli_real_escape_string($conn, $_POST['cycle']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $paid = mysqli_real_escape_string($conn, $_POST['paid']);
    $balance = mysqli_real_escape_string($conn, $_POST['balance']);

    $query = "
        UPDATE `ApprovalInfo` 
        SET 
            `Borrower` = '$name',
            `Address` = '$address',
            `FundSource` = '$fundsource',
            `MonthlyIncome` = '$monthlyincome',
            `LoanPurpose` = '$loanpurpose',
            `PreviousLoanAmount` = '$previousloanamount',
            `ProposedLoanAmount` = '$proposedloanamount',
            `ApprovedLoanAmount` = '$approvedloanamount',
            `RequirementsPassed` = '$requirmentpassed',
            `NameofCI` = '$ci',
            `AccountAllocation` = '$accountallocation',
            `Status` = '$stat',
            `Remarks` = '$remark',
            `AccountStatus` = '$accstat',
            `Cycle` = '$cycle',
            `AccountType` = '$type',
            `Paid` = '$paid',
            `Balance` = '$balance',
            `approvebycomp` = '$compname' 
        WHERE 
            `ID` = '$id'
    ";

    mysqli_query($conn, $query);
    mysqli_close($conn);
    header("Location: ../views/dashboard.php");
}
?>