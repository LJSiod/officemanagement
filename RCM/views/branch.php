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
    <title>Remittance Collection</title>
    <style>
        table {
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
        }

        .br-pagebody {
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
            max-width: 98vw;
        }

        .br-section-wrapper {
            border-radius: 3px;
            padding: 20px;
            max-height: 89vh;
            height: 89vh;
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
            opacity: 95%;
        }

        #detailsTable {
            height: 30vh;
            max-height: 30vh;
        }

        .btn {
            border-radius: 50px;
        }

        .smol {
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .received {
            background-color: #86de86 !important;
            color: black !important
        }

        .pending {
            background-color: #efdfae !important;
            color: black !important
        }

        .encoded {
            background-color: #81c4ff !important;
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
                        RCM Records</h6>
                    <span class="text-uppercase fw-bold small"><?php echo $dateformat; ?></span>
                </div>
                <div class="d-flex" style="margin-bottom: -15px">
                    <p class="me-auto text-muted border p-1" id="legend">
                        <i class="fa fa-square" style="color: #efdfae"></i><span class="small fw-bold"
                            style="font-size: 0.7rem;">
                            :Pending</span>
                        <i class="fa fa-square ms-3" style="color: #86de86"></i><span class="small fw-bold"
                            style="font-size: 0.7rem;">
                            :Received</span>
                        <i class="fa fa-square ms-3" style="color: #81c4ff"></i><span class="small fw-bold"
                            style="font-size: 0.7rem;">
                            :Encoded</span>
                    </p>
                </div>
                <table id="approvaltable" class="table table-hover table-bordered">
                    <thead class="border" title="Click to Sort">
                        <tr>
                            <th>CHANNEL</th>
                            <th>CODE</th>
                            <th>SENDER</th>
                            <th>CUSTOMER</th>
                            <th>AMOUNT</th>
                            <th>DATE/TIME RECEIVED</th>
                            <th>RECEIVER</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th>SENT BY</th>
                        </tr>
                    </thead>
                    <tbody title="Right Click to Open Menu" style="font-size: 0.8rem;">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">RCM Encode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="detailsModalform" method="">
                        <input type="hidden" name="branchid" value="<?php echo $branchid; ?>">
                        <div class="ms-2">
                            <div class="row mb-2">
                                <h6>Channel Details</h6>
                                <div class="form-control-sm col-md-3">
                                    <label class="form-label small ms-1" for="channel">Payment Channel</label><span
                                        class="small text-danger">*</span>
                                    <select class="form-select form-select-sm" id="channel" name="channel">
                                    </select>
                                </div>
                                <div class="form-control-sm col-md-6">
                                    <label for="code" class="form-label small ms-1">Transaction Code</label><span
                                        class="small text-danger">*</span>
                                    <input type="text" class="form-control form-control-sm" placeholder="" id="code"
                                        name="code" required>
                                </div>
                                <div class="form-control-sm col-md-3">
                                    <label for="amount" class="form-label small ms-1">Amount</label><span
                                        class="small text-danger">*</span>
                                    <input type="number" class="form-control form-control-sm" placeholder="" id="amount"
                                        name="amount" required>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <h6>Customer Information</h6>
                                <div class="form-control-sm col-md">
                                    <label for="firstname" class="form-label small ms-1">First Name</label><span
                                        class="small text-danger">*</span>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        id="firstname" name="firstname" required>
                                </div>
                                <div class="form-control-sm col-md">
                                    <label for="middlename" class="form-label small ms-1">Middle Name</label>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        id="middlename" name="middlename">
                                </div>
                                <div class="form-control-sm col-md">
                                    <label for="lastname" class="form-label small ms-1">Last Name</label><span
                                        class="small text-danger">*</span>
                                    <input type="text" class="form-control form-control-sm" placeholder="" id="lastname"
                                        name="lastname" required>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="form-control-sm col-md mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="samesender"
                                            name="samesender">
                                        <label class="form-check-label small ms-1" for="samesender">Sender is also the
                                            Customer</label>
                                    </div>
                                </div>
                                <div class="form-control-sm col-md-9">
                                    <label for="sender" class="form-label small ms-1">Sender Name - Contact
                                        Number</label><span class="small text-danger">*</span>
                                    <input type="text" class="form-control form-control-sm" placeholder="" id="sender"
                                        name="sender" required>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="form-control-sm col-md-10">
                                    <label for="proof" class="form-label small">Attach Proof of
                                        Remittance</label>
                                    <input type="file" class="form-control form-control-sm" id="proof" name="proof"
                                        required>
                                </div>
                                <div class="form-control-sm col-md-2">
                                    <button class="btn btn-sm btn-success addBtn mt-4" style="border-radius: 5px;">Add
                                        Row</button>
                                </div>
                            </div>

                            <div id="detailsTable" class="border">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>Channel</th>
                                            <th>Code</th>
                                            <th>Sender</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>PoR</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">

                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer row">
                                <div class="form-control-sm col-md">
                                    <label for="receiver" class="form-label small ms-1">Receiver</label><span
                                        class="small text-danger">*</span>
                                    <select class="form-select form-select-sm" id="receiver" name="receiver">
                                    </select>
                                </div>
                                <button class="btn btn-sm btn-success mt-4 col ms-1 submit-btn"
                                    style="border-radius: 5px;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-lg fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">RCM Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="editModalform" method="">
                        <input type="hidden" id="ids" name="id">
                        <div class="ms-2">
                            <div class="row mb-2">
                                <h6>Channel Details</h6>
                                <div class="form-control-sm col-6">
                                    <label class="form-label small ms-1" for="channel">Payment Channel</label><span
                                        class="small text-danger">*</span>
                                    <select class="form-select form-select-sm" id="channels" name="channel">
                                    </select>
                                </div>
                                <div class="form-control-sm col-3">
                                    <label for="code" class="form-label small ms-1">Transaction Code</label><span
                                        class="small text-danger">*</span>
                                    <input type="text" class="form-control form-control-sm" placeholder="" id="codes"
                                        name="code" required>
                                </div>
                                <div class="form-control-sm col-3">
                                    <label for="amount" class="form-label small ms-1">Amount</label><span
                                        class="small text-danger">*</span>
                                    <input type="number" class="form-control form-control-sm" placeholder=""
                                        id="amounts" name="amount" step="0.01" required>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <h6>Customer Name</h6>
                                <div class="form-control-sm col">
                                    <label for="firstname" class="form-label small ms-1">First Name</label><span
                                        class="small text-danger">*</span>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        id="firstnames" name="firstname" required>
                                </div>
                                <div class="form-control-sm col">
                                    <label for="middlename" class="form-label small ms-1">Middle Name</label>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        id="middlenames" name="middlename">
                                </div>
                                <div class="form-control-sm col">
                                    <label for="lastname" class="form-label small ms-1">Last Name</label><span
                                        class="small text-danger">*</span>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        id="lastnames" name="lastname" required>
                                </div>
                            </div>
                            <div class="form-control-sm">
                                <label for="sender" class="form-label small">Sender Name - Contact
                                    Number</label><span class="small text-danger">*</span>
                                <input type="text" class="form-control form-control-sm" placeholder="" id="senders"
                                    name="sender" required>
                            </div>
                            <div class="modal-footer row">
                                <div class="form-control-sm col">
                                    <label for="receiver" class="form-label small ms-1">Receiver</label><span
                                        class="small text-danger">*</span>
                                    <select class="form-select form-select-sm" id="receivers" name="receiver">
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-success mt-4 col ms-1 edit-btn"
                                    style="border-radius: 5px;">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="imagePreviewContainer" style="display:none;">
        <img id="imagePreview" src="" alt="Proof of Remittance">
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
            var table = $('#approvaltable').DataTable({
                ajax: {
                    url: '../load/loadbranch.php',
                    method: 'GET',
                    dataSrc: 'data'
                },
                layout: {
                    topStart: false,
                    bottomEnd: false
                },
                pageLength: 12,
                rowCallback: function (row, data, index) {
                    if (data.status === 'RECEIVED') {
                        $('td', row).addClass('received');
                    } else if (data.status === 'PENDING') {
                        $('td', row).addClass('pending');
                    } else {
                        $('td', row).addClass('encoded');
                    }
                },
                order: false,
                columns: [
                    { data: 'channel', render: function (data, type, row) { return '<span class="label">Channel: </span><span style="white-space:normal">' + data + "</span>"; } },
                    { data: "transcode", render: function (data, type, row) { return '<span class="label">Transaction Code: </span><span style="white-space:normal">' + data + "</span>"; } },
                    { data: 'sendername', render: function (data, type, row) { return '<span class="label">Sender: </span><span style="white-space:normal">' + data + "</span>"; } },
                    { data: 'fullname', render: function (data, type, row) { return '<span class="label">Name: </span><span style="white-space:normal">' + data + "</span>"; } },
                    {
                        data: 'amount',
                        render: function (data, type, row) {
                            return '<span class="label">Amount: </span>' + parseFloat(data).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        }
                    },
                    { data: 'datereceived', render: function (data, type, row) { return '<span class="label">Date/Time Received: </span><span style="white-space:normal">' + data + "</span>"; } },
                    { data: 'receivername', render: function (data, type, row) { return '<span class="label">Receiver: </span><span style="white-space:normal">' + data + "</span>"; } },
                    { data: 'status', render: function (data, type, row) { return '<span class="label">Status: </span><span style="white-space:normal">' + data + "</span>"; } },
                    { data: 'date', render: function (data, type, row) { return '<span class="label">Date/Time Sent: </span><span style="white-space:normal">' + data + "</span>"; } },
                    { data: 'transby', render: function (data, type, row) { return '<span class="label">Sent by: </span><span style="white-space:normal">' + data + "</span>"; } },
                ],
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

            $.ajax({
                url: '../load/getchannels.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var select = $('#channel');
                    var select2 = $('#channels');
                    data.forEach(function (channel) {
                        select2.append('<option value="' + channel.id + '">' + channel.channel + '</option>');
                        select.append('<option value="' + channel.id + '">' + channel.channel + '</option>');
                    });
                }
            });

            $.ajax({
                url: '../load/getreceivers.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var select = $('#receiver');
                    var select2 = $('#receivers');
                    data.forEach(function (receiver) {
                        select2.append('<option value="' + receiver.id + '">' + receiver.receivername + '</option>');
                        select.append('<option value="' + receiver.id + '">' + receiver.receivername + '</option>');
                    });
                }
            });

            $('#samesender').on('change', function () {
                updateSender();
                if ($(this).is(':checked')) {
                } else {
                    $('#sender').val('');
                }
            });

            $('#firstname, #middlename, #lastname').on('input', function () {
                if ($('#samesender').is(':checked')) {
                    updateSender();
                }
            });

            function updateSender() {
                var fullname = $('#firstname').val() + ' ' + $('#middlename').val() + ' ' + $('#lastname').val();
                $('#sender').val(fullname.trim());
            }

            $(document).on('contextmenu', function (e) {
                e.preventDefault();
            });

            $(document).on('contextmenu', '#approvaltable tbody tr', function (e) {
                $('.removedrop').remove();
                var rowData = table.row($(this).closest('tr')).data();
                var status = rowData.status;
                var menu = $('<div class="dropdown-menu small removedrop" id="actiondropdown" style="display:block; position:absolute; z-index:1000;">'
                    + '<a class="dropdown-item small" id="preview"  href ="#"><i class="fa fa-eye text-info" aria-hidden="true"></i> <span><img src="' + rowData.filepath + '" class="d-none"> View Proof of Remittance</span ></a>'
                    + (status == 'RECEIVED' ? '<a class="dropdown-item small" href="../actions/actions.php?action=encoded&id=' + rowData.ID + '" id="encoded"><i class="fa fa-check-circle text-success" aria-hidden="true"></i> <span>Set to Encoded</span></a>' : '')
                    + (status == 'PENDING' ? '<a class="dropdown-item small" href="#" id="edit"><i class="fa fa-pencil text-info" aria-hidden="true"></i> <span>Edit</span></a>' : '')
                    + (status == 'PENDING' ? '<a class="dropdown-item small" href="../actions/actions.php?action=cancel&id=' + rowData.ID + '" id="cancel"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i> <span>Cancel</span></a>' : '')
                    + '</div>').appendTo('body');
                menu.css({ top: e.pageY + 'px', left: e.pageX + 'px' });
                $('#edit').on('click', function (e) {
                    e.preventDefault();
                    $('#editModal #ids').val(rowData.ID);
                    $('#editModal #firstnames').val(rowData.firstname);
                    $('#editModal #middlenames').val(rowData.middlename);
                    $('#editModal #lastnames').val(rowData.lastname);
                    $('#editModal #codes').val(rowData.transcode);
                    $('#editModal #receivers').val(rowData.receiverid);
                    $('#editModal #amounts').val(rowData.amount);
                    $('#editModal #channels').val(rowData.channelid);
                    $('#editModal #senders').val(rowData.sendername);
                    $('#editModal').modal('show');
                });

                $(document).on('click', function () {
                    menu.remove();
                });

            });

            $('.addBtn').on('click', function (e) {
                e.preventDefault();

                var channel = $('#channel').val();
                var code = $('#code').val().trim();
                var firstname = $('#firstname').val().trim();
                var middlename = $('#middlename').val().trim();
                var lastname = $('#lastname').val().trim();
                var amount = $('#amount').val().trim();
                var proof = $('#proof')[0].files[0];
                var sender = $('#sender').val().trim();
                // Check if any required field is empty
                if (!code || !firstname || !lastname || !amount || !sender) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Fields',
                        text: 'Please fill in required fields before adding a row.'
                    });
                    return;
                }

                if (proof) {
                    var formData = new FormData();
                    formData.append('proof', proof);
                    $.ajax({
                        url: '../actions/upload.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            var response = JSON.parse(response);
                            var filepath = response.filepath;
                            var customer = firstname + ' ' + middlename + ' ' + lastname;
                            var channelText = $('#channel').find('option:selected').text();
                            console.log(filepath);
                            $('#detailsTable tbody').append(
                                '<tr data-channel="' + channel + '" data-firstname="' + firstname + '" data-middlename="' + middlename + '" data-lastname="' + lastname + '">' +
                                '<td>' + channelText + '</td>' +
                                '<td>' + code + '</td>' +
                                '<td>' + sender + '</td>' +
                                '<td>' + customer + '</td>' +
                                '<td>' + amount + '</td>' +
                                '<td>' + filepath + '</td>' +
                                '<td><button type="button" style="border-radius: 5px;" class="btn btn-danger py-0 btn-sm remove-row">Remove</button></td>' +
                                '</tr>'
                            );

                            $('#code').val('');
                            $('#firstname').val('');
                            $('#middlename').val('');
                            $('#lastname').val('');
                            $('#sender').val('');
                            $('#amount').val('');
                            $('#proof').val('');
                        }
                    });
                } else {
                    // Combine customer name
                    var customer = firstname + ' ' + middlename + ' ' + lastname;
                    var channelText = $('#channel').find('option:selected').text();
                    // Append new row to the table
                    $('#detailsTable tbody').append(
                        '<tr data-channel="' + channel + '" data-firstname="' + firstname + '" data-middlename="' + middlename + '" data-lastname="' + lastname + '">' +
                        '<td>' + channelText + '</td>' +
                        '<td>' + code + '</td>' +
                        '<td>' + sender + '</td>' +
                        '<td>' + customer + '</td>' +
                        '<td>' + amount + '</td>' +
                        '<td>' + '' + '</td>' +
                        '<td><button type="button" style="border-radius: 5px;" class="btn btn-danger py-0 btn-sm remove-row">Remove</button></td>' +
                        '</tr>'
                    );

                    $('#code').val('');
                    $('#firstname').val('');
                    $('#middlename').val('');
                    $('#lastname').val('');
                    $('#sender').val('');
                    $('#amount').val('');
                    $('#proof').val('');
                }
            });

            $('#detailsTable').on('click', '.remove-row', function () {
                $(this).closest('tr').remove();
            });

            $('.submit-btn').on('click', function (event) {
                event.preventDefault();

                // 1. Collect all rows from the detailsTable
                var rows = [];
                $('#detailsTable tbody tr').each(function () {
                    var $tds = $(this).find('td');
                    rows.push({
                        channel: $(this).data('channel'), // get value
                        code: $tds.eq(1).text(),
                        firstname: $(this).data('firstname'),
                        middlename: $(this).data('middlename'),
                        lastname: $(this).data('lastname'),
                        sender: $tds.eq(2).text(),
                        proof: $tds.eq(5).text(),
                        amount: $tds.eq(4).text()
                    });
                });

                // 2. Get the selected receiver
                var receiver_id = $('#receiver').val();
                var branchid = <?= $branchid ?>;
                // 3. Check if there are rows to submit
                if (rows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Rows',
                        text: 'Please add at least one row before submitting.'
                    });
                    return;
                }

                // 4. Send data via AJAX
                $.ajax({
                    url: '../actions/submit.php',
                    type: 'POST',
                    data: {
                        rows: JSON.stringify(rows),
                        receiver_id: receiver_id,
                        branchid: branchid
                    },
                    success: function (data) {
                        const response = JSON.parse(data);
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Rows submitted successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function () {
                                location.reload();
                            });
                        }
                    }
                });
            });

            $('#editModalform').submit(function (event) {
                event.preventDefault();
                const formData = new FormData(this);
                $.ajax({
                    url: '../actions/edit.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        const response = JSON.parse(data);
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Form updated successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function () {
                                location.reload();
                            });
                        }
                    }
                });
            });

            $(document).on('click', '#cancel', function (e) {
                e.preventDefault();
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Cancel Transaction?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel!'
                })
                    .then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
            });


            $(document).on('click', '#encoded', function (e) {
                e.preventDefault();
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Encode Payment?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                })
                    .then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
            });

            $(document).on('click', '#preview', function (e) {
                e.preventDefault();
                var img = $(this).find('img');
                var imgSrc = img.attr('src');
                if (imgSrc) {
                    $('#imagePreview').attr('src', imgSrc);
                    var viewer = new Viewer(document.getElementById('imagePreview'), {
                        hidden: function () {
                            viewer.destroy();
                        },
                        inline: false,
                        navbar: false,
                        toolbar: {
                            zoomIn: 0,
                            zoomOut: 0,
                            oneToOne: 0,
                            reset: 0,
                            prev: 0,
                            play: 0,
                            next: 0,
                            rotateLeft: 1,
                            rotateRight: 1,
                            flipHorizontal: 0,
                            flipVertical: 0
                        },
                        title: false,
                    });
                    viewer.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Image source not found.',
                        timer: 2000,
                        timerProgressBar: true,
                    });
                }
            });

            setInterval(() => {
                table.ajax.reload(null, false);
            }, 10000);
        });

    </script>
</body>

</html>