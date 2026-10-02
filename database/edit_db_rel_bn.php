<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="view?id=' . $get_id . '";</script>';

    exit();
}

$rel_id = $_GET['id'];
$get_id = $_GET['get_id'];
// echo $get_id;

$query = "SELECT * FROM tbl_database_relation_bank WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rel_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Vehicle Id not found!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$rel_bank = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Relation With Bank </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="rel_id" value="<?= $rel_bank['id']; ?>">

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <label for="form-label">Bank Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-building"></i></span>
                                                        <select name="r_bank_name" class="form-select">
                                                            <option value="">Select Bank</option>
                                                            <?php
                                                            $query = "SELECT id, bank_name FROM tbl_portfolio_bank ORDER BY bank_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                $selected = ($row['id'] == $rel_bank['r_bank_name']) ? 'selected' : '';
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['bank_name'].'</option>';
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="form-label">Type of Loan</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-spreadsheet"></i></span>

                                                        <select name="r_loan_type" class="form-select">
                                                        <option value="">Select Loan</option>
                                                        <?php
                                                            $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                               
                                                                $selected = ($row['id'] == $rel_bank['r_loan_type']) ? 'selected' : '';
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['loan_type'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">Loan Amount </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-money"></i></span>
                                                        <input type="text" class="form-control" name="r_loan_amount"
                                                            value="<?= $rel_bank['r_loan_amount']; ?>"
                                                            placeholder="Loan Amount">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="form-label">ROI (%)</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-trending-up"></i></span>
                                                        <select name="r_roi"
                                                            class="form-select">
                                                                                <option value="">Select ROI</option>
                                                                                <?php
                                                                        $query = "SELECT id, roi_name FROM tbl_roi ORDER BY roi_name ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            $selected = ($row['id'] == $rel_bank['r_roi']) ? 'selected' : '';
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['roi_name'].'</option>';
                                                                        }
                                                                    ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">Tenure (Months)</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-time"></i></span>

                                                        <select name="r_tenure" class="form-select">
                                                            <option value="">Select Tenure</option>
                                                            <?php
                                                            $query = "SELECT id, tenure_name FROM tbl_tenure ORDER BY tenure_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {

                                                                $selected = ($row['id'] == $rel_bank['r_tenure']) ? 'selected' : '';
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['tenure_name'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="form-label">EMI</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-credit-card"></i></span>
                                                        <input type="text" class="form-control" name="r_emi"
                                                            value="<?= $rel_bank['r_emi']; ?>"
                                                            placeholder="EMI">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">First EMI Date</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="text" class="form-control" name="first_emi_date" id="first_emi_date"
                                                            value="<?= $rel_bank['first_emi_date']; ?>"
                                                             placeholder="DD/MM/YYYY">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="form-label">Last EMI Date</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="text" class="form-control" name="last_emi_date" id="last_emi_date"
                                                            value="<?= $rel_bank['last_emi_date']; ?>"
                                                            placeholder="DD/MM/YYYY">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">Loan Account Number</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <input type="text" class="form-control" name="loan_account_name"
                                                            value="<?= $rel_bank['loan_account_name']; ?>"
                                                            placeholder="Loan Account Number">
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            <input type="submit" name="update_form" class="btn btn-primary mt-3"
                                                value="Update">
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
    <script>
        // first_emi_date
        document.addEventListener("DOMContentLoaded", function() {
            let dateInput = document.getElementById("first_emi_date");

            // Function to format date as DD/MM/YYYY
            function formatDate(date) {
                let d = new Date(date);
                let day = ("0" + d.getDate()).slice(-2);
                let month = ("0" + (d.getMonth() + 1)).slice(-2);
                let year = d.getFullYear();
                return `${day}/${month}/${year}`;
            }

            // ✅ If there's a prefilled value (e.g., from database), format it properly
            if (dateInput.value) {
                dateInput.value = formatDate(new Date(dateInput.value));
            }

            // ✅ Restrict input to only valid date format (DD/MM/YYYY)
            dateInput.addEventListener("input", function() {
                this.value = this.value.replace(/[^0-9/]/g, "").substring(0, 10);
            });

            // ✅ Convert DD/MM/YYYY to YYYY-MM-DD before form submission
            dateInput.form.addEventListener("submit", function() {
                let parts = dateInput.value.split("/");
                if (parts.length === 3) {
                    let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert to YYYY-MM-DD
                    dateInput.value = formattedDate;
                }
            });
        });

        // last_emi_date
        document.addEventListener("DOMContentLoaded", function() {
            let dateInput = document.getElementById("last_emi_date");

            // Function to format date as DD/MM/YYYY
            function formatDate(date) {
                let d = new Date(date);
                let day = ("0" + d.getDate()).slice(-2);
                let month = ("0" + (d.getMonth() + 1)).slice(-2);
                let year = d.getFullYear();
                return `${day}/${month}/${year}`;
            }

            // ✅ If there's a prefilled value (e.g., from database), format it properly
            if (dateInput.value) {
                dateInput.value = formatDate(new Date(dateInput.value));
            }

            // ✅ Restrict input to only valid date format (DD/MM/YYYY)
            dateInput.addEventListener("input", function() {
                this.value = this.value.replace(/[^0-9/]/g, "").substring(0, 10);
            });

            // ✅ Convert DD/MM/YYYY to YYYY-MM-DD before form submission
            dateInput.form.addEventListener("submit", function() {
                let parts = dateInput.value.split("/");
                if (parts.length === 3) {
                    let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert to YYYY-MM-DD
                    dateInput.value = formattedDate;
                }
            });
        });
    </script>
</body>

</html>

<?php
// Handle update
if (isset($_POST['update_form'])) {
    $rel_id = $_POST['rel_id'];
    $r_bank_name = $_POST['r_bank_name'] ?? '';
    $r_loan_type = $_POST['r_loan_type'] ?? '';
    $r_loan_amount = $_POST['r_loan_amount'] ?? '';
    $r_roi = $_POST['r_roi'] ?? '';
    $r_tenure = $_POST['r_tenure'] ?? '';
    $r_emi = $_POST['r_emi'] ?? '';
    $first_emi_date = $_POST['first_emi_date'] ?? '';
    $last_emi_date = $_POST['last_emi_date'] ?? '';
    $loan_account_name = $_POST['loan_account_name'] ?? '';

    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    // if (empty($r_bank_name) || empty($r_loan_type) || empty($r_loan_amount) || empty($r_roi) || empty($r_tenure) || empty($r_emi) || empty($first_emi_date) || empty($last_emi_date) || empty($loan_account_name)) {
    //     echo '<script>
    //         iziToast.warning({
    //             title: "Error",
    //             message: "All Fields are required",
    //             position: "topRight",
    //         });
    //     </script>';
    //     exit();
    // }

    // Update query
    $sql = "UPDATE tbl_database_relation_bank SET r_bank_name = ?, r_loan_type = ?, r_loan_amount = ?, r_roi = ?, r_tenure = ?, r_emi = ?, first_emi_date = ?, last_emi_date = ?, loan_account_name = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $r_bank_name, $r_loan_type, $r_loan_amount, $r_roi, $r_tenure, $r_emi, $first_emi_date, $last_emi_date, $loan_account_name, $rel_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Relation With Bank Updated Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
        </script>';
    }
}
?>