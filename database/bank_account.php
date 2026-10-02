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

$get_id = $_GET['id'];

// echo $get_id;
// exit();

$query = "SELECT * FROM tbl_database WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $get_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("File Status not found!"); window.location.href="view?id=' . $get_id . '";</script>';
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
                                                <h5>Add Bank Account Details</h5>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Bank Name
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Type Of Account
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Account Number
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Branch Name
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        IFSC Code
                                                                    </th>

                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="bankAccTable">
                                                                <tr>
                                                                    <td>
                                                                        <select name="b_bank_name[]"
                                                                            class="form-select">
                                                                            <option value="">Select Bank
                                                                            </option>
                                                                            <?php
                                                                    $query = "SELECT id, bank_name FROM tbl_bank ORDER BY bank_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                    }
                                                                ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="b_account_type[]"
                                                                            class="form-select">
                                                                            <option value="">Select Account Type
                                                                            </option>
                                                                            <?php
                                                                $query = "SELECT id, account_type FROM tbl_bank_account_type ORDER BY account_type ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['account_type'].'</option>';
                                                                }
                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="b_account_no[]"
                                                                            placeholder="Account Number">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="b_branch_name[]"
                                                                            placeholder="Branch Name">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="b_ifsc_code[]" placeholder="IFSC Code"
                                                                            oninput="this.value = this.value.toUpperCase();">
                                                                    </td>

                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-success action-btn2 addRow2">Add</button>
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
    // bank account table
    $(document).on("click", ".action-btn2", function() {
        let btn = $(this);
        let row = btn.closest("tr");

        if (btn.hasClass("addRow2")) {
            let newRow = `<tr>
                          <td>
                                                                        <select name="b_bank_name[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Bank
                                                                                    </option>
                                                                                    <?php
                                                                        $query = "SELECT id, bank_name FROM tbl_bank ORDER BY bank_name ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                        }
                                                                    ?>
                                                                                </select>
                                                                        </td>
                                                                        <td>
                                                                        <select name="b_account_type[]"
                                                                                class="form-select">
                                                                                <option value="">Select Account Type
                                                                                </option>
                                                                                <?php
                                                                    $query = "SELECT id, account_type FROM tbl_bank_account_type ORDER BY account_type ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['account_type'].'</option>';
                                                                    }
                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="b_account_no[]"
                                                                                placeholder="Account Number">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="b_branch_name[]"
                                                                                placeholder="Branch Name">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="b_ifsc_code[]"
                                                                                placeholder="IFSC Code" oninput="this.value = this.value.toUpperCase();">
                                                                        </td>
                                                                            
                            <td>
                                <button type="button" class="btn btn-success action-btn2 addRow2">Add</button>
                            </td>
                        </tr>`;

            $("#bankAccTable").append(newRow);
            btn.removeClass("btn-primary addRow2").addClass("btn-danger removeRow2").text("Delete");
        } else if (btn.hasClass("removeRow2")) {
            row.remove();
            updateLastRow();
        }
    });

    function updateLastRow() {
        let lastRow = $("#bankAccTable tr:last");
        lastRow.find(".action-btn2").removeClass("btn-danger removeRow2").addClass("btn-primary addRow2").text(
            "Add");
    }
    // bank account table
    </script>
</body>

</html>

<?php
// Handle update
if (isset($_POST['submit_form'])) {
    $get_id = $_POST['get_id'];
    $created_at = date('Y-m-d H:i:s');
    $all_success = true;

    // bank account table
    if (!empty($_POST['b_bank_name'])) {
        foreach ($_POST['b_bank_name'] as $key => $b_bank_name) {
            $b_bank_name = mysqli_real_escape_string($conn, $b_bank_name);
            $b_account_type = mysqli_real_escape_string($conn, $_POST['b_account_type'][$key]);
            $b_account_no = mysqli_real_escape_string($conn, $_POST['b_account_no'][$key]);
            $b_branch_name = mysqli_real_escape_string($conn, $_POST['b_branch_name'][$key]);
            $b_ifsc_code = mysqli_real_escape_string($conn, $_POST['b_ifsc_code'][$key]);     

            $bank_acc_sql = "INSERT INTO `tbl_database_bank_account_details`(`database_id`, `b_bank_name`, `b_account_type`, `b_account_no`, `b_branch_name`, `b_ifsc_code`, `created_at`) 
                             VALUES ('$get_id', '$b_bank_name', '$b_account_type', '$b_account_no', '$b_branch_name', '$b_ifsc_code', '$created_at')";

            if (!mysqli_query($conn, $bank_acc_sql)) {
                $all_success = false;
            }
        }

        // Show message after all rows processed
        if ($all_success) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Bank Account Added Successfully",
                    position: "topRight"
                });
                 setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Some rows failed to insert. Please try again.",
                    position: "topRight"
                });
                 setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
            </script>';
        }
    }
}
?>
