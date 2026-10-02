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
                                        <h5 class="mb-0">Add Agent Details</h5>
                                        <!-- <small class="text-muted float-end">Merged input group</small> -->
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <div class="row mt-0">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="Phone_number">Phone No</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="Phone_number2" class="input-group-text">
                                                            <i class="bx bx-phone"></i>
                                                        </span>
                                                        <input type="text" id="Phone_number" name="Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            aria-label="658 799 8941" aria-describedby="Phone_number2"
                                                            maxlength="10" pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')"
                                                            onblur="checkPhoneNumber()" />

                                                        <!-- Hidden input for logged-in user -->
                                                        <input type="hidden" id="loggedInUser" name="loggedInUser"
                                                            value="<?= $loggedInUser; ?>">
                                                    </div>
                                                    <small id="phoneError"></small> <!-- Removed red border -->
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
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
                                                    <label class="form-label" for="company_name">Company
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="company_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="company_name"
                                                            id="company_name" placeholder="Company Name"
                                                            aria-label="Company Name"
                                                            aria-describedby="company_name2" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="alternative_Phone_number">Alternative
                                                        Phone
                                                        No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="alternative_Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="alternative_Phone_number"
                                                            name="alternative_Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            aria-label="658 799 8941"
                                                            aria-describedby="alternative_Phone_number2" maxlength="10"
                                                            pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="email_id"> Email</label>

                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="email" name="email_id" id="email_id"
                                                            class="form-control" placeholder="User Email"
                                                            aria-label="Partner Email" />
                                                    </div>

                                                </div>
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
                                                    <label class="form-label" for="address">Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"> <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="address" id="address" class="form-control"
                                                            rows="1" placeholder="Address"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="visiting_card" class="form-label">Visiting Card
                                                    </label>
                                                    <input class="form-control" type="file" id="visiting_card"
                                                        name="visiting_card" accept="image/*,application/pdf">
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
        function checkPhoneNumber() {
            var phone = document.getElementById("Phone_number").value.trim();
            var loggedInUser = document.getElementById("loggedInUser").value;
            var errorField = document.getElementById("phoneError");

            if (phone.length === 10) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "check_phone.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                console.log("Checking phone number:", phone, "User:", loggedInUser);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        console.log("Response status:", xhr.status);
                        console.log("Response text:", xhr.responseText);

                        if (xhr.status === 200) {
                            let response = xhr.responseText.trim(); // Trim spaces
                            
                            if (response === "exists") {
                                errorField.innerHTML = "<span style='color: red;'>This phone number is already registered!</span>";
                            } else {
                                errorField.innerHTML = "<span style='color: green;'>Phone Number is unique. Please fill the form.</span>";
                            }
                        } else {
                            errorField.innerHTML = "<span style='color: red;'>Error checking phone number.</span>";
                        }
                    }
                };

                xhr.send("Phone_number=" + encodeURIComponent(phone) + "&loggedInUser=" + encodeURIComponent(loggedInUser));
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
    </script>
</body>

</html>

<?php
if (isset($_POST['form_submit'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $Phone_number = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $alternative_Phone_number = mysqli_real_escape_string($conn, $_POST['alternative_Phone_number']);
    $email_id = mysqli_real_escape_string($conn, $_POST['email_id']);
    $partnerType = mysqli_real_escape_string($conn, $_POST['partnerType']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
  
    $created_at = date('Y-m-d H:i:s');
    $visiting_card = null; // Default value

    if (!empty($full_name) && !empty($Phone_number) && !empty($partnerType) && !empty($state) && !empty($location) && !empty($company_name)) {

         // **Check if the phone number already exists for this user**
        //  $check_query = "SELECT id FROM tbl_agent_data WHERE Phone_number = '$Phone_number' AND createdBy = '$loggedInUser'";
        //  $check_result = mysqli_query($conn, $check_query);
 
        //  if (mysqli_num_rows($check_result) > 0) {
        //      echo '<script>
        //      iziToast.error({
        //          title: "Error",
        //          message: "This mobile number is already registered by you.",
        //          position: "topRight"
        //      });
        //      </script>';
        //      exit;
        //  }

        // **File Upload Handling**
        if (isset($_FILES['visiting_card']) && $_FILES['visiting_card']['error'] == 0) {
            $path = '../uploads/agent-data/'; // Upload directory
            $valid_extensions = ['jpeg', 'jpg', 'png', 'pdf']; // Allowed formats
            $filename = $_FILES['visiting_card']['name'];
            $tmp = $_FILES['visiting_card']['tmp_name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $valid_extensions)) {
                $final_image = time() . '-' . strtolower(str_replace(' ', '-', $filename));
                if (move_uploaded_file($tmp, $path . $final_image)) {
                    $visiting_card = $final_image; // Store uploaded file name for DB
                }
            } else {
                echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Invalid file format. Only JPG, JPEG, PNG, and PDF allowed.",
                    position: "topRight"
                });
                </script>';
                exit;
            }
        }

        // **Insert into database**
        if ($visiting_card) {
            $sql = "INSERT INTO `tbl_agent_data`(`full_name`, `company_name`, `Phone_number`, `alternative_Phone_number`, `email_id`, `partnerType`, `state`, `location`, `address`, `visiting_card`, `createdBy`, `created_at`) 
            VALUES ('$full_name','$company_name','$Phone_number','$alternative_Phone_number','$email_id','$partnerType','$state','$location','$address','$visiting_card','$loggedInUser','$created_at')";
        } else {
            $sql = "INSERT INTO `tbl_agent_data`(`full_name`, `company_name`, `Phone_number`, `alternative_Phone_number`, `email_id`, `partnerType`, `state`, `location`, `address`, `createdBy`, `created_at`) 
            VALUES ('$full_name','$company_name','$Phone_number','$alternative_Phone_number','$email_id','$partnerType','$state','$location','$address','$loggedInUser','$created_at')";
        }

        if (mysqli_query($conn, $sql)) {
            echo '<script>
            iziToast.success({
                title: "Success",
                message: "Agent Data Added successfully!",
                position: "topRight"
            });
            setTimeout(() => { window.location.href = "list"; }, 1000);
            </script>';
        } else {
            echo '<script>
            iziToast.error({
                title: "Error",
                message: "Failed to Add Agent Data. Please try again.",
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