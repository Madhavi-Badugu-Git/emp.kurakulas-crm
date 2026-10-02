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
                                                <h5>Add Vehicle</h5>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Vehicle Number
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Make</th>
                                                                    <th style="width: 15%; min-width: 150px;">
                                                                        Modal</th>
                                                                    <th style="width: 15%; min-width: 150px;">
                                                                        MAN Year
                                                                    </th>
                                                                    <th style="width: 15%; min-width: 150px;">
                                                                        Engine Number</th>
                                                                    <th style="width: 15%; min-width: 150px;">
                                                                        Chases Number</th>

                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="vehicleTable">
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="vehicle_number[]"
                                                                            placeholder="Vehicle Number">
                                                                    </td>
                                                                    <td>
                                                                        <select name="vehicle_make[]"
                                                                            class="form-select">
                                                                            <option value="">Select Vehicle Make
                                                                            </option>
                                                                            <?php
                                                                                $query = "SELECT id, vehical_make FROM tbl_vehical_make ORDER BY vehical_make ASC";
                                                                                $result = $conn->query($query);
                                                                                while ($row = $result->fetch_assoc()) {
                                                                                    echo '<option value="'.$row['id'].'">'.$row['vehical_make'].'</option>';
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="vehical_modal[]"
                                                                            class="form-select">
                                                                            <option value="">Select Vehicle
                                                                                Modal
                                                                            </option>
                                                                            <?php
                                                                                $query = "SELECT id, vehical_modal FROM tbl_vehical_modal ORDER BY vehical_modal ASC";
                                                                                $result = $conn->query($query);
                                                                                while ($row = $result->fetch_assoc()) {
                                                                                    echo '<option value="'.$row['id'].'">'.$row['vehical_modal'].'</option>';
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>

                                                                        <select name="manufacture_year[]"
                                                                            class="form-select">
                                                                            <option value="">Select MAN Year
                                                                            </option>
                                                                            <?php
                                                                                $query = "SELECT id, manufacture_year FROM tbl_manufacture_year ORDER BY manufacture_year ASC";
                                                                                $result = $conn->query($query);
                                                                                while ($row = $result->fetch_assoc()) {
                                                                                    echo '<option value="'.$row['id'].'">'.$row['manufacture_year'].'</option>';
                                                                                }
                                                                            ?>
                                                                    </td>

                                                                    <td><input type="text" class="form-control"
                                                                            name="engine_number[]"
                                                                            placeholder="Engine No"></td>
                                                                    <td><input type="text" class="form-control"
                                                                            name="chases_number[]"
                                                                            placeholder="Chases No"></td>

                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-success action-btn-1 addRow-1">Add</button>
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
    $(document).on("click", ".action-btn-1", function() {
        let btn = $(this);
        let row = btn.closest("tr");

        if (btn.hasClass("addRow-1")) {
            let newRow = `<tr>
                           <td>
                                                                            <input type="text" class="form-control"
                                                                            name="vehicle_number[]" placeholder="Vehicle Number">
                                                                            </td>
                                                                            <td>
                                                                                <select
                                                                                    name="vehicle_make[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Vehicle Make
                                                                                    </option>
                                                                                    <?php
                                                                                    $query = "SELECT id, vehical_make FROM tbl_vehical_make ORDER BY vehical_make ASC";
                                                                                    $result = $conn->query($query);
                                                                                    while ($row = $result->fetch_assoc()) {
                                                                                        echo '<option value="'.$row['id'].'">'.$row['vehical_make'].'</option>';
                                                                                    }
                                                                                ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                            <select
                                                                                    name="vehical_modal[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Vehicle Modal
                                                                                    </option>
                                                                                    <?php
                                                                                    $query = "SELECT id, vehical_modal FROM tbl_vehical_modal ORDER BY vehical_modal ASC";
                                                                                    $result = $conn->query($query);
                                                                                    while ($row = $result->fetch_assoc()) {
                                                                                        echo '<option value="'.$row['id'].'">'.$row['vehical_modal'].'</option>';
                                                                                    }
                                                                                ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>

                                                                                <select name="manufacture_year[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select MAN Year</option>
                                                                                    <?php
                                                                                    $query = "SELECT id, manufacture_year FROM tbl_manufacture_year ORDER BY manufacture_year ASC";
                                                                                    $result = $conn->query($query);
                                                                                    while ($row = $result->fetch_assoc()) {
                                                                                        echo '<option value="'.$row['id'].'">'.$row['manufacture_year'].'</option>';
                                                                                    }
                                                                                ?>
                                                                            </td>
                                                                           
                                                                            <td><input type="text"
                                                                                    class="form-control" name="engine_number[]" placeholder="Engine No"
                                                                                    ></td>
                                                                            <td><input type="text" class="form-control"
                                                                                    name="chases_number[]" placeholder="Chases No"></td>
                            <td>
                                <button type="button" class="btn btn-success action-btn-1 addRow-1">Add</button>
                            </td>
                        </tr>`;

            $("#vehicleTable").append(newRow);
            btn.removeClass("btn-primary addRow-1").addClass("btn-danger removeRow-1").text("Delete");
        } else if (btn.hasClass("removeRow-1")) {
            row.remove();
            updateLastRow();
        }
    });

    function updateLastRow() {
        let lastRow = $("#vehicleTable tr:last");
        lastRow.find(".action-btn-1").removeClass("btn-danger removeRow-1").addClass("btn-primary addRow-1").text(
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

    if (!empty($_POST['vehicle_number'])) {
        foreach ($_POST['vehicle_number'] as $key => $vehicle_number) {
            $vehicle_make = mysqli_real_escape_string($conn, $_POST['vehicle_make'][$key]);
            $vehical_modal = mysqli_real_escape_string($conn, $_POST['vehical_modal'][$key]);
            $manufacture_year = mysqli_real_escape_string($conn, $_POST['manufacture_year'][$key]);
            $engine_number = mysqli_real_escape_string($conn, $_POST['engine_number'][$key]);  
            $chases_number = mysqli_real_escape_string($conn, $_POST['chases_number'][$key]);     

            $vehicle_sql = "INSERT INTO `tbl_database_vehicle_details`(`database_id`, `vehicle_number`, `vehicle_make`, `vehical_modal`, `manufacture_year`, `engine_number`, `chases_number`, `created_at`) 
                            VALUES ('$get_id', '$vehicle_number', '$vehicle_make', '$vehical_modal', '$manufacture_year', '$engine_number', '$chases_number', '$created_at')";

            if (!mysqli_query($conn, $vehicle_sql)) {
                $all_success = false;
            }
        }

        if ($all_success) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Vehicle details added successfully.",
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