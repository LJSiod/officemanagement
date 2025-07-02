<?php
session_start();
include '../../db.php';
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
</head>
<style>
  .fileThumbnail {
    /* width: 250px;
    height: 100px; */
    max-width: fit-content;
    max-height: fit-content;
    margin-bottom: 10px;
  }

  .dragover {
    background-color: #1CAF9A;
    transition: all 0.2s ease-in-out;
  }

  .br-pagebody {
    margin-top: 10px;
    margin-left: auto;
    margin-right: auto;
    max-width: 1300px;
  }

  .br-section-wrapper {
    padding: 20px;
    margin-left: 0px;
    margin-right: 0px;
    box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
    opacity: 95%;
  }

  .teal {
    background-color: #1CAF9A;
    color: white;
  }

  .form-layout-footer .btn,
  .form-layout-footer .sp-container button,
  .sp-container .form-layout-footer button {
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 1px;
    font-weight: 500;
    padding: 12px 20px;
  }

  .form-layout-2 .form-group,
  .form-layout-3 .form-group {
    position: relative;
    border: 1px solid #ced4da;
    padding: 20px 20px;
    margin-bottom: 0;
    height: 100%;
  }

  .form-layout-2 .form-group-active,
  .form-layout-3 .form-group-active {
    background-color: #f8f9fa;
  }

  .form-layout-2 .form-control-label,
  .form-layout-3 .form-control-label {
    margin-bottom: 8px;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    display: block;
  }

  .form-layout-2 .form-control,
  .form-layout-2 .dataTables_filter input,
  .dataTables_filter .form-layout-2 input,
  .form-layout-3 .form-control,
  .form-layout-3 .dataTables_filter input,
  .dataTables_filter .form-layout-3 input {
    border: 0;
    padding: 0;
    background-color: transparent;
    border-radius: 0;
    font-weight: 500;
  }

  .form-layout-2 .select2-container--default .select2-selection--single,
  .form-layout-3 .select2-container--default .select2-selection--single {
    background-color: transparent;
    border-color: transparent;
    height: auto;
  }

  .form-layout-2 .select2-container--default .select2-selection--single .select2-selection__rendered,
  .form-layout-3 .select2-container--default .select2-selection--single .select2-selection__rendered {
    padding: 0;
    font-weight: 500;
  }

  .form-layout-2 .select2-container--default .select2-selection--single .select2-selection__arrow,
  .form-layout-3 .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 26px;
  }
</style>

