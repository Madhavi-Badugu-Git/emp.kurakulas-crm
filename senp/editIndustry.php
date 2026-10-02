<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="type_industry";</script>';
    exit();
}

$industry_id = $_GET['id'];

// Fetch designation details
$query = "SELECT * FROM tbl_senp_industry_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $industry_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Industry not found!"); window.location.href="type_industry";</script>';
    exit();
}

$industry = $result->fetch_assoc();
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
                                        <h4 class="mb-0">Edit Industry</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="industry_id" value="<?= $industry['id']; ?>">
                                           
                                            <div class="mb-6">
                                                <label class="form-label" for="industry_name"> Industry Name</label> <span
                                                style="color:red;"> *</span>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-store-alt"></i></span>
                                                    <input type="text" class="form-control" name="industry_name"
                                                        id="industry_name" value="<?= $industry['industry_name']; ?>" placeholder=" Industry Name" />
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
    $industry_id = $_POST['industry_id'];
    $industry_name = $_POST['industry_name'];
    $created_at = date('Y-m-d H:i:s');


    // Validate required field
    if (empty($industry_name)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Industry field is required",
                position: "topRight",
            });
        </script>';
        exit();
    } else {
        // Update query
        $sql = "UPDATE tbl_senp_industry_type SET industry_name = ?, updated_at ='$created_at'  WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $industry_name, $industry_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Industry Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="type_industry"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Something Went Wrong, Please Try Again",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="type_industry"; }, 1000);
            </script>';
        }
    }
}
?>
