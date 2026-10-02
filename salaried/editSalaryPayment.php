<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="salary_payment_type";</script>';
    exit();
}

$salary_id = $_GET['id'];

// Fetch department details
$query = "SELECT * FROM tbl_salary_payment_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $salary_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Net Salary not found!"); window.location.href="salary_payment_type";</script>';
    exit();
}

$salary = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Salary Payment Type</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="salary_id" value="<?= $salary['id']; ?>">

                                            <div class="mb-3">
                                                
                                                    <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-money"></i></span>
                                                    <input type="text" class="form-control" name="salary_payment_type"
                                                        id="salary_payment_type" value="<?= $salary['salary_payment_type']; ?>" placeholder="Salary Payment Type" />
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
    $salary_id = $_POST['salary_id'];
    $salary_payment_type = $_POST['salary_payment_type'] ?? '';
    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($salary_payment_type)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Salary Payment Type Field is required",
                position: "topRight",
            });
        </script>';
        exit();
    }

    // Update query
    $sql = "UPDATE tbl_salary_payment_type SET salary_payment_type = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $salary_payment_type, $salary_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Salary Payment Type Updated Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="salary_payment_type"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="salary_payment_type"; }, 1000);
        </script>';
    }
}
?>
