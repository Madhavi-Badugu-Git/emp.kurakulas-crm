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

$designation_id = $_GET['id'];

// Fetch designation details
$query = "SELECT * FROM tbl_salaried_designation WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $designation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Salaried Designation not found!"); window.location.href="add";</script>';
    exit();
}

$designation = $result->fetch_assoc();
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
                                        <h4 class="mb-0">Edit Salaried Designation</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="designation_id" value="<?= $designation['id']; ?>">
                                            
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                                                    <input type="text" class="form-control" name="designation_name"
                                                        id="designation_name"
                                                        value="<?= $designation['designation_name']; ?>"
                                                        placeholder="Salaried Designation" />
                                                </div>
                                            </div>
                                            <input type="submit" name="update_designation" class="btn btn-primary" value="Update">
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
if (isset($_POST['update_designation'])) {

    $designation_id = $_POST['designation_id'];
    $designation_name = trim($_POST['designation_name']); // Trim input to remove unwanted spaces
    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($designation_name)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Salaried Designation field is required",
                position: "topRight",
            });
        </script>';
        exit();
    } else {
        // Update query with prepared statement
        $sql = "UPDATE tbl_salaried_designation SET designation_name = ?, updated_at = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        // echo $sql;
        // exit();

        if ($stmt) {
            $stmt->bind_param("ssi", $designation_name, $created_at, $designation_id);

            if ($stmt->execute()) {
                echo '<script>
                    iziToast.success({
                        title: "Success",
                        message: "Salaried Designation Updated Successfully",
                        position: "topRight",
                    });
                    setTimeout(() => { window.location.href="add"; }, 1000);
                </script>';
            } else {
                echo '<script>
                    iziToast.warning({
                        title: "Error",
                        message: "Something Went Wrong, Please Try Again",
                        position: "topRight",
                    });
                    setTimeout(() => { window.location.href="add"; }, 1000);
                </script>';
            }
            $stmt->close();
        } else {
            echo '<script>
                iziToast.error({
                    title: "Database Error",
                    message: "Failed to prepare statement",
                    position: "topRight",
                });
            </script>';
        }
    }
    $conn->close();
}
?>
