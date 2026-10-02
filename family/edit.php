<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="relation";</script>';
    exit();
}

$relation_id = $_GET['id'];

// Fetch relation details
$query = "SELECT * FROM tbl_family_relation WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $relation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Relation not found!"); window.location.href="relation";</script>';
    exit();
}

$relation = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Relation</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="relation_id" value="<?= $relation['id']; ?>">

                                            <div class="mb-3">
                                                
                                                    <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-briefcase"></i></span>
                                                    <input type="text" class="form-control" name="family_relation"
                                                        id="family_relation" value="<?= $relation['family_relation']; ?>" placeholder="Relation"/>
                                                </div>
                                            </div>
                                            

                                            <input type="submit" name="update_relation" class="btn btn-primary"
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
if (isset($_POST['update_relation'])) {
    $relation_id = $_POST['relation_id'];
    $family_relation = $_POST['family_relation'] ?? '';
    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($family_relation)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Relation field is required",
                position: "topRight",
            });
        </script>';
        exit();
    }

    // Update query
    $sql = "UPDATE tbl_family_relation SET family_relation = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $family_relation, $relation_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Relation Updated Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="relation"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="relation"; }, 1000);
        </script>';
    }
}
?>
