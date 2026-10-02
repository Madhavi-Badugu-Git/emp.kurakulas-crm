<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="addState";</script>';
    exit();
}

$state_id = $_GET['id'];

// Fetch state details
$query = "SELECT * FROM tbl_branch_state WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $state_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Branch State not found!"); window.location.href="addState";</script>';
    exit();
}

$state = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Branch State</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="state_id" value="<?= $state['id']; ?>">
                                            <div class="mb-3">

                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="menu-icon tf-icons bx bx-flag"></i></span>
                                                    <input type="text" class="form-control" name="state_name"
                                                        id="state_name"  value="<?= $state['branch_state_name']; ?>" placeholder="Branch State Name" />
                                                </div>
                                            </div>
                                            <input type="submit" name="update_state" class="btn btn-primary" value="Update">
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
if (isset($_POST['update_state'])) {
    $state_id = $_POST['state_id'];
    $state_name = $_POST['state_name'];
    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($state_name)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Branch State field is required",
                position: "topRight",
            });
        </script>';
        exit();
    } else {
        // Update query
        $sql = "UPDATE tbl_branch_state SET branch_state_name = ?, updated_at='$created_at' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $state_name, $state_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Branch State Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="addState"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Something Went Wrong, Please Try Again",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="addState"; }, 1000);
            </script>';
        }
    }
}
?>
