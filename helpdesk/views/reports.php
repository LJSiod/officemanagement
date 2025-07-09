<?php
session_start();
include '../includes/header.php';

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
    <link href="../assets/css/loader.css" rel="stylesheet">
</head>

<style>
    iframe {
        width: 100%;
        height: 90vh;
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
                        <input type="hidden" id="branchname" name="branchname">
                        <input class="form-check-input" id="alltype" type="checkbox" checked>
                        <label class="form-label small" for="alltype">Select All Type</label>
                        <select class="form-select form-select-sm" id="typeselect" required disabled>
                        </select>
                        <input type="hidden" id="type" name="type">
                    </div>
                    <div class="col">
                        <input class="form-check-input" id="alleditor" type="checkbox" checked>
                        <label class="form-label small" for="alleditor">Select All Editor</label>
                        <select class="form-select form-select-sm" id="editorselect" required disabled>
                        </select>
                        <input type="hidden" id="editor" name="editor">
                        <input type="hidden" id="editorname" name="editorname">
                        <button type="submit" id="generate" class="btn btn-sm btn-primary mt-4">Generate</button>
                    </div>
                </div>
            </form>
            <div class="container mt-2">
                <iframe class="border" src="">
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
        $('#branchname').val('All Branch');
        $('#type').val('All Types');
        $('#editor').val('All Editors');
        $('#editorname').val('All Editors');

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
            url: '../load/getall.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var typeselect = $('#typeselect');
                typeselect.empty();
                data.type.forEach(function (type) {
                    typeselect.append('<option>' + type.type + '</option>');
                });
                var branchselect = $('#branchselect');
                branchselect.empty();
                data.branch.forEach(function (branch) {
                    branchselect.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                });
                var editorselect = $('#editorselect');
                editorselect.empty();
                data.editor.forEach(function (editor) {
                    editorselect.append('<option value="' + editor.id + '">' + editor.fullname + '</option>');
                });
            }
        });

        $('#branchselect').on('change', function () {
            $('#branch').val(this.value);
            $('#branchname').val($('option:selected', this).text());
        });

        $('#typeselect').on('change', function () {
            $('#type').val(this.value);
        });

        $('#editorselect').on('change', function () {
            $('#editor').val(this.value);
            $('#editorname').val($('option:selected', this).text());
        });

        $('#allbranch').on('change', function () {
            if (this.checked) {
                $('#branchselect').prop('disabled', true);
                $('#branch').val('All Branch');
                $('#branchname').val('All Branch');
            } else {
                $('#branchselect').prop('disabled', false);
                $('#branch').val($('#branchselect').val());
                $('#branchname').val($('#branchselect option:selected').text());
            }
        });

        $('#alltype').on('change', function () {
            if (this.checked) {
                $('#typeselect').prop('disabled', true);
                $('#type').val('All Types');
            } else {
                $('#typeselect').prop('disabled', false);
                $('#type').val($('#typeselect option:selected').text());
            }
        });

        $('#alleditor').on('change', function () {
            if (this.checked) {
                $('#editorselect').prop('disabled', true);
                $('#editor').val('All Editors');
                $('#editorname').val('All Editors');
            } else {
                $('#editorselect').prop('disabled', false);
                $('#editor').val($('#editorselect').val());
                $('#editorname').val($('#editorselect option:selected').text());
            }
        });

    })
</script>

</html>