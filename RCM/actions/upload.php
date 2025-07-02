<?php
error_reporting(0);
$uploadDir = '../uploads/';
$filename = substr(uniqid(), 0, 3) . '_' . basename($_FILES['proof']['name']);
$targetFile = $uploadDir . $filename;
if (move_uploaded_file($_FILES['proof']['tmp_name'], $targetFile)) {
    echo json_encode(['success' => true, 'filepath' => '../uploads/' . $filename]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
}