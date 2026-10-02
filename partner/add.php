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
                                        <h5 class="mb-0">Add Partner Details</h5>
                                        <!-- <small class="text-muted float-end">Merged input group</small> -->
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row mt-0">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number">Phone No</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="Phone_number" name="Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            aria-label="658 799 8941" aria-describedby="Phone_number2"
                                                            maxlength="10" pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')"
                                                            onblur="checkPhoneNumber()" />
                                                    </div>
                                                    <small id="phoneError"></small>
                                                    <!-- Ensure error message appears -->
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="full_name">Full
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="full_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="full_name"
                                                            id="full_name" placeholder="Full Name"
                                                            aria-label="Full Name" aria-describedby="full_name2" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="alias_name">Alias
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="alias_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="alias_name"
                                                            id="alias_name" placeholder="Alias Name"
                                                            aria-label="Alias Name" aria-describedby="alias_name2" />
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
                                                    <label class="form-label" for="aadhar_number"> Aadhar Card
                                                        No</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="aadhar_number2" class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <input type="text" id="aadhar_number" name="aadhar_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            aria-label="658 799 8941" aria-describedby="aadhar_number2"
                                                            maxlength="12" pattern="[0-9]{12}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="pan_number"> Pan Card No</label>
                                                    <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <input type="text" name="pan_number" id="pan_number"
                                                            class="form-control" placeholder="Pan Card No"
                                                            aria-label="Pan Card No" />
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
                                                    <label class="form-label" for="location">Branch
                                                        Location</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-map"></i></span>
                                                        <select id="location" name="location" class="form-select">
                                                            <option value="">Select Branch Location</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="partnerType">Type
                                                        Of Partner</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <select id="partnerType" name="partnerType" class="form-select">
                                                            <option value="">Select Partner Type</option>
                                                            <?php
                                                        $query = "SELECT id, partner_type FROM tbl_partner_type WHERE status = 1 ORDER BY partner_type ASC;";
                                                        $result = $conn->query($query);
                                                        
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo '<option value="'.$row['id'].'">'.$row['partner_type'].'</option>'; // Corrected key
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="password">Password</label>
                                                    <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <!-- Eye icon on the left -->
                                                        <span class="input-group-text" onclick="togglePassword()"
                                                            style="cursor: pointer;">
                                                            <i id="toggleIcon" class="bx bx-show"></i>
                                                        </span>
                                                        <!-- Password input -->
                                                        <input type="password" class="form-control" name="password"
                                                            id="password" placeholder="Password" aria-label="Password"
                                                            aria-describedby="password2" autocomplete="new-password" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="partner_file" class="form-label">Partner Image
                                                    </label>
                                                    <input class="form-control" type="file" id="partner_file"
                                                        name="partner_file" accept="image/*">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="birth_date">Date of
                                                        Birth</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="text" class="form-control" name="birth_date"
                                                            id="birth_date" placeholder="DD/MM/YYYY"
                                                            aria-describedby="birth_date2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="partner_address">Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"> <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="partner_address" id="partner_address"
                                                            class="form-control" rows="2"
                                                            placeholder="Address"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="mt-4">Bank Details</h5>
                                            <div class="row mt-0">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="acc_holder_name">Account Holder Name
                                                    </label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="acc_holder_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="acc_holder_name"
                                                            id="acc_holder_name" placeholder="Account Holder Name"
                                                            aria-label="Account Holder Name"
                                                            aria-describedby="acc_holder_name2" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="account_number">Account
                                                        Number</label> <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="account_number2" class="input-group-text"><i
                                                                class="bx bx-credit-card"></i></span>
                                                        <input type="text" class="form-control" name="account_number"
                                                            id="account_number" placeholder="Account Number"
                                                            aria-label="Account Number"
                                                            aria-describedby="account_number2" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="bank_name">Bank Name
                                                    </label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="acc_holder_name2" class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="bank_name" name="bank_name" class="form-select">
                                                            <option value="">Select Bank</option>
                                                            <?php
                                                        $query = "SELECT id, bank_name FROM tbl_bank WHERE status = 1 ORDER BY bank_name ASC;";
                                                        $result = $conn->query($query);
                                                        
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>'; // Corrected key
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="ifsc_code">IFSC Code
                                                    </label> <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="ifsc_code2" class="input-group-text"><i
                                                                class="bx bx-hash"></i></span>
                                                        <input type="text" class="form-control" name="ifsc_code"
                                                            id="ifsc_code" placeholder="IFSC Code"
                                                            aria-label="IFSC Code" aria-describedby="ifsc_code2"
                                                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');" />

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="branch_name">Branch Name
                                                    </label> <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="branch_name2" class="input-group-text"><i
                                                                class="bx bx-map"></i></span>
                                                        <input type="text" class="form-control" name="branch_name"
                                                            id="branch_name" placeholder="Branch Name"
                                                            aria-label="Branch Name" aria-describedby="branch_name2" />
                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="mt-4">Upload Documents</h5>
                                            <div class="row mt-0">

                                                <div class="col-md-6">
                                                    <label for="pan_file" class="form-label">Pan Card
                                                    </label> <span style="color:red;"> *</span>
                                                    <input class="form-control" type="file" id="pan_file"
                                                        name="pan_file" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="passbook_file" class="form-label">Bank Passbook
                                                    </label> <span style="color:red;"> *</span>
                                                    <input class="form-control" type="file" id="passbook_file"
                                                        name="passbook_file" accept="image/*,application/pdf">
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="aadhar_file" class="form-label">Aadhar Card Front
                                                    </label> <span style="color:red;"> *</span>
                                                    <input class="form-control" type="file" id="aadhar_file"
                                                        name="aadhar_file" accept="image/*,application/pdf">
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="aadhar_back_file" class="form-label">Aadhar Card Back
                                                    </label> <span style="font-size:12px;"> (Optional)</span>
                                                    <input class="form-control" type="file" id="aadhar_back_file"
                                                        name="aadhar_back_file" accept="image/*,application/pdf">
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
    // check phone number
    function checkPhoneNumber() {
        var phone = document.getElementById("Phone_number").value.trim();
        var errorField = document.getElementById("phoneError");

        if (!errorField) {
            console.error("Error field not found.");
            return;
        }

        if (phone.length === 10) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "check_phone.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            console.log("Checking phone number:", phone);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    console.log("Response status:", xhr.status);
                    console.log("Response text:", xhr.responseText);

                    if (xhr.status === 200) {
                        let response = xhr.responseText.trim(); // Trim spaces

                        if (response === "exists") {
                            errorField.innerHTML =
                                "<span style='color: red;'>This phone number is already registered!</span>";
                        } else if (response === "available") {
                            errorField.innerHTML =
                                "<span style='color: green;'>Phone Number is unique. Please fill the form.</span>";
                        } else {
                            errorField.innerHTML =
                                "<span style='color: red;'>Unexpected response from server.</span>";
                        }
                    } else {
                        errorField.innerHTML = "<span style='color: red;'>Error checking phone number.</span>";
                    }
                }
            };

            xhr.send("Phone_number=" + encodeURIComponent(phone));
        } else {
            errorField.innerHTML = "<span style='color: red;'>Enter a valid 10-digit phone number.</span>";
        }
    }

    // branch location
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

    // password
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var toggleIcon = document.getElementById("toggleIcon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.replace("bx-show", "bx-hide");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.replace("bx-hide", "bx-show");
        }
    }

    // date of birth 
    document.addEventListener("DOMContentLoaded", function() {
        let dateInput = document.getElementById("birth_date");

        // Function to format date as DD/MM/YYYY
        function formatDate(date) {
            let d = new Date(date);
            let day = ("0" + d.getDate()).slice(-2);
            let month = ("0" + (d.getMonth() + 1)).slice(-2);
            let year = d.getFullYear();
            return `${day}/${month}/${year}`;
        }

        // ✅ If there's a prefilled value (e.g., from database), format it properly
        if (dateInput.value) {
            dateInput.value = formatDate(new Date(dateInput.value));
        }

        // ✅ Restrict input to only valid date format (DD/MM/YYYY)
        dateInput.addEventListener("input", function() {
            this.value = this.value.replace(/[^0-9/]/g, "").substring(0, 10);
        });

        // ✅ Convert DD/MM/YYYY to YYYY-MM-DD before form submission
        dateInput.form.addEventListener("submit", function() {
            let parts = dateInput.value.split("/");
            if (parts.length === 3) {
                let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert to YYYY-MM-DD
                dateInput.value = formattedDate;
            }
        });
    });
    </script>
</body>

</html>

<?php
if (isset($_POST['form_submit'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    // echo $full_name;
    $alias_name = mysqli_real_escape_string($conn, $_POST['alias_name']);
    $Phone_number = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $email_id = mysqli_real_escape_string($conn, $_POST['email_id']);
    $aadhar_number = mysqli_real_escape_string($conn, $_POST['aadhar_number']);
    $pan_number = mysqli_real_escape_string($conn, $_POST['pan_number']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $partnerType = mysqli_real_escape_string($conn, $_POST['partnerType']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $partner_address = mysqli_real_escape_string($conn, $_POST['partner_address']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);


    $acc_holder_name = mysqli_real_escape_string($conn, $_POST['acc_holder_name']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $ifsc_code = mysqli_real_escape_string($conn, $_POST['ifsc_code']);
    $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name']);
  
    $created_at = date('Y-m-d H:i:s');

    if (!empty($Phone_number) && !empty($full_name) && !empty($email_id)  && !empty($partnerType)  && !empty($password) && !empty($acc_holder_name) && !empty($account_number)  && !empty($bank_name) && !empty($ifsc_code) && !empty($branch_name) ) {

         // Check if the phone number already exists
         $check_sql = "SELECT Phone_number FROM tbl_partner WHERE Phone_number = '$Phone_number'";
         $check_result = mysqli_query($conn, $check_sql);
 
         if (mysqli_num_rows($check_result) > 0) {
             echo '<script>
             iziToast.error({
                 title: "Error",
                 message: "Mobile number already exists!",
                 position: "topRight"
             });
             </script>';
         } else {

        $path = '../uploads/partners/'; // Upload directory
        $valid_extensions = array('jpeg', 'jpg', 'png', 'pdf'); // Allowed file formats
        $uploaded_files = []; // Store uploaded filenames

        $path = '../uploads/partners/'; // Upload directory
        $valid_extensions = array('jpeg', 'jpg', 'png', 'pdf'); // Allowed file formats
        $uploaded_files = []; // Store uploaded filenames
        
        // Define file input names and corresponding database column names
        $file_inputs = [
            'aadhar_file' => 'aadhar_document',
            'pan_file' => 'pan_document',
            'passbook_file' => 'passbook_document',
            'aadhar_back_file' => 'aadhar_backside_document',
            'partner_file' => 'avatar'
        ];
        
        foreach ($file_inputs as $input_name => $db_column) {
            if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
                $filename = $_FILES[$input_name]['name'];
                $tmp = $_FILES[$input_name]['tmp_name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
                if (in_array($ext, $valid_extensions)) {
                    $final_image = date("dHis") . '-' . strtolower(str_replace(' ', '-', $filename));
                    if (move_uploaded_file($tmp, $path . $final_image)) {
                        $uploaded_files[$db_column] = $final_image; // Store uploaded file name for DB
                    }
                } else {
                    echo '<script>
                    iziToast.error({
                        title: "Error",
                        message: "Invalid file format for ' . ucfirst(str_replace('_', ' ', $input_name)) . '. Only JPG, JPEG, PNG, and PDF allowed.",
                        position: "topRight"
                    });
                    </script>';
                    exit;
                }
            } else {
                $uploaded_files[$db_column] = null; // Set null if no file uploaded
            }
        }
        
        // Insert into database
        $sql = "INSERT INTO `tbl_partner` (`full_name`, `alias_name`, `Phone_number`, `email_id`, `aadhar_number`, `pan_number`, `state`, `location`, `partnerType`, `password`, `address`,`birth_date`, `acc_holder_name`, `account_number`, `bank_name`, `ifsc_code`, `branch_name`, `aadhar_document`, `pan_document`, `passbook_document`,`aadhar_backside_document`,`avatar`,`rank`, `createdBy`, `created_at`) 
        VALUES ('$full_name', '$alias_name', '$Phone_number', '$email_id', '$aadhar_number', '$pan_number', '$state', '$location', '$partnerType', '$password', '$partner_address','$birth_date', '$acc_holder_name', '$account_number', '$bank_name', '$ifsc_code', '$branch_name', '{$uploaded_files['aadhar_document']}', '{$uploaded_files['pan_document']}', '{$uploaded_files['passbook_document']}', '{$uploaded_files['aadhar_backside_document']}','{$uploaded_files['avatar']}','Partner', '$loggedInUser', '$created_at')";

            if (mysqli_query($conn, $sql)) {
                echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Partner details Added successfully!",
                    position: "topRight"
                });
                setTimeout(() => { window.location.href = "list"; }, 1000);
                </script>';
            } else {
                echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Failed to Add Partner details. Please try again.",
                    position: "topRight"
                });
                </script>';
            }
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