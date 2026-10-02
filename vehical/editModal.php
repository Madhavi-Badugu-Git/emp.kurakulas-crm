<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="modal";</script>';
    exit();
}

$modal_id = $_GET['id'];

// Fetch Vehical Modal details
$query = "SELECT * FROM tbl_vehical_modal WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $modal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Vehical Modal not found!"); window.location.href="modal";</script>';
    exit();
}

$modal = $result->fetch_assoc();
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
                                        <h4 class="mb-0">Edit Vehical Modal</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="modal_id" value="<?= $modal['id']; ?>">
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-briefcase"></i></span>
                                                    <select id="vehical_make" name="vehical_make" class="form-select">
                                                        <option value="">Select Vehical Modal</option>
                                                        <?php
                                                        $query = "SELECT id, vehical_make FROM tbl_vehical_make ORDER BY vehical_make ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $modal['vehical_make_id']) ? 'selected' : '';
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['vehical_make'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                                                    <input type="text" class="form-control" name="vehical_modal"
                                                        id="vehical_modal"
                                                        value="<?= $modal['vehical_modal']; ?>"
                                                        placeholder="Designation Name" />
                                                </div>
                                            </div>
                                            <input type="submit" name="update_form" class="btn btn-primary" value="Update">
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
    $modal_id = $_POST['modal_id'];
    $vehical_make = $_POST['vehical_make'];
    $vehical_modal = $_POST['vehical_modal'];
    $created_at = date('Y-m-d H:i:s');


    // Validate required fields
    if (empty($vehical_make) || empty($vehical_modal)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else {
        // Update query
        $sql = "UPDATE tbl_vehical_modal SET vehical_modal = ?, vehical_make_id = ?, updated_at ='$created_at'  WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $vehical_modal, $vehical_make, $modal_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Vehical Modal Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="modal"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Something Went Wrong, Please Try Again",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="modal"; }, 1000);
            </script>';
        }
    }
}
?>
