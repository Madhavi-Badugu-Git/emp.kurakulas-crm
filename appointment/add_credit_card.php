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
                                                <h5> Credit Card Details</h5>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th style="width: 40%; min-width: 400px;">
                                                                        Bank Name
                                                                    </th>

                                                                    <th style="width: 40%; min-width: 400px;">
                                                                        Limit
                                                                    </th>

                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="creditCardTable">
                                                                <tr>
                                                                    <td>
                                                                        <select name="c_bank_name[]"
                                                                            class="form-select">
                                                                            <option value="">Select Bank
                                                                            </option>
                                                                            <?php
                                                                        $query = "SELECT id, bank_name FROM tbl_credit_card_bank ORDER BY bank_name ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                        }
                                                                    ?>
                                                                        </select>
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="c_limit[]" placeholder="Limit">
                                                                    </td>

                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-success action-btn4 addRow4">Add</button>
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
    $(document).on("click", ".action-btn4", function() {
        let btn = $(this);
        let row = btn.closest("tr");

        if (btn.hasClass("addRow4")) {
            let newRow = `<tr>
                          <td>
                                                                            <select 
                                                                                name="c_bank_name[]"
                                                                                class="form-select">
                                                                                <option value="">Select Bank
                                                                                </option>
                                                                                <?php
                                                                        $query = "SELECT id, bank_name FROM tbl_credit_card_bank ORDER BY bank_name ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                        }
                                                                    ?>
                                                                            </select>
                                                                        </td>
                                                                       
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="c_limit[]"
                                                                                placeholder="Limit">
                                                                        </td>                                           
                            <td>
                                <button type="button" class="btn btn-success action-btn4 addRow4">Add</button>
                            </td>
                        </tr>`;

            $("#creditCardTable").append(newRow);
            btn.removeClass("btn-primary addRow4").addClass("btn-danger removeRow4").text("Delete");
        } else if (btn.hasClass("removeRow4")) {
            row.remove();
            updateLastRow();
        }
    });

    function updateLastRow() {
        let lastRow = $("#creditCardTable tr:last");
        lastRow.find(".action-btn4").removeClass("btn-danger removeRow4").addClass("btn-primary addRow4").text(
            "Add");
    }
    </script>
</body>

</html>

<?php
// Handle update
if (!empty($_POST['c_bank_name'])) {
    $get_id = $_POST['get_id'];
    $created_at = date('Y-m-d H:i:s');
    $all_success = true;

     // credit card details
     foreach ($_POST['c_bank_name'] as $key => $c_bank_name) {
        $c_bank_name = mysqli_real_escape_string($conn, $c_bank_name);
        $c_limit = mysqli_real_escape_string($conn, $_POST['c_limit'][$key]);

        $credit_sql = "INSERT INTO `tbl_database_credit_card_details`(`database_id`, `c_bank_name`, `c_limit`, `created_at`) 
                       VALUES ('$get_id', '$c_bank_name', '$c_limit', '$created_at')";

        if (!mysqli_query($conn, $credit_sql)) {
            $all_success = false;
        }
    }

    // Show single success or error message after loop
    if ($all_success) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Credit Card Added Successfully",
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


?>