<?php
error_reporting(0);
session_start();
include '../../db.php';
$uploadDir = '../uploads/';
$filecount = count($_FILES);

//initializing variables
if (isset($_POST['save'])) {
    $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
    $dateresult = $date->format('Y-m-d');
    $timenodash = $date->format('dhi');
    $branchid = $_SESSION['branchid'];
    $status = "PENDING";

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $fundsource = mysqli_real_escape_string($conn, $_POST['sourceoffund']);
    $loanpurpose = mysqli_real_escape_string($conn, $_POST['purposeofloan']);
    $monthlyincome = mysqli_real_escape_string($conn, $_POST['monthlyincome']);
    $previousloanamount = mysqli_real_escape_string($conn, $_POST["previous"]);
    $proposedloanamount = mysqli_real_escape_string($conn, $_POST['proposed']);
    $approvedloanamount = mysqli_real_escape_string($conn, $_POST['approved']);
    $requirmentpassed = mysqli_real_escape_string($conn, $_POST['requirement']);
    $ci = mysqli_real_escape_string($conn, $_POST['ci']);
    $accountallocation = mysqli_real_escape_string($conn, $_POST['allocation']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);

    if ($type == "Renewal") {
        $accstat = mysqli_real_escape_string($conn, $_POST['accountstatus']);
        $cycle = mysqli_real_escape_string($conn, $_POST['cycle']);
        $paid = mysqli_real_escape_string($conn, $_POST['paids']);
        $balance = mysqli_real_escape_string($conn, $_POST['balances']);
    } else {
        $accstat = "N/A";
        $cycle = "0";
        $paid = "0";
        $balance = "0";
    }

    //insert string datas
    $query1 = "INSERT INTO `ApprovalInfo`(
        `Borrower`, `Address`, `FundSource`, `MonthlyIncome`, 
        `LoanPurpose`, `PreviousLoanAmount`, `ProposedLoanAmount`, `ApprovedLoanAmount`, 
        `RequirementsPassed`, `NameofCI`, `AccountAllocation`, `Remarks`, `BranchID`, `Status`, 
        `AccountStatus`, `Cycle`, `AccountType`, `DateAdded`, `Paid`, `Balance`) 
        VALUES (
        '$name','$address','$fundsource','$monthlyincome','$loanpurpose',
        '$previousloanamount','$proposedloanamount','$approvedloanamount',
        '$requirmentpassed','$ci','$accountallocation', ' ',$branchid,'$status',
        '$accstat','$cycle','$type','$dateresult','$paid','$balance'
    )";

    //if success, upload files and insert filepath in database
    if (mysqli_query($conn, $query1)) {
        $rowID = mysqli_insert_id($conn);

        for ($i = 1; $i <= $filecount; $i++) {
            $fileTmp = $_FILES["file$i"]['tmp_name'];
            $fileName = $_FILES["file$i"]['name'];
            $filePath = $uploadDir . substr(str_shuffle(MD5(microtime())), 0, 4) . '_' . $fileName; //unique filename to avoid conflict

            if (move_uploaded_file($fileTmp, $filePath)) {
                $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                //convert pdf to png for easier manipulation and system efficiency
                if ($fileExtension === 'pdf') {
                    $outputFile = substr($filePath, 0, strrpos($filePath, '.')) . '.png';
                    $command = "gswin64c -dNOPAUSE -dBATCH -sDEVICE=png16m -r100 -sOutputFile=" . escapeshellarg($outputFile) . " " . escapeshellarg($filePath);
                    exec($command, $output, $returnCode);

                    //insert to approvaldb when conversion is successful
                    if ($returnCode === 0) {
                        $reqquery = "INSERT INTO requirements (userId, File, DateAdded) VALUES ('$rowID', '$outputFile', '$dateresult')";
                        unlink($filePath);
                        if (!mysqli_query($conn, $reqquery)) {
                            echo 'Error inserting file record: ' . mysqli_error($conn);
                        }
                    } else {
                        echo "Error converting PDF to PNG.";
                    }
                    //direct insert to db when file type is an image
                } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $reqquery = "INSERT INTO requirements (userId, File, DateAdded) VALUES ('$rowID', '$filePath', '$dateresult')";
                    if (!mysqli_query($conn, $reqquery)) {
                        echo 'Error inserting file record: ' . mysqli_error($conn);
                    }
                } else {
                    echo "Unsupported file type.";
                }
            }
        }
        //initializing variables for ledger file upload and db insertion
        $fronttmp = $_FILES['ledgerf']['tmp_name'];
        $frontname = $_FILES['ledgerf']['name'];
        $backtmp = $_FILES['ledgerb']['tmp_name'];
        $backname = $_FILES['ledgerb']['name'];
        $front = $uploadDir . substr(str_shuffle(MD5(microtime())), 0, 4) . '_' . $frontname; //unique filename to avoid conflict
        $back = $uploadDir . substr(str_shuffle(MD5(microtime())), 0, 4) . '_' . $backname; //unique filename to avoid conflict

        //check if front or back file is uploaded
        if (!empty($fronttmp) || !empty($backtmp)) {
            $frontPath = null;
            $backPath = null;

            //convert pdf to png for easier manipulation and system efficiency
            if (!empty($fronttmp)) {
                if (move_uploaded_file($fronttmp, $front)) {
                    $fileExtension = strtolower(pathinfo($front, PATHINFO_EXTENSION));

                    if ($fileExtension === 'pdf') {
                        $outputFile = substr($front, 0, strrpos($front, '.')) . '.png';
                        $command = "gswin64c -dNOPAUSE -dBATCH -sDEVICE=png16m -r100 -sOutputFile=" . escapeshellarg($outputFile) . " " . escapeshellarg($front);
                        exec($command, $output, $returnCode);

                        if ($returnCode === 0) {
                            $frontPath = $outputFile;
                            unlink($front);
                        } else {
                            echo "Error converting PDF to PNG.";
                        }
                        //direct insert to db when file type is an image
                    } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $frontPath = $front;
                    }
                } else {
                    echo 'Error moving front file.';
                }

            }

            //convert pdf to png for easier manipulation and system efficiency
            if (!empty($backtmp)) {
                if (move_uploaded_file($backtmp, $back)) {
                    $fileExtension = strtolower(pathinfo($back, PATHINFO_EXTENSION));

                    if ($fileExtension === 'pdf') {
                        $outputFile = substr($back, 0, strrpos($back, '.')) . '.png';
                        $command = "gswin64c -dNOPAUSE -dBATCH -sDEVICE=png16m -r100 -sOutputFile=" . escapeshellarg($outputFile) . " " . escapeshellarg($back);
                        exec($command, $output, $returnCode);

                        if ($returnCode === 0) {
                            $backPath = $outputFile;
                            unlink($back);
                        } else {
                            echo "Error converting PDF to PNG.";
                        }
                        //direct insert to db when file type is an image
                    } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $backPath = $back;
                    }
                } else {
                    echo 'Error moving back file.';
                }
            }

            if ($frontPath || $backPath) {
                $reqquery = "INSERT INTO ledger (userId, front, back, date) VALUES ('$rowID', '$frontPath', '$backPath', '$dateresult')";
                if (!mysqli_query($conn, $reqquery)) {
                    echo 'Error inserting file records: ' . mysqli_error($conn);
                }
            }
        }

        // Return success JSON response to be processed on client side
        echo json_encode(["success" => true, "message" => "Files uploaded and processed successfully."]);
        mysqli_close($conn);
        exit;

    } else {
        echo json_encode(["error" => mysqli_error($conn), "query" => $query1]);
    }
}

?>