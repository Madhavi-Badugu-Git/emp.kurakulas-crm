<?php 
session_start(); // ✅ Ensure this is at the top!
// print_r($_SESSION); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 

// echo $loggedInUser;
// exit();
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>

<body>


    <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">

            <!-- side menu -->
            <?php include('../includes/sideMenu.php'); ?>
            <!-- side menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">


                        <!-- Basic Layout -->
                        <div class="row">

                            <div class="col-xl">
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Add DSA Code </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="vendor_bank">Vendor Bank</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="vendor_bank" name="vendor_bank" class="form-select"
                                                            >
                                                            <option value="">Select Vendor</option>
                                                            <?php
                                                                $query = "SELECT id, vendor_bank_name FROM tbl_vendor_bank ORDER BY vendor_bank_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['vendor_bank_name'].'</option>';
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
                                                            id="dsa_code" placeholder="DSA Code" />
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
                                                            >
                                                            <option value="">Select DSA Name</option>
                                                            <?php
                                                                $query = "SELECT id, bsa_name FROM tbl_bsa_name ORDER BY bsa_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['bsa_name'].'</option>';
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
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['loan_type'].'</option>';
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
                                                        <select id="state" name="state" class="form-select"
                                                            onchange="loadBranchLocation(this.value)">
                                                            <option value="">Select Branch State</option>
                                                            <?php
                                                               $query = "SELECT id, branch_state_name FROM tbl_branch_state ORDER BY branch_state_name ASC";
                                                               $result = $conn->query($query);
                                                               while ($row = $result->fetch_assoc()) {
                                                                   echo '<option value="'.$row['id'].'">'.$row['branch_state_name'].'</option>';
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

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <input type="submit" name="form_submit" value="Submit"
                                                    class="btn btn-primary mt-2">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include('../includes/footer.php'); ?>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

    </div>
    <!-- / Layout wrapper -->
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
if (isset($_POST['form_submit'])) {
    $vendor_bank = mysqli_real_escape_string($conn, $_POST['vendor_bank']);
    $dsa_code = mysqli_real_escape_string($conn, $_POST['dsa_code']);
    $bsa_name = mysqli_real_escape_string($conn, $_POST['bsa_name']);
    $loan_type = mysqli_real_escape_string($conn, $_POST['loan_type']);
   
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
   
    $created_at = date('Y-m-d H:i:s');

    if (!empty($vendor_bank) && !empty($dsa_code) && !empty($bsa_name)  && !empty($loan_type)  && !empty($state) && !empty($location)) {

        $sql = "INSERT INTO `tbl_dsa_code`(`vendor_bank`, `dsa_code`, `bsa_name`, `loan_type`, `state`, `location`, `created_at`) VALUES ('$vendor_bank','$dsa_code','$bsa_name','$loan_type','$state','$location','$created_at')";
       

        if (mysqli_query($conn, $sql)) {
            echo '<script>
            iziToast.success({
                title: "Success",
                message: "DSA Code Added successfully!",
                position: "topRight"
            });
            setTimeout(() => { window.location.href = "list"; }, 1000);
            </script>';
        } else {
            echo '<script>
            iziToast.error({
                title: "Error",
                message: "Failed to Add DSA Code. Please try again.",
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