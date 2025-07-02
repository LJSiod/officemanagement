<?php
error_reporting(0);
include '../../db.php';
require '../../vendor/autoload.php';
$id = $_POST['id'];
// Fetch contract and tenant details
$query = "SELECT 
    a.ID AS ApprovalID,
    b.Name AS BranchName,
    a.PreviousLoanAmount,
    a.AccountStatus,
    a.AccountType,
    a.Cycle,
    a.AccountAllocation,
    a.Address,
    a.ApprovedLoanAmount,
    a.Borrower,
    DATE_FORMAT(a.DateAdded, '%b %d, %Y') AS DateAdded,
    a.FundSource,
    a.LoanPurpose,
    a.MonthlyIncome,
    a.NameofCI,
    a.ProposedLoanAmount,
    a.Remarks,
    a.RequirementsPassed,
    a.Status,
    a.Paid,
    a.Balance,
    l.front as front,
    l.back as back
FROM
    ApprovalInfo AS a 
JOIN
    Branch AS b
ON
    a.BranchID = b.ID
LEFT JOIN
    Ledger AS l ON a.ID = l.userID
WHERE
    a.ID = '$id'";
$result = $conn->query($query);
$row = $result->fetch_assoc();

// Initialize PHPWord
$phpWord = new \PhpOffice\PhpWord\PhpWord();
use PhpOffice\PhpWord\TemplateProcessor;


// Populate template
$template = new TemplateProcessor('../assets/templates/Details_Template.docx');
$template->setValue('acctype', strtoupper($row['AccountType']));
$template->setValue('branch', strtoupper($row['BranchName']));
$template->setValue('borrower', strtoupper($row['Borrower']));
$template->setValue('address', strtoupper($row['Address']));
$template->setValue('source', strtoupper($row['FundSource']));
$template->setValue('purpose', strtoupper($row['LoanPurpose']));
$template->setValue('req', strtoupper($row['RequirementsPassed']));
$template->setValue('ci', strtoupper($row['NameofCI']));
$template->setValue('cat', strtoupper($row['AccountAllocation']));
$template->setValue('monthlyincome', number_format($row['MonthlyIncome'], 2));
$template->setValue('proposed', number_format($row['ProposedLoanAmount'], 2));
$template->setValue('previous', number_format($row['PreviousLoanAmount'], 2));
$template->setValue('cycle', $row['Cycle']);
$template->setValue('count', $row['Paid']);
$template->setValue('balance', number_format($row['Balance'], 2));
$template->setValue('accstat', $row['AccountStatus']);
$template->setImageValue('frontledger', array(
    'path' => $row['front'] ?? '../assets/image/null.png',
    'width' => 620,
    'height' => 350,
    'ratio' => false,
));
$template->setImageValue('backledger', array(
    'path' => $row['back'] ?? '../assets/image/null.png',
    'width' => 620,
    'height' => 350,
    'ratio' => false,
));

// Save template
$template->saveAs('../printables/Approval_' . $row['BranchName'] . $row['ApprovalID'] . '.docx');
$docxFile = realpath('../printables/Approval_' . $row['BranchName'] . $row['ApprovalID'] . '.docx');
$pdfFile = realpath('../printables/') . '/Approval_' . $row['BranchName'] . $row['ApprovalID'] . '.pdf';

// Convert to PDF using Libreoffice (Libreoffice required in Server!)
$command = "soffice --headless --convert-to pdf --outdir " . escapeshellarg(dirname($pdfFile)) . " " . escapeshellarg($docxFile);

exec($command, $output, $returnCode);
unlink($docxFile);

// Return response
if ($returnCode === 0) {
    echo json_encode(array('success' => true, 'status' => 'Details generated successfully!', 'filename' => basename($pdfFile)));
    exit;
} else {
    echo json_encode(array('success' => false, 'status' => 'There was an error generating the Details!'));

}

exit();
