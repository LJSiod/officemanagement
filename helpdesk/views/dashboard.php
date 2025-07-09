<?php
session_start();
include '../../maindb.php';
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
            max-width: 1800px;
        }

        .br-section-wrapper {
            border-radius: 3px;
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
            opacity: 95%;
        }

        #ticketwrapper {
            max-height: 60vh;
            height: 60vh;
            padding: 20px;
        }

        .btn {
            border-radius: 50px;
        }

        .dot {
            cursor: pointer;
        }

        .note {
            font-size: 0.7rem;
        }

        .servingwrapper {
            max-height: 30vh;
            height: 30vh;
            padding: 15px;
            overflow: auto;
        }

        .servingtable {
            max-height: 10vh;
            height: 10vh;
            overflow: auto;
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

        @media (max-width: 767px) {
            .servingwrapper {
                border-bottom: 1px solid #868e96;
            }
        }
    </style>
</head>

<body>
    <div class="br-pagebody">
        <div class="row" id="staffpanel">

        </div>
        <div class="br-section-wrapper bg-body mt-2" id="ticketwrapper">
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

    <div class="modal fade modal-lg" id="previewmodal" tabindex="-1" aria-labelledby="previewLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 id="previewLabel">Preview Ticket</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-uppercase fw-bold" style="font-family: Raleway, sans-serif">Ticket Information</h6>
                    <hr>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="fw-bold">Branch:</td>
                                <td id="branch"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Type:</td>
                                <td id="type"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Concern:</td>
                                <td id="concern"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Source of Documents:</td>
                                <td id="source"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Reason:</td>
                                <td id="reason"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Borrower:</td>
                                <td id="borrower"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Requested by:</td>
                                <td id="reqby"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Position:</td>
                                <td id="position"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Approved by:</td>
                                <td id="approvedby"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Addressed by:</td>
                                <td id="addressedby"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Date Created:</td>
                                <td id="created"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Date Updated:</td>
                                <td id="updated"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status:</td>
                                <td id="status"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-4">
                        <h6 class="text-uppercase fw-bold" style="font-family: Raleway, sans-serif">Files Attached</h6>
                        <hr>
                        <div class="row d-flex justify-content-center" id="attachments">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="receive" class="btn btn-sm btn-primary d-none">Accept</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
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
            loadStaffPanel();
            heartbeat();
            function heartbeat() {
                console.log('ONLINE!');
                $.ajax({
                    url: '../actions/actions.php',
                    type: 'POST',
                    data: {
                        id: <?= $id ?>,
                        action: 'online',
                    },
                    success: function (response) {
                    }
                })
            }

            function loadStaffPanel() {
                $.getJSON('../load/loadstaff.php', function (response) {
                    let html = '';
                    response.data.forEach(function (row) {
                        let status = '';
                        let diff = Date.now() - Date.parse(row.activeStat);
                        let minutes = Math.floor((diff / 1000) / 60);
                        if (minutes > 10) {
                            status = 'offline';
                        } else if (minutes > 5) {
                            status = 'away';
                        } else {
                            status = 'online';
                        }
                        html += `
                        <div class="servingwrapper br-section-wrapper bg-body col-sm mx-1">
                            <div class="d-flex justify-content-between">
                                <div class="fw-bold">${row.fullname}</div>
                                <div class="dot ${status == 'offline' ? 'text-secondary' : (status == 'away' ? 'text-warning' : 'text-success')}">⬤</div>
                            </div>
                            <div class="note">Note: Test!</div>
                            <hr>
                            <div class="small fw-bold">Now Serving: </div>
                            <div class="servingtable">                        
                                <table class="table table-bordered" id="${row.id}">
                                </table>
                            </div>
                        </div>
                    `;
                        $.getJSON('../load/getserve.php?id=' + row.id, function (response) {
                            let html = '';
                            response.forEach(function (row) {
                                html += `
                            <tr data-id="${row.id}" data-updatedby="${row.updatedby}" class="fw-bold">
                                <td>${`0000${row.id}`.slice(-4)}</td>
                                <td class="text-end">${row.branchname}</td>
                            </tr>
                        `;
                            });
                            $('#' + row.id).empty().html(html);
                        });
                    });
                    $('#staffpanel').empty().html(html);
                });
            }

            function previewModal(id) {
                $.ajax({
                    url: '../load/loadmodal.php',
                    type: 'POST',
                    data: {
                        id: id,
                    },
                    success: function (response) {
                        var modalRow = JSON.parse(response);
                        $('#receive').removeClass('d-none');
                        if (modalRow.ID) {
                            if (modalRow.status != 'OPEN') {
                                $('#receive').addClass('d-none');
                            }
                            $('#receive').data('id', modalRow.ID);
                            $('#branch').text(modalRow.branchname);
                            $('#type').text(modalRow.type);
                            $('#concern').text(modalRow.concern);
                            $('#source').text(modalRow.sourceofdoc);
                            $('#reason').text(modalRow.reason);
                            $('#borrower').text(modalRow.borrower);
                            $('#reqby').text(modalRow.requestedby);
                            $('#position').text(modalRow.position);
                            $('#approvedby').text(modalRow.approvedby);
                            $('#addressedby').text(modalRow.updatedby);
                            $('#created').text(modalRow.datecreated);
                            $('#updated').text(modalRow.dateupdated);
                            $('#status').text(modalRow.status);
                            var atthtml = '';
                            $.each(modalRow.attachments, function (index, attachment) {
                                atthtml += '<img class="images col" src="' + attachment.filepath + '" alt="' + attachment.filepath + '">';
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
                            $('#previewmodal').modal('show');
                            $('#previewmodal').on('hidden.bs.modal', function () {
                                attviewer.destroy();
                            });
                        }
                    }
                });
            }

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
                <?php if ($admin) { ?> columns: [
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

                <?php } else { ?> columns: [
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
                scrollY: '38vh', //64vh
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

            // it staff panel right click events
            $(document).on('contextmenu', '.servingtable table tr', function (e) {
                e.preventDefault();
                $('.removedrop').remove();
                var data = $(this).closest('tr').data();
                var updatedby = data.updatedby;
                var menu = $('<div class="dropdown-menu small removedrop" id="actiondropdown" style="display:block; position:absolute; z-index:1000;">'
                    + '<a class="dropdown-item small" href="#" id="preview2"><i class="fa fa-eye text-success" aria-hidden="true"></i> <span>Preview</span></a>'
                    + (updatedby == <?= $id ?> ? '<a class="dropdown-item small" href="#" id="close"><i class="fa fa-check text-success" aria-hidden="true"></i> <span>Close Ticket</span></a>' : '<a class="dropdown-item small disabled" href="#" id="close"><i class="fa fa-check text-secondary" aria-hidden="true"></i> <span>Close Ticket</span></a>')
                    + '</div>').appendTo('body');
                menu.css({ top: e.pageY + 'px', left: e.pageX + 'px' });

                $(document).on('click', function () {
                    menu.remove();
                });

                $('#preview2').on('click', function () {
                    var ids = data.id;
                    previewModal(ids);
                });

                //close/complete listener
                $(document).off('click', '#close').on('click', '#close', function (e) {
                    e.preventDefault();
                    var id = data.id;
                    Swal.fire({
                        title: 'Close?',
                        text: "Close Ticket?",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes!',
                        input: 'textarea',
                        inputPlaceholder: 'Enter Note',
                        inputAttributes: {
                            rows: 5,
                            id: "note-textarea"
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var note = result.value;
                            $.ajax({
                                url: '../actions/actions.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    action: 'close',
                                    note: note
                                },
                                success: function (result) {
                                    result = JSON.parse(result);
                                    if (result.success) {
                                        table.ajax.reload(null, false);
                                        loadStaffPanel();
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
            });

            //ticket table right click events
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
                        + '<a class="dropdown-item small" href="#" id="preview"><i class="fa fa-eye text-success" aria-hidden="true"></i> <span>Preview/Accept</span></a>'
                        + '</div>').appendTo('body');
                <?php } else { ?>
                    var menu = $('<div class="dropdown-menu small removedrop" id="actiondropdown" style="display:block; position:absolute; z-index:1000;">'
                        + '<a class="dropdown-item small" href="#" id="preview"><i class="fa fa-eye text-success" aria-hidden="true"></i> <span>Preview</span></a>'
                        + (status == 'OPEN' ? '<a class="dropdown-item small" data-id="' + ID + '" href="#" id="cancel"><i class="fa fa-trash-o text-danger" aria-hidden="true"></i> <span>Cancel</span></a>'
                            : '<a class="dropdown-item small disabled" data-id="' + ID + '" href="#" id="cancel"><i class="fa fa-trash-o text-secondary" aria-hidden="true"></i> <span>Cancel</span></a>')
                        + '</div>').appendTo('body');
                <?php } ?>
                menu.css({ top: e.pageY + 'px', left: e.pageX + 'px' });

                $(document).on('click', function () {
                    menu.remove();
                });

                //receive listener
                $(document).off('click', '#receive').on('click', '#receive', function (e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    console.log(id);
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
                                url: '../actions/check.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                },
                                success: function (result) {
                                    result = JSON.parse(result);
                                    if (result.vacant) {
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
                                                    $('#previewmodal').modal('hide');
                                                    table.ajax.reload(null, false);
                                                    loadStaffPanel();
                                                } else {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: result.message,
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                    });
                                                }
                                            }
                                        })
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: result.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        $('#previewmodal').modal('hide');
                                        table.ajax.reload(null, false);
                                    }
                                }
                            })
                        }
                    });
                });

                //cancel listener
                $(document).off('click', '#cancel').on('click', '#cancel', function (e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Cancel?',
                        text: "Cancel Ticket?",
                        icon: 'warning',
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
                                    action: 'cancel',
                                },
                                success: function (result) {
                                    result = JSON.parse(result);
                                    if (result.success) {
                                        table.ajax.reload(null, false);
                                        loadStaffPanel();
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
                    var id = rowData.ID;
                    previewModal(id);
                });
            });

            setInterval(() => {
                table.ajax.reload(null, false);
                loadStaffPanel();
                heartbeat();
            }, 10000);
        });
    </script>
</body>

</html>