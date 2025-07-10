<?php
include '../../maindb.php';
header('Content-Type: application/json');

$notefile = '../temp/note.txt';
$lines = file($notefile, FILE_IGNORE_NEW_LINES);
$notes = [];
foreach ($lines as $line) {
    list($userid, $note) = explode(':', $line);
    $notes[trim($userid)] = trim($note);
}

$sql = "
    SELECT
    id,
        CONCAT(FirstName, ' ', MiddleName, '. ', LastName) AS fullname,
        activeStat
    FROM employee 
    WHERE employmentstatus != 'RESIGNED'
        AND positionid IN (1, 2, 3, 13)";
$result = $connmain->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $row['note'] = isset($notes[$row['id']]) ? $notes[$row['id']] : '';
    $data[] = $row;
}
echo json_encode(['data' => $data]);