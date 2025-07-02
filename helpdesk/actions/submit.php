<?php
error_reporting(0);
session_start();
date_default_timezone_set('Asia/Manila');
$date = date('Y-m-d H:i:s');
include '../../db.php';
$filecount = count($_FILES);
$uploadDir = '../uploads/';

$branchid = $_SESSION['branchid'];
$type = $_POST['type'];
$concern = $_POST['concern'];
$source = $_POST['source'];
$borrower = $_POST['borrower'];
$reason = $_POST['reason'];
$requestedby = $_SESSION['name'];
$position = $_SESSION['position'];
$approvedby = $_POST['approvedby'];

$query = "INSERT INTO ticketinfo (branchid, type, concern, sourceofdoc, reason, borrower, requestedby, position, approvedby)
VALUES ($branchid, '$type', '$concern', '$source', '$reason', '$borrower', '$requestedby', '$position', '$approvedby')";
if (!mysqli_query($conn, $query)) {
    echo json_encode(["error" => mysqli_error($conn), "query" => $query]);
    exit();
} else {
    $ticketID = mysqli_insert_id($conn);

    for ($i = 1; $i <= $filecount; $i++) {
        $fileTmp = $_FILES["file$i"]['tmp_name'];
        $fileName = $_FILES["file$i"]['name'];
        $filePath = $uploadDir . substr(str_shuffle(MD5(microtime())), 0, 4) . '_' . $fileName; //unique filename to avoid conflict

        if (move_uploaded_file($fileTmp, $filePath)) {
            $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            //convert pdf to png for easier manipulation and system efficiency
            if ($fileExtension === 'pdf') {
                $outputFile = substr($filePath, 0, strrpos($filePath, '.')) . '.png';
                $command = "gswin64c -dNOPAUSE -dBATCH -sDEVICE=png16m -r100 -sOutputFile=" . escapeshellarg($outputFile) . " " . escapeshellarg($filePath);
                exec($command, $output, $returnCode);

                if ($returnCode === 0) {
                    $reqquery = "INSERT INTO attachments (ticketid, filepath) VALUES ('$ticketID', '$outputFile')";
                    unlink($filePath);
                    if (!mysqli_query($conn, $reqquery)) {
                        echo 'Error inserting file record: ' . mysqli_error($conn);
                    }
                } else {
                    echo "Error converting PDF to PNG.";
                }
                //direct insert to db when file type is an image
            } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $reqquery = "INSERT INTO attachments (ticketid, filepath) VALUES ('$ticketID', '$filePath')";
                if (!mysqli_query($conn, $reqquery)) {
                    echo 'Error inserting file record: ' . mysqli_error($conn);
                }
            } else {
                echo "Unsupported file type.";
            }
        }
    }
    mysqli_close($conn);
    echo json_encode(["success" => true, "message" => "Files uploaded and processed successfully."]);
}
