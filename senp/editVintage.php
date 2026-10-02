<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="vintage_years";</script>';
    exit();
}

$vintage_id = $_GET['id'];

// Fetch designation details
$query = "SELECT * FROM tbl_vintage_year WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vintage_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Vintage Year not found!"); window.location.href="vintage_years";</script>';
    exit();
}

$vintage = $result->fetch_assoc();
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
                                        <h4 class="mb-0">Edit Vintage Year</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="vintage_id" value="<?= $vintage['id']; ?>">
                                           
                                            <div class="mb-6">
                                                <label class="form-label" for="vintage_year"> Vintage Year</label> <span
                                                style="color:red;"> *</span>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-calendar"></i></span>
                                                    <input type="text" class="form-control" name="vintage_year"
                                                        id="vintage_year" value="<?= $vintage['vintage_year']; ?>" placeholder=" Vintage Name" />
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
    $vintage_id = $_POST['vintage_id'];
    $vintage_year = $_POST['vintage_year'];
    $created_at = date('Y-m-d H:i:s');


    // Validate required field
    if (empty($vintage_year)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Vintage field is required",
                position: "topRight",
            });
        </script>';
        exit();
    } else {
        // Update query
        $sql = "UPDATE tbl_vintage_year SET vintage_year = ?, updated_at ='$created_at'  WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $vintage_year, $vintage_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Vintage Year Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="vintage_years"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Something Went Wrong, Please Try Again",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="vintage_years"; }, 1000);
            </script>';
        }
    }
}
?>
