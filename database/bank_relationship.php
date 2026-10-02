<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="database";</script>';
    exit();
}

$get_id = $_GET['id'];

// echo $get_id;
// exit();

$query = "SELECT * FROM tbl_database WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $get_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("File Status not found!"); window.location.href="database";</script>';
    exit();
}

$database = $result->fetch_assoc();
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

                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="get_id" value="<?= $database['id']; ?>">

                                            <div class="row mt-5">
                                                <h5> Relationship With Bank</h5>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Bank Name
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Type of
                                                                        Loan</th>
                                                                    <th style="width: 15%; min-width: 150px;">
                                                                        Loan
                                                                        Amount</th>
                                                                    <th style="width: 15%; min-width: 150px;">
                                                                        ROI (%)
                                                                    </th>
                                                                    <th style="width: 15%; min-width: 150px;">
                                                                        Tenure
                                                                        (Months)</th>
                                                                    <th style="width: 15%; min-width: 150px;">
                                                                        EMI</th>
                                                                    <th style="width: 15%; min-width: 150px;">
                                                                        First EMI
                                                                        Date</th>
                                                                    <th style="width: 15%; min-width: 150px;">
                                                                        Last EMI
                                                                        Date</th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Loan
                                                                        Account Number</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="loanTable">
                                                                <tr>
                                                                    <td>
                                                                        <select name="r_bank_name[]"
                                                                            class="form-select">
                                                                            <option value="">Select Bank
                                                                            </option>
                                                                            <?php
                                                                    $query = "SELECT id, bank_name FROM tbl_portfolio_bank ORDER BY bank_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                    }
                                                                ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="r_loan_type[]"
                                                                            class="form-select">
                                                                            <option value="">Select Loan
                                                                            </option>
                                                                            <?php
                                                                    $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['loan_type'].'</option>';
                                                                    }
                                                                ?>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number" class="form-control"
                                                                            name="r_loan_amount[]" min="0"
                                                                            placeholder="Amount"></td>
                                                                    <td>

                                                                        <select name="r_roi[]" class="form-select">
                                                                            <option value="">Select ROI</option>
                                                                            <?php
                                                                    $query = "SELECT id, roi_name FROM tbl_roi ORDER BY roi_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['roi_name'].'</option>';
                                                                    }
                                                                ?>
                                                                    </td>
                                                                    <td>

                                                                        <select name="r_tenure[]" class="form-select">
                                                                            <option value="">Select Tenure
                                                                            </option>
                                                                            <?php
                                                                    $query = "SELECT id, tenure_name FROM tbl_tenure ORDER BY tenure_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['tenure_name'].'</option>';
                                                                    }
                                                                ?>
                                                                    </td>
                                                                    <td><input type="number" class="form-control"
                                                                            name="r_emi[]" min="0" placeholder="EMI">
                                                                    </td>
                                                                    <td><input type="date" class="form-control"
                                                                            name="first_emi_date[]"></td>
                                                                    <td><input type="date" class="form-control"
                                                                            name="last_emi_date[]"></td>
                                                                    <td><input type="text" class="form-control"
                                                                            name="loan_account_name[]"
                                                                            placeholder="Account Number"></td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-success action-btn addRow">Add</button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <input type="submit" name="submit_form" class="btn btn-primary mt-10"
                                                    value="Save">
                                            </div>
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
   
    $(document).on("click", ".action-btn", function() {
        let btn = $(this);
        let row = btn.closest("tr");

        if (btn.hasClass("addRow")) {
            let newRow = `<tr>
                            <td>
                                <select name="r_bank_name[]" class="form-select">
                                    <option value="">Select Bank</option>
                                    <?php
                                        $query = "SELECT id, bank_name FROM tbl_portfolio_bank ORDER BY bank_name ASC";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="r_loan_type[]" class="form-select">
                                    <option value="">Select Loan</option>
                                    <?php
                                        $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="'.$row['id'].'">'.$row['loan_type'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td><input type="number" class="form-control" name="r_loan_amount[]" min="0" placeholder="Amount"></td>
                            <td> <select name="r_roi[]"
                                                                            class="form-select">
                                                                            <option value="">Select ROI</option>
                                                                            <?php
                                                                    $query = "SELECT id, roi_name FROM tbl_roi ORDER BY roi_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['roi_name'].'</option>';
                                                                    }
                                                                ?></td>
                            <td> <select name="r_tenure[]"
                                                                            class="form-select">
                                                                            <option value="">Select Tenure</option>
                                                                            <?php
                                                                    $query = "SELECT id, tenure_name FROM tbl_tenure ORDER BY tenure_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['tenure_name'].'</option>';
                                                                    }
                                                                ?></td>
                            <td><input type="number" class="form-control" name="r_emi[]" min="0" placeholder="EMI"></td>
                            <td><input type="date" class="form-control" name="first_emi_date[]"></td>
                            <td><input type="date" class="form-control" name="last_emi_date[]"></td>
                            <td><input type="text" class="form-control" name="loan_account_name[]" placeholder="Account Number"></td>
                            <td>
                                <button type="button" class="btn btn-success action-btn addRow">Add</button>
                            </td>
                        </tr>`;

            $("#loanTable").append(newRow);
            btn.removeClass("btn-primary addRow").addClass("btn-danger removeRow").text("Delete");
        } else if (btn.hasClass("removeRow")) {
            row.remove();
            updateLastRow();
        }
    });

    function updateLastRow() {
        let lastRow = $("#loanTable tr:last");
        lastRow.find(".action-btn").removeClass("btn-danger removeRow").addClass("btn-primary addRow").text(
            "Add");
    }
   
    </script>
