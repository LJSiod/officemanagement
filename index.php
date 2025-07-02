<?php
error_reporting(0);
session_start();
$servername = "124.106.144.31";
$username = "root";
$password = "neocashmarbel2020";

// $servername = "192.168.0.79";
// $username = "root";
// $password = "admin1234";

$dbname = "payroll";
$officedbname = "officemanagementdb";

$conn = new mysqli($servername, $username, $password, $dbname);
$officeconn = new mysqli($servername, $username, $password, $officedbname);
date_default_timezone_set('Asia/Manila');
$_SESSION['version'] = '1.1.2';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query =
        "SELECT 
            e.ID, 
        	e.FirstName,
        	e.LastName,
        	CONCAT(FirstName, ' ', MiddleName, '. ', LastName) AS fullname,
        	e.Username, 
        	e.Password, 
            e.PositionID,
            e.BranchID as payrollbranchid,
            pp.Name AS positionName,
        	ob.Name AS branchname,
        	ob.id AS branchid
        FROM payroll.employee AS e
        LEFT JOIN payroll.branch AS pb
        	ON e.BranchID = pb.ID
        LEFT JOIN officemanagementdb.branch AS ob
        	ON pb.Name = ob.Name
        LEFT JOIN payroll.position AS pp
        	ON e.PositionID = pp.ID
        WHERE e.EmploymentStatus != 'RESIGNED'
        AND e.PositionID != 4
        AND e.Username = ?
        AND e.Password = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        // if ($user['Username'] == 20240226) {
        //     header("Location: https://www.homecredit.ph/get-installments/get-product-installments-uc2211?utm_source=google&utm_medium=cpc&utm_campaign=online_pm-pos-acqui-google_rsla_lgm_uc_2.2.1.1_pos_rsa_manual_branded_pos-kws_rsa_pos_copy-hcph_lgp_pos_get-product-installments-uc2211_021925&gad_source=1&gad_campaignid=22257539900&gbraid=0AAAAA-qgd_NqqXRnytZ1k7IQjnDH_tqa8&gclid=EAIaIQobChMIlajir-fbjQMVS6lmAh2FkTNJEAAYASAAEgJe2fD_BwE");
        // }

        $_SESSION['IDNumber'] = $user['IDNumber'];
        $_SESSION['positionid'] = $user['PositionID'];
        $admin = !in_array($_SESSION['positionid'], [5, 6, 7]);
        $_SESSION['position'] = $user['positionName'];
        $_SESSION['userid'] = $user['ID'];
        $_SESSION['payrollbranchid'] = $user['payrollbranchid'];
        $_SESSION['branchid'] = $user['branchid'];
        $_SESSION['name'] = $user['fullname'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        $success = "Welcome " . htmlspecialchars($user['fullname'] . "!");
        if ($admin) {
            $files = glob('NLI/uploads/*');
            foreach ($files as $file) {
                if (time() - filemtime($file) > 2 * 24 * 60 * 60) {
                    unlink($file);
                }
            }

            $pdf = glob('NLI/printables/*');
            foreach ($pdf as $file) {
                if (time() - filemtime($file) > 2 * 24 * 60 * 60) {
                    unlink($file);
                }
            }

            $sql = "DELETE FROM approvalinfo WHERE dateadded < CURDATE()";
            $stmt = $officeconn->prepare($sql);
            $stmt->execute();

            $sql1 = "DELETE FROM requirements WHERE dateadded < CURDATE()";
            $stmt1 = $officeconn->prepare($sql1);
            $stmt1->execute();

            $sql2 = "DELETE FROM ledger WHERE date < CURDATE()";
            $stmt2 = $officeconn->prepare($sql2);
            $stmt2->execute();
        }
    } elseif (mysqli_num_rows($result) > 1) {
        $error = "Multiple users found, please contact your administrator.";
    } else {
        $error = "Invalid Credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Management</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="assets/image/oms_logo.ico">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: "Fira Sans", sans-serif;
            color: #0D9849;
        }

        .light {
            background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
            /* background-image: url('assets/image/login_bg.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center; */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form {
            animation: fadeIn 0.5s ease;
            background-color: #fff;
            display: flex;
            flex-direction: row;
            height: auto;
            max-width: 900px;
            width: 100%;
            border-radius: 2rem;
            box-shadow:
                0 4px 24px 0 rgba(13, 152, 73, 0.10),
                0 1.5px 6px 0 rgba(0, 0, 0, 0.08);
            margin: 0 auto;
            overflow: hidden;
            opacity: 95%;
            backdrop-filter: blur(10px);
        }

        .form-logo {
            background-image: url('assets/image/8.png');
            background-size: cover;
            background-position: left;
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-right: 1px solid #ddd;
        }

        .form-logo img {
            max-width: 100%;
            height: auto;
            max-height: 150px;
        }

        .form-logo h5 {
            margin-top: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
        }

        .form-content {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            font-family: Raleway, sans-serif;
            line-height: 1.75rem;
            letter-spacing: 0.10rem;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            color: #0D9849;
            margin-bottom: 20px;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .input-container input {
            width: 100%;
            padding: 10px 40px 10px 40px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }

        .input-container i.fa-lock,
        .input-container i.fa-user {
            position: absolute;
            left: 10px;
            top: 65%;
            transform: translateY(-50%);
            color: #0D9849;
            font-size: 1.5rem;
        }

        .input-container i#togglePassword {
            position: absolute;
            right: 15px;
            top: 80%;
            transform: translateY(-50%);
            color: #0D9849;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .submit {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background-color: #0D9849;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
        }

        .submit:hover {
            background-color: #0d8240;
        }

        .copyright {
            text-align: center;
            color: #555;
            margin-top: 15px;
            font-size: 0.9rem;
            font-style: italic;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form {
                flex-direction: column;
                border-radius: 1rem;
            }

            .form-logo {
                border-right: none;
                border-bottom: 1px solid #ddd;
                padding: 20px;
            }

            .form-content {
                padding: 20px;
            }

            .form-logo img {
                max-height: 150px;
            }

            .form-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body class="light">
    <form class="form mx-3 border" method="POST" action="">
        <div class="form-logo">
            <img src="assets/image/Neologo.png" alt="Logo" draggable="false">
            <h5>NEOCASH LENDING INC.</h5>
            <h6 style="margin-top: -10px; font-size: 0.8rem;">Office Management</h6>
        </div>
        <div class="form-content">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <h6 class="fw-bold"><i class="fa fa-exclamation-triangle text-danger me-2"></i><?php echo $error; ?>!
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <h2 class="form-title">ACCOUNT LOGIN</h2>
            <div class="input-container">
                <i class="fa fa-user fa-2x mr-2"></i>
                <label class="small m-0" for="username">Username</label>
                <input type="text" placeholder="Enter username" name="username" autocomplete="username" required
                    style="padding-left: 3rem;"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="input-container" style="position: relative;">
                <i class="fa fa-lock fa-2x mr-2"></i>
                <label class="small m-0" for="password">Password</label>
                <input type="password" placeholder="Enter password" name="password" autocomplete="password"
                    id="password" style="width: 100%; padding-left: 3rem;" required>
                <i class="fa fa-eye" id="togglePassword"
                    style="position:absolute; right:15px; top:55%; cursor:pointer;"></i>
            </div>
            <button type="submit" class="submit">Sign in</button>
            <div class="copyright">
                NLI|Office Management. &copy; <?php echo date('Y'); ?>. All rights reserved.
            </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {

            $('#togglePassword').on('click', function () {
                const pass = $('#password');
                const type = pass.attr('type') === 'password' ? 'text' : 'password';
                pass.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            <?php if (isset($success)): ?>
                Swal.fire({
                    icon: 'success',
                    title: '<?php echo $success; ?>',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                }).then(function () {
                    window.location.href = "landing.php";
                })
            <?php endif; ?>

        });
    </script>
</body>

</html>