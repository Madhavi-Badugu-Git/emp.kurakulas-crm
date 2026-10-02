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

$loan_id = $_GET['id'];

// Fetch loan type details
$query = "SELECT * FROM tbl_loan_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $loan_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Loan Type not found!"); window.location.href="add";</script>';
    exit();
}

$loan = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Loan Type</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <input type="hidden" name="loan_id" value="<?= $loan['id']; ?>">

                                            <div class="mb-3">
                                                <!-- <label class="form-label" for="loan_type">Loan Type</label>
                                                <input type="text" class="form-control" name="loan_type"
                                                    value="<?= htmlspecialchars($loan['loan_type']); ?>" required /> -->

                                                    <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-file"></i></span>
                                                    <input type="text" class="form-control" name="loan_type"
                                                        id="loan_type" value="<?= $loan['loan_type']; ?>" placeholder="Loan Type" />
                                                </div>
                                            </div>

                                            <input type="submit" name="update_loan" class="btn btn-primary"
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
if (isset($_POST['update_loan'])) {
    $loan_id = $_POST['loan_id'];
    $loan_type = $_POST['loan_type'];
    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($loan_type)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Loan Type cannot be empty!",
                position: "topRight",
            });
        </script>';
    } else {
        // Update query
        $sql = "UPDATE tbl_loan_type SET loan_type = ?, updated_at='$created_at' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $loan_type, $loan_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Loan Type Updated Successfully",
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