</body>

</html>

<?php
// Handle update
if (isset($_POST['submit_form'])) {
    $get_id = $_POST['get_id'];
    $created_at = date('Y-m-d H:i:s');
    $all_success = true;

    if (!empty($_POST['r_bank_name'])) {
        foreach ($_POST['r_bank_name'] as $key => $r_bank_name) {
            $r_bank_name = mysqli_real_escape_string($conn, $r_bank_name);
            $r_loan_type = mysqli_real_escape_string($conn, $_POST['r_loan_type'][$key]);
            $r_loan_amount = mysqli_real_escape_string($conn, $_POST['r_loan_amount'][$key]);
            $r_roi = mysqli_real_escape_string($conn, $_POST['r_roi'][$key]);
            $r_tenure = mysqli_real_escape_string($conn, $_POST['r_tenure'][$key]);  
            $r_emi = mysqli_real_escape_string($conn, $_POST['r_emi'][$key]);     
            $first_emi_date = mysqli_real_escape_string($conn, $_POST['first_emi_date'][$key]);     
            $last_emi_date = mysqli_real_escape_string($conn, $_POST['last_emi_date'][$key]);     
            $loan_account_name = mysqli_real_escape_string($conn, $_POST['loan_account_name'][$key]);     

            $bank_rel_sql = "INSERT INTO `tbl_database_relation_bank`(`database_id`, `r_bank_name`, `r_loan_type`, `r_loan_amount`, `r_roi`, `r_tenure`, `r_emi`, `first_emi_date`, `last_emi_date`, `loan_account_name`, `created_at`) 
                             VALUES ('$get_id', '$r_bank_name', '$r_loan_type', '$r_loan_amount', '$r_roi', '$r_tenure', '$r_emi', '$first_emi_date', '$last_emi_date', '$loan_account_name', '$created_at')";

            if (!mysqli_query($conn, $bank_rel_sql)) {
                $all_success = false;
            }
        }

        if ($all_success) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Relationship with Bank Added Successfully",
                    position: "topRight"
                });
                setTimeout(() => { window.location.href="database"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Some rows failed to insert. Please try again.",
                    position: "topRight"
                });
                setTimeout(() => { window.location.href="database"; }, 1000);
            </script>';
        }
    }
}
?>
