<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="dr_specialisation";</script>';
    exit();
}

$specialisation_id = $_GET['id'];

// Fetch department details
$query = "SELECT * FROM tbl_sep_doctor_specialisation WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $specialisation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Specialisation not found!"); window.location.href="dr_specialisation";</script>';
    exit();
}

$specialisation = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Doctor Specialisation</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="specialisation_id" value="<?= $specialisation['id']; ?>">

                                            <div class="mb-3">
                                                
                                                    <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-certification"></i></span>
                                                    <input type="text" class="form-control" name="specialisation"
                                                        id="specialisation" value="<?= $specialisation['specialisation']; ?>" placeholder="Doctor Specialisation" />
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
    $specialisation_id = $_POST['specialisation_id'];
    $specialisation = $_POST['specialisation'] ?? '';
    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($specialisation)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Doctor Specialisation Field is required",
                position: "topRight",
            });
        </script>';
        exit();
    }

    // Update query
    $sql = "UPDATE tbl_sep_doctor_specialisation SET specialisation = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $specialisation, $specialisation_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Doctor Specialisation Updated Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="dr_specialisation"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="dr_specialisation"; }, 1000);
        </script>';
    }
}
?>
