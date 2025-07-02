<?php
session_start();
include '../../db.php';
include '../includes/header.php';
date_default_timezone_set('Asia/Manila');

$dateformat = date('F j, Y');
$id = $_SESSION['userid'];
$period = $_SESSION['period'];

$branchid = $_SESSION['branchid'];
$currentdate = date('Y-m-d');

$preview = false;
$formData = [];
if (isset($_GET['id'])) {
    $preview = true;
    $previewId = intval($_GET['id']);
    $query = "SELECT 
                e.*,
                e.id as eid,
                CONCAT(e.FirstName, ' ', e.MiddleName, '. ', e.LastName) AS fullname,
                pr.*
            FROM evaluationinfo e
            LEFT JOIN performancereview pr ON e.prID = pr.id
            WHERE e.id = '$previewId'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $formData = $result->fetch_assoc();
    }
}
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
    <link href="../assets/css/loader.css" rel="stylesheet">
    <title>NLI</title>
    <style>
        table {
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.21);
        }

        th {
            text-align: center;
            font-size: 1rem;
        }

        .form-check-input {
            transform: scale(1.3);
        }

        .br-pagebody {
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
            max-width: 1360px;
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
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="br-pagebody">
            <div class="br-section-wrapper mt-3 col" id="wrapper">
                <div class="d-flex">
                    <h6 class="text-uppercase fw-bold me-auto" style="font-family: Raleway, sans-serif">
                        Performance Review</h6>
                    <span class="text-uppercase fw-bold small"><?php echo $dateformat; ?></span>
                </div>
                <hr>
                <form action="../actions/submit.php" method="POST" id="evaluationform">
                    <div class="d-flex">
                        <h6 class="fw-bold">Employee Information</h6>
                        <span class="fw-light ms-auto">This evaluation is for the period of <u
                                class="fw-bold"><?= $period ?></u>
                        </span>
                    </div>
                    <div class="row">
                        <?php if (!$preview) { ?>
                            <div class="col form-floating">
                                <select class="form-select form-select-sm" name="fullname" id="fullname" required>
                                    <option> </option>

                                </select>
                                <label class="ms-1" for="fullname">Employee Name</label>
                            </div>
                        <?php } else { ?>
                            <div class="col form-floating">
                                <input type="text" class="form-control form-control-sm" name="fullname" id="fullname"
                                    value="<?= $formData['fullname'] ?? '' ?>" readonly required>
                                <label class="ms-1" for="fullname">Employee Name</label>
                            </div>
                        <?php } ?>
                        <div class="col form-floating">
                            <input type="text" class="form-control form-control-sm" name="department" id="department"
                                value="<?= $formData['department'] ?? '' ?>" readonly required>
                            <label class="ms-1" for="department">Department</label>
                        </div>
                        <input type="hidden" id="firstname" name="firstname">
                        <input type="hidden" id="middlename" name="middlename">
                        <input type="hidden" id="lastname" name="lastname">
                    </div>
                    <hr>
                    <span class="mt-3"><b>Direction:</b> Use the following rating interpretation during your
                        observation. Click
                        the button (<input class="form-check-input" name="sampleradio" type="radio">) of the number
                        that corresponds to your rating.</span>
                    <div class="mx-auto" id="ratingTable">
                        <table class="table table-sm table-striped table-bordered mt-3">
                            <thead>
                                <th>RATING</th>
                                <th>INTERPRETATION</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold">5</td>
                                    <td><b>EXCELLENT.</b> The required attributes/behaviors are observed all the
                                        time or
                                        condition
                                        is very extensive and functioning perfectly.</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">4</td>
                                    <td><b>SUPERIOR OR VERY GOOD.</b> The required attributes/behaviors are observed
                                        most of
                                        the time or condition is moderately extensive and functioning well.</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">3</td>
                                    <td><b>GOOD.</b> The required attributes/behaviors are observed sometimes or
                                        condition
                                        is met and functioning adequately.</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">2</td>
                                    <td><b>FAIR.</b> The required attributes/behaviors are observed rarely or
                                        condition
                                        is
                                        limited and functioning minimally.</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">1</td>
                                    <td><b>POOR.</b> The required attributes/behaviors are not observed or condition
                                        is
                                        limited and functioning poorly.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div>
                        <table class="table table-bordered table-striped align-middle table-hover mt-3">
                            <thead class="sticky-top">
                                <tr>
                                    <th rowspan="2" class="text-center align-middle"
                                        style="width:70%; vertical-align:middle;">PERFORMANCE EVALUATION</th>
                                    <th colspan="5" class="text-center align-middle"
                                        style="width:30%; vertical-align:middle;">RATING</th>
                                </tr>
                                <tr>
                                    <th class="text-center">1</th>
                                    <th class="text-center">2</th>
                                    <th class="text-center">3</th>
                                    <th class="text-center">4</th>
                                    <th class="text-center">5</th>
                                </tr>
                            </thead>
                            <tbody id="performancetable">
                                <tr>
                                    <td><b>Job Knowledge</b> (<i>Kaalaman sa Trabaho</i>) - The employee has a
                                        proper
                                        understanding
                                        of his
                                        job and responsibilities</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="jobknowledge" value="1" <?php if (isset($formData['jobKnowledge']) && $formData['jobKnowledge'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="jobknowledge" value="2" <?php if (isset($formData['jobKnowledge']) && $formData['jobKnowledge'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="jobknowledge" value="3" <?php if (isset($formData['jobKnowledge']) && $formData['jobKnowledge'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="jobknowledge" value="4" <?php if (isset($formData['jobKnowledge']) && $formData['jobKnowledge'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="jobknowledge" value="5" <?php if (isset($formData['jobKnowledge']) && $formData['jobKnowledge'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Productivity</b> (<i>Pagiging Produktibo</i>) - The employee is able to
                                        reach
                                        quota
                                        limits and is
                                        able to finish tasks assigned to him/her promptly</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="productivity" value="1" <?php if (isset($formData['productivity']) && $formData['productivity'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="productivity" value="2" <?php if (isset($formData['productivity']) && $formData['productivity'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="productivity" value="3" <?php if (isset($formData['productivity']) && $formData['productivity'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="productivity" value="4" <?php if (isset($formData['productivity']) && $formData['productivity'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="productivity" value="5" <?php if (isset($formData['productivity']) && $formData['productivity'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Work Quality</b> (<i>Kalidad ng Trabaho</i>) - The employee produces work
                                        that is
                                        satisfactory to
                                        the standards of the employer</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="workquality" value="1" <?php if (isset($formData['workQuality']) && $formData['workQuality'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="workquality" value="2" <?php if (isset($formData['workQuality']) && $formData['workQuality'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="workquality" value="3" <?php if (isset($formData['workQuality']) && $formData['workQuality'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="workquality" value="4" <?php if (isset($formData['workQuality']) && $formData['workQuality'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="workquality" value="5" <?php if (isset($formData['workQuality']) && $formData['workQuality'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Technical Skills</b> (<i>Teknikal na Kasanayan</i>) - The employee has
                                        sufficient
                                        technical
                                        skills to perform his/her work</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="technicalskills" value="1" <?php if (isset($formData['technicalSkills']) && $formData['technicalSkills'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="technicalskills" value="2" <?php if (isset($formData['technicalSkills']) && $formData['technicalSkills'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="technicalskills" value="3" <?php if (isset($formData['technicalSkills']) && $formData['technicalSkills'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="technicalskills" value="4" <?php if (isset($formData['technicalSkills']) && $formData['technicalSkills'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="technicalskills" value="5" <?php if (isset($formData['technicalSkills']) && $formData['technicalSkills'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Work Consistency</b> (<i>pagkakapare-pareho ng Trabaho</i>) - The
                                        employee’s
                                        quality of
                                        output is
                                        consistent throughout his/her work</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="workconsistency" value="1" <?php if (isset($formData['workConsistency']) && $formData['workConsistency'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="workconsistency" value="2" <?php if (isset($formData['workConsistency']) && $formData['workConsistency'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="workconsistency" value="3" <?php if (isset($formData['workConsistency']) && $formData['workConsistency'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="workconsistency" value="4" <?php if (isset($formData['workConsistency']) && $formData['workConsistency'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="workconsistency" value="5" <?php if (isset($formData['workConsistency']) && $formData['workConsistency'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Enthusiasm</b> (<i>Sigasig</i>) - The employee is enthusiastic in
                                        performing
                                        his/her
                                        work</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="enthusiasm" value="1" <?php if (isset($formData['enthusiasm']) && $formData['enthusiasm'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="enthusiasm" value="2" <?php if (isset($formData['enthusiasm']) && $formData['enthusiasm'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="enthusiasm" value="3" <?php if (isset($formData['enthusiasm']) && $formData['enthusiasm'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="enthusiasm" value="4" <?php if (isset($formData['enthusiasm']) && $formData['enthusiasm'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="enthusiasm" value="5" <?php if (isset($formData['enthusiasm']) && $formData['enthusiasm'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Cooperation</b> (<i>Kooperasyon</i>) - The employee cooperates well with
                                        his/her
                                        co-employees
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="cooperation" value="1" <?php if (isset($formData['cooperation']) && $formData['cooperation'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="cooperation" value="2" <?php if (isset($formData['cooperation']) && $formData['cooperation'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="cooperation" value="3" <?php if (isset($formData['cooperation']) && $formData['cooperation'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="cooperation" value="4" <?php if (isset($formData['cooperation']) && $formData['cooperation'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="cooperation" value="5" <?php if (isset($formData['cooperation']) && $formData['cooperation'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Attitude</b> (<i>Pag-uugali</i>) - The employee is agreeable and fosters
                                        a
                                        healthy
                                        work
                                        relationship with his/her co-employees</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="attitude" value="1" <?php if (isset($formData['attitude']) && $formData['attitude'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="attitude" value="2" <?php if (isset($formData['attitude']) && $formData['attitude'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="attitude" value="3" <?php if (isset($formData['attitude']) && $formData['attitude'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="attitude" value="4" <?php if (isset($formData['attitude']) && $formData['attitude'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="attitude" value="5" <?php if (isset($formData['attitude']) && $formData['attitude'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Initiative</b> (<i>Pagkukusa</i>) - The employee takes charge of his work
                                        and
                                        is
                                        willing to get
                                        things done.</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="initiative" value="1" <?php if (isset($formData['initiative']) && $formData['initiative'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="initiative" value="2" <?php if (isset($formData['initiative']) && $formData['initiative'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="initiative" value="3" <?php if (isset($formData['initiative']) && $formData['initiative'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="initiative" value="4" <?php if (isset($formData['initiative']) && $formData['initiative'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="initiative" value="5" <?php if (isset($formData['initiative']) && $formData['initiative'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Creativity</b> (<i>Pagkakamalikhain</i>) - The employee is able to
                                        generate
                                        ideas
                                        that may be
                                        useful in solving problems and communicating with others.</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="creativity" value="1" <?php if (isset($formData['creativity']) && $formData['creativity'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="creativity" value="2" <?php if (isset($formData['creativity']) && $formData['creativity'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="creativity" value="3" <?php if (isset($formData['creativity']) && $formData['creativity'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="creativity" value="4" <?php if (isset($formData['creativity']) && $formData['creativity'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="creativity" value="5" <?php if (isset($formData['creativity']) && $formData['creativity'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Punctuality</b> (<i>Pagbibigay ng Oras</i>) - The employee arrives and
                                        leaves
                                        the
                                        workplace on
                                        time.</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="punctuality" value="1" <?php if (isset($formData['punctuality']) && $formData['punctuality'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="punctuality" value="2" <?php if (isset($formData['punctuality']) && $formData['punctuality'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="punctuality" value="3" <?php if (isset($formData['punctuality']) && $formData['punctuality'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="punctuality" value="4" <?php if (isset($formData['punctuality']) && $formData['punctuality'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="punctuality" value="5" <?php if (isset($formData['punctuality']) && $formData['punctuality'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Attendance</b> (<i>Pagdalo</i>) - The employee is present and regularly
                                        attends
                                        work </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="attendance" value="1" <?php if (isset($formData['attendance']) && $formData['attendance'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="attendance" value="2" <?php if (isset($formData['attendance']) && $formData['attendance'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="attendance" value="3" <?php if (isset($formData['attendance']) && $formData['attendance'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="attendance" value="4" <?php if (isset($formData['attendance']) && $formData['attendance'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="attendance" value="5" <?php if (isset($formData['attendance']) && $formData['attendance'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Dependability</b> (<i>Pagiging Maaasahan</i>) - The employee is
                                        trustworthy
                                        and
                                        can
                                        be relied on
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="dependability" value="1" <?php if (isset($formData['dependability']) && $formData['dependability'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="dependability" value="2" <?php if (isset($formData['dependability']) && $formData['dependability'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="dependability" value="3" <?php if (isset($formData['dependability']) && $formData['dependability'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="dependability" value="4" <?php if (isset($formData['dependability']) && $formData['dependability'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="dependability" value="5" <?php if (isset($formData['dependability']) && $formData['dependability'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Communication Skills</b> (<i>Kakayahan sa Pakikipag-usap</i>) - The
                                        employee
                                        is
                                        able to
                                        communicate well with others</td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="commskills" value="1" <?php if (isset($formData['commSkills']) && $formData['commSkills'] == 1)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?> required></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="commskills" value="2" <?php if (isset($formData['commSkills']) && $formData['commSkills'] == 2)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="commskills" value="3" <?php if (isset($formData['commSkills']) && $formData['commSkills'] == 3)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="commskills" value="4" <?php if (isset($formData['commSkills']) && $formData['commSkills'] == 4)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline"><input class="form-check-input"
                                                type="radio" name="commskills" value="5" <?php if (isset($formData['commSkills']) && $formData['commSkills'] == 5)
                                                    echo 'checked'; ?> <?php if ($preview)
                                                           echo 'disabled'; ?>></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Overall Rating:</b></td>
                                    <td colspan="5" class="text-center"><span class="fw-bold" id="overall">
                                            <?php echo isset($formData['overall']) ? $formData['overall'] : '0'; ?></span>
                                    </td>
                                    <input type="hidden" name="overall"
                                        value="<?php echo isset($formData['overall']) ? $formData['overall'] : '0'; ?>"
                                        id="overallInput">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div>
                        <h6 class="text-uppercase fw-bold me-auto" style="font-family: Raleway, sans-serif">
                            Narrative Report</h6>
                        <hr>
                        <span class="mt-3"><b>Instructions:</b> Write the narrative report using English or Filipino
                            only.</span>
                        <div>
                            <label for="relObs" class="mt-3">1. Observations on the Employee's relationship with the
                                management and
                                his/her co-employees: </label>
                            <textarea class="form-control mb-4" rows="6" name="relObs" id="relObs" <?php if ($preview)
                                echo 'disabled'; ?>
                                required><?php echo isset($formData['relObs']) ? $formData['relObs'] : ''; ?></textarea>
                        </div>
                        <div>
                            <label for="knoObs">2. Observations on the Knowledge of the Employee with regards to his/her
                                job: </label>
                            <textarea class="form-control mb-4" rows="6" name="knoObs" id="knoObs" <?php if ($preview)
                                echo 'disabled'; ?>
                                required><?php echo isset($formData['knoObs']) ? $formData['knoObs'] : ''; ?></textarea>
                        </div>
                        <div>
                            <label for="genObs">3. General Observations: </label>
                            <textarea class="form-control" rows="6" name="genObs" id="genObs" <?php if ($preview)
                                echo 'disabled'; ?>
                                required><?php echo isset($formData['genObs']) ? $formData['genObs'] : ''; ?></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <?php if ($preview) { ?>
                            <button class="btn btn-secondary ms-auto" onclick="history.back()" type="button">Close</button>
                        <?php } else { ?>
                            <span class="small"><i><b>Note: </b><u class="text-success">Review Entries Before
                                        Submitting</u></i></span>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        <?php } ?>
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
    <script>
        $(document).ready(function () {

            $('#evaluationform').on('submit', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Submit Form?',
                    text: "Once Received, You won't be able to change it.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            $('input[type="radio"]').on('click', function () {
                var total = 0;
                var checked = 0;
                $('input[type="radio"]:checked').each(function () {
                    if ($(this).attr('name') !== 'sampleradio') {
                        total += parseInt($(this).val());
                        checked++;
                    }
                });
                var average = total / checked;
                $('#overall').text(average.toFixed(1));
                $('#overallInput').val(average.toFixed(1));
            });

            $.ajax({
                url: '../load/getemployees.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var select = $('#fullname');
                    var firstnameinput = $('#firstname');
                    var middlenameinput = $('#middlename');
                    var lastnameinput = $('#lastname');
                    var department = $('#department');
                    data.forEach(function (employee) {
                        var option = $('<option data-department="' + employee.Name + '" data-firstname="' + employee.FirstName + '" data-middlename="' + employee.MiddleName + '" data-lastname="' + employee.LastName + '">' + employee.fullname + '</option>');
                        if (employee.evaluation_exists == 1) {
                            option.attr('disabled', true).attr('title', 'Employee has already been evaluated for this period.');
                            option.prepend('✅ - ');
                        }
                        select.append(option);
                        select.on('change', function () {
                            var selectedOption = $(this).find('option:selected');
                            var departmentName = selectedOption.data('department');
                            var firstname = selectedOption.data('firstname');
                            var middlename = selectedOption.data('middlename');
                            var lastname = selectedOption.data('lastname');
                            firstnameinput.val(firstname);
                            middlenameinput.val(middlename);
                            lastnameinput.val(lastname);
                            department.val(departmentName);
                        });
                    });
                }
            });

        });
    </script>
</body>

</html>