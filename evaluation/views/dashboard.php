<?php
session_start();
include '../../db.php';
include '../includes/header.php';
date_default_timezone_set('Asia/Manila');

$today = new DateTime();
$year = $today->format('Y');
$month = $today->format('n');
$day = $today->format('j');

if (($month === '1') || ($month === '2') || ($month === '3') || ($month === '4' && $day <= 5)) {
    $period = 'January to March ' . $year;
} elseif (($month === '4') || ($month === '5') || ($month === '6') || ($month === '7' && $day <= 5)) {
    $period = 'April to June ' . $year;
} elseif (($month === '7') || ($month === '8') || ($month === '9') || ($month === '10' && $day <= 5)) {
    $period = 'July to September ' . $year;
} elseif (($month === '10') || ($month === '11') || ($month === '12') || ($month === '1' && $day <= 5)) {
    $period = 'October to December ' . $year;
}

$_SESSION['period'] = $period;

$dateformat = date('F j, Y');
$id = $_SESSION['userid'];
$branchid = $_SESSION['branchid'];
$currentdate = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/dt/dt-2.2.2/sc-2.4.3/datatables.min.css" rel="stylesheet"
        integrity="sha384-4YCf35SCoNErxKb3uZGFlBfNxnFh2r1O1NaAO7wl6CIB2geJDtriZeLwca3usiAR" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="../assets/css/loader.css" rel="stylesheet">
    <title>NLI</title>
    <style>
        table {
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
        }

        .br-pagebody {
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
            max-width: 1360px;
        }

        .br-section-wrapper {
            border-radius: 3px;
            padding: 20px;
            max-height: 89vh;
            height: auto;
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
            opacity: 95%;
        }

        .btn {
            border-radius: 50px;
        }

        .smol {
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .approved {
            background-color: #86de86 !important;
            color: black !important
        }

        .pending {
            background-color: #efdfae !important;
            color: black !important
        }

        .rejected {
            background-color: #ebbab9 !important;
            color: black !important
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="br-pagebody">
            <div class="br-section-wrapper mt-3 col" id="wrapper">
                <div class="d-flex">
                    <h6 class="text-uppercase fw-bold me-auto" style="font-family: Raleway, sans-serif">
                        Evaluation Records</h6>
                    <span class="text-uppercase fw-bold small"><?php echo $dateformat; ?></span>
                </div>
                <table id="evaluationtable" class="table table-hover table-responsive-sm align-middle">
                    <thead class="border" title="Click to Sort">
                        <?php if ($admin) { ?>
                            <tr>
                                <th>BRANCH</th>
                                <th>EMPLOYEE</th>
                                <th>DEPARTMENT</th>
                                <th>PERIOD</th>
                                <th>REVIEW DATE</th>
                                <th>STATUS</th>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <th>EMPLOYEE</th>
                                <th>DEPARTMENT</th>
                                <th>PERIOD</th>
                                <th>REVIEW DATE</th>
                                <th>STATUS</th>
                            </tr>
                        <?php } ?>
                    </thead>
                    <tbody title="Right Click to Open Menu" style="font-size: 0.9rem;">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/v/dt/dt-2.2.2/sc-2.4.3/datatables.min.js"
        integrity="sha384-1zOgQnerHMsipDKtinJHWvxGKD9pY4KrEMQ4zNgZ946DseuYh0asCewEBafsiuEt"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#evaluationtable').DataTable({
                ajax: {
                    url: '../load/loadevaluation.php',
                    method: 'GET',
                    dataSrc: 'data'
                },
                layout: {
                    topStart: false,
                    bottomEnd: false
                },
                pageLength: 12,
                order: false,
                <?php if ($admin) { ?>columns: [
                        { data: 'BranchName', render: function (data, type, row) { return "<span class='label'>Branch: </span><b class='text-uppercase'>" + data + "</b>"; } },
                        { data: 'employeeName', render: function (data, type, row) { return "<span class='label'>Employee: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'department', render: function (data, type, row) { return "<span class='label'>Department: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'period', render: function (data, type, row) { return "<span class='label'>Period: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'reviewdate', render: function (data, type, row) { return "<span class='label'>Review Date: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        {
                            data: 'status', render: function (data, type, row) {
                                if (data == "PENDING") {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-warning text-dark'>" + data + "</span>";
                                } else {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-success'>" + data + "</span>";
                                }
                            }
                        }
                    ],

                <?php } else { ?>columns: [
                        { data: 'employeeName', render: function (data, type, row) { return "<span class='label'>Employee: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'department', render: function (data, type, row) { return "<span class='label'>Department: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'period', render: function (data, type, row) { return "<span class='label'>Period: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'reviewdate', render: function (data, type, row) { return "<span class='label'>Review Date: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        {
                            data: 'status', render: function (data, type, row) {
                                if (data == "PENDING") {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-warning text-dark'>" + data + "</span>";
                                } else {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-success'>" + data + "</span>";
                                }
                            }
                        }
                    ],
                <?php } ?>

                deferRender: true,
                scrollY: '64vh', //67vh
                scroller: true,
                drawCallback: function () {
                    $('.dts_label').hide();
                    $('.dt-scroll-body').css('background-image', 'none');
                    $('.dt-layout-start').css({
                        'font-size': '13px',
                        'font-weight': 'bold',
                        'font-family': 'Raleway, sans-serif'
                    });
                },
            });

            $(document).on('contextmenu', function (e) {
                e.preventDefault();
            });

            $(document).on('contextmenu', '#evaluationtable tbody tr', function (e) {
                e.preventDefault();
                $('.removedrop').remove();
                var rowData = table.row($(this).closest('tr')).data();
                var ID = rowData.ID;
                var status = rowData.status;
                <?php if ($admin) { ?>
                    var menu = $('<div class="dropdown-menu small removedrop" id="actiondropdown" style="display:block; position:absolute; z-index:1000;">'
                        + '<a class="dropdown-item small" href="evaluate.php?id=' + ID + '" id="preview"><i class="fa fa-eye text-success" aria-hidden="true"></i> <span>Preview</span></a>'
                        + '<a class="dropdown-item small" data-id="' + ID + '" href="#" id="generate"><i class="fa fa-file-text-o text-success" aria-hidden="true"></i> <span>Generate Evaluation Form</span></a>'
                        + (status == 'PENDING' ? '<a class="dropdown-item small" data-id="' + ID + '" href="#" id="receive"><i class="fa fa-check-circle-o text-success" aria-hidden="true"></i> <span>Receive</span></a>'
                            : '<a class="dropdown-item small disabled" data-id="' + ID + '" href="#" id="receive"><i class="fa fa-check-circle-o text-secondary" aria-hidden="true"></i> <span>Receive</span></a>')
                        + '</div>').appendTo('body');
                <?php } else { ?>
                    var menu = $('<div class="dropdown-menu small removedrop" id="actiondropdown" style="display:block; position:absolute; z-index:1000;">'
                        + '<a class="dropdown-item small" href="evaluate.php?id=' + ID + '" id="preview"><i class="fa fa-eye text-success" aria-hidden="true"></i> <span>Preview</span></a>'
                        + (status == 'PENDING' ? '<a class="dropdown-item small" data-id="' + ID + '" href="#" id="cancel"><i class="fa fa-trash-o text-danger" aria-hidden="true"></i> <span>Cancel</span></a>'
                            : '<a class="dropdown-item small disabled" data-id="' + ID + '" href="#" id="cancel"><i class="fa fa-trash-o text-secondary" aria-hidden="true"></i> <span>Cancel</span></a>')
                        + '</div>').appendTo('body');
                <?php } ?>
                menu.css({ top: e.pageY + 'px', left: e.pageX + 'px' });

                $(document).on('click', function () {
                    menu.remove();
                });

                $(document).off('click', '#cancel').on('click', '#cancel', function (e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Cancel?',
                        text: "Are you sure you want to cancel evaluation?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, cancel it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = '../actions/actions.php?id=' + id + '&action=cancel';
                        }
                    });
                });

                $(document).off('click', '#receive').on('click', '#receive', function (e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Receive?',
                        text: "Receive Evaluation?",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Receive!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = '../actions/actions.php?id=' + id + '&action=receive';
                        }
                    });
                });

                $(document).off('click', '#generate').on('click', '#generate', function (e) {
                    e.preventDefault();
                    var overlay = $('<div class="overlay text-center d-flex justify-content-center align-items-center" style="position: fixed; width: 100vw; height: 100vh; z-index: 9000; background-color: rgba(0, 0, 0, 0.3); top: 0; left: 0; display: none"><div class="loader JS_on"><span class="binary"></span><span class="binary"></span><span class="getting-there">GENERATING FORM...</span></div></div>').appendTo('body');
                    var id = $(this).data('id');
                    $.ajax({
                        url: '../load/generate.php',
                        type: 'POST',
                        data: {
                            id: id,
                        },
                        success: function (result) {
                            var result = JSON.parse(result);
                            overlay.remove();
                            if (result.success == true) {
                                var url = 'details.php?filename=' + result.filename;
                                var a = document.createElement("a");
                                // a.target = "_blank";
                                a.href = url;
                                a.click();
                                // location.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: result.status,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function () {
                                    location.reload();
                                });
                            }
                        }
                    });
                });
            });
        });
        setInterval(() => {
            table.ajax.reload(null, false);
        }, 600000);
    </script>
</body>

</html>