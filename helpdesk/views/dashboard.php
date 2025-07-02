<?php
session_start();
include '../../db.php';
include '../includes/header.php';
date_default_timezone_set('Asia/Manila');

$dateformat = date('F j, Y');
$id = $_SESSION['userid'];
$branchid = $_SESSION['branchid'];
$currentdate = date('Y-m-d');
$fullname = $_SESSION['name'];
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
    <link rel="stylesheet" href="https://unpkg.com/viewerjs@1.11.7/dist/viewer.css">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <style>
        table {
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
        }

        .dragover {
            background-color: #1CAF9A;
            transition: all 0.2s ease-in-out;
        }

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
            max-width: 1360px;
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
                        Tickets</h6>
                    <span class="text-uppercase fw-bold small"><?php echo $dateformat; ?></span>
                </div>
                <table id="tickettable" class="table table-hover table-responsive-sm align-middle">
                    <thead class="border" title="Click to Sort">
                        <?php if ($admin) { ?>
                            <tr>
                                <th style="width: 5%">NO.</th>
                                <th>BRANCH</th>
                                <th>TYPE</th>
                                <th>REQUESTED BY</th>
                                <th>POSITION</th>
                                <th>APPROVED BY</th>
                                <th>ADDRESSED BY</th>
                                <th>STATUS</th>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <th style="width: 5%">NO.</th>
                                <th>TYPE</th>
                                <th>REQUESTED BY</th>
                                <th>POSITION</th>
                                <th>APPROVED BY</th>
                                <th>ADDRESSED BY</th>
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

    <div class="modal fade" id="preview" tabindex="-1" aria-labelledby="previewLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 id="previewLabel">Preview Ticket</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-uppercase fw-bold" style="font-family: Raleway, sans-serif">Ticket Information</h6>
                    <hr>
                    <div>
                        <div class="d-flex justify-content-between"><b>Branch:</b> <span id="branch"></span></div>
                        <div class="d-flex justify-content-between"><b>Type:</b> <span id="type"></span></div>
                        <div class="d-flex justify-content-between"><b>Concern:</b> <span id="concern"></span></div>
                        <div class="d-flex justify-content-between"><b>Source of Documents:</b> <span
                                id="source"></span></div>
                        <div class="d-flex justify-content-between"><b>Reason:</b> <span id="reason"></span></div>
                        <div class="d-flex justify-content-between"><b>Borrower:</b> <span id="borrower"></span></div>
                        <div class="d-flex justify-content-between"><b>Requested by:</b> <span id="reqby"></span></div>
                        <div class="d-flex justify-content-between"><b>Position:</b> <span id="position"></span></div>
                        <div class="d-flex justify-content-between"><b>Approved by:</b> <span id="approvedby"></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Addressed by:</b> <span id="addressedby"></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Date Created:</b> <span id="created"></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Date Updated:</b> <span id="updated"></span>
                        </div>
                        <div class="d-flex justify-content-between"><b>Status:</b> <span id="status"></span></div>
                    </div>
                    <div class="mt-4">
                        <h6 class="text-uppercase fw-bold" style="font-family: Raleway, sans-serif">Files Attached</h6>
                        <hr>
                        <div class="row">
                            <div class="col" id="attachments">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary">Save changes</button>
                </div>
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
    <script src="https://unpkg.com/viewerjs@1.11.7/dist/viewer.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#tickettable').DataTable({
                ajax: {
                    url: '../load/loadtable.php',
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
                        {
                            data: 'ID',
                            render: function (data, type, row) {
                                return "<span class='label'>Employee: </span><span style='white-space:normal'>" + data.toString().padStart(4, '0') + "</span>";
                            }
                        },
                        { data: 'branchname', render: function (data, type, row) { return "<span class='label'>Branch: </span><b class='text-uppercase'>" + data + "</b>"; } },
                        { data: 'type', render: function (data, type, row) { return "<span class='label'>Employee: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'requestedby', render: function (data, type, row) { return "<span class='label'>Review Date: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'position', render: function (data, type, row) { return "<span class='label'>Review Date: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'approvedby', render: function (data, type, row) { return "<span class='label'>Review Date: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'updatedby', render: function (data, type, row) { return "<span class='label'>Review Date: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        {
                            data: 'status', render: function (data, type, row) {
                                if (data == "OPEN") {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-warning text-dark'>" + data + "</span>";
                                } else if (data == "ONGOING") {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-info'>" + data + "</span>";
                                } else if (data == "COMPLETED") {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-success'>" + data + "</span>";
                                } else {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-danger'>" + data + "</span>";
                                }
                            }
                        }
                    ],

                <?php } else { ?>columns: [
                        {
                            data: 'ID',
                            render: function (data, type, row) {
                                return "<span class='label'>Employee: </span><span style='white-space:normal'>" + data.toString().padStart(4, '0') + "</span>";
                            }
                        },
                        { data: 'type', render: function (data, type, row) { return "<span class='label'>Employee: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'requestedby', render: function (data, type, row) { return "<span class='label'>Review Date: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'position', render: function (data, type, row) { return "<span class='label'>Review Date: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'approvedby', render: function (data, type, row) { return "<span class='label'>Review Date: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        { data: 'updatedby', render: function (data, type, row) { return "<span class='label'>Review Date: </span><span style='white-space:normal'>" + data + "</span>"; } },
                        {
                            data: 'status', render: function (data, type, row) {
                                if (data == "OPEN") {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-warning text-dark'>" + data + "</span>";
                                } else if (data == "ONGOING") {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-info'>" + data + "</span>";
                                } else if (data == "COMPLETED") {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-success'>" + data + "</span>";
                                } else {
                                    return "<span class='label'>Status: </span><span class='badge rounded-pill bg-danger'>" + data + "</span>";
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

            $(document).on('contextmenu', '#tickettable tbody tr', function (e) {
                e.preventDefault();
                $('.removedrop').remove();
                var rowData = table.row($(this).closest('tr')).data();
                console.log(rowData);
                var ID = rowData.ID;
                var status = rowData.status;
                var updatedby = rowData.updatedby;
                <?php if ($admin) { ?>
                    var menu = $('<div class="dropdown-menu small removedrop" id="actiondropdown" style="display:block; position:absolute; z-index:1000;">'
                        + '<a class="dropdown-item small" data-bs-toggle="modal" data-bs-target="#preview" href="#" id="preview"><i class="fa fa-eye text-success" aria-hidden="true"></i> <span>Preview</span></a>'
                        + (status == 'OPEN' ? '<a class="dropdown-item small" data-id="' + ID + '" href="#" id="receive"><i class="fa fa-check-circle-o text-success" aria-hidden="true"></i> <span>Receive Ticket</span></a>'
                            : '<a class="dropdown-item small disabled" data-id="' + ID + '" href="#" id="receive"><i class="fa fa-check-circle-o text-secondary" aria-hidden="true"></i> <span>Receive Ticket</span></a>')
                        + (updatedby == '<?= $fullname; ?>' && status == 'ONGOING' ? '<a class="dropdown-item small" data-id="' + ID + '" href="#" id="close"><i class="fa fa-check text-success" aria-hidden="true"></i> <span>Close Ticket</span></a>'
                            : '<a class="dropdown-item small disabled" data-id="' + ID + '" href="#" id="close"><i class="fa fa-check text-secondary" aria-hidden="true"></i> <span>Close Ticket</span></a>')
                        + '</div>').appendTo('body');
                <?php } else { ?>
                    var menu = $('<div class="dropdown-menu small removedrop" id="actiondropdown" style="display:block; position:absolute; z-index:1000;">'
                        + '<a class="dropdown-item small" data-bs-toggle="modal" data-bs-target="#preview" href="#" id="preview"><i class="fa fa-eye text-success" aria-hidden="true"></i> <span>Preview</span></a>'
                        + (status == 'OPEN' ? '<a class="dropdown-item small" data-id="' + ID + '" href="#" id="cancel"><i class="fa fa-trash-o text-danger" aria-hidden="true"></i> <span>Cancel</span></a>'
                            : '<a class="dropdown-item small disabled" data-id="' + ID + '" href="#" id="cancel"><i class="fa fa-trash-o text-secondary" aria-hidden="true"></i> <span>Cancel</span></a>')
                        + '</div>').appendTo('body');
                <?php } ?>
                menu.css({ top: e.pageY + 'px', left: e.pageX + 'px' });

                $(document).on('click', function () {
                    menu.remove();
                });

                $(document).off('click', '#receive').on('click', '#receive', function (e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Accept?',
                        text: "Accept Ticket?",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Accept!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '../actions/actions.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    action: 'receive',
                                },
                                success: function (result) {
                                    result = JSON.parse(result);
                                    if (result.success) {
                                        table.ajax.reload(null, false);
                                    } else {
                                        Swal.fire({
                                            icon: error,
                                            title: result.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }
                                }
                            })
                        }
                    });
                });

                $(document).off('click', '#close').on('click', '#close', function (e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Close?',
                        text: "Close Ticket?",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '../actions/actions.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    action: 'close',
                                },
                                success: function (result) {
                                    result = JSON.parse(result);
                                    if (result.success) {
                                        table.ajax.reload(null, false);
                                    } else {
                                        Swal.fire({
                                            icon: error,
                                            title: result.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }
                                }
                            })
                        }
                    });
                });

                $(document).off('click', '#preview').on('click', '#preview', function (e) {
                    e.preventDefault();
                    $('#branch').text(rowData.branchname);
                    $('#type').text(rowData.type);
                    $('#concern').text(rowData.concern);
                    $('#source').text(rowData.sourceofdoc);
                    $('#reason').text(rowData.reason);
                    $('#borrower').text(rowData.borrower);
                    $('#reqby').text(rowData.requestedby);
                    $('#position').text(rowData.position);
                    $('#approvedby').text(rowData.approvedby);
                    $('#addressedby').text(rowData.updatedby);
                    $('#created').text(rowData.datecreated);
                    $('#updated').text(rowData.dateupdated);
                    $('#status').text(rowData.status);
                    var atthtml = '';
                    $.each(rowData.attachments, function (index, attachment) {
                        atthtml += '<img class="images" src="' + attachment.filepath + '" alt="' + attachment.filepath + '">';
                    });
                    $('#attachments').html(atthtml);

                    const attviewer = new Viewer(document.getElementById('attachments'), {
                        viewed() {
                            const image = attviewer.image;
                            image.style.border = '1px solid #595757';
                            image.style.borderRadius = '5px';
                        },
                        transition: false,
                        toolbar: {
                            zoomIn: 1,
                            zoomOut: 1,
                            oneToOne: 0,
                            reset: 0,
                            prev: 1,
                            play: 0,
                            next: 1,
                            rotateLeft: 1,
                            rotateRight: 1,
                            flipHorizontal: 0,
                            flipVertical: 0
                        },
                    });

                    $('#preview').modal('show');
                });

                // $(document).off('click', '#generate').on('click', '#generate', function (e) {
                //     e.preventDefault();
                //     var overlay = $('<div class="overlay text-center d-flex justify-content-center align-items-center" style="position: fixed; width: 100vw; height: 100vh; z-index: 9000; background-color: rgba(0, 0, 0, 0.3); top: 0; left: 0; display: none"><div class="loader JS_on"><span class="binary"></span><span class="binary"></span><span class="getting-there">GENERATING FORM...</span></div></div>').appendTo('body');
                //     var id = $(this).data('id');
                //     $.ajax({
                //         url: '../load/generate.php',
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
                //                 // a.target = "_blank";
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
            });
            setInterval(() => {
                table.ajax.reload(null, false);
            }, 10000);
        });
    </script>
</body>

</html>