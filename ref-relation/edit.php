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

$ref_id = $_GET['id'];

// Fetch Ref Relation details
$query = "SELECT * FROM tbl_ref_relation WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ref_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Ref Relation not found!"); window.location.href="add";</script>';
    exit();
}

$ref = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Ref Relation</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <input type="hidden" name="ref_id" value="<?= $ref['id']; ?>">

                                            <div class="mb-3">
                                               
                                                    <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-group"></i></span>
                                                    <input type="text" class="form-control" name="ref_relation"
                                                        id="ref_relation" value="<?= $ref['ref_relation']; ?>" placeholder="Ref Relation" />
                                                </div>
                                            </div>

                                            <input type="submit" name="update_form" class="btn btn-primary"
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
    $ref_id = $_POST['ref_id'];
    $ref_relation = $_POST['ref_relation'];
    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($ref_relation)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Ref Relation cannot be empty!",
                position: "topRight",
            });
        </script>';
    } else {
        // Update query
        $sql = "UPDATE tbl_ref_relation SET ref_relation = ?, updated_at='$created_at' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $ref_relation, $ref_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Ref Relation Updated Successfully",
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
    }
}
?>