<body>
  <div>
    <div class="br-pagebody">
      <div class="br-section-wrapper">

        <h6 class="text-uppercase">Borrower's Information</h6>

        <form id="formsubmit" method="post" action="#" enctype="multipart/form-data">

          <div class="form-layout form-layout-2">
            <div class="row g-0">

              <div class="col-md-6 mx-0">
                <div class="form-group">
                  <label class="form-control-label">Borrower's Name: <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="name" placeholder="Enter Name">
                </div>
              </div><!-- col-8 -->

              <div class="col-md-3 mx-0">
                <div class="form-group">
                  <label class="form-control-label mg-b-0-force">Source of Fund: <span
                      class="text-danger">*</span></label>
                  <select class="form-select border-0" name="sourceoffund" data-placeholder="Choose source of fund"
                    aria-hidden="true">
                    <option>Employed</option>
                    <option>Self-Employed</option>
                  </select>
                </div>
              </div><!-- col-8 -->

              <div class="col-md-3 mx-0">
                <div class="form-group">
                  <label class="form-control-label mg-b-0-force">Account Type: <span
                      class="text-danger">*</span></label>
                  <select class="form-select border-0" name="type" id="accounttype"
                    data-placeholder="Choose account type" aria-hidden="true">
                    <option>New Account</option>
                    <option>Renewal</option>
                  </select>
                </div>
              </div><!-- col-8 -->

              <div class="col-md-12">
                <div class="form-group">
                  <label class="form-control-label">Borrower's Address: <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="address" required="" value=""
                    placeholder="Enter Address">
                </div>
              </div><!-- col-8 -->

              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-control-label">Estimated Monthly Income: <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="monthlyincome" required="" value=""
                    placeholder="Enter estimated monthly income">
                </div>
              </div><!-- col-8 -->

              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-control-label">Purpose of Loan: <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="purposeofloan" required="" value=""
                    placeholder="Enter Purpose of Loan">
                </div>
              </div><!-- col-8 -->

              <div class="col-md-2">
                <div class="form-group">
                  <label class="form-control-label">Account Allocation: <span class="text-danger">*</span></label>
                  <select class="form-select border-0" name="allocation" data-placeholder="Choose account allocation"
                    aria-hidden="true">
                    <option>Neo</option>
                    <option>Gen</option>
                  </select>
                </div>
              </div><!-- col-8 -->

              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-control-label">Previous Loan Amount: <span class="text-danger">*</span></label>
                  <input class="form-control" type="number" name="previous" required="" value="0"
                    placeholder="Enter Previous Loan Amount">
                </div>
              </div><!-- col-4 -->

              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-control-label">Proposed Loan Amount: <span class="text-danger">*</span></label>
                  <input class="form-control" type="number" name="proposed" required="" value=""
                    placeholder="Enter Proposed Loan Amount">
                </div>
              </div><!-- col-4 -->


              <div class="col-md-4 mg-t--1 mg-md-t-0">
                <div class="form-group mg-md-l--1">
                  <label class="form-control-label">Approved Loan Amount: <span class="text-danger">*</span></label>
                  <input class="form-control" type="number" name="approved" required="" value=""
                    placeholder="Enter Approved Loan Amount">
                </div>
              </div><!-- col-4 -->

              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-control-label">Name of Credit Investigator: <span
                      class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="ci" value="" required=""
                    placeholder="Enter credit investigator">
                </div>
              </div><!-- col-8 -->

              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-control-label">Requirements Passed: <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="requirement" required="" value=""
                    placeholder="Enter requirements passed">
                </div>
              </div><!-- col-8 -->

              <div class="col-md-3 smooth" id="requirement1">
                <div class="form-group" id="drop-area1">
                  <label class="small form-control-label" for="file1">Requirements No. 1: <span
                      class="text-danger small" style="font-size: 0.6rem">*PDF/Images file only</span></label>
                  <img class="form-control form-control-sm fileThumbnail" id="thumbnail1"
                    style="border: 1px solid #e5e5e5; border-radius: 5px;" src="../assets/image/14.gif"
                    alt="File Thumbnail" ondrop="">
                  <input type="file" id="file1" name="file1" style="display: none;">
                  <div class="d-flex">
                    or <span class="btn btn-sm btn-secondary ms-2 mt-2" style="font-size: 0.7rem"
                      id="click-to-choose1">Choose files</span>
                    <span class="small mt-2 ms-2" id="file-name1" name="filename1">No file Chosen</span>
                  </div>
                </div>
              </div>

              <div class="col-md-3" id="addDiv">
                <div class="form-group" style="display: flex; align-items: center;">
                  <button type="button" class="btn btn-secondary" id="addMoreFiles" style="width: 100%;">Add More
                    Files</button>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group" id="accountstatus">
                  <label class="form-control-label mg-b-0-force">Account Status:</label>
                  <select class="form-select border-0" name="accountstatus" data-placeholder="Choose account allocation"
                    aria-hidden="true">
                    <option value=""></option>
                    <option value="Updated">Updated</option>
                    <option value="Days-Delayed">Days-Delayed</option>
                    <option value="Past-due">Past-due</option>
                    <option value="Expired">Expired</option>
                  </select>
                </div>
              </div><!-- col-4 -->


              <div class="col-md-3">
                <div class="form-group" id="cycle">
                  <label class="form-control-label">Number of Cycle: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="number" name="cycle" value="0"
                    placeholder="Enter Approved Loan Amount">
                </div>
              </div><!-- col-4 -->

              <div class="col-md-3">
                <div class="form-group" id="paid">
                  <label class="form-control-label">Remaining Payment Count: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="number" name="paids" value="0" placeholder="Enter Amount Paid">
                </div>
              </div><!-- col-4 -->

              <div class="col-md-3">
                <div class="form-group" id="balance">
                  <label class="form-control-label">Remaining Balance: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="number" name="balances" value="0"
                    placeholder="Enter Remaining Balance">
                </div>
              </div><!-- col-4 -->

              <div class="col-md-3">
                <div class="form-group" id="frontdiv">
                  <label class="small form-control-label" for="filef">Ledger Card Front: <span class="text-danger small"
                      style="font-size: 0.6rem">*PDF/Images file only</span></label>
                  <img class="form-control form-control-sm fileThumbnail" id="thumbnailf"
                    style="border: 1px solid #e5e5e5; border-radius: 5px;" src="../assets/image/14.gif"
                    alt="File Thumbnail" ondrop="">
                  <input type="file" id="ledgerF" name="ledgerf" style="display: none;">
                  <div class="d-flex">
                    or <span class="btn btn-sm btn-secondary ms-2 mt-2" style="font-size: 0.7rem"
                      id="choose-front">Choose files</span>
                    <span class="small mt-2 ms-2" id="ledger-front" name="filenamef">No file Chosen</span>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group" id="backdiv">
                  <label class="small form-control-label" for="fileb">Ledger Card Back: <span class="text-danger small"
                      style="font-size: 0.6rem">*PDF/Images file only</span></label>
                  <img class="form-control form-control-sm fileThumbnail" id="thumbnailb"
                    style="border: 1px solid #e5e5e5; border-radius: 5px;" src="../assets/image/14.gif"
                    alt="File Thumbnail" ondrop="">
                  <input type="file" id="ledgerB" name="ledgerb" style="display: none;">
                  <div class="d-flex">
                    or <span class="btn btn-sm btn-secondary ms-2 mt-2" style="font-size: 0.7rem"
                      id="choose-back">Choose files</span>
                    <span class="small mt-2 ms-2" id="ledger-back" name="filenameb">No file Chosen</span>
                  </div>
                </div>
              </div>

            </div><!-- row -->
            <div class="form-layout-footer">
              <br>
              <input type="text" name="save" value="Submit Borrower" hidden>
              <button class="btn btn-info" type="submit" id="save">Submit Borrower</button>
            </div><!-- form-group -->
          </div>
        </form>

      </div>
    </div><!-- br-pagebody -->
  </div><!-- br-mainpanel -->

</body>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>



<script>
  document.addEventListener("DOMContentLoaded", function (event) {

    var num = 1;
    $('#addMoreFiles').on('click', function () {
      if (num < 12) {
        num++;
        const newDiv = $(`
         <div class="col-md-3" id="requirement${num}">
           <div class="form-group" id="drop-area${num}">
             <label class="small form-control-label" for="file${num}">Requirements No. ${num}: <span
                      class="text-danger small" style="font-size: 0.6rem">*PDF/Images file only</span></label>
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
        $(`#requirement${num - 1}`).after(newDiv);
        if (num === 12) {
          $('#addDiv').hide();
        }
      }
    });
    $(document).on('click', '[id^="click-to-choose"]', function (e) {
      e.preventDefault();
      const num = $(this).attr('id').match(/\d+/)[0];
      $(`#file${num}`).click();
    });

    $(document).on('click', '#choose-front', function (e) {
      e.preventDefault();
      $('#ledgerF').click();
    });

    $(document).on('click', '#choose-back', function (e) {
      e.preventDefault();
      $('#ledgerB').click();
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

    $(document).on('dragover', '#frontdiv', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).addClass('dragover');
    });

    $(document).on('dragover', '#backdiv', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).addClass('dragover');
    });

    $(document).on('dragleave', '[id^="drop-area"]', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass('dragover');
    });

    $(document).on('dragleave', '#frontdiv', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass('dragover');
    });

    $(document).on('dragleave', '#backdiv', function (e) {
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

    $(document).on('change', '#ledgerF', function () {
      var file = this.files[0];
      $('#ledger-front').text(file.name);
      if (file) {
        if (file.type.match('image.*')) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#thumbnailf').attr('src', e.target.result).show();
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
                  $('#thumbnailf').attr('src', canvas.toDataURL('image/png')).show();
                });
              });
            });
          };
          reader.readAsArrayBuffer(file);
        }
      }
    });

    $(document).on('change', '#ledgerB', function () {
      var file = this.files[0];
      $('#ledger-back').text(file.name);
      if (file) {
        if (file.type.match('image.*')) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#thumbnailb').attr('src', e.target.result).show();
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
                  $('#thumbnailb').attr('src', canvas.toDataURL('image/png')).show();
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

    $(document).on('drop', '#frontdiv', function (e) {
      $(this).removeClass('dragover');
      e.preventDefault();
      e.stopPropagation();
      var file = e.originalEvent.dataTransfer.files[0];
      $('#ledgerF').prop('files', e.originalEvent.dataTransfer.files);
      $('#ledger-front').text(file.name);
      var file = e.originalEvent.dataTransfer.files[0];
      if (file) {
        if (file.type.match('image.*')) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#thumbnailf').attr('src', e.target.result).show();
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
                  $('#thumbnailf').attr('src', canvas.toDataURL('image/png')).show();
                });
              });
            });
          };
          reader.readAsArrayBuffer(file);
        }
        $('#ledgerF').prop('files', e.originalEvent.dataTransfer.files);
      }
    });

    $(document).on('drop', '#backdiv', function (e) {
      $(this).removeClass('dragover');
      e.preventDefault();
      e.stopPropagation();
      var file = e.originalEvent.dataTransfer.files[0];
      $('#ledgerB').prop('files', e.originalEvent.dataTransfer.files);
      $('#ledger-back').text(file.name);
      var file = e.originalEvent.dataTransfer.files[0];
      if (file) {
        if (file.type.match('image.*')) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#thumbnailb').attr('src', e.target.result).show();
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
                  $('#thumbnailb').attr('src', canvas.toDataURL('image/png')).show();
                });
              });
            });
          };
          reader.readAsArrayBuffer(file);
        }
        $('#ledgerB').prop('files', e.originalEvent.dataTransfer.files);
      }
    });

    const $purposeSelect = $('#purposeselect');
    $purposeSelect.change(function () {
      if ($(this).val() === 'Others') {
        $('#othertext').show();
      } else {
        $('#othertext').hide();
      }
    });
    $purposeSelect.trigger('change');

    const $accountType = $('#accounttype');
    $accountType.change(function () {
      if ($(this).val() === 'Renewal') {
        $('#accountstatus').show();
        $('#cycle').show();
        $('#paid').show();
        $('#balance').show();
        $('#frontdiv').show();
        $('#backdiv').show();
      } else {
        $('#accountstatus').hide();
        $('#cycle').hide();
        $('#paid').hide();
        $('#balance').hide();
        $('#frontdiv').hide();
        $('#backdiv').hide();
      }
    });
    $accountType.trigger('change');

    $('#formsubmit').on('submit', function (e) {
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

      // $('#formsubmit').submit(function (event) {
      //   event.preventDefault();
      //   const formData = new FormData(this);
      //   Swal.fire({
      //     icon: 'info',
      //     title: 'Uploading...',
      //     allowOutsideClick: false,
      //     showConfirmButton: false,
      //     onBeforeOpen: () => {
      //       Swal.showLoading();
      //     }
      //   });
      //   $.ajax({
      //     url: '../actions/submit.php',
      //     type: 'POST',
      //     data: formData,
      //     cache: false,
      //     contentType: false,
      //     processData: false,
      //     success: function (response) {
      //       Swal.fire({
      //         icon: 'success',
      //         title: 'Success',
      //         text: 'Upload Success',
      //         timer: 1500,
      //         showConfirmButton: false
      //       }).then(function () {
      //         window.location.href = 'dashboard.php';
      //       });
      //     },
      //     error: function (xhr, status, error) {
      //       Swal.fire({
      //         icon: 'error',
      //         title: 'Error',
      //         text: response.error
      //       });
      //     }
      //   });
      // });
    });
  });

</script>

</html>