<?php
session_start();
include '../../db.php';
include '../includes/header.php';
$today = date('Y-m-d');
$datetime = date('Ymd_his');
$id = $_GET['id'];
$branchid = $_SESSION['branchid'];
$filequery = "SELECT ID, File FROM Requirements WHERE userID = '$id' ORDER BY ID DESC";
$fileresult = mysqli_query($conn, $filequery);
$countquery = "SELECT COUNT(File) as filecount FROM Requirements WHERE userID = '$id' AND File != ''";
$countresult = mysqli_query($conn, $countquery);
$count = mysqli_fetch_assoc($countresult);
$query = "SELECT 
    a.ID,
    b.Name AS BranchName,
    a.PreviousLoanAmount,
    a.AccountStatus,
    a.AccountType,
    a.Cycle,
    a.AccountAllocation,
    a.Address,
    a.ApprovedLoanAmount,
    a.Borrower,
    DATE_FORMAT(a.DateAdded, '%b %d, %Y') AS DateAdded,
    a.FundSource,
    a.LoanPurpose,
    a.MonthlyIncome,
    a.NameofCI,
    a.ProposedLoanAmount,
    a.Remarks,
    a.RequirementsPassed,
    a.Status,
    a.Paid,
    a.Balance,
    l.id as ledgerid,
    l.front as front,
    l.back as back
FROM
    ApprovalInfo AS a 
JOIN
    Branch AS b
ON
    a.BranchID = b.ID
LEFT JOIN
    Ledger AS l ON a.ID = l.userID
WHERE
    a.DateAdded >= '$today' 
AND a.ID = '$id'
ORDER BY a.ID DESC";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" type="image/x-icon">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://unpkg.com/viewerjs@1.11.7/dist/viewer.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<style>
  .br-pagebody {
    margin-left: auto;
    margin-right: auto;
    max-width: 1700px;
  }

  .br-section-wrapper {
    padding: 20px;
    margin-left: 0px;
    margin-right: 0px;
    box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
    opacity: 90%;
  }

  .imagelist {
    width: 120px;
    height: 120px;
    max-width: 120px;
    max-height: 120px;
    border: 1px solid #595757;
    margin: 2px;
  }

  .ledgerprev {
    width: 350px;
    height: 230px;
    max-width: 350px;
    max-height: 230px;
    border: 1px solid #595757;
    margin: 2px;
  }

  .viewer-toolbar .viewer-download {
    background-image: url('../assets/image/download.gif');
    /* Replace with your custom icon */
    background-size: cover;
  }
</style>

<div class="br-pagebody">
  <div class="br-section-wrapper mt-2">
    <?php if ($admin) { ?>
      <div class="d-flex justify-content-between">
        <h6 class="text-uppercase fw-bold">Update</h6>
        <h5 class="fw-bold text-uppercase" style="font-family: Raleway, sans-serif">
          <u><?= $row['BranchName'] ?> Branch</u>
        </h5>
      </div>
      <hr>
    <?php } ?>
    <form method="post" action="../actions/edit.php">
      <input type="hidden" name="rowids" value="<?= $row['ID'] ?>">

      <h6 class="mt-1 text-uppercase">Borrowers Information</h6>
      <div class="row">
        <div class="col-md">
          <strong><label class="text-muted">Name of Borrower</label></strong>
          <input type="text" name="name" id="names" required class="form-control form-control-sm"
            value="<?= $row['Borrower'] ?>">
        </div>

        <div class="col-md">
          <strong><label class="text-muted">Borrower's Address</label></strong>
          <input type="text" name="address" id="addresss" required class="form-control form-control-sm"
            value="<?= $row['Address'] ?>">
        </div>

        <div class="col-md">
          <strong><label class="text-muted">Source of Fund:</label></strong>
          <select class="form-select form-select-sm" id="asourceoffunds" name="sourceoffund">
            <option>Employed</option>
            <option>Self-Employed</option>
          </select>
        </div>

        <div class="col-md">
          <strong><label class="text-muted">Purpose of Loan</label></strong>
          <input type="text" name="purposeofloan" id="purposeofloans" required class="form-control form-control-sm"
            value="<?= $row['LoanPurpose'] ?>">
        </div>

        <div class="col-md">
          <strong><label class="text-muted">Requirements Passed</label></strong>
          <input type="text" name="requirement" id="requirements" required class="form-control form-control-sm"
            value="<?= $row['RequirementsPassed'] ?>">
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md">
          <strong><label class="text-muted">Name of Credit Investigator</label></strong>
          <input type="text" name="ci" id="cis" required class="form-control form-control-sm"
            value="<?= $row['NameofCI'] ?>">
        </div>
        <div class="col-md">
          <strong><label class="text-muted">Estimated Monthly Income</label></strong>
          <input type="number" name="monthlyincome" id="monthlyincomes" required class="form-control form-control-sm"
            value="<?= $row['MonthlyIncome'] ?>">
        </div>
        <div class="col-md">
          <strong><label class="text-muted">Previous Loan Amount</label></strong>
          <input type="number" name="prevs" id="prevs" required class="form-control form-control-sm"
            value="<?= $row['PreviousLoanAmount'] ?>">
        </div>

        <div class="col-md">
          <strong><label class="text-muted">Proposed Loan Amount</label></strong>
          <input type="number" name="proposed" id="proposeds" required class="form-control form-control-sm"
            value="<?= $row['ProposedLoanAmount'] ?>">
        </div>

        <div class="col-md">
          <strong><label class="text-muted">Approved Loan Amount</label></strong>
          <input type="number" name="approved" id="approveds" required class="form-control form-control-sm"
            value="<?= $row['ApprovedLoanAmount'] ?>">
        </div>
      </div>

      <hr>

      <h6 class="text-uppercase">Account Information</h6>
      <div class="row">
        <div class="col-md" id="nocycle">
          <strong><label class="text-muted" for="cycle">No. of Cycle</label></strong>
          <input type="number" name="cycle" id="cycles" required class="form-control form-control-sm"
            value="<?= $row['Cycle']; ?>">
        </div>
        <div class="col-md" id="paidss">
          <strong><label class="text-muted" for="paid">Remaining Payment Count</label></strong>
          <input type="number" name="paid" id="paids" class="form-control form-control-sm" value="<?= $row['Paid']; ?>">
        </div>
        <div class="col-md" id="balancess">
          <strong><label class="text-muted" for="balance">Remaining Balance</label></strong>
          <input type="number" name="balance" id="balances" class="form-control form-control-sm"
            value="<?= $row['Balance']; ?>">
        </div>

        <div class="col-md" id="accountstatuss">
          <strong><label class="text-muted" for="accountstatus">Account Status:</label></strong>
          <select class="form-select form-select-sm" id="accstats" name="accountstatus">
            <option value=""></option>
            <option value="Updated">Updated</option>
            <option value="Days-Delayed">Days-Delayed</option>
            <option value="Past-due">Past-due</option>
            <option value="Expired">Expired</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md">
          <strong><label class="text-muted">Account Type:</label></strong>
          <select class="form-select form-select-sm" name="type" id="acctype">
            <option>New Account</option>
            <option>Renewal</option>
          </select>
        </div>

        <div class="col-md">
          <strong><label class="text-muted">Account Allocation:</label></strong>
          <select class="form-select form-select-sm" id="allocations" name="allocation">
            <option>Neo</option>
            <option>Gen</option>
          </select>
        </div>

        <?php if ($admin) { ?>
          <div class="col-md">
            <strong><label class="text-muted">Borrower Status</label></strong>
            <select class="form-select form-select-sm" id="statuss" name="status">
              <option>APPROVED</option>
              <option>REJECTED</option>
              <option>PENDING</option>
            </select>
          </div>
        <?php } else { ?>
          <div class="col-md">
            <strong><label class="text-muted">Borrower Status</label></strong>
            <input type="text" id="statuss" name="status" class="form-control form-control-sm"
              value="<?= $row['Status']; ?>" readonly>
          </div>
        <?php } ?>

        <div class="col-md">
          <strong><label class="text-muted">Remarks</label></strong>
          <input type="text" id="remarkss" name="remark" class="form-control form-control-sm"
            value="<?= $row['Remarks']; ?>" <?php if (!$admin) {
                echo 'readonly';
              } ?>>
        </div>
      </div>
      <div class="row mt-3" style="min-height: 40vh;">
        <div class="col-md border">
          <strong><label class="text-muted" for="filelist"><?= $count['filecount'] ?> files uploaded</label></strong>
          <ol id="fileList" name="fileList">
            <div class="row" id="images">
              <?php while ($files = mysqli_fetch_assoc($fileresult)):
                if ($files['File'] != null) { ?>
                  <div class="col-md text-start px-0 mx-1">
                    <img class="imagelist" src="<?= $files['File'] ?>" alt="<?= $files['File'] ?>" title="Click to expand">
                    <?php if (!$admin) { ?>
                      <input type="file" title="Select file to update uploaded file." data-id="<?= $files['ID'] ?>"
                        name="file<?= $files['ID'] ?>" style="font-size: 0.7rem" data-type="requirement">
                    <?php } ?>
                  </div>
                <?php }
              endwhile; ?>
            </div>
          </ol>
        </div>

        <div class="col-sm border">

          <strong><label class="text-muted" for="ledgers">Ledgers</label></strong>
          <div class="row" name="ledgers" id="ledgers">
            <?php if ($row['front'] != null) { ?>
              <div class="col-sm">
                <img class="ledgerprev" src="<?= $row['front'] ?>" alt="<?= $row['front'] ?>" title="Click to expand">
                <?php if (!$admin) { ?>
                  <input type="file" data-id="<?= $row['ledgerid'] ?>" data-type="frontledger" style="font-size: 0.7rem">
                <?php } ?>
              </div>
            <?php } ?>

            <?php if ($row['back'] != null) { ?>
              <div class="col-sm">
                <img class="ledgerprev" src="<?= $row['back'] ?>" alt="<?= $row['back'] ?>" title="Click to expand">
                <?php if (!$admin) { ?>
                  <input type="file" data-id="<?= $row['ledgerid'] ?>" data-type="backledger" style="font-size: 0.7rem">
                <?php } ?>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="d-flex mt-2 flex-column flex-sm-row" style="font-family: Raleway, sans-serif">
        <div class="me-auto border p-2 small" id="esc"><span>Press <kbd>ESC</kbd> to <kbd>Close</kbd></span></div>
        <div class="d-flex justify-content-center justify-content-sm-end">
          <button type="submit" name="save" class="btn btn-sm btn-primary text-uppercase me-2 px-4 py-2 fw-bold"
            style="font-size: 12px;">Save Changes</button>
          <button type="button" class="btn btn-sm btn-secondary text-uppercase px-4 py-2 fw-bold"
            onclick="window.history.back()" style="font-size: 12px;">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script src="https://unpkg.com/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<script src="https://unpkg.com/viewerjs@1.11.7/dist/viewer.min.js"></script>
<script>
  const requirements = new Viewer(document.getElementById('images'), {
    viewed() {
      const image = requirements.image;
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
      flipVertical: 0,
      download: function () {
        const link = document.createElement('a');
        link.href = requirements.image.src;
        link.download = '<?= $row['BranchName'] . '_' . $datetime ?>.png';
        link.click();
      },
    },
  });

  const ledgers = new Viewer(document.getElementById('ledgers'), {
    viewed() {
      const image = ledgers.image;
      image.style.border = '1px solid  #595757';
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
      flipVertical: 0,
      download: function () {
        const link = document.createElement('a');
        link.href = ledgers.image.src;
        link.download = '<?= $row['BranchName'] . '_' . $datetime ?>.png';
        link.click();
      },
    },
  });

  $(document).ready(function () {
    $('#esc').show();
    $('#asourceoffunds').val('<?= $row['FundSource']; ?>');
    $('#accstats').val('<?= $row['AccountStatus']; ?>');
    $('#acctype').val('<?= $row['AccountType']; ?>');
    $('#allocations').val('<?= $row['AccountAllocation']; ?>');
    $('#statuss').val('<?= $row['Status']; ?>');

    if ($('#acctype').val() == 'Renewal') {
      $('#accountstatuss').show();
      $('#nocycle').show();
      $('#paidss').show();
      $('#balancess').show();
    } else {
      $('#accountstatuss').hide();
      $('#nocycle').hide();
      $('#paidss').hide();
      $('#balancess').hide();
    }

    $(document).on('keydown', function (event) {
      if (event.keyCode === 27) {
        console.log('Esc key pressed');
        window.history.back();
      }
    });

    $('#acctype').change(function () {
      if ($(this).val() == "Renewal") {
        $('#accountstatuss').show();
        $('#nocycle').show();
        $('#paidss').show();
        $('#balancess').show();
      } else {
        $('#accountstatuss').hide();
        $('#nocycle').hide();
        $('#paidss').hide();
        $('#balancess').hide();
      }
    });
  });

  $(document).on('change', 'input[type="file"]', function (e) {
    const fileInput = $(this);
    const fileId = fileInput.data('id'); // Get the file ID from the data attribute
    const type = fileInput.data('type');
    const formData = new FormData();
    const file = fileInput[0].files[0];

    if (!file.type.match('application/pdf') && !file.type.match('image.*')) {
      Swal.fire('Error', 'Only PDF and image files are allowed!', 'error');
      return;
    }

    formData.append('file', fileInput[0].files[0]); // Append the selected file
    formData.append('fileId', fileId); // Append the file ID
    formData.append('type', type); // Append the file ID

    Swal.fire({
      icon: 'question',
      title: 'Confirm Upload',
      text: `Are you sure you want to upload a new file for this field?`,
      showCancelButton: true,
      confirmButtonText: 'Yes, upload it!',
      cancelButtonText: 'No, cancel',
    }).then((result) => {
      if (result.isConfirmed) {
        // Show a loading indicator (optional)
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
          url: '../actions/updatefile.php', // Path to the PHP script
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            const result = JSON.parse(response);
            if (result.success) {
              Swal.fire({
                icon: 'success',
                title: 'File Updated!',
                text: 'The file has been successfully updated.',
                showConfirmButton: false,
                timer: 1500
              }).then(function () {
                location.reload();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.error || 'An error occurred while uploading the file.',
              });
            }
          },
          error: function () {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to upload the file. Please try again.',
            });
          }
        });
      } else {
        fileInput.val('');
      }
    });
  });

</script>