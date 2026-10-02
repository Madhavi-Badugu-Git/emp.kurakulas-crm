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
                                        <h5 class="mb-0">Add Bankers </h5>
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
                                                    <label class="form-label" for="banker_name"> Banker Name</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="banker_name"
                                                            id="banker_name" placeholder="Banker Name" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number"> Phone
                                                        No</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="Phone_number" name="Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            aria-label="658 799 8941" aria-describedby="Phone_number2"
                                                            maxlength="10" pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="email_id"> Email</label>
                                                    <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="email" name="email_id" id="email_id"
                                                            class="form-control" placeholder="User Email"
                                                            aria-label="Partner Email" />
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="banker_designation">Banker Designation</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <select id="banker_designation" name="banker_designation" class="form-select"
                                                            >
                                                            <option value="">Select Banker Designation</option>
                                                            <?php
                                                                $query = "SELECT id, designation_name FROM tbl_banker_designation ORDER BY designation_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['designation_name'].'</option>';
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

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="visiting_card" class="form-label">Visiting Card
                                                    </label>
                                                    <input class="form-control" type="file" id="visiting_card"
                                                        name="visiting_card" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="address">Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"> <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="address" id="address"
                                                            class="form-control" rows="1"
                                                            placeholder="Address"></textarea>
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
    $banker_name = mysqli_real_escape_string($conn, $_POST['banker_name']);
    $Phone_number = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $email_id = mysqli_real_escape_string($conn, $_POST['email_id']);
    $banker_designation = mysqli_real_escape_string($conn, $_POST['banker_designation']);
    $loan_type = mysqli_real_escape_string($conn, $_POST['loan_type']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $created_at = date('Y-m-d H:i:s');

    // File Upload Handling
    $target_dir = "../uploads/bankers/";

    $visiting_card = "default.png"; // Default file
    if (!empty($_FILES["visiting_card"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["visiting_card"]["name"]); // Generate unique filename
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = array("jpg", "jpeg", "png", "pdf");

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["visiting_card"]["tmp_name"], $target_file)) {
                $visiting_card = $file_name; // Save only the file name
            } else {
                echo '<script>
                    iziToast.error({
                        title: "Error",
                        message: "File upload failed!",
                        position: "topRight"
                    });
                </script>';
                exit();
            }
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Warning",
                    message: "Invalid file type. Only JPG, PNG, PDF allowed.",
                    position: "topRight"
                });
            </script>';
            exit();
        }
    }

    // Insert into Database
    if (!empty($vendor_bank) && !empty($banker_name) && !empty($Phone_number) && !empty($email_id) && !empty($banker_designation) && !empty($loan_type) && !empty($state) && !empty($location)) {
        $sql = "INSERT INTO `tbl_bankers` (`vendor_bank`, `banker_name`, `Phone_number`, `email_id`, `banker_designation`, `loan_type`, `state`, `location`, `visiting_card`, `address`,`createdBy`, `created_at`) 
                VALUES ('$vendor_bank', '$banker_name', '$Phone_number', '$email_id', '$banker_designation', '$loan_type', '$state', '$location', '$visiting_card', '$address','$loggedInUser', '$created_at')";

        if (mysqli_query($conn, $sql)) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Bankers Added successfully!",
                    position: "topRight"
                });
                setTimeout(() => { window.location.href = "list"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Failed to Add Bankers. Please try again.",
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
