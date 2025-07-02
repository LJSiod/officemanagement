<?php
error_reporting(0);
session_start();
if (!isset($_SESSION['userid'])) {
  header('Location: ../../index.php');
  exit;
}
$role = $_SESSION['role'];
$name = $_SESSION['name'];
$positionid = $_SESSION['positionid'];
$admin = !in_array($positionid, [5, 6, 7]);
$branchid = $_SESSION['branchid'];
?>

<head>
  <link rel="icon" type="image/x-icon" href="../assets/image/neocash.ico">
  <link href="../assets/css/styles.css" rel="stylesheet">
  <title>Queueing System</title>
  <style>
    .selected {
      border-bottom: 3px solid #009688;
      font-weight: bolder;
      color: #0056b3;
      background-color: rgba(0, 150, 135, 0.11);
      padding-bottom: 2px;
    }

    .navbar-brand {
      color: #fff;
    }

    .navbar-nav .nav-link {
      color: black !important;
      font-size: 15px;
    }

    .strong {
      font-weight: bolder;
    }

    .white {
      color: white;
    }

    .dropdown1:hover .dropdownreport {
      display: block;
    }

    .footer {
      height: 35px;
    }

    .btn {
      border-radius: 50px;
    }

    .profile {
      width: 200px;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg sticky-top bg-light navbar-light py-0 d-print-none">
    <a class="navbar-brand small" href="dashboard.php">
      <img src="../assets/image/Neologo.png" width="30" height="30" class="d-inline-block align-top" alt="">
      <strong style="font-family: Century Gothic;">NEOCASH|Queueing System</strong></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto" style="font-family: Raleway; font-size: 13.5px;">
        <li class="nav-item">
          <a class="nav-link" href="create_ticket.php"><i class="fa fa-plus text-success" aria-hidden="true"></i> Add
            Queue</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="records.php"><i class="fa fa-database text-primary" aria-hidden="true" selected></i>
            Records</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="noterecords.php"><i class="fa fa-clipboard text-success" aria-hidden="true"></i>
            Notes</a>
        </li>

        <?php if ($admin) { ?>
          <li class="nav-item">
            <a class="nav-link" href="summary.php"><i class="fa fa-bar-chart text-warning" aria-hidden="true"></i>
              Reports</a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link" href="branchsummary.php"><i class="fa fa-bar-chart text-warning" aria-hidden="true"></i>
              Reports</a>
          </li>
        <?php } ?>
        <?php if ($positionid == 2) { ?>
          <li class="nav-item">
            <a class="nav-link" href="branchmanage.php"><i class="fa fa-user text-primary" aria-hidden="true"></i> Branch
              Management</a>
          </li>
        <?php } ?>
      </ul>

      <div class="dropdown">
        <a class="btn btn-tertiary dropdown-toggle font-weight-bold" style="font-size: 0.8rem;" href="#" role="button"
          data-toggle="dropdown" aria-expanded="false">
          <img src="../assets/image/profile.png" style="width: 30px; height: 30px;" name="profile"
            class="rounded-circle mr-2">
          <?= $name; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right profile" aria-labelledby="dropdownMenuButton"
          style="z-index: 1000;">
          <div style="display: flex; align-items: center; justify-content: center;">
            <img src="<?php if ($positionid == 2) {
              echo "../assets/image/mcdo.png";
            } else {
              echo "../assets/image/Neologo.png";
            } ?>" class="rounded-circle mt-3" alt="User Image" style="width: 70px; height: 70px;">
          </div>
          <h6 class="dropdown-item font-weight-bold text-center"><?= $name; ?></h6>
          <div id="totalrunning">
            <p class="small text-center">Total Running Collection</p>
            <p class="text-center teal"><strong><u><span id="profiletotal"></u></span></strong></p>
          </div>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item text-danger small" href="../../landing.php"><i class="fa fa-arrow-left"
              aria-hidden="true"></i> <b>Back to Page Selection</b></a>
        </div>
      </div>
    </div>
    </div>
  </nav>

  <!-- <nav class="navbar fixed-bottom navbar-light bg-light footer">
  <a class="navbar-brand strong mr-auto" href="#" style="font-size: 0.7rem; font-family: Fahkwang, sans-serif;">&copy; Queueing System, All Rights Reserved 2025</a>
  <span class="text-muted" style="font-size: 0.7rem; font-family: Fahkwang, sans-serif;">Dev: LJ Siodora | Version <?php echo $_SESSION['version']; ?></span>
</nav> -->


  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.all.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>

    $(document).ready(function () {
      loadoveralltotal();
      setInterval(function () { loadoveralltotal(); }, 10000);

      document.querySelectorAll('.nav-link').forEach(link => {
        if (link.href === window.location.href) {
          link.classList.add('selected');
        }
      });
    });

    $('.dropdown').on('show.bs.dropdown', function () {
      $('.profile').slideDown(200);
    });

    $('.dropdown').on('hide.bs.dropdown', function () {
      $('.profile').slideUp(200);
    });

    function loadoveralltotal() {
      $.ajax({
        url: '../load/dailyrunningcollection.php',
        type: 'GET',
        success: function (data) {
          $('#profiletotal').html("₱" + data.overalltotal);
          $('#c1running').html(data.total1);
          $('#c2running').html(data.total2);
          $('#c3running').html(data.total3);
          $('#c4running').html(data.total4);
        }
      });
    }

    $('#editprofileform').submit(function (event) {
      event.preventDefault();
      if ($('#username').val() !== $('#confirmusername').val()) {
        Swal.fire("Username does not match!", "Please enter the same username in both fields.", "error");
      } else {
        Swal.fire({
          title: "Save Changes?",
          text: "Are you sure you want to save changes to your profile?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, save it!'
        })
          .then((result) => {
            if (result.isConfirmed) {
              this.submit();
            }
          });
      }
    });

    $('#editpasswordform').submit(function (event) {
      event.preventDefault();
      if ($('#password').val() !== $('#confirmpassword').val()) {
        Swal.fire("Password does not match!", "Please enter the same password in both fields.", "error");
      } else {
        Swal.fire({
          title: "Save Changes?",
          text: "Are you sure you want to save changes to your password?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, save it!'
        })
          .then((result) => {
            if (result.isConfirmed) {
              this.submit();
            }
          });
      }
    });

  </script>
</body>

</html>