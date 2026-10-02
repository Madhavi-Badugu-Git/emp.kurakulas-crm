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

$bank_id = $_GET['id'];

// Fetch bank details
$query = "SELECT * FROM tbl_bank_account_details WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bank_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Bank not found!"); window.location.href="add";</script>';
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
                                        <h5 class="mb-0">Edit Bank Account Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="bank_id" value="<?= $bank['id']; ?>">
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="bank_name">Account Bank Name</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="bank_name" name="bank_name" class="form-select"
                                                            >
                                                            <option value="">Select Account Bank Name</option>
                                                            <?php
                                                                $query = "SELECT id, bank_name FROM tbl_account_bank ORDER BY bank_name ASC";
                                                                $result = $conn->query($query);
                                                                
                                                                while ($bank_row = $result->fetch_assoc()) {
                                                                    $selected = ($bank_row['id'] == $bank['bank_name']) ? 'selected' : '';
                                                                    echo '<option value="'.$bank_row['id'].'" '.$selected.'>'.$bank_row['bank_name'].'</option>';
                                                                }
                                                            ?>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" >Branch Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map-pin"></i></span>
                                                        <input type="text" id="branch_name" name="branch_name" value="<?= $bank['branch_name']; ?>" class="form-control" placeholder="Branch Name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" >Account Number</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map-pin"></i></span>
                                                        <input type="text" id="account_no" name="account_no" value="<?= $bank['account_number']; ?>" class="form-control" placeholder="Account Number"  inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="account_name">Account Name</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user-voice"></i></span>
                                                        <select id="account_name" name="account_name" class="form-select"
                                                            >
                                                            <option value="">Select Account Name</option>
                                                            <?php
                                                                $query = "SELECT id, dsa_name FROM tbl_account_dsa ORDER BY dsa_name ASC";
                                                                $result = $conn->query($query);
                                                               
                                                                while ($dsa = $result->fetch_assoc()) {
                                                                    $selected = ($dsa['id'] == $bank['account_name']) ? 'selected' : '';
                                                                    echo '<option value="'.$dsa['id'].'" '.$selected.'>'.$dsa['dsa_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                               
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number">MICR Code</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="text" id="micr_code" name="micr_code" value="<?= $bank['micr_code']; ?>"  class="form-control" placeholder="MICR Code"  style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number">IFSC Code</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="text" id="ifsc_code" name="ifsc_code" value="<?= $bank['ifsc_code']; ?>"  class="form-control" placeholder="IFSC Code"  style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="submit" name="update_bank_statement" class="btn btn-primary mt-3" value="Update">
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
if (isset($_POST['update_bank_statement'])) {
    $bank_id = $_POST['bank_id'];
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name']);
    $account_no = mysqli_real_escape_string($conn, $_POST['account_no']);
    $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
    $micr_code = mysqli_real_escape_string($conn, $_POST['micr_code']);
    $ifsc_code = mysqli_real_escape_string($conn, $_POST['ifsc_code']);
    $created_at = date('Y-m-d H:i:s');

    // ---------- Validation ----------
    if (empty($bank_name) || empty($branch_name) || empty($account_no) || empty($account_name) || empty($micr_code) || empty($ifsc_code)) {
        echo '<script>
            iziToast.warning({
                title: "Warning",
                message: "Please fill all required fields.",
                position: "topRight"
            });
        </script>';
    } else {

        // ---------- UPDATE QUERY ----------
        $sql = "UPDATE `tbl_bank_account_details` SET
            `bank_name`    = '$bank_name',
            `branch_name`     = '$branch_name',
            `account_number`   = '$account_no',
            `account_name` = '$account_name',
            `micr_code`     = '$micr_code',
            `ifsc_code`     = '$ifsc_code',
            `updated_at`   = '$created_at'
        WHERE `id` = '$bank_id'";

        if (mysqli_query($conn, $sql)) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Bank Account Details updated successfully!",
                    position: "topRight"
                });
                setTimeout(() => { window.location.href = "add"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Failed to update Bank Account Details. Please try again.",
                    position: "topRight"
                });
            </script>';
        }
    }
}
?>
