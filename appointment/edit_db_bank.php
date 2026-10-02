<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="view?id=' . $get_id . '";</script>';

    exit();
}

$bank_id = $_GET['id'];
$get_id = $_GET['get_id'];
// echo $get_id;

$query = "SELECT * FROM tbl_database_bank_account_details WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bank_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Credit Card Id not found!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$bank = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Bank Account</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="bank_id" value="<?= $bank['id']; ?>">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="form-label">Bank Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>

                                                        <select name="b_bank_name" class="form-select">
                                                            <option value="">Select Bank
                                                            </option>
                                                            <?php
                                                            $query = "SELECT id, bank_name FROM tbl_bank ORDER BY bank_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                            
                                                                $selected = ($row['id'] == $bank['b_bank_name']) ? 'selected' : '';
                                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['bank_name'].'</option>';
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="form-label">Type Of Account </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-wallet"></i></span>
                                                        <select name="b_account_type" class="form-select">
                                                            <option value="">Select Account Type
                                                            </option>
                                                            <?php
                                                                $query = "SELECT id, account_type FROM tbl_bank_account_type ORDER BY account_type ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                

                                                                    $selected = ($row['id'] == $bank['b_account_type']) ? 'selected' : '';
                                                                    echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['account_type'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">Account Number</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <input type="text" class="form-control" name="b_account_no"
                                                            value="<?= $bank['b_account_no']; ?>"
                                                            placeholder="Account Number" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="form-label">Branch Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-store"></i></span>
                                                        <input type="text" class="form-control" name="b_branch_name"  value="<?= $bank['b_branch_name']; ?>"
                                                            placeholder="Branch Name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">IFSC Code</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-code-alt"></i></span>
                                                        <input type="text" class="form-control" name="b_ifsc_code"
                                                            placeholder="IFSC Code"  value="<?= $bank['b_ifsc_code']; ?>"
                                                            oninput="this.value = this.value.toUpperCase();">
                                                    </div>
                                                </div>
                                            </div>



                                            <input type="submit" name="update_form" class="btn btn-primary mt-3"
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
    $bank_id = $_POST['bank_id'];
    $b_bank_name = $_POST['b_bank_name'] ?? '';
    $b_account_type = $_POST['b_account_type'] ?? '';
    $b_account_no = $_POST['b_account_no'] ?? '';
    $b_branch_name = $_POST['b_branch_name'] ?? '';
    $b_ifsc_code = $_POST['b_ifsc_code'] ?? '';

    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    // if (empty($b_bank_name) || empty($b_account_type) || empty($b_account_no) || empty($b_branch_name) || empty($b_ifsc_code)) {
    //     echo '<script>
    //         iziToast.warning({
    //             title: "Error",
    //             message: "Both Fields are required",
    //             position: "topRight",
    //         });
    //     </script>';
    //     exit();
    // }

    // Update query
    $sql = "UPDATE tbl_database_bank_account_details SET b_bank_name = ?, b_account_type = ?, b_account_no = ?, b_branch_name = ?, b_ifsc_code = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $b_bank_name, $b_account_type, $b_account_no, $b_branch_name, $b_ifsc_code, $bank_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Bank Account Updated Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
        </script>';
    }
}
?>