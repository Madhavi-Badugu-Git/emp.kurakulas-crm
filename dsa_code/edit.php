<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="list";</script>';
    exit();
}

$dsa_code_id = $_GET['id'];

// Fetch department details
$query = "SELECT * FROM tbl_dsa_code WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $dsa_code_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("DSA Code not found!"); window.location.href="list";</script>';
    exit();
}

$dsa_code = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit DSA Code</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="dsa_code_id" value="<?= $dsa_code['id']; ?>">

                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="vendor_bank">Vendor Bank</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="vendor_bank" name="vendor_bank" class="form-select"
                                                            onchange="loadDesignation(this.value)">
                                                            <option value="">Select Vendor</option>
                                                            <?php
                                                                $query = "SELECT id, vendor_bank_name FROM tbl_vendor_bank ORDER BY vendor_bank_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($vendor = $result->fetch_assoc()) {
                                                                    
                                                                    $selected = ($vendor['id'] == $dsa_code['vendor_bank']) ? 'selected' : '';
                                                                    echo '<option value="'.$vendor['id'].'" '.$selected.'>'.$vendor['vendor_bank_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="dsa_code"> DSA Code</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-code-alt"></i></span>
                                                        <input type="text" class="form-control" name="dsa_code"
                                                            id="dsa_code" value="<?= $dsa_code['dsa_code']; ?>"
                                                            placeholder="DSA Code" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="bsa_name">DSA Name</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <select id="bsa_name" name="bsa_name" class="form-select"
                                                            onchange="loadDesignation(this.value)">
                                                            <option value="">Select DSA Name</option>
                                                            <?php
                                                                $query = "SELECT id, bsa_name FROM tbl_bsa_name ORDER BY bsa_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($d_name = $result->fetch_assoc()) {
                                                                   
                                                                    $selected = ($d_name['id'] == $dsa_code['bsa_name']) ? 'selected' : '';
                                                                    echo '<option value="'.$d_name['id'].'" '.$selected.'>'.$d_name['bsa_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="loan_type">Type Of Loan</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="loan_type" name="loan_type" class="form-select">
                                                            <option value="">Select Loan Type</option>
                                                            <?php
                                                            $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";
                                                            $result = $conn->query($query);
                                                            while ($loan = $result->fetch_assoc()) {
                                                                $selected = ($loan['id'] == $dsa_code['loan_type']) ? 'selected' : '';
                                                                echo '<option value="'.$loan['id'].'" '.$selected.'>'.$loan['loan_type'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="state">Branch State</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map-alt"></i></span>
                                                        <select id="state" name="state" class="form-select"  onchange="loadBranchLocation(this.value)"
                                                            >
                                                            <option value="">Select Branch State</option>
                                                            <?php
                                                            $query = "SELECT id, branch_state_name FROM tbl_branch_state ORDER BY branch_state_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($state = $result->fetch_assoc()) {
                                                                $selected = ($state['id'] == $dsa_code['state']) ? 'selected' : '';
                                                                echo '<option value="'.$state['id'].'" '.$selected.'>'.$state['branch_state_name'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="location">Branch Location</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <select id="location" name="location" class="form-select">
                                                            <option value="">Select Branch Location</option>
                                                            <?php
                                                        $query = "SELECT id, branch_location FROM tbl_branch_location WHERE branch_state_id = '".$dsa_code['state']."' ORDER BY branch_location ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $dsa_code['location']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['branch_location'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="text-end">
                                                <input type="submit" name="update_form" class="btn btn-primary mt-3"
                                                    value="Update">
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
    <script>
        function loadBranchLocation(stateId) {
            if (stateId) {
                fetch("../info/get_branch_location", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "branch_state_id=" + stateId
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("location").innerHTML = data;
                    });
            } else {
                document.getElementById("location").innerHTML = '<option value="">Select Branch Location</option>';
            }
        }
    </script>
</body>

</html>

<?php
if (isset($_POST['update_form'])) {
    $dsa_code_id = $_POST['dsa_code_id'];
    $vendor_bank = mysqli_real_escape_string($conn, $_POST['vendor_bank']);
    $dsa_code = mysqli_real_escape_string($conn, $_POST['dsa_code']);
    $bsa_name = mysqli_real_escape_string($conn, $_POST['bsa_name']);
    $loan_type = mysqli_real_escape_string($conn, $_POST['loan_type']);
   
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
   
    $created_at = date('Y-m-d H:i:s');

    if (!empty($vendor_bank) && !empty($dsa_code) && !empty($bsa_name)  && !empty($loan_type)  && !empty($state) && !empty($location)) {

       
        $sql = "UPDATE `tbl_dsa_code` SET `vendor_bank`='$vendor_bank',`dsa_code`='$dsa_code',`bsa_name`='$bsa_name',`loan_type`='$loan_type',`state`='$state',`location`='$location',`updated_at`='$created_at' WHERE `id`='$dsa_code_id'";

        // echo $sql;
        // exit();
        if (mysqli_query($conn, $sql)) {
            echo '<script>
            iziToast.success({
                title: "Success",
                message: "DSA Code Updated successfully!",
                position: "topRight"
            });
            setTimeout(() => { window.location.href = "list"; }, 1000);
            </script>';
        } else {
            echo '<script>
            iziToast.error({
                title: "Error",
                message: "Failed to Update DSA Code. Please try again.",
                position: "topRight"
            });
            </script>';
        }
    } else {
        echo '<script>
        iziToast.warning({
            title: "Warning",
            message: "Please fill in all required fields.",
            position: "topRight"
        });
        </script>';
    }
}
?>