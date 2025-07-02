<?php
error_reporting(0);
session_start();
date_default_timezone_set('Asia/Manila');
$date = date('Y-m-d H:i:s');
include '../../db.php';

$IDNumber = $_SESSION['IDNumber'];
$branchid = $_SESSION['branchid'];
$evaluator = $_SESSION['name'];
$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname = $_POST['lastname'];
$department = $_POST['department'];
$period = $_SESSION['period'];

$jobknowledge = $_POST['jobknowledge'];
$productivity = $_POST['productivity'];
$workquality = $_POST['workquality'];
$technicalskills = $_POST['technicalskills'];
$workconsistency = $_POST['workconsistency'];
$enthusiasm = $_POST['enthusiasm'];
$cooperation = $_POST['cooperation'];
$attitude = $_POST['attitude'];
$initiative = $_POST['initiative'];
$creativity = $_POST['creativity'];
$punctuality = $_POST['punctuality'];
$attendance = $_POST['attendance'];
$dependability = $_POST['dependability'];
$commskills = $_POST['commskills'];
$overall = $_POST['overall'];

$relObs = $_POST['relObs'];
$knoObs = $_POST['knoObs'];
$genObs = $_POST['genObs'];

$query = "INSERT INTO performancereview (jobKnowledge, productivity, workQuality, technicalSkills, workConsistency, enthusiasm, cooperation, attitude, initiative, creativity, punctuality, attendance, dependability, commSkills, overall, relObs, knoObs, genObs)
VALUES ($jobknowledge, $productivity, $workquality, $technicalskills, $workconsistency, $enthusiasm, $cooperation, $attitude, $initiative, $creativity, $punctuality, $attendance, $dependability, $commskills, $overall, '$relObs', '$knoObs', '$genObs')";
if (!mysqli_query($conn, $query)) {
    echo 'Error inserting into performancereview: ' . mysqli_error($conn);
    exit();
} else {
    $prID = mysqli_insert_id($conn);

    $query2 = "INSERT INTO evaluationinfo (branchid, evaluator, firstname, middlename, lastname, department, period, prID, reviewdate, status)
    VALUES ($branchid, '$evaluator', '$firstname', '$middlename', '$lastname', '$department', '$period', $prID, '$date', 'PENDING')";
    if (!mysqli_query($conn, $query2)) {
        echo 'Error inserting into evaluationinfo: ' . mysqli_error($conn);
        exit();
    }
    mysqli_close($conn);
    header("Location: ../views/dashboard.php");
}
