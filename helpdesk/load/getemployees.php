<?php
error_reporting(0);
session_start();
$servername = "124.106.144.31";
$username = "root";
$password = "neocashmarbel2020";
// $servername = "192.168.0.79";
// $username = "root";
// $password = "admin1234";
$dbname = "payroll";
$conn = new mysqli($servername, $username, $password, $dbname);

$positionid = $_SESSION['positionid'];
$payrollbranchid = $_SESSION['payrollbranchid'];

// Get the period from the session
$period = $_SESSION['period'];

$result = $conn->query(
    "SELECT 
        e.IDNumber,
        e.FirstName,
        e.MiddleName,
        e.LastName,
        CONCAT(e.FirstName, ' ', e.MiddleName, '. ', e.LastName) AS fullname,
        d.Name,
        IF(EXISTS (SELECT 1 FROM officemanagementdb.evaluationinfo as ei WHERE ei.period = '$period' AND CONCAT(ei.firstname, ' ', ei.middlename, '. ', ei.lastname) = CONCAT(e.FirstName, ' ', e.MiddleName, '. ', e.LastName) AND ei.status != 'CANCELLED'), 1, 0) AS evaluation_exists
    FROM employee e
    LEFT JOIN department AS d
        ON d.ID = e.DepartmentID
    WHERE BranchID = $payrollbranchid
    AND EmploymentStatus != 'RESIGNED'
    AND PositionID IN (2, 4, 5, 7)"
);

$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}
mysqli_close($conn);
header('Content-Type: application/json');
echo json_encode($employees);

