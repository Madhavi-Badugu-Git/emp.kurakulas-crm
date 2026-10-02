<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Debugging: Check session values
// print_r($_SESSION);

$last_id = isset($_SESSION['last_id']) ? $_SESSION['last_id'] : null;

// if ($last_id) {
//     echo "<h3 style='color: green;'>Last ID: " . $last_id . "</h3>";
// } else {
//     echo "<h3 style='color: red;'>No last ID found</h3>";
// }
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
                                        <h5 class="mb-0">Add Bank Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="last_id"
                                                value="<?= htmlspecialchars($last_id) ?>">
                                            <div class="row mt-2">
                                                <div class="mb-6">
                                                    <label class="form-label" for="acc_holder_name">Account Holder Name
                                                    </label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-user"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="acc_holder_name"
                                                            id="acc_holder_name" placeholder="Account Holder Name" />
                                                    </div>
                                                </div>
                                                <div class="mb-6">
                                                    <label class="form-label" for="bank_name">Bank Name</label>

                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="bank_name" name="bank_name" class="form-select">
                                                            <option value="">Select Bank</option>
                                                            <?php
                                                                $query = "SELECT id, bank_name FROM tbl_bank ORDER BY bank_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-6">
                                                    <label class="form-label" for="branch_name">Branch Name</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-map"></i>

                                                        </span>
                                                        <input type="text" class="form-control" name="branch_name"
                                                            id="branch_name" placeholder="Branch Name" />
                                                    </div>
                                                </div>

                                                <div class="mb-6">
                                                    <label class="form-label" for="account_number">Account
                                                        Number</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-credit-card"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="account_number"
                                                            id="account_number" placeholder="Account Number" />
                                                    </div>
                                                </div>

                                                <div class="mb-6">
                                                    <label class="form-label" for="ifsc_code">IFSC Code</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-barcode"></i>

                                                        </span>
                                                        <input type="text" class="form-control" name="ifsc_code"
                                                            id="ifsc_code" placeholder="IFSC Code" />
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="text-end">
                                                <input type="submit" name="update_bank_info"
                                                    class="btn btn-primary mt-3" value="Next">
                                                <input type="hidden" value="<?= $last_id ?>">
                                            </div>
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
if (isset($_POST['update_bank_info'])) {
    $acc_holder_name = $_POST['acc_holder_name'];
    $bank_name = $_POST['bank_name'];
    $branch_name = $_POST['branch_name'];
    $account_number = $_POST['account_number'];  
    $ifsc_code = $_POST['ifsc_code'];
  

    $updated_at = date('Y-m-d H:i:s');

    if (isset($_POST['last_id']) && !empty($_POST['last_id'])) {
        $user_id = $_POST['last_id'];

        // Check if all fields are filled
    if (empty($acc_holder_name) || empty($bank_name) || empty($branch_name) || empty($account_number) || empty($ifsc_code)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required!",
                position: "topRight",
            });
        </script>';
        exit;
    }
    $sql = "UPDATE tbl_user 
            SET acc_holder_name = ?, bank_name = ?, branch_name = ?, account_number = ?, ifsc_code = ?, updated_at = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $acc_holder_name, $bank_name, $branch_name, $account_number, $ifsc_code, $updated_at, $user_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Bank details updated successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="upload_document.php"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something went wrong, please try again",
                position: "topRight",
            });
        </script>';
    }

    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Employee ID not found",
                position: "topRight",
            });
        </script>';
    }

   
}
?>