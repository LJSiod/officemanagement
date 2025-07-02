<?php
error_reporting(0);
session_start();
if (!isset($_SESSION['userid'])) {
  header('Location: ../../index.php');
  exit;
}

$page = basename($_SERVER['REQUEST_URI']);
$name = $_SESSION['name'];
$userid = $_SESSION['userid'];
$branchid = $_SESSION['branchid'];
$username = $_SESSION['username'];
$position = $_SESSION['position'];
$positionid = $_SESSION['positionid'];
$admin = !in_array($positionid, [5, 6, 7]);
?>

<head>
  <link rel="icon" type="image/x-icon" href="../assets/image/neocash.ico">
  <title>Help Desk</title>
  <style>
    .light {
      background-image: url("../assets/image/10.png");
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      background-repeat: no-repeat;
    }

    .dark {
      background-image: url("../assets/image/12.png");
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      background-repeat: no-repeat;
    }

    .selected {
      border-bottom: 3px solid #009688;
      font-weight: bolder;
      color: #0056b3;
      background-color: rgba(0, 150, 135, 0.11);
      padding-bottom: 2px;
    }

    .btn {
      border-radius: 50px;
    }

    .profile {
      width: 220px;
    }
  </style>
</head>

<body id="body">
  <nav class="navbar navbar-expand-lg sticky-top bg-body-tertiary py-0 shadow-sm me-auto">
    <a class="navbar-brand d-flex align-items-center ms-1" href="dashboard.php">
      <img src="../assets/image/Neologo.png" width="30" height="30" class="d-inline-block align-top me-2" alt="">
      <strong style="font-family: Century Gothic, sans-serif;">NEOCASH|Help Desk</strong>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav" style="font-family: Raleway; font-size: 13.5px;">
        <li class="nav-item <?= $page == 'evaluate.php' ? 'selected' : ''; ?>">
          <a href="createticket.php" class="nav-link"><i class="fa fa-plus text-success" aria-hidden="true"></i>
            Create Ticket</a>
        </li>
      </ul>

      <span class="ms-auto navbar-text">
        <div class="form-check form-switch d-flex align-items-center ms-1">
          <input class="form-check-input me-1" type="checkbox" id="darkModeSwitch" checked>
          <label class="form-check-label mt-1 me-1" style="font-size: 0.7rem;" for="darkModeSwitch">Press
            <kbd>F2</kbd> to
            toggle <u><b>Dark mode</b></u></label>
        </div>
      </span>

      <div class="dropdown">
        <a class="btn btn-tertiary dropdown-toggle fw-bold" style="font-size: 0.8rem;" href="#" role="button"
          data-bs-toggle="dropdown" aria-expanded="false">
          <img src="../assets/image/profile.png" style="width: 30px; height: 30px;" name="profile"
            class="rounded-circle mr-2">
          <?= $name; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-end profile" aria-labelledby="dropdownMenuButton">
          <div style="display: flex; align-items: center; justify-content: center;">
            <img src="../assets/image/Neologo.png" class="rounded-circle mt-3" alt="User Image"
              style="width: 90px; height: 90px;">
          </div>
          <h6 class="dropdown-item fw-bold text-center"><?= $name; ?></h6>
          <h6 class="dropdown-item fw-bold text-center small teal" style="margin-top: -10px;">
            <?= $position ?>
          </h6>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item text-danger small" href="../../landing.php"><i class="fa fa-arrow-left"
              aria-hidden="true"></i>
            <b>Back to Page Selection</b></a>
        </div>
      </div>
    </div>
  </nav>

  <nav class="navbar fixed-bottom navbar-light py-0 footer" style="z-index: 0;">
    <a class="navbar-brand strong mr-auto" href="#" style="font-size: 0.7rem; font-family: Fahkwang, sans-serif;">&copy;
      NLI, All Rights Reserved <?php echo date('Y'); ?></a>
    <span class="text-muted" style="font-size: 0.7rem; font-family: Fahkwang, sans-serif;">Version
      <?php echo $_SESSION['version']; ?> | Dev: L J&nbsp;</span>
  </nav>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>

    $(document).ready(function () {
      const htmlElement = $('html');
      const switchElement = $('#darkModeSwitch');
      const wrapper = $('.br-section-wrapper');
      const body = $('#body');
      const sidebar = $('#sidebar');

      const currentTheme = localStorage.getItem('bsTheme') || 'light';
      htmlElement.attr('data-bs-theme', currentTheme);
      switchElement.prop('checked', currentTheme === 'dark');

      switchElement.on('change', function () {
        if ($(this).is(':checked')) {
          htmlElement.attr('data-bs-theme', 'dark');
          localStorage.setItem('bsTheme', 'dark');
          body.removeClass('light');
          body.addClass('dark');
          wrapper.removeClass('bg-light');
          wrapper.addClass('bg-dark');
          sidebar.removeClass('bg-light');
          sidebar.addClass('bg-dark');
        } else {
          htmlElement.attr('data-bs-theme', 'light');
          localStorage.setItem('bsTheme', 'light');
          body.removeClass('dark');
          body.addClass('light');
          wrapper.removeClass('bg-dark');
          wrapper.addClass('bg-light');
          sidebar.removeClass('bg-dark');
          sidebar.addClass('bg-light');
        }
      });

      if (currentTheme === 'light') {
        body.removeClass('dark');
        body.addClass('light');
        wrapper.removeClass('bg-dark');
        wrapper.addClass('bg-light');
        sidebar.removeClass('bg-dark');
        sidebar.addClass('bg-light');

      } else {
        body.removeClass('light');
        body.addClass('dark');
        wrapper.removeClass('bg-light');
        wrapper.addClass('bg-dark');
        sidebar.removeClass('bg-light');
        sidebar.addClass('bg-dark');
      }

      $(document).keydown(function (e) {
        if (e.keyCode == 113) {
          switchElement.click();
        }
      });
    });
  </script>