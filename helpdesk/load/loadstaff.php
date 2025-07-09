<?php
include '../../maindb.php';
header('Content-Type: application/json');

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
    $data[] = $row;
}
echo json_encode(['data' => $data]);