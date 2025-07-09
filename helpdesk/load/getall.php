<?php
error_reporting(0);
require '../../db.php';
require '../../maindb.php';

$type = [];
$branch = [];
$editor = [];

$typeResult = $conn->query("SELECT * FROM tickettype");
while ($typeRow = $typeResult->fetch_assoc()) {
    $type[] = $typeRow;
}

$branchResult = $conn->query("SELECT * FROM branch");
while ($branchRow = $branchResult->fetch_assoc()) {
    $branch[] = $branchRow;
}

$editorResult = $connmain->query("SELECT id, CONCAT(FirstName, ' ', MiddleName, '. ', LastName) AS fullname FROM employee WHERE positionid IN (1, 2, 3, 13) AND employmentstatus != 'RESIGNED'");
while ($editorRow = $editorResult->fetch_assoc()) {
    $editor[] = $editorRow;
}

$data = [
    'type' => $type,
    'branch' => $branch,
    'editor' => $editor
];
mysqli_close($conn);
mysqli_close($connmain);

header('Content-Type: application/json');
echo json_encode($data);

