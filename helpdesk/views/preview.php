<?php
session_start();
include '../../db.php';
include '../includes/header.php';
date_default_timezone_set('Asia/Manila');

$dateformat = date('F j, Y');
$currentdate = date('Y-m-d');

$query = "SELECT 
    t.*,
    t.id as TID,
    IFNULL(t.updatedby, '') AS updatedby,
    b.name AS branchname 
FROM 
    ticketinfo t 
LEFT JOIN 
    branch b ON t.branchid = b.ID
WHERE 
    t.id = " . $_GET['id'];
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <style>
        .images {
            width: 120px;
            height: 120px;
            max-width: 120px;
            max-height: 120px;
            border: 1px solid #595757;
            margin: 2px;
        }

        .br-pagebody {
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
            max-width: 600px;
        }

        .br-section-wrapper {
            border-radius: 3px;
            padding: 20px;
            max-height: 89vh;
            height: 89vh;
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
            opacity: 95%;
        }

        .btn {
            border-radius: 50px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="br-pagebody">
            <div class="br-section-wrapper mt-3 col" id="wrapper">
                <div>
                    <h5>Ticket Information</h5>
                    <hr>
                    <div>
                        <div class="d-flex justify-content-between"><b>Ticket No.:</b> <span><?= $row['TID'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Branch:</b>
                            <span><?= $row['branchname'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Type:</b> <span><?= $row['type'] ?></span></div>
                        <div class="d-flex justify-content-between"><b>Concern:</b> <span><?= $row['concern'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Source of Documents:</b>
                            <span><?= $row['sourceofdoc'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Reason:</b> <span><?= $row['reason'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Borrower:</b>
                            <span><?= $row['borrower'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Requested by:</b>
                            <span><?= $row['requestedby'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Position:</b>
                            <span><?= $row['position'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Approved by:</b>
                            <span><?= $row['approvedby'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Addressed by:</b>
                            <span><?= $row['updatedby'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Date Created:</b>
                            <span><?= $row['datecreated'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Date Updated:</b>
                            <span><?= $row['dateupdated'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Status:</b> <span><?= $row['status'] ?></span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h5>Files Attached</h5>
                        <hr>
                        <div class="row">
                            <div class="col" id="attachments">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
            crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
        <script>

        </script>
</body>

</html>