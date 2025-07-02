<?php
error_reporting(0);

$servername = "192.168.0.79";
$username = "root";
$password = "admin1234";
$dbname = "officemanagementdb";

// $servername = "192.168.0.103";
// $username = "root";
// $password = "neocashmarbel2020";
// $dbname = "approval";

// $servername = "124.106.144.31";
// $username = "root";
// $password = "neocashmarbel2020";
// $dbname = "officemanagementdb";

$conn = new mysqli($servername, $username, $password, $dbname);
?>