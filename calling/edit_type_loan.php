<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="type_loan";</script>';
    exit();
}

$status_id = $_GET['id'];

// Fetch designation details
$query = "SELECT * FROM tbl_calling_type_loan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $status_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Calling Loan Type not found!"); window.location.href="type_loan";</script>';
    exit();
}

$status = $result->fetch_assoc();
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
                                        <h4 class="mb-0">Edit Calling Type Loan</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="status_id" value="<?= $status['id']; ?>">
                                            <!-- <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-building"></i></span>
                                                    <select id="calling_bank" name="calling_bank" class="form-select">
                                                        <option value="">Select File Status</option>
                                                        <?php
                                                        $query = "SELECT id, calling_bank FROM tbl_calling_bank ORDER BY calling_bank ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $status['calling_bank_id']) ? 'selected' : ''; // Preselect the department
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['calling_bank'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div> -->
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-money"></i></span>
                                                    <input type="text" class="form-control" name="calling_type_loan"
                                                        id="calling_type_loan"
                                                        value="<?= $status['calling_type_loan']; ?>"
                                                        placeholder="Calling Type Loan"/>
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
    $status_id = $_POST['status_id'];
    $calling_type_loan = $_POST['calling_type_loan'];
    // $calling_bank = $_POST['calling_bank'];
    $created_at = date('Y-m-d H:i:s');


    // Validate required fields
    if (empty($calling_type_loan)) {
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
        $sql = "UPDATE tbl_calling_type_loan SET calling_type_loan = ?, updated_at ='$created_at'  WHERE id = ?";
       
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $calling_type_loan, $status_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Calling Type Loan Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="type_loan"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Something Went Wrong, Please Try Again",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="type_loan"; }, 1000);
            </script>';
        }
    }
}
?>
