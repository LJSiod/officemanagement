<?php
error_reporting(0);
include '../db.php';
require_once '../../vendor/autoload.php';
date_default_timezone_set('Asia/Manila');
$today = date('F j, Y');

$startdate = $_POST['start'] . " 00:00:00";
$enddate = $_POST['end'] . " 23:59:59";
$branch = $_POST['branch'];
$channel = $_POST['channel'];
$receiver = $_POST['receiver'];
$startformatted = new DateTime($startdate);
$endformatted = new DateTime($enddate);

// echo "start:" . $startdate . " end: " . $enddate . " branch: " . $branch . " channel: " . $channel . " receiver: " . $receiver;


$query = "
    SELECT
        rcmbranch.name,
        rcmchannel.channel,
        rcm.transactioncode, 
        rcm.sendername,
        CONCAT(custfirstname, ' ', custmiddlename, ' ', custlastname) AS fullname,
        rcm.transamount,
        rcm.datetimereceived,
        rcmreceiver.receivername,
        rcm.transby
    FROM
        rcm
    LEFT JOIN
        rcmbranch
    ON
        rcm.branchid = rcmbranch.id
    LEFT JOIN
        rcmchannel
    ON
        rcm.channelid = rcmchannel.id
    LEFT JOIN
        rcmreceiver
    ON
        rcm.receiverid = rcmreceiver.id
    WHERE rcm.status = 'ENCODED'
    AND rcm.datetimereceived BETWEEN '$startdate' AND '$enddate'
    " . ($branch != 'All Branch' ? "AND rcmbranch.name = '$branch'" : "") . "
    " . ($channel != 'All Channels' ? "AND rcmchannel.channel = '$channel'" : "") . "
    " . ($receiver != 'All Receivers' ? "AND rcmreceiver.receivername = '$receiver'" : "") . "
    ORDER BY rcm.id DESC
";
$result = $conn->query($query);
$data = [];

function sanitize($text)
{
    return preg_replace('/[^\P{C}\n\r\t]+/u', '', $text);
}

use PhpOffice\PhpWord\TemplateProcessor;

$template = new TemplateProcessor('../assets/templates/RCM_Template.docx');

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$totalamount = array_sum(array_column($data, 'transamount'));
$resultnumber = count($data);
$template->cloneRow('branch', $resultnumber);
$resultnumber = count($data);

$template->setValue("date", $today);
$template->setValue("reportcoverage", $startformatted->format('F j, Y') . " to " . $endformatted->format('F j, Y'));
$template->setValue("selectedchannel", $channel);
$template->setValue("selectedbranch", $branch);
$template->setValue("selectedreceiver", $receiver);

foreach ($data as $i => $row) {
    $n = $i + 1;
    $template->setValue("branch#$n", sanitize($row['name']));
    $template->setValue("channel#$n", sanitize($row['channel']));
    $template->setValue("ref#$n", sanitize($row['transactioncode']));
    $template->setValue("sender#$n", sanitize($row['sendername']));
    $template->setValue("customer#$n", sanitize($row['fullname']));
    $template->setValue("amount#$n", number_format($row['transamount'], 2));
    $template->setValue("datereceived#$n", date('M j, Y h:i A', strtotime($row['datetimereceived'])));
    $template->setValue("receiver#$n", sanitize($row['receivername']));
    $template->setValue("sentby#$n", sanitize($row['transby']));
}
$template->setValue("rcmcount", $resultnumber);
$template->setValue("totalamount", number_format($totalamount, 2));

$template->saveAs('../temp/RCMreport.docx');
$docxFile = realpath('../temp/RCMreport.docx');
$pdfFile = realpath('../temp/') . '/' . 'RCMreport' . '.pdf';

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