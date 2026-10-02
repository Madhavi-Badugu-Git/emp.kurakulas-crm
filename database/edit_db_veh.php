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

$veh_id = $_GET['id'];
$get_id = $_GET['get_id'];
// echo $get_id;

$query = "SELECT * FROM tbl_database_vehicle_details WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $veh_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Vehicle Id not found!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$vehicle = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Vehicle </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="veh_id" value="<?= $vehicle['id']; ?>">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="form-label">Vehicle Number</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-car me-1"></i></span>
                                                        <input type="text" class="form-control" name="vehicle_number"
                                                            value="<?= $vehicle['vehicle_number']; ?>"
                                                            placeholder="Vehicle Number">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="form-label">Vehicle Make</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-cog"></i></span>
                                                        <select name="vehicle_make" class="form-select">
                                                            <option value="">Select Vehicle Make
                                                            </option>
                                                            <?php
                                                            $query = "SELECT id, vehical_make FROM tbl_vehical_make ORDER BY vehical_make ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                               
                                                                $selected = ($row['id'] == $vehicle['vehicle_make']) ? 'selected' : '';
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['vehical_make'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">Vehicle Modal</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-detail"></i></span>

                                                        <select name="vehical_modal" class="form-select">
                                                            <option value="">Select Vehicle Modal </option>
                                                            <?php
                                                                $query = "SELECT id, vehical_modal FROM tbl_vehical_modal ORDER BY vehical_modal ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                $selected = ($row['id'] == $vehicle['vehical_modal']) ? 'selected' : '';
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['vehical_modal'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="form-label">MAN Year</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <select name="manufacture_year" class="form-select">
                                                            <option value="">Select MAN Year</option>
                                                            <?php
                                                            $query = "SELECT id, manufacture_year FROM tbl_manufacture_year ORDER BY manufacture_year ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = ($row['id'] == $vehicle['manufacture_year']) ? 'selected' : '';
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['manufacture_year'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">Engine Number</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <input type="text" class="form-control" name="engine_number"
                                                            value="<?= $vehicle['engine_number']; ?>"
                                                            placeholder="Engine Number">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="form-label">Chases Number</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <input type="text" class="form-control" name="chases_number"
                                                            value="<?= $vehicle['chases_number']; ?>"
                                                            placeholder="Chases Number">
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

</body>

</html>

<?php
// Handle update
if (isset($_POST['update_form'])) {
    $veh_id = $_POST['veh_id'];
    $vehicle_number = $_POST['vehicle_number'] ?? '';
    $vehicle_make = $_POST['vehicle_make'] ?? '';
    $vehical_modal = $_POST['vehical_modal'] ?? '';
    $manufacture_year = $_POST['manufacture_year'] ?? '';
    $engine_number = $_POST['engine_number'] ?? '';
    $chases_number = $_POST['chases_number'] ?? '';

    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    // if (empty($vehicle_number) || empty($vehicle_make) || empty($vehical_modal) || empty($manufacture_year) || empty($engine_number)) {
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
    $sql = "UPDATE tbl_database_vehicle_details SET vehicle_number = ?, vehicle_make = ?, vehical_modal = ?, manufacture_year = ?, engine_number = ?, chases_number = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $vehicle_number, $vehicle_make, $vehical_modal, $manufacture_year, $engine_number, $chases_number, $veh_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Vehicle Updated Successfully",
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