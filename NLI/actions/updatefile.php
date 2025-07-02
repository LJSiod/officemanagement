<?php
error_reporting(0);
session_start();
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $fileId = $_POST['fileId']; // ID of the file to update
    $uploadDir = '../uploads/'; // Directory to store uploaded files

    // Check if a file is uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $filePath = $uploadDir . substr(str_shuffle(MD5(microtime())), 0, 4) . '_' . $fileName; //unique filename to avoid conflict

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($fileTmp, $filePath)) {
            $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            // Convert PDF to PNG if the uploaded file is a PDF
            if ($fileExtension === 'pdf') {
                $outputFile = substr($filePath, 0, strrpos($filePath, '.')) . '.png';
                $command = "gswin64c -dNOPAUSE -dBATCH -sDEVICE=png16m -r150 -sOutputFile=" . escapeshellarg($outputFile) . " " . escapeshellarg($filePath);
                exec($command, $output, $returnCode);

                if ($returnCode === 0) {
                    unlink($filePath); // Remove the original PDF file
                    $filePath = $outputFile; // Update the file path to the PNG file
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to convert PDF to PNG.']);
                    exit;
                }
            }

            // Update the file path in the database
            if ($type === 'frontledger') {
                $query = "UPDATE ledger SET front = '$filePath' WHERE ID = '$fileId'";
            } else if ($type === 'backledger') {
                $query = "UPDATE ledger SET back = '$filePath' WHERE ID = '$fileId'";
            } else {
                $query = "UPDATE requirements SET File = '$filePath' WHERE ID = '$fileId'";
            }
            if (mysqli_query($conn, $query)) {
                mysqli_close($conn);
                echo json_encode(['success' => true, 'filePath' => $filePath]);
            } else {
                echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error.']);
    }
}