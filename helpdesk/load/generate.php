<?php
error_reporting(0);
include '../../db.php';
require '../../vendor/autoload.php';
$id = $_POST['id'];
// Fetch contract and tenant details
$query = "SELECT 
    e.*,
    pr.*
FROM evaluationinfo e
LEFT JOIN performancereview pr ON e.prID = pr.id
WHERE e.id = '$id'";
$result = $conn->query($query);
$row = $result->fetch_assoc();

// Initialize PHPWord
$phpWord = new \PhpOffice\PhpWord\PhpWord();
use PhpOffice\PhpWord\TemplateProcessor;

$fullname = $row['firstname'] . ' ' . ($row['middlename'] ? $row['middlename'] . '. ' : '') . $row['lastname'];

// Populate template
$template = new TemplateProcessor('../assets/templates/Evaluation_Template.docx');
$template->setValue('employeename', strtoupper($fullname));
$template->setValue('department', strtoupper($row['department']));
$template->setValue('period', $row['period']);
$template->setValue('reviewdate', date('F j, Y', strtotime($row['reviewdate'])));
$template->setValue('jobknowledge', $row['jobKnowledge']);
$template->setValue('productivity', $row['productivity']);
$template->setValue('workqual', $row['workQuality']);
$template->setValue('techskills', $row['technicalSkills']);
$template->setValue('workcons', $row['workConsistency']);
$template->setValue('enthusiasm', $row['enthusiasm']);
$template->setValue('cooperation', $row['cooperation']);
$template->setValue('attitude', $row['attitude']);
$template->setValue('initiative', $row['initiative']);
$template->setValue('creativity', $row['creativity']);
$template->setValue('punctuality', $row['punctuality']);
$template->setValue('attendance', $row['attendance']);
$template->setValue('dependability', $row['dependability']);
$template->setValue('commskills', $row['commSkills']);
$template->setValue('overall', number_format($row['overall'], 1));
$template->setValue('relObs', $row['relObs']);
$template->setValue('knoObs', $row['knoObs']);
$template->setValue('genObs', $row['genObs']);
$template->setValue('evaluator', $row['evaluator']);
$template->setValue('hrmanager', 'FLOREMAE A. DEMETAIS');
$template->setValue('datetime', $row['datetime']);

// Save template
$template->saveAs('../printables/' . strtoupper($row['lastname']) . '_EVALUATION' . '.docx');
$docxFile = realpath('../printables/' . strtoupper($row['lastname']) . '_EVALUATION' . '.docx');
$pdfFile = realpath('../printables/') . '/' . strtoupper($row['lastname']) . '_EVALUATION' . '.pdf';

// Convert to PDF using Libreoffice (Kinanglan ang LibreOffice sa Server!!!)
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
mysqli_close($conn);
exit();
