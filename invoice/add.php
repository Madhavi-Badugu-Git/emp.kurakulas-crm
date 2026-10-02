<?php 

session_start(); // ✅ Ensure this is at the top!

// print_r($_SESSION); 

include('../includes/dbConfig.php');

include('../includes/validation.php'); 

include('../includes/functions.php'); 



// echo $loggedInUser;

// exit();

?>



<!DOCTYPE html>



<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"

    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">



<?php include('../includes/header.php'); ?>



<body>





    <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0"

            style="display: none; visibility: hidden"></iframe></noscript>

    <!-- End Google Tag Manager (noscript) -->



    <!-- Layout wrapper -->

    <div class="layout-wrapper layout-content-navbar  ">

        <div class="layout-container">



            <!-- side menu -->

            <?php include('../includes/sideMenu.php'); ?>

            <!-- side menu -->



            <!-- Layout container -->

            <div class="layout-page">



                <!-- Navbar -->

                <?php include('../includes/navbar.php'); ?>

                <!-- / Navbar -->



                <!-- Content wrapper -->

                <div class="content-wrapper">



                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">



                        <!-- Basic Layout -->

                        <div class="row">

                            <div class="col-xl">

                                <div class="card mb-6">

                                    <div class="card-header d-flex justify-content-between align-items-center">

                                        <h5 class="mb-0">Add Invoice </h5>

                                    </div>

                                    <div class="card-body">

                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <div class="row mt-2">

                                                <div class="col-md-6">

                                                    <label class="form-label" for="dsa_name">Account DSA Name</label><span

                                                        style="color:red;"> *</span>

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-user-voice"></i></span>

                                                        <select id="dsa_name" name="dsa_name" class="form-select"

                                                            >

                                                            <option value="">Select Account DSA</option>

                                                            <?php

                                                                $query = "SELECT id, dsa_name FROM tbl_account_dsa ORDER BY dsa_name ASC";

                                                                $result = $conn->query($query);

                                                                while ($row = $result->fetch_assoc()) {

                                                                    echo '<option value="'.$row['id'].'">'.$row['dsa_name'].'</option>';

                                                                }

                                                            ?>

                                                        </select>

                                                    </div>

                                                </div>

                                                <div class="col-md-6">

                                                    <label class="form-label" for="vendor_bank">Vendor Bank</label><span

                                                        style="color:red;"> *</span>

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i

                                                                class="bx bx-building"></i></span>

                                                        <select id="vendor_bank" name="vendor_bank" class="form-select"

                                                            >

                                                            <option value="">Select Vendor</option>

                                                            <?php

                                                                $query = "SELECT id, vendor_bank_name FROM tbl_vendor_bank ORDER BY vendor_bank_name ASC";

                                                                $result = $conn->query($query);

                                                                while ($row = $result->fetch_assoc()) {

                                                                    echo '<option value="'.$row['id'].'">'.$row['vendor_bank_name'].'</option>';

                                                                }

                                                            ?>

                                                        </select>

                                                    </div>

                                                </div>

                                            </div>

                                            

                                            <div class="row mt-3">

                                                <div class="col-md-6">

                                                    <label class="form-label" for="loan_type">Type Of Loan</label><span

                                                        style="color:red;"> *</span>

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i

                                                                class="bx bx-briefcase"></i></span>

                                                        <select id="loan_type" name="loan_type" class="form-select">

                                                            <option value="">Select Loan Type</option>

                                                            <?php

                                                                $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";

                                                                $result = $conn->query($query);

                                                                while ($row = $result->fetch_assoc()) {

                                                                    echo '<option value="'.$row['id'].'">'.$row['loan_type'].'</option>';

                                                                }

                                                            ?>

                                                        </select>

                                                    </div>

                                                </div>

                                                <div class="col-md-6">

                                                    <label class="form-label" for="Phone_number">Month / Year</label><span style="color:red;"> *</span>

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i

                                                                class="bx bx-calendar"></i></span>

                                                        <input type="month" id="month_year" name="month_year" class="form-control" placeholder="MM-YYYY">

                                                    </div>

                                                </div>

                                            </div>

                                        

                                            <div class="row mt-3">

                                                <div class="col-md-6">

                                                    <label for="file" class="form-label">Upload File

                                                    </label><span

                                                        style="color:red;"> *</span>

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i

                                                                class="bx bx-file"></i></span>

                                                        <input class="form-control" type="file" id="file"

                                                        name="file" accept="image/*,application/pdf">

                                                    </div>

                                                </div>

                                                <div class="col-md-6">

                                                    <label class="form-label" for="payment_status">Payment Status</label><span

                                                        style="color:red;"> *</span>

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-check-circle"></i></span>

                                                        <select id="payment_status" name="payment_status" class="form-select">

                                                            <option value="">Select Payment Status</option>

                                                            <?php

                                                                $query = "SELECT id, payment_status FROM tbl_invoice_payment_status ORDER BY payment_status ASC";

                                                                $result = $conn->query($query);

                                                                while ($row = $result->fetch_assoc()) {

                                                                    echo '<option value="'.$row['id'].'">'.$row['payment_status'].'</option>';

                                                                }

                                                            ?>

                                                        </select>

                                                    </div>

                                                </div>

                                            </div>

                                            

                                            <div class="row mt-3">

                                                <div class="col-md-6">

                                                    <label class="form-label" for="bank_name">Payment Bank</label><span

                                                        style="color:red;"> *</span>

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i

                                                                class="bx bx-briefcase"></i></span>

                                                        <select id="bank_name" name="bank_name" class="form-select">

                                                            <option value="">Select Payment Bank</option>

                                                            <?php

                                                                $query = "SELECT id, bank_name FROM tbl_account_bank ORDER BY bank_name ASC";

                                                                $result = $conn->query($query);

                                                                while ($row = $result->fetch_assoc()) {

                                                                    echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';

                                                                }

                                                            ?>

                                                        </select>

                                                    </div>

                                                </div>

                                                <div class="col-md-6">

                                                    <label class="form-label" for="Phone_number">Received Amount</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i

                                                                class="bx bx-rupee"></i></span>

                                                        <input type="number" id="received_amount" name="received_amount" class="form-control" placeholder="Received Amount"  step="0.01">

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="row mt-3">

                                                <div class="col-md-6">

                                                    <label class="form-label" for="paid_date">Paid Date</label>

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i

                                                                class="bx bx-calendar"></i></span>

                                                        <input type="date" id="paid_date" name="paid_date" class="form-control" placeholder="MM-DD-YYYY">

                                                    </div>

                                                </div>

                                            </div>



                                            <!-- <div class="text-end"> -->

                                                <input type="submit" name="form_submit" value="Submit"

                                                    class="btn btn-primary mt-3">

                                            <!-- </div> -->

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>



                        <div class="row">

                            <div class="col-xl">

                                <div class="card">

                                    <h5 class="card-header">Invoice List</h5>

                                    <!-- Filter Form -->

                                    <div class="row" style="margin-left:0px;">

                                        <form method="GET">

                                            <div class="row">

                                                <!-- Account DSA Filter -->

                                                <div class="col-md-3">

                                                    <label class="form-label">Account DSA Name</label>

                                                    <select name="dsa_name" class="form-select">

                                                        <option value="">Select Account DSA Name</option>

                                                        <?php

                                                        $query = "SELECT id, dsa_name FROM tbl_account_dsa WHERE status=1 ORDER BY dsa_name ASC";

                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {

                                                            $selected = (!empty($_GET['dsa_name']) && $_GET['dsa_name'] == $row['id']) ? 'selected' : '';

                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['dsa_name'].'</option>';

                                                        }

                                                        ?>

                                                    </select>

                                                </div>



                                                <!-- Vendor Bank Filter -->

                                                <div class="col-md-3">

                                                    <label class="form-label">Vendor Bank Name</label>

                                                    <select name="vendor_bank" class="form-select">

                                                        <option value="">Select Vendor Bank</option>

                                                        <?php

                                                            $query = "SELECT id, vendor_bank_name FROM tbl_vendor_bank WHERE status=1 ORDER BY vendor_bank_name ASC";

                                                            $result = $conn->query($query);

                                                            while ($row = $result->fetch_assoc()) {

                                                                $selected = (isset($_GET['vendor_bank']) && $_GET['vendor_bank'] == $row['id']) ? "selected" : "";

                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['vendor_bank_name'].'</option>';

                                                            }

                                                        ?>

                                                    </select>

                                                </div>



                                                <!-- Type of loan -->

                                                <div class="col-md-3">

                                                    <label class="form-label">Type Of Loan</label>

                                                    <select name="loan_type" class="form-select">

                                                        <option value="">Select Loan Type</option>

                                                        <?php

                                                            $query = "SELECT id, loan_type FROM tbl_loan_type WHERE status=1 ORDER BY loan_type ASC";

                                                            $result = $conn->query($query);

                                                            while ($row = $result->fetch_assoc()) {

                                                                $selected = (isset($_GET['loan_type']) && $_GET['loan_type'] == $row['id']) ? "selected" : "";

                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['loan_type'].'</option>';

                                                            }

                                                        ?>

                                                    </select>

                                                </div>



                                                <!-- Month / Year -->

                                                <div class="col-md-3">

                                                    <label class="form-label">Month / Year</label>

                                                    <input type="month" name="month_year" class="form-control"

                                                        value="<?= !empty($_GET['month_year']) ? $_GET['month_year'] : '' ?>">

                                                </div>



                                                <!-- Buttons -->

                                                <div class="col-md-3 d-flex align-items-end">

                                                    <button type="submit" class="btn btn-primary mt-2">Filter</button>&nbsp;

                                                    <a href="add" class="btn btn-secondary">Reset</a>

                                                </div>



                                            </div>

                                        </form>



                                    </div>

                                    <div class="table-responsive text-nowrap mt-3">

                                        <table class="table">

                                            <thead class="table-dark">

                                                <tr>

                                                    <th>Account DSA Name</th>

                                                    <th>Vendor Bank </th>

                                                    <th>Type Of Loan</th>

                                                    <th>Month Year</th>

                                                    <th>File View/ Download</th>

                                                    <th>Payment Status</th>

                                                    <th>Created By</th>

                                                    <th>Created At</th>



                                                    <?php if ($loggedInUserRank == 'superAdmin' || $loggedInUserDesignation == '17' || $loggedInUserDesignation == '34'  || $loggedInUserDesignation == '13' || $loggedInUserDesignation == '14') { ?>

                                                        <th>Actions</th>

                                                    <?php } ?>

                                                </tr>

                                            </thead>



                                            <tbody>

                                            <?php

                                            // ---------------- WHERE CONDITIONS ----------------

                                            $where = "WHERE 1";



                                            if (!empty($_GET['dsa_name'])) {

                                                $dsa = mysqli_real_escape_string($conn, $_GET['dsa_name']);

                                                $where .= " AND i.dsa_name = '$dsa'";

                                            }



                                            if (!empty($_GET['vendor_bank'])) {

                                                $bank = mysqli_real_escape_string($conn, $_GET['vendor_bank']);

                                                $where .= " AND i.vendor_bank = '$bank'";

                                            }



                                            if (!empty($_GET['loan_type'])) {

                                                $bank = mysqli_real_escape_string($conn, $_GET['loan_type']);

                                                $where .= " AND i.loan_type = '$bank'";

                                            }



                                            if (!empty($_GET['month_year'])) {

                                                $monthYear = mysqli_real_escape_string($conn, $_GET['month_year']);

                                                $where .= " AND i.month_year = '$monthYear'";

                                            }

                                            // ---------------- PAGINATION ----------------

                                            $limit = 10;

                                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                                            if ($page < 1) $page = 1;

                                            $offset = ($page - 1) * $limit;



                                            $countQuery = "

                                                SELECT COUNT(*) AS total 

                                                FROM tbl_invoice i

                                                $where

                                            ";

                                            $countResult = mysqli_query($conn, $countQuery);

                                            $totalRows = mysqli_fetch_assoc($countResult)['total'];

                                            $totalPages = ceil($totalRows / $limit);



                                            // ---------------- MAIN QUERY ----------------

                                            $sql = mysqli_query($conn, "

                                                SELECT 

                                                    *

                                                FROM tbl_invoice i

                                                $where

                                                ORDER BY i.id DESC

                                                LIMIT $offset, $limit

                                            ");



                                            // ---------------- DATA DISPLAY ----------------

                                            if (mysqli_num_rows($sql) > 0) {

                                                while ($row = mysqli_fetch_assoc($sql)) {

                                                ?>

                                                <tr>

                                                    <td><?= fetchColumnValue($conn, 'tbl_account_dsa', 'id', $row['dsa_name'], 'dsa_name'); ?></td>

                                                    <td><?= fetchColumnValue($conn, 'tbl_vendor_bank', 'id', $row['vendor_bank'], 'vendor_bank_name'); ?></td>

                                                    <td><?= fetchColumnValue($conn, 'tbl_loan_type', 'id', $row['loan_type'], 'loan_type'); ?></td>

                                                    <td>

                                                        <?= (!empty($row['month_year']) && $row['month_year'] !== '0000-00-00')

                                                            ? date('F - Y', strtotime($row['month_year']))

                                                            : '' ?>

                                                    </td>

                                                    <td>

                                                        <?php if (!empty($row['uploaded_file']) && $row['uploaded_file'] !== 'default.png') { ?>

                                                            <a href="../uploads/invoice/<?= $row['uploaded_file']; ?>" target="_blank">

                                                                View

                                                            </a>

                                                            |

                                                            <a href="../uploads/invoice/<?= $row['uploaded_file']; ?>" download>

                                                                Download

                                                            </a>

                                                        <?php } else { ?>

                                                            <span class="text-danger">No File Uploaded</span>

                                                        <?php } ?>

                                                    </td>

                                                    <td><?= fetchColumnValue($conn, 'tbl_invoice_payment_status', 'id', $row['payment_status'], 'payment_status'); ?></td>

                                                    <td><?= getCreatedByName($conn, $row['createdBy']); ?></td>

                                                    <td>

                                                        <?= !empty($row['created_at'])

                                                            ? date('d/m/Y', strtotime($row['created_at']))

                                                            : '' ?>

                                                    </td>



                                                    <?php if ($loggedInUserRank == 'superAdmin') { ?>

                                                    <td>

                                                        <div class="dropdown">

                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"

                                                                type="button" data-bs-toggle="dropdown">

                                                                <i class="bx bx-dots-vertical-rounded"></i>

                                                            </button>

                                                            <ul class="dropdown-menu">

                                                                <li>

                                                                    <a class="dropdown-item" href="edit?id=<?= $row['id']; ?>">

                                                                        <i class="bx bx-edit"></i> Edit

                                                                    </a>

                                                                </li>

                                                                <li>

                                                                    <a class="dropdown-item text-danger"

                                                                    href="delete?id=<?= $row['id']; ?>"

                                                                    onclick="return confirm('Are you sure?');">

                                                                        <i class="bx bx-trash"></i> Delete

                                                                    </a>

                                                                </li>

                                                            </ul>

                                                        </div>

                                                    </td>

                                                    <?php } else if($loggedInUserDesignation == '17' || $loggedInUserDesignation == '34' || $loggedInUserDesignation == '13' || $loggedInUserDesignation == '14'){

                                                        ?>

                                                        <td>

                                                            <a href="edit?id=<?= $row['id']; ?>" class="btn btn-primary me-2 text-white">

                                                                <!-- <i class="bx bx-edit"></i>&nbsp;  -->

                                                                Edit

                                                            </a>

                                                        </td>

                                                        <?php

                                                    } ?>

                                                </tr>

                                            <?php

                                                }

                                            } else {

                                                echo '<tr><td colspan="7" class="text-center text-danger">No Records Found.</td></tr>';

                                            }

                                            ?>

                                            </tbody>

                                        </table>



                                        <?php include('../includes/pagination.php'); ?>

                                    </div>



                                </div>

                            </div>

                        </div>



                    </div>

                    <!-- / Content -->



                    <!-- Footer -->

                    <?php include('../includes/footer.php'); ?>

                    <!-- / Footer -->



                    <div class="content-backdrop fade"></div>

                </div>

                <!-- Content wrapper -->

            </div>

            <!-- / Layout page -->

        </div>

        <!-- Overlay -->

        <div class="layout-overlay layout-menu-toggle"></div>



    </div>

    <!-- / Layout wrapper -->

    <?php include('../includes/script.php'); ?>

</body>

</html>



<?php



if (isset($_POST['form_submit'])) {



    $dsa_name = mysqli_real_escape_string($conn, $_POST['dsa_name']);

    $vendor_bank = mysqli_real_escape_string($conn, $_POST['vendor_bank']);

    $loan_type = mysqli_real_escape_string($conn, $_POST['loan_type']);

    $month_year = mysqli_real_escape_string($conn, $_POST['month_year']);

    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);

    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);

    $received_amount = mysqli_real_escape_string($conn, $_POST['received_amount']);

    $paid_date = mysqli_real_escape_string($conn, $_POST['paid_date']);





    $created_at = date('Y-m-d H:i:s');



    if ( empty($dsa_name) || empty($vendor_bank) || empty($loan_type) || empty($month_year) || empty($_FILES['file']['name']) || empty($payment_status) || empty($bank_name) ) {

        echo '<script>

            iziToast.warning({

                title: "Warning",

                message: "Please fill all required fields.",

                position: "topRight"

            });

        </script>';

        exit();

    }



    $target_dir = "../uploads/invoice/";

    $file_name = time() . "_" . basename($_FILES["file"]["name"]);

    $target_file = $target_dir . $file_name;

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));



    $allowed_types = array("jpg", "jpeg", "png", "pdf");



    if (!in_array($file_type, $allowed_types)) {

        echo '<script>

            iziToast.warning({

                title: "Warning",

                message: "Invalid file type. Only JPG, PNG, PDF allowed.",

                position: "topRight"

            });

        </script>';

        exit();

    }



    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

        echo '<script>

            iziToast.error({

                title: "Error",

                message: "File upload failed!",

                position: "topRight"

            });

        </script>';

        exit();

    }



    $file = $file_name;



    $sql = "INSERT INTO `tbl_invoice`

    (`dsa_name`, `vendor_bank`, `loan_type`, `month_year`, `uploaded_file`, `payment_status`, `payment_bank`, `received_amount`,`paid_date`, `createdBy`, `created_at`)

    VALUES

    ('$dsa_name','$vendor_bank','$loan_type','$month_year','$file','$payment_status','$bank_name','$received_amount','$paid_date','$loggedInUser','$created_at')";



    if (mysqli_query($conn, $sql)) {

        echo '<script>

            iziToast.success({

                title: "Success",

                message: "Invoice Added successfully!",

                position: "topRight"

            });

            setTimeout(() => { window.location.href = "add"; }, 1000);

        </script>';

    } else {

        echo '<script>

            iziToast.error({

                title: "Error",

                message: "Failed to Add Invoice. Please try again.",

                position: "topRight"

            });

        </script>';

    }



}

?>

