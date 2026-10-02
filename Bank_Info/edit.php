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

// Fetch customer type details
$query = "SELECT * FROM tbl_bank_info WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bank_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Company Type not found!"); window.location.href="add";</script>';
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
                                        <h5 class="mb-0">Edit Bank Info</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <input type="hidden" name="bank_id" value="<?= $bank['id']; ?>">

                                            <div class="row">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="department_name"> Company
                                                        Name</label><span class="text-danger"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>

                                                        <select id="company_name" name="company_name"
                                                            class="form-select">
                                                            <option value="">Select Company</option>

                                                            <?php
                                                            $query = "SELECT id, company_name FROM tbl_company_name ORDER BY company_name ASC";
                                                            $result = $conn->query($query);

                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = ($row['id'] == $bank['company_name']) ? 'selected' : ''; // Preselect the company
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['company_name'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">

                                                    <label class="form-label" for="bank_name"> Bank Name
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>

                                                        <select id="bank_name" name="bank_name" class="form-select">
                                                            <option value="">Select Bank</option>
                                                            <?php
                                                            $query = "SELECT id, bank_name FROM tbl_bank ORDER BY bank_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                            

                                                                $selected = ($row['id'] == $bank['bank_name']) ? 'selected' : ''; // Preselect the company
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['bank_name'].'</option>';
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="account_number"> Account Number
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="account_number"
                                                            id="account_number" value="<?= $bank['account_number']; ?>" placeholder="Account Number" />
                                                    </div>

                                                </div>
                                                <div class="col-md-6">

                                                    <label class="form-label" for="IFSC_Code"> IFSC Code
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="IFSC_Code"
                                                            id="IFSC_Code" value="<?= $bank['IFSC_Code']; ?>"
                                                             placeholder="IFSC Code" />
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="branch_name"> Branch Name
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="branch_name"
                                                            id="branch_name" value="<?= $bank['branch_name']; ?>" placeholder="Branch Name" />
                                                    </div>

                                                </div>
                                                <div class="col-md-6">

                                                    <label class="form-label" for="MICR_Code"> MICR code
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="MICR_Code"
                                                            id="MICR_Code" value="<?= $bank['MICR_Code']; ?>"
                                                             placeholder="MICR code" />
                                                    </div>

                                                </div>

                                                <div class="row mt-3">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="bank_Address"> Bank Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="bank_Address"
                                                            id="bank_Address" value="<?= $bank['bank_address']; ?>" placeholder="Bank Address" />
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
    $company_name = $_POST['company_name'];

    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $branch_name = $_POST['branch_name'];
    $bank_address = $_POST['bank_address'];
    $IFSC_Code = $_POST['IFSC_Code'];
    $MICR_Code = $_POST['MICR_Code'];
    

    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($company_name)  || empty($account_number) ||  empty($IFSC_Code) ) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required!",
                position: "topRight",
            });
        </script>';
    } else {
        // Update query
        $sql = "UPDATE tbl_bank_info SET company_name = ?, bank_name = ?, account_number = ?, branch_name = ?, 
        bank_address = ?, IFSC_Code = ?, MICR_Code = ?, updated_at='$created_at' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $company_name, $bank_name, $account_number, $branch_name, $bank_address, 
        $IFSC_Code, $MICR_Code, $bank_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Bank Info  Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="list"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Something Went Wrong, Please Try Again",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="list"; }, 1000);
            </script>';
        }
    }
}
?>