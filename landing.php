<?php
error_reporting(0);
session_start();
$userid = $_SESSION['userid'];
$user = $_SESSION['name'];
$positionid = $_SESSION['positionid'];
$evaluationadmin = in_array($positionid, [2, 6, 8, 9, 10, 11]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Management - Home</title>
    <link rel="icon" type="image/x-icon" href="assets/image/oms_logo.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/btn.css">
    <style>
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        body {
            background: url('assets/image/greencorner.png');
            background-size: cover;
            background-position: start top;
            background-repeat: no-repeat;
            /* background: #232e2f; */
            color: #fff;
            min-height: 100vh;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .split-container {
            height: 100vh;
            min-height: 100vh;
            display: flex;
            flex-direction: row;
        }

        .left-panel {
            position: relative;
            /* background: url('assets/image/leftpanelbg.webp');
            background-size: cover;
            background-position: bottom;
            background-repeat: no-repeat; */
            /* background: white; */
            background-color: rgba(255, 255, 255, 0.4);
            -webkit-backdrop-filter: blur(4px);
            backdrop-filter: blur(4px);
            color: black;
            flex: 1 1 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 15vw;
        }

        .left-panel .logo-img img {
            width: 100%;
            max-width: 420px;
            height: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .left-panel .company-name {
            font-family: 'Raleway', sans-serif;
            font-size: 3rem;
            font-weight: bold;
        }

        .left-panel .company-desc {
            font-family: 'Roboto', sans-serif;
            font-size: 1.5rem;
            text-align: center;
            letter-spacing: 2px;
        }

        .left-panel-topbar {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.5rem;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 10;
            background: transparent;
        }

        .left-panel-topbar span:last-child {
            margin-left: auto;
        }

        .right-panel {
            flex: 1.5 1 0;
            display: flex;
            align-items: stretch;
            justify-content: stretch;
            /* background: #232e2f; */
            min-width: 340px;
            padding: 0;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr 1fr;
            gap: 0;
            width: 100%;
            height: 100%;
        }

        .card-square {
            background-color: rgba(245, 245, 245, 0.4);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
            border-radius: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-end;
            padding: 2.2rem 1.5rem 1.5rem 1.5rem;
            font-size: 2.5rem;
            font-weight: 600;
            position: relative;
            cursor: pointer;
            box-shadow: none;
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            overflow: hidden;
        }

        .card-square img {
            width: 10vw;
            height: auto;
            transition: transform 0.2s;
        }

        .card-square:hover {
            transform: scale(1.04);
            box-shadow: 0 8px 32px 0 rgba(13, 152, 73, 0.12), 0 2px 8px 0 rgba(0, 0, 0, 0.10);
            opacity: 0.97;
        }

        .card-square:hover img {
            transform: scale(1.08) rotate(-4deg);
        }

        /* Gradient backgrounds for each card */
        /* .bg-1 {
            background-image: linear-gradient(to right top, #88cbda, #7ac5db, #6dbedc, #61b7de, #58b0df, #5ab0e1, #5cb0e3, #5fb0e5, #6ab8e8, #75c0ec, #80c7ef, #8ccff2);
        }

        .bg-2 {
            background-image: linear-gradient(to right top, #c6b6b9, #d1a3aa, #da9097, #e17c81, #e46868, #e56462, #e55f5b, #e55b54, #e56561, #e56f6d, #e47979, #e38384);
        }

        .bg-3 {
            background-image: linear-gradient(to right top, #f2b6ac, #f5ac9b, #f6a388, #f59b73, #f1945e, #ef9654, #ec9949, #e89c3e, #e7a840, #e5b344, #e2bf49, #dfca51);
        }

        .bg-4 {
            background-image: linear-gradient(to right top, #c4d9fd, #acc6ff, #97b3ff, #889eff, #7f88fb, #8880f9, #9178f6, #9b6ff2, #ae76f2, #bf7ef2, #ce86f2, #dc8ff3);
        } */

        @media (max-width: 991.98px) {

            .left-panel .company-name {
                font-size: 2rem;
            }

            .card-square {
                font-size: 2rem;
            }

            .split-container {
                flex-direction: column;
            }

            .left-panel,
            .right-panel {
                min-width: 0;
                width: 100%;
                min-height: 0;
                height: 50vh;
            }

            .left-panel .logo-img img {
                max-width: 180px;
            }

            .right-panel {
                padding: 0;
            }
        }

        @media (max-width: 600px) {
            .left-panel .company-name {
                font-size: 1.5rem;
            }

            .split-container {
                flex-direction: column;
            }

            .left-panel .logo-img img {
                max-width: 180px;
            }

            .left-panel,
            .right-panel {
                min-width: 0;
                width: 100%;
                min-height: 0;
                height: auto;
                overflow: auto;
            }

            .right-panel {
                padding: 0;
            }

            .grid {
                grid-template-columns: 1fr;
                grid-template-rows: repeat(4, 1fr);
                height: 80vh;
            }

            .card-square {
                border-radius: 0;
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="split-container">
        <div class="left-panel" style="position: relative;">
            <div class="left-panel-topbar" style="display: flex; align-items: center; width: 100%; padding: 0.5rem;">
                <div style="display: flex; align-items: center;">
                    <img src="assets/image/Neologo.png" alt="" style="width: 40px; height: 40px;">
                    <span class="fw-bold ms-2">NEOCASH Lending Inc.</span>
                </div>
                <span style="margin-left: auto;">
                    <b>Current User: </b><?php echo htmlspecialchars($user); ?>
                </span>
                <button id="logoutButton" class="Btn ms-2">
                    <div class="sign">
                        <i class="fa fa-sign-out text-light" aria-hidden="true"></i>
                    </div>
                    <div class="text">Logout</div>
                </button>
            </div>

            <!-- <button id="logoutButton" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 mt-2 me-2"
                style="z-index: 10;">
                <i class="fa fa-sign-out"></i> Logout
            </button> -->
            <div class="logo-img">
                <img src="assets/image/oms_logo.webp" alt="Company Logo">
            </div>
            <div class="company-name text-center">OFFICE MANAGEMENT SYSTEM</div>
            <div class="company-desc">VERSION: <?= $_SESSION['version'] ?></div>
        </div>
        <div class="right-panel">
            <div class="grid text-dark">
                <div class="card-square bg-1 border w-100 h-100" onclick="location.href='NLI/views/dashboard.php'">
                    <img src="assets/image/approval2.webp" alt="" title="Go to Loan Approval">
                    Loan Approval
                </div>
                <div class="card-square bg-2 border w-100 h-100" onclick="location.href='queuing/views/dashboard.php'">
                    <img src="assets/image/queue2.webp" alt="" title="Go to Queueing System">

                    Queueing System
                </div>
                <div class="card-square bg-3 border w-100 h-100" onclick="location.href='RCM/views/dashboard.php'">
                    <img src="assets/image/remittance2.webp" alt="" title="Go to RCM System">

                    Remittance Collection
                </div>
                <div class="card-square bg-4 border w-100 h-100" id="evaluationButton">
                    <img src="assets/image/evaluation2.webp" alt="" title="Go to Evaluation System">

                    Evaluation System
                </div>
                <div class="card-square bg-4 border w-100 h-100" onclick="location.href='helpdesk/views/dashboard.php'">
                    <img src="assets/image/helpdesk.webp" alt="" title="Go to Help Desk">

                    IT Help Desk
                </div>
                <!-- <div class="card-square bg-4 border w-100 h-100" id="evaluationButton">
                    <img src="assets/image/evaluation2.webp" alt="" title="Go to Evaluation System">

                    Evaluation System
                </div> -->
            </div>
        </div>
    </div>

    <div class="modal fade" style="font-family: 'Raleway', sans-serif; color: black;" id="editprofileModal"
        tabindex="-1" aria-labelledby="editprofileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="editprofileModalLabel"><i class="fa fa-edit" aria-hidden="true"></i>
                        Edit Profile</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="actions.php" id="editprofileform" method="get">
                        <h6>Username</h6>
                        <input type="hidden" name="action" value="username">
                        <input type="hidden" name="id" value="<?php echo $userid; ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">New Username</label>
                            <input type="text" class="form-control form-control-sm" id="username" name="username"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmusername" class="form-label">Confirm New Username</label>
                            <input type="text" class="form-control form-control-sm" id="confirmusername"
                                name="confirmusername" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
                        </div>
                    </form>
                    <hr>
                    <form action="actions.php" id="editpasswordform" method="get">
                        <h6>Password</h6>
                        <input type="hidden" name="action" value="password">
                        <input type="hidden" name="id" value="<?php echo $userid; ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control form-control-sm" id="password" name="password"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmpassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control form-control-sm" id="confirmpassword"
                                name="confirmpassword" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $('#logoutButton').click(function () {
            Swal.fire({
                title: "Logout?",
                text: "Are you sure you want to logout?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            })
                .then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'logout.php';
                    }
                });
        });

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

        $('#evaluationButton').click(function () {
            <?php if ($evaluationadmin) { ?>
                window.location.href = 'evaluation/views/dashboard.php';
            <?php } else { ?>
                Swal.fire({
                    title: "Unauthorized",
                    text: "You are not authorized to access the Evaluation System.",
                    icon: "error",
                    timer: 2000,
                    showConfirmButton: false
                });
            <?php } ?>
        });
    </script>
</body>

</html>