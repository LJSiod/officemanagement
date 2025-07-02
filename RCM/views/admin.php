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
            max-height: 90vh;
            height: 90vh;
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

        .active {
            font-weight: bold;
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
                <ul class="nav nav-tabs small" id="recordTabs">
                    <li class="nav-item" style="font-family: Raleway, sans-serif"><a class="nav-link active"
                            data-status="PENDING" href="#">Pending</a></li>
                    <li class="nav-item" style="font-family: Raleway, sans-serif"><a class="nav-link"
                            data-status="RECEIVED" href="#">Received</a></li>
                    <li class="nav-item" style="font-family: Raleway, sans-serif"><a class="nav-link"
                            data-status="ENCODED" href="#">Encoded</a></li>
                </ul>
                <table id="approvaltable" class="table table-striped table-hover table-bordered">
                    <thead class="border" title="Click to Sort">
                        <tr>
                            <th>BRANCH</th>
                            <th>CHANNEL</th>
                            <th>TRANSACTION CODE</th>
                            <th>SENDER</th>
                            <th>NAME</th>
                            <th>AMOUNT</th>
                            <th>DATE/TIME RECEIVED</th>
                            <th>RECEIVER</th>
                            <th>DATE/TIME SENT</th>
                            <th>TRANSBY</th>
                        </tr>
                    </thead>
                    <tbody title="Right Click to Open Menu" style="font-size: 0.8rem;">
                    </tbody>
                </table>
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

            var cachedData = {
                pending: null,
                received: null,
                encoded: null
            };
            var table = $('#approvaltable').DataTable({
                data: [],
                pageLength: 12,
                order: false,
                columns: [
                    { data: 'branchname', render: function (data) { return "<span class='label'>Branch: </span><span class='text-uppercase'>" + data + "</span>"; } },
                    { data: 'channel', render: function (data) { return '<span style="white-space:normal"><span class="label">Channel: </span>' + data + "</span>"; } },
                    { data: "transcode", render: function (data) { return '<span style="white-space:normal"><span class="label">Transaction Code: </span>' + data + "</span>"; } },
                    { data: 'sendername', render: function (data) { return "<span class='label'>Sender: </span><span class='text-uppercase'>" + data + "</span>"; } },
                    { data: 'fullname', render: function (data) { return "<span class='label'>Name: </span><span class='text-uppercase'>" + data + "</span>"; } },
                    {
                        data: 'amount',
                        render: function (data) {
                            return '<span class="label">Amount: </span>' + parseFloat(data).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        }
                    },
                    { data: 'datereceived', render: function (data) { return '<span style="white-space:normal"><span class="label">Date/Time Received: </span>' + data + "</span>"; } },
                    { data: 'receivername', render: function (data) { return '<span style="white-space:normal"><span class="label">Receiver: </span>' + data + "</span>"; } },
                    { data: 'date', render: function (data) { return '<span style="white-space:normal"><span class="label">Date/Time Sent: </span>' + data + "</span>"; } },
                    { data: 'transby', render: function (data) { return '<span style="white-space:normal"><span class="label">Trans by: </span>' + data + "</span>"; } },
                ],
                deferRender: true,
                scrollY: '64vh',
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

            function loadTabData(status) {
                if (cachedData[status]) {
                    table.clear().rows.add(cachedData[status]).draw();
                } else {
                    $.ajax({
                        url: '../load/loadadmin.php',
                        method: 'GET',
                        data: { status: status },
                        dataType: 'json',
                        success: function (response) {
                            cachedData[status] = response.data;
                            table.clear().rows.add(response.data).draw();
                        }
                    });
                }
            }

            $(document).on('contextmenu', function (e) {
                e.preventDefault();
            });

            $(document).on('contextmenu', '#approvaltable tbody tr', function (e) {
                $('.removedrop').remove();
                var rowData = table.row($(this).closest('tr')).data();
                var id = rowData.ID;
                var status = getActiveStatus();
                console.log(rowData);
                var menu = $('<div class="dropdown-menu small removedrop" id="actiondropdown" style="display:block; position:absolute; z-index:1000;">'
                    + '<a class="dropdown-item small"  id="preview" href="#"><i class="fa fa-eye text-info" aria-hidden="true"></i> <span><img src="' + rowData.filepath + '" class="d-none">View Proof of Remittance</span></a>'
                    + (status == 'PENDING' ? '<a class="dropdown-item small" href="../actions/actions.php?action=receive&id=' + rowData.ID + '" id="receive"><i class="fa fa-check-circle text-success" aria-hidden="true"></i> <span>Receive</span></a>' : '')
                    + (status == 'PENDING' ? '<a class="dropdown-item small" href="../actions/actions.php?action=cancel&id=' + rowData.ID + '" id="cancel"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i> <span>Cancel</span></a>' : '')
                    + '</div>').appendTo('body');
                menu.css({ top: e.pageY + 'px', left: e.pageX + 'px' });

                $(document).on('click', '#receive', function (e) {
                    e.preventDefault();
                    var href = $(this).attr('href');
                    Swal.fire({
                        title: 'Payment Received?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, receive!'
                    })
                        .then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = href;
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

                $(document).on('click', function () {
                    menu.remove();
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

            $('#recordTabs .nav-link').on('click', function (e) {
                e.preventDefault();
                $('#recordTabs .nav-link').removeClass('active');
                $(this).addClass('active');
                var status = $(this).data('status');
                loadTabData(status);
            });

            // Load default tab (Pending)
            loadTabData('PENDING');

            function getActiveStatus() {
                return $('#recordTabs .nav-link.active').data('status');
            }


            setInterval(function () {
                var status = getActiveStatus();
                console.log(status);
                $.ajax({
                    url: '../load/loadadmin.php',
                    method: 'GET',
                    data: { status: status },
                    dataType: 'json',
                    success: function (response) {
                        cachedData[status] = response.data;
                        table.clear().rows.add(response.data).draw();
                    }
                });
            }, 30000);

        });

    </script>
</body>

</html>