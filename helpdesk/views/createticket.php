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
    <style>
        table {
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
        }

        .dragover {
            background-color: #1CAF9A;
            transition: all 0.2s ease-in-out;
        }

        .br-pagebody {
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
            max-width: 1000px;
        }

        .br-section-wrapper {
            border-radius: 3px;
            padding: 20px;
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
                <form action="" id="ticketform" enctype="multipart/form-data" method="">
                    <h6>Request Details</h6>
                    <div class="form-control-sm">
                        <label class="form-label small ms-1" for="type">Concern Type</label><span
                            class="small text-danger">*</span>
                        <select class="form-select form-select-sm" id="type" name="type">
                            <option>System Edit</option>
                            <option>Technical Issues</option>
                            <option>Others</option>
                        </select>
                    </div>
                    <div class="form-control-sm">
                        <label for="concern" class="form-label small ms-1">Concern</label><span
                            class="small text-danger">*</span>
                        <input type="text" class="form-control form-control-sm" placeholder="" id="concern"
                            name="concern" required>
                    </div>
                    <div class="form-control-sm">
                        <label for="source" class="form-label small ms-1">Source of Document</label><span
                            class="small text-danger">*</span>
                        <input type="text" class="form-control form-control-sm" placeholder="" id="source" name="source"
                            required>
                    </div>
                    <div class="form-control-sm">
                        <label for="borrower" class="form-label small ms-1">Borrower</label><span
                            class="small text-danger">*</span>
                        <input type="text" class="form-control form-control-sm" placeholder="" id="borrower"
                            name="borrower" required>
                    </div>
                    <div class="form-control-sm">
                        <label for="reason" class="form-label small ms-1">Reason</label><span
                            class="small text-danger">*</span>
                        <textarea class="form-control form-control-sm" placeholder="" id="reason" name="reason" rows="5"
                            required></textarea>
                    </div>
                    <div class="form-control-sm">
                        <label for="approvedby" class="form-label small ms-1">Approved By</label><span
                            class="small text-danger">*</span>
                        <input type="text" class="form-control form-control-sm" placeholder="" id="approvedby"
                            name="approvedby" required>
                    </div>
                    <div class="form-control-sm">
                        <label for="sourcefile" class="form-label small">Attach Source of
                            Document</label>
                        <div class="row">
                            <div class="col-md-3 border" id="document1">
                                <div class="form-group p-2" id="drop-area1">
                                    <label class="small form-control-label" for="file1">Document No. 1:
                                        <span class="text-danger small" style="font-size: 0.6rem">*PDF/Image
                                            file
                                            only</span></label>
                                    <img class="form-control form-control-sm fileThumbnail" id="thumbnail1"
                                        style="border: 1px solid #e5e5e5; border-radius: 5px;"
                                        src="../assets/image/14.gif" alt="File Thumbnail" ondrop="">
                                    <input type="file" id="file1" name="file1" style="display: none;">
                                    <div class="d-flex">
                                        or <span class="btn btn-sm btn-secondary ms-2 mt-2" style="font-size: 0.7rem"
                                            id="click-to-choose1">Choose
                                            files</span>
                                        <span class="small mt-2 ms-2" id="file-name1" name="filename1">No
                                            file
                                            Chosen</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" id="addDiv">
                                <div class="form-group">
                                    <button type="button" class="btn btn-outline-secondary p-4"
                                        style="height: 100%; width: 100%; border-radius: 3px;" id="addMoreFiles"
                                        title="Add More Files">
                                        <h1 style="font-size: 8rem;">+</h1>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex"> <button class="btn btn-sm btn-success mt-2 ms-auto submit-btn"
                                style="border-radius: 5px;">Submit</button>
                        </div>
                    </div>
                </form>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        $(document).ready(function () {
            var num = 1;
            $('#addMoreFiles').on('click', function () {
                if (num < 4) {
                    num++;
                    const newDiv = $(`
                     <div class="col-md-3 border" id="document${num}">
                       <div class="form-group p-2" id="drop-area${num}">
                         <label class="small form-control-label" for="file${num}">Document No. ${num}: <span
                                  class="text-danger small" style="font-size: 0.6rem">*PDF/Image file only</span></label>
                         <img class="form-control form-control-sm fileThumbnail" id="thumbnail${num}"
                           style="border: 1px solid #e5e5e5; border-radius: 5px;" src="../assets/image/14.gif"
                           alt="File Thumbnail" ondrop="">
                         <input type="file" id="file${num}" name="file${num}" style="display: none;">
                         <div class="d-flex">
                           or <span class="btn btn-sm btn-secondary ms-2 mt-2" style="font-size: 0.7rem"
                             id="click-to-choose${num}">Choose files</span>
                           <span class="small mt-2 ms-2" id="file-name${num}" name="filename${num}">No file Chosen</span>
                         </div>
                       </div>
                     </div>
                    `);
                    $(`#document${num - 1}`).after(newDiv);
                    if (num === 4) {
                        $('#addDiv').hide();
                    }
                }
            });

            $(document).on('click', '[id^="click-to-choose"]', function (e) {
                e.preventDefault();
                const num = $(this).attr('id').match(/\d+/)[0];
                $(`#file${num}`).click();
            });

            $(document).on('change', '[id^="file"]', function () {
                const num = $(this).attr('id').match(/\d+/)[0];
                $(`#file-name${num}`).text(this.files[0].name);
            });

            $(document).on('dragover', '[id^="drop-area"]', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
            });

            $(document).on('dragleave', '[id^="drop-area"]', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
            });

            $(document).on('change', '[id^="file"]', function () {
                const num = $(this).attr('id').match(/\d+/)[0];
                var file = this.files[0];
                if (file) {
                    if (file.type.match('image.*')) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $(`#thumbnail${num}`).attr('src', e.target.result).show();
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type.match('application/pdf')) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var loadingTask = pdfjsLib.getDocument({ data: e.target.result });
                            loadingTask.promise.then(function (pdf) {
                                pdf.getPage(1).then(function (page) {
                                    var scale = 1.0;
                                    var viewport = page.getViewport({ scale: scale });
                                    var canvas = document.createElement('canvas');
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;
                                    page.render({
                                        canvasContext: context,
                                        viewport: viewport
                                    }).promise.then(function () {
                                        $(`#thumbnail${num}`).attr('src', canvas.toDataURL('image/png')).show();
                                    });
                                });
                            });
                        };
                        reader.readAsArrayBuffer(file);
                    }
                }
            });

            $(document).on('drop', '[id^="drop-area"]', function (e) {
                $(this).removeClass('dragover');
                e.preventDefault();
                e.stopPropagation();
                var file = e.originalEvent.dataTransfer.files[0];
                const num = $(this).attr('id').match(/\d+/)[0];
                $(`#file${num}`).prop('files', e.originalEvent.dataTransfer.files);
                $(`#file-name${num}`).text(file.name);
                var file = e.originalEvent.dataTransfer.files[0];
                if (file) {
                    if (file.type.match('image.*')) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $(`#thumbnail${num}`).attr('src', e.target.result).show();
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type.match('application/pdf')) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var loadingTask = pdfjsLib.getDocument({ data: e.target.result });
                            loadingTask.promise.then(function (pdf) {
                                pdf.getPage(1).then(function (page) {
                                    var scale = 1.0;
                                    var viewport = page.getViewport({ scale: scale });
                                    var canvas = document.createElement('canvas');
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;
                                    page.render({
                                        canvasContext: context,
                                        viewport: viewport
                                    }).promise.then(function () {
                                        $(`#thumbnail${num}`).attr('src', canvas.toDataURL('image/png')).show();
                                    });
                                });
                            });
                        };
                        reader.readAsArrayBuffer(file);
                    }
                }
            });

            $('#ticketform').on('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                Swal.fire({
                    icon: 'info',
                    title: 'Uploading...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: '../actions/submit.php',
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
                                text: 'Form submitted successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function () {
                                window.location.href = 'dashboard.php';
                            });
                        }
                    }
                });
            });
        });

    </script>
</body>

</html>