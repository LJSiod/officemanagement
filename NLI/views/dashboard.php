<?php
session_start();
include '../../db.php';
include '../includes/header.php';
date_default_timezone_set('Asia/Manila');

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
            max-height: 91vh;
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
                        Borrowers Information</h6>
                    <span class="text-uppercase fw-bold small"><?php echo $dateformat; ?></span>
                </div>
                <div class="d-flex" style="margin-bottom: -15px">
                    <p class="me-auto text-muted border p-1" id="legend">
                        <i class="fa fa-square" style="color: #86de86"></i><span class="small fw-bold"
                            style="font-size: 0.7rem;">
                            :Approved</span>
                        <i class="fa fa-square ms-3" style="color: #ebbab9"></i><span class="small fw-bold"
                            style="font-size: 0.7rem;">
                            :Disapproved</span>
                        <i class="fa fa-square ms-3" style="color: #efdfae"></i><span class="small fw-bold"
                            style="font-size: 0.7rem;">
                            :Pending</span>
                    </p>
                    <?php if ($admin) { ?>
                        <span class="text-uppercase text-muted small" id="counter"></span>
                    <?php } ?>
                </div>
                <table style="font-size: 0.8rem;" id="approvaltable"
                    class="table table-hover table-responsive-sm align-middle">
                    <thead class="border" title="Click to Sort">
                        <tr>
                            <?php if ($admin) { ?>
                                <th>BRANCH</th>
                                <th>NAME</th>
                                <th>PREVIOUS</th>
                                <th>PROPOSED</th>
                                <th>APPROVED</th>
                                <th>ACCOUNT</th>
                                <th>ALLOCATION</th>
                                <th>REMARKS</th>
                                <th>ACTIONS</th>
                            <?php } else { ?>
                                <th>NAME</th>
                                <th>PREVIOUS</th>
                                <th>PROPOSED</th>
                                <th>APPROVED</th>
                                <th>ACCOUNT</th>
                                <th>REMARKS</th>
                                <th>ACTIONS</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
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
            // var scrollY = (window.innerWidth === 1366 && window.innerHeight === 768) ? '59vh' : '67vh';
            // var scrollY = (screen.width === 1366 && screen.height === 768) ? '59vh' : '67vh';
            var table = $('#approvaltable').DataTable({
                ajax: {
                    url: '../load/loadapproval.php',
                    method: 'GET',
                    dataSrc: 'data'
                },
                layout: {
                    topStart: false,
                    topEnd: false,
                    bottomEnd: false
                },
                pageLength: 12,
                rowCallback: function (row, data, index) {
                    if (data.Status === 'APPROVED') {
                        $('td', row).addClass('approved');
                    } else if (data.Status === 'REJECTED') {
                        $('td', row).addClass('rejected');
                    } else {
                        $('td', row).addClass('pending');
                    }
                },
                order: false,
                <?php if ($admin) { ?>columns: [
                        { data: 'BranchName', render: function (data, type, row) { return "<b class='text-uppercase'>" + data + "</b>"; } },
                        { data: "Borrower" },
                        { data: 'PreviousLoanAmount' },
                        { data: 'ProposedLoanAmount' },
                        { data: 'ApprovedLoanAmount' },
                        { data: 'AccountStatus' },
                        { data: 'AccountAllocation' },
                        { data: 'Remarks' },
                        {
                            "data": null,
                            "render": function (data, type, row) {
                                return "<div class='btn-group btn-group-sm' role='group' aria-label='Basic example'><a href='preview.php?id=" + data.ID + "' type='button' id='edit' class='btn btn-sm smol teal'>Edit</a><a href='../actions/actions.php?id=" + data.ID + "&action=disapprove' type='button' id='del' class='btn btn-sm smol btn-danger'>Del</a><a href='#' class='btn btn-sm smol btn-success' id='details'>PRNT</a>";
                            }
                        }
                    ],
                    // deferRender: true,
                    // scrollY: '62vh', //67vh
                    // scroller: true,

                    // < button class= 'btn btn-sm smol btn-success dropdown-toggle' data - bs - toggle='dropdown' aria - expanded='false' > Print</button > <ul class='dropdown-menu' style='font-size: 0.9rem'><li><button class='dropdown-item' id='details'><i class='fa fa-file-text-o'></i> Ledger and Details</button></li><li><a class='dropdown-item' id='req' href='print.php?id=" + data.ID + "&action=requirements'><i class='fa fa-file-image-o'></i> Requirements</a></li></ul></div>


                <?php } else { ?>columns: [
                        { data: 'Borrower', render: function (data, type, row) { return "<b style='white-space:normal'>" + data + "</b>"; } },
                        { data: 'PreviousLoanAmount' },
                        { data: 'ProposedLoanAmount' },
                        { data: 'ApprovedLoanAmount' },
                        { data: 'AccountStatus' },
                        { data: 'Remarks', render: function (data, type, row) { return "<b class='text-uppercase'>" + data + "</b>"; } },
                        {
                            "data": null,
                            "render": function (data, type, row) {
                                if (data.Status === 'APPROVED') {
                                    return "<div></div>";
                                } else {
                                    return "<div class='btn-group btn-group-sm' role='group' aria-label='Basic example'><a href='preview.php?id=" + data.ID + "' type='button' id='edit' class='btn btn-sm teal'>Edit</a></div>";
                                }
                            }
                        }
                    ],
                <?php } ?>

                deferRender: true,
                scrollY: '66vh', //67vh
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

            function counter() {
                $.ajax({
                    url: '../load/loadcounter.php',
                    success: function (data) {
                        $('#counter').html(data);
                    }
                })
            }

            $(document).on('click', '#details', function (e) {
                e.preventDefault();
                var overlay = $('<div class="overlay text-center d-flex justify-content-center align-items-center" style="position: fixed; width: 100vw; height: 100vh; z-index: 9000; background-color: rgba(0, 0, 0, 0.3); top: 0; left: 0; display: none"><div class="loader JS_on"><span class="binary"></span><span class="binary"></span><span class="getting-there">LOADING PDF...</span></div></div>').appendTo('body');
                var rowdata = table.row($(this).closest('tr')).data();
                var id = rowdata.ID;
                $.ajax({
                    url: '../load/printdetails.php',
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

            // $(document).on('click', '#req', function (e) {
            //     e.preventDefault();
            //     var overlay = $('<div class="overlay text-center d-flex justify-content-center align-items-center" style="position: fixed; width: 100vw; height: 100vh; z-index: 9000; background-color: rgba(0, 0, 0, 0.3); top: 0; left: 0; display: none"><div class="loader JS_on"><span class="binary"></span><span class="binary"></span><span class="getting-there">LOADING PDF...</span></div></div>').appendTo('body');
            //     var rowdata = table.row($(this).closest('tr')).data();
            //     var id = rowdata.ID;
            //     $.ajax({
            //         url: '../load/printrequirements.php',
            //         type: 'POST',
            //         data: {
            //             id: id,
            //         },
            //         success: function (result) {
            //             var result = JSON.parse(result);
            //             overlay.remove();
            //             if (result.success == true) {
            //                 var url = 'details.php?filename=' + result.filename;
            //                 var a = document.createElement("a");
            //                 a.target = "_blank";
            //                 a.href = url;
            //                 a.click();
            //                 // location.reload();
            //             } else {
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Error',
            //                     text: result.status,
            //                     showConfirmButton: false,
            //                     timer: 1500
            //                 }).then(function () {
            //                     location.reload();
            //                 });
            //             }
            //         }
            //     });
            // });

            $(document).on('click', '#del', function (e) {
                e.preventDefault();
                var href = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    text: 'Disapprove Application',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Disapprove'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });

            counter();
            setInterval(() => {
                counter();
                table.ajax.reload(null, false);
                console.log('%cTable Updated!', 'color:red; font-family:Pacifico, cursive; font-size:17px; font-weight:bold');
            }, 10000);
        });

    </script>
</body>

</html>