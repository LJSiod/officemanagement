<?php
error_reporting(0);
include '../../db.php';
require_once '../../vendor/autoload.php';
date_default_timezone_set('Asia/Manila');
$today = date('F j, Y');

$startdate = $_POST['start'] . " 00:00:00";
$enddate = $_POST['end'] . " 23:59:59";
$branch = $_POST['branch'];
$branchname = $_POST['branchname'] ?? 'All Branch';
$type = $_POST['type'];
$editor = $_POST['editor'] ?? 'All Editors';
$editorname = $_POST['editorname'];
$startformatted = new DateTime($startdate);
$endformatted = new DateTime($enddate);

// echo "start:" . $startdate . " end: " . $enddate . " branch: " . $branch . " channel: " . $channel . " receiver: " . $receiver;


$query = "
    SELECT
        t.*,
        CONCAT(p.FirstName, ' ', p.MiddleName, '. ', p.LastName) AS updatedby,
        t.id as TID,
        b.*
    FROM
        officemanagementdb.ticketinfo t
    LEFT JOIN
        officemanagementdb.branch b
    ON
        t.branchid = b.id
    LEFT JOIN
        payroll.employee p ON t.updatedby = p.ID
    WHERE t.status = 'COMPLETED'
    AND t.dateupdated BETWEEN '$startdate' AND '$enddate'
    " . ($branch != 'All Branch' ? "AND b.id = '$branch'" : "") . "
    " . ($type != 'All Types' ? "AND t.type = '$type'" : "") . "
    " . ($editor != 'All Editors' ? "AND t.updatedby = '$editor'" : "") . "
    ORDER BY t.id DESC
";
$result = $conn->query($query);
$data = [];

function sanitize($text)
{
    return preg_replace('/[^\P{C}\n\r\t]+/u', '', $text);
}

use PhpOffice\PhpWord\TemplateProcessor;

$template = new TemplateProcessor('../assets/templates/Editing_Template.docx');

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$resultnumber = count($data);
$template->cloneRow('branch', $resultnumber);

$template->setValue("date", $today);
$template->setValue("reportcoverage", $startformatted->format('F j, Y') . " to " . $endformatted->format('F j, Y'));
$template->setValue("selectedtype", $type);
$template->setValue("selectedbranch", $branchname);
$template->setValue("selectededitor", $editorname);

foreach ($data as $i => $row) {
    $n = $i + 1;

    $ticketid = str_pad($row['TID'], 4, '0', STR_PAD_LEFT);
    $template->setValue("ticketid#$n", sanitize($ticketid));
    $template->setValue("branch#$n", sanitize($row['name']));
    $template->setValue("type#$n", sanitize($row['type']));
    $template->setValue("concern#$n", sanitize($row['concern']));
    $template->setValue("reason#$n", sanitize($row['reason']));
    $template->setValue("requestedby#$n", sanitize($row['requestedby']));
    $template->setValue("borrower#$n", sanitize($row['borrower']));
    $template->setValue("editedby#$n", sanitize($row['updatedby']));
    $template->setValue("datecreated#$n", date('M j, Y', strtotime($row['datecreated'])));
    $attquery = "SELECT * FROM attachments WHERE ticketid = '" . $row['TID'] . "'";
    $attresult = $conn->query($attquery);
    $att = array();
    while ($attrow = $attresult->fetch_assoc()) {
        $att[] = $attrow['filepath'];
    }
    for ($imgIdx = 0; $imgIdx < 4; $imgIdx++) {
        $placeholder = "attachment{$imgIdx}#$n";
        if (isset($att[$imgIdx]) && file_exists($att[$imgIdx])) {
            $template->setImageValue($placeholder, [
                'path' => $att[$imgIdx],
                'width' => 275,
                'height' => 375
            ]);
        } else {
            $template->setValue($placeholder, '');
        }
    }
}

$template->saveAs('../temp/EditingReport.docx');
$docxFile = realpath('../temp/EditingReport.docx');
$pdfFile = realpath('../temp/') . '/' . 'EditingReport' . '.pdf';

// Convert to PDF using Libreoffice (Dapat may LibreOffice sa Server!!!)
$command = "soffice --headless --convert-to pdf --outdir " . escapeshellarg(dirname($pdfFile)) . " " . escapeshellarg($docxFile);

exec($command, $output, $returnCode);

// Return response
if ($returnCode === 0) {
    echo json_encode(array('success' => true, 'status' => 'Details generated successfully!', 'filename' => basename($pdfFile)));
    unlink($docxFile);
    mysqli_close($conn);
    exit;
} else {
    echo json_encode(array('success' => false, 'status' => 'There was an error generating the Details!'));
    mysqli_close($conn);
}

exit();