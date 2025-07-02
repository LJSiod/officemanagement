<?php
session_start();
include '../db.php';
include '../includes/header.php';

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
    <link href="../assets/css/loader.css" rel="stylesheet">
    <title>Remittance Collection</title>
</head>

<style>
    iframe {
        width: 100%;
        height: 90vh;
        border-radius: 15px;
        opacity: 90%;
    }

    .br-pagebody {
        margin-top: 10px;
        margin-bottom: 10px;
        margin-left: auto;
        margin-right: auto;
        max-width: 80vw;
    }

    .br-section-wrapper {
        border-radius: 3px;
        padding: 20px;
        min-height: 90vh;
        box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
        opacity: 95%;
    }
</style>

<body>

    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <h6 class="text-uppercase fw-bold me-auto" style="font-family: Raleway, sans-serif">
                Reports</h6>
            <h6>Select Report Date</h6>
            <form action="" id="reportform" method="">
                <div class="d-flex">
                    <div class="col me-2">
                        <label for="start" class="form-label small">From</label>
                        <input type="date" name="start" id="start" value="<?php echo date('Y-m-d'); ?>"
                            class="form-control form-control-sm me-2" required>
                        <label for="start" class="form-label small">To</label>
                        <input type="date" name="end" id="end" value="<?php echo date('Y-m-d'); ?>"
                            class="form-control form-control-sm me-2" required>
                    </div>
                    <div class="col me-2">
                        <input class="form-check-input" id="allbranch" type="checkbox" checked>
                        <label class="form-label small" for="allbranch">Select All Branch</label>
                        <select class="form-select form-select-sm" id="branchselect" required disabled>
                        </select>
                        <input type="hidden" id="branch" name="branch">
                        <input class="form-check-input" id="allchannel" type="checkbox" checked>
                        <label class="form-label small" for="allchannel">Select All Channel</label>
                        <select class="form-select form-select-sm" id="channelselect" required disabled>
                        </select>
                        <input type="hidden" id="channel" name="channel">
                    </div>
                    <div class="col">
                        <input class="form-check-input" id="allreceiver" type="checkbox" checked>
                        <label class="form-label small" for="allreceiver">Select All Receiver</label>
                        <select class="form-select form-select-sm" id="receiverselect" required disabled>
                        </select>
                        <input type="hidden" id="receiver" name="receiver">
                        <button type="submit" id="generate" class="btn btn-sm btn-primary mt-4">Generate</button>
                    </div>
                </div>
            </form>
            <div class="container mt-2">
                <iframe class="border border-success" src="">
                </iframe>
            </div>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function () {
        $('#branch').val('All Branch');
        $('#channel').val('All Channels');
        $('#receiver').val('All Receivers');

        $(document).off('click', '#generate').on('click', '#generate', function (e) {
            e.preventDefault();
            var overlay = $('<div class="overlay text-center d-flex justify-content-center align-items-center" style="position: fixed; width: 100vw; height: 100vh; z-index: 9000; background-color: rgba(0, 0, 0, 0.3); top: 0; left: 0; display: none"><div class="loader JS_on"><span class="binary"></span><span class="binary"></span><span class="getting-there">GENERATING REPORT</span></div></div>').appendTo('body');
            $.ajax({
                url: '../load/generatereport.php',
                type: 'POST',
                data: new FormData($('#reportform')[0]),
                processData: false,
                contentType: false,
                success: function (result) {
                    var result = JSON.parse(result);
                    overlay.remove();
                    if (result.success == true) {
                        var src = "../temp/" + result.filename;
                        $('iframe').attr('src', src);
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

        $.ajax({
            url: '../load/getchannels.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var select = $('#channelselect');
                select.empty();
                data.forEach(function (channel) {
                    select.append('<option>' + channel.channel + '</option>');
                });
            }
        });

        $.ajax({
            url: '../load/getbranch.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var select = $('#branchselect');
                select.empty();
                data.forEach(function (branch) {
                    select.append('<option>' + branch.name + '</option>');
                });
            }
        });

        $.ajax({
            url: '../load/getreceivers.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var select = $('#receiverselect');
                select.empty();
                data.forEach(function (receiver) {
                    select.append('<option>' + receiver.receivername + '</option>');
                });
            }
        });

        $('#branchselect').on('change', function () {
            $('#branch').val(this.value);
        });

        $('#channelselect').on('change', function () {
            $('#channel').val(this.value);
        });

        $('#receiverselect').on('change', function () {
            $('#receiver').val(this.value);
        });

        $('#allbranch').on('change', function () {
            if (this.checked) {
                $('#branchselect').prop('disabled', true);
                $('#branch').val('All Branch');
            } else {
                $('#branchselect').prop('disabled', false);
                $('#branch').val($('#branchselect option:selected').text());
            }
        });

        $('#allchannel').on('change', function () {
            if (this.checked) {
                $('#channelselect').prop('disabled', true);
                $('#channel').val('All Channels');
            } else {
                $('#channelselect').prop('disabled', false);
                $('#channel').val($('#channelselect option:selected').text());
            }
        });

        $('#allreceiver').on('change', function () {
            if (this.checked) {
                $('#receiverselect').prop('disabled', true);
                $('#receiver').val('All Receivers');
            } else {
                $('#receiverselect').prop('disabled', false);
                $('#receiver').val($('#receiverselect option:selected').text());
            }
        });

    })
</script>

</html>