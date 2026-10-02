<?php 

session_start();

include('../includes/dbConfig.php');

include('../includes/validation.php'); 

include('../includes/functions.php');



// Get ID from URL

if (!isset($_GET['id']) || empty($_GET['id'])) {

    echo '<script>alert("Invalid Request!"); window.location.href="add";</script>';

    exit();

}



$invoice_id = $_GET['id'];



// Fetch Invoice details

$query = "SELECT * FROM tbl_invoice WHERE id = ?";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $invoice_id);

$stmt->execute();

$result = $stmt->get_result();



if ($result->num_rows == 0) {

    echo '<script>alert("Invoice not found!"); window.location.href="add";</script>';

    exit();

}



$invoice = $result->fetch_assoc();

?>



<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"

    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">



<?php include('../includes/header.php'); ?>



<body>



    <div class="layout-wrapper layout-content-navbar">

        <div class="layout-container">

            <?php include('../includes/sideMenu.php'); ?>



            <div class="layout-page">

                <?php include('../includes/navbar.php'); ?>



                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">



                        <div class="row">

                            <div class="col-xl">

                                <div class="card mb-6">

                                    <div class="card-header d-flex justify-content-between align-items-center">

                                        <h5 class="mb-0">Edit Invoice </h5>

                                    </div>

                                    <div class="card-body">

                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="invoice_id" value="<?= $invoice['id']; ?>">



                                            <div class="row mt-2">

                                                <div class="col-md-6">

                                                    <label class="form-label" for="dsa_name">Account DSA Name</label><span

                                                        style="color:red;"> *</span>

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-user-voice"></i></span>

                                                        <select id="dsa_name" name="dsa_name" class="form-select"

                                                            >

                                                            <option value="">Select Account DSA Name</option>

                                                            <?php

                                                                $query = "SELECT id, dsa_name FROM tbl_account_dsa ORDER BY dsa_name ASC";

                                                                $result = $conn->query($query);

                                                               

                                                                while ($dsa = $result->fetch_assoc()) {

                                                                    $selected = ($dsa['id'] == $invoice['dsa_name']) ? 'selected' : '';

                                                                    echo '<option value="'.$dsa['id'].'" '.$selected.'>'.$dsa['dsa_name'].'</option>';

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

                                                            <option value="">Select Vendor Bank</option>

                                                            <?php

                                                                $query = "SELECT id, vendor_bank_name FROM tbl_vendor_bank ORDER BY vendor_bank_name ASC";

                                                                $result = $conn->query($query);

                                                               

                                                                while ($vendor = $result->fetch_assoc()) {

                                                                    $selected = ($vendor['id'] == $invoice['vendor_bank']) ? 'selected' : '';

                                                                    echo '<option value="'.$vendor['id'].'" '.$selected.'>'.$vendor['vendor_bank_name'].'</option>';

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

                                                                

                                                                while ($loan = $result->fetch_assoc()) {

                                                                    $selected = ($loan['id'] == $invoice['loan_type']) ? 'selected' : '';

                                                                    echo '<option value="'.$loan['id'].'" '.$selected.'>'.$loan['loan_type'].'</option>';

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

                                                        <input type="month" id="month_year" name="month_year" class="form-control" value="<?= $invoice['month_year']; ?>" placeholder="MM-YYYY">

                                                    </div>

                                                </div>

                                                

                                            </div>

                                        

                                            <div class="row mt-3">

                                                <div class="col-md-6">

                                                    <label for="file" class="form-label">Upload File

                                                    </label><span style="color:red;"> *</span>

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

                                                                

                                                                while ($status = $result->fetch_assoc()) {

                                                                    $selected = ($status['id'] == $invoice['payment_status']) ? 'selected' : '';

                                                                    echo '<option value="'.$status['id'].'" '.$selected.'>'.$status['payment_status'].'</option>';

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

                                                                

                                                                while ($bank = $result->fetch_assoc()) {

                                                                    $selected = ($bank['id'] == $invoice['payment_bank']) ? 'selected' : '';

                                                                    echo '<option value="'.$bank['id'].'" '.$selected.'>'.$bank['bank_name'].'</option>';

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

                                                        <input type="number" id="received_amount" name="received_amount" value="<?= $invoice['received_amount']; ?>" class="form-control" placeholder="Received Amount" step="0.01">

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="row mt-3">

                                                <div class="col-md-6">

                                                    <label class="form-label" for="paid_date">Paid Date</label>

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i

                                                                class="bx bx-calendar"></i></span>

                                                        <input type="date" id="paid_date" name="paid_date" value="<?= $invoice['paid_date']; ?>" class="form-control" placeholder="MM-DD-YYYY">

                                                    </div>

                                                </div>

                                            </div>



                                            <input type="submit" name="update_invoice" class="btn btn-primary mt-3" value="Update">

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    <?php include('../includes/footer.php'); ?>

                    <div class="content-backdrop fade"></div>

                </div>

            </div>

        </div>

        <div class="layout-overlay layout-menu-toggle"></div>

    </div>



    <?php include('../includes/script.php'); ?>

   



</body>

</html>



<?php

// Handle update

if (isset($_POST['update_invoice'])) {

    $invoice_id = $_POST['invoice_id'];

    $dsa_name = mysqli_real_escape_string($conn, $_POST['dsa_name']);

    $vendor_bank = mysqli_real_escape_string($conn, $_POST['vendor_bank']);

    $loan_type = mysqli_real_escape_string($conn, $_POST['loan_type']);

    $month_year = mysqli_real_escape_string($conn, $_POST['month_year']);

    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);

    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);

    $received_amount = mysqli_real_escape_string($conn, $_POST['received_amount']);

    $paid_date = mysqli_real_escape_string($conn, $_POST['paid_date']);



    $created_at = date('Y-m-d H:i:s');



    // ---------- Validation ----------

    if (empty($dsa_name) || empty($vendor_bank) || empty($loan_type) || empty($month_year) || empty($payment_status) || empty($bank_name) ) {

        echo '<script>

            iziToast.warning({

                title: "Warning",

                message: "Please fill all required fields.",

                position: "topRight"

            });

        </script>';

    } else {



        // Fetch existing file

        $getOldFile = mysqli_query(

            $conn,

            "SELECT uploaded_file FROM tbl_invoice WHERE id = '$invoice_id'"

        );

        $oldData = mysqli_fetch_assoc($getOldFile);

        $oldFile = $oldData['uploaded_file'] ?? '';



        // ---------- File Handling ----------

        $file = $oldFile; // default → keep old file

        $target_dir = "../uploads/invoice/";



        if (!empty($_FILES['file']['name'])) {



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



            // Delete old file (optional but recommended)

            if (!empty($oldFile) && file_exists($target_dir . $oldFile)) {

                unlink($target_dir . $oldFile);

            }



            $file = $file_name;

        }



        // ---------- UPDATE QUERY ----------

        $sql = "UPDATE `tbl_invoice` SET

            `dsa_name`     = '$dsa_name',

            `vendor_bank`    = '$vendor_bank',

            `loan_type`     = '$loan_type',

            `month_year`   = '$month_year',

            `uploaded_file`= '$file',

            `updated_at`   = '$created_at',

            `payment_status` = '$payment_status', 

            `payment_bank` = '$bank_name', 

            `received_amount` = '$received_amount',

            `paid_date` = '$paid_date'

        WHERE `id` = '$invoice_id'";



        if (mysqli_query($conn, $sql)) {

            echo '<script>

                iziToast.success({

                    title: "Success",

                    message: "Invoice updated successfully!",

                    position: "topRight"

                });

                setTimeout(() => { window.location.href = "add"; }, 1000);

            </script>';

        } else {

            echo '<script>

                iziToast.error({

                    title: "Error",

                    message: "Failed to update Invoice. Please try again.",

                    position: "topRight"

                });

            </script>';

        }

    }

}

?>

