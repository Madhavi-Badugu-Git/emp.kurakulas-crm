<?php 
session_start(); // ✅ Ensure this is at the top!
// print_r($_SESSION); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 

// echo $loggedInUser;
// exit();

if (isset($_POST['form_submit'])) {
    // $employee_no = mysqli_real_escape_string($conn, $_POST['employee_no']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $Phone_number = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $email_id = mysqli_real_escape_string($conn, $_POST['email_id']);
    $father_name = mysqli_real_escape_string($conn, $_POST['father_name']);
    $joining_date = mysqli_real_escape_string($conn, $_POST['joining_date']);
    // $department = mysqli_real_escape_string($conn, $_POST['department']);
    // $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    // $password = mysqli_real_escape_string($conn, $_POST['password']);
    $present_address = mysqli_real_escape_string($conn, $_POST['present_address']);
    $permanent_address = mysqli_real_escape_string($conn, $_POST['permanent_address']);
    $rank = mysqli_real_escape_string($conn, $_POST['rank']);
    $height = mysqli_real_escape_string($conn, $_POST['height']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $created_at = date('Y-m-d H:i:s');

    if (!empty($Phone_number) && !empty($first_name) && !empty($rank) && !empty($email_id)) {
        $sql = "INSERT INTO tbl_user 
            (firstName, lastName, mobile, email_id, dob, father_name, joining_date, present_address, permanent_address, rank, avatar, height, weight,createdBy, created_at)
            VALUES ('$first_name', '$last_name', '$Phone_number', '$email_id', '$birth_date', '$father_name', '$joining_date','$present_address', '$permanent_address', '$rank', '', '$height', '$weight','$loggedInUser', '$created_at')";

            echo $sql;

        if (mysqli_query($conn, $sql)) {
            $last_id = mysqli_insert_id($conn);
            $_SESSION['last_id'] = $last_id; // ✅ Store last inserted ID in session

            // ✅ Handle File Upload
            $brocher = 'default.png';
            if (!empty($_FILES['file']['name'])) {
                $filename = $_FILES['file']['name'];
                $tmp = $_FILES['file']['tmp_name'];
                $path = '../uploads/users/';
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $valid_extensions = array('jpeg', 'jpg', 'png');

                if (in_array($ext, $valid_extensions)) {
                    $final_image = date("dHis") . '-' . strtolower(str_replace(' ', '-', $filename));
                    if (move_uploaded_file($tmp, $path . $final_image)) {
                        $brocher = $final_image;
                    }
                }
            }

            // ✅ Update user avatar
            mysqli_query($conn, "UPDATE tbl_user SET avatar='$brocher' WHERE id='$last_id'");

            echo "Session Last ID: " . $_SESSION['last_id']; 
            
            // ✅ Redirect to `list.php`
            header("Location: family-details.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "All Fields Are Required!";
    }
}
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
                                        <h5 class="mb-0">Add User Details</h5>
                                        <!-- <small class="text-muted float-end">Merged input group</small> -->
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <div class="row">

                                                <div class="col-md-6">

                                                    <label class="form-label" for="rank">Role</label><span
                                                        style="color:red;">*</span>
                                                    <?php 
                                                    if($loggedInUserRank == 'superAdmin'){
                                                        ?>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="rank" name="rank" class="form-select">
                                                            <option value="">Select Role</option>
                                                            <option value="Admin">Admin</option>
                                                            <option value="User">User</option>

                                                        </select>

                                                    </div>
                                                    <?php
                                                    } else if($loggedInUserRank == 'Admin'){
                                                    ?>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="rank" name="rank" class="form-select">
                                                            <option value="">Select Role</option>
                                                            <!-- <option value="Admin">Admin</option> -->
                                                            <option value="User">User</option>

                                                        </select>

                                                    </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="formFile" class="form-label">Image
                                                    </label>
                                                    <input class="form-control" type="file" id="formFile" name="file">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="first_name">First
                                                        Name</label><span
                                                        style="color:red;">*</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="first_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="first_name"
                                                            id="first_name" placeholder="First Name"
                                                            aria-label="John Doe" aria-describedby="first_name2" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="last_name">Last
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="last_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="last_name"
                                                            id="last_name" placeholder="Last Name" aria-label="John Doe"
                                                            aria-describedby="last_name2" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number">Personal Phone
                                                        No</label><span
                                                        style="color:red;">*</span>
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
                                                    <label class="form-label" for="email_id">Personal Email</label>
                                                    <span
                                                        style="color:red;">*</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="email" name="email_id" id="email_id"
                                                            class="form-control" placeholder="User Email"
                                                            aria-label="User Email" aria-describedby="email_id2" />
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="father_name">Father
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="father_name2" class="input-group-text"><i
                                                                class="bx bx-male"></i></span>
                                                        <input type="text" class="form-control" name="father_name"
                                                            id="father_name" placeholder="Father Name"
                                                            aria-label="Father Name" aria-describedby="father_name2" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="joining_date">Date Of Joining
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="joining_date2" class="input-group-text"><i
                                                                class="bx bx-calendar-check"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="joining_date"
                                                            id="joining_date" placeholder="DD/MM/YYYY"
                                                            aria-describedby="joining_date2" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="birth_date">Date of
                                                        Birth</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <!-- <input type="date" class="form-control" name="birth_date"
                                                            id="birth_date" aria-describedby="birth_date2" /> -->
                                                        <input type="text" class="form-control" name="birth_date"
                                                            id="birth_date" placeholder="DD/MM/YYYY"
                                                            aria-describedby="birth_date2" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="formFile" class="form-label">Height
                                                            </label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="height2" class="input-group-text"><i
                                                                        class="bx bx-ruler"></i>
                                                                </span>
                                                                <input class="form-control" type="number" id="height"
                                                                    name="height" placeholder="Height (cm)"
                                                                    aria-label="Height" aria-describedby="height2"
                                                                    min="0">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="formFile" class="form-label">Weight
                                                            </label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="weight2" class="input-group-text"><i
                                                                        class="bx bx-dumbbell"></i>
                                                                </span>
                                                                <input class="form-control" type="number" id="weight"
                                                                    name="weight" placeholder="Weight (kg)"
                                                                    aria-label="Weight" aria-describedby="weight2"
                                                                    min="0" step="0.1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="present_address">Present Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text">
                                                            <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="present_address" id="present_address"
                                                            class="form-control"
                                                            placeholder="Present Address"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="present_address">Permanent Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text">
                                                            <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="permanent_address" id="permanent_address"
                                                            class="form-control"
                                                            placeholder="Permanent Address"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <input type="submit" name="form_submit" value="Next"
                                                    class="btn btn-primary mt-2">
                                                <input type="hidden" value="<?= $last_id ?>">
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
    function loadDesignation(deptId) {
        if (deptId) {
            fetch("../info/get_designation", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "department_id=" + deptId
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("designation").innerHTML = data;
                });
        } else {
            document.getElementById("designation").innerHTML = '<option value="">Select Designation</option>';
        }
    }



    // joining date
    document.addEventListener("DOMContentLoaded", function() {
        let dateInput = document.getElementById("joining_date");

        // Function to format date as DD/MM/YYYY
        function formatDate(date) {
            let d = new Date(date);
            let day = ("0" + d.getDate()).slice(-2);
            let month = ("0" + (d.getMonth() + 1)).slice(-2);
            let year = d.getFullYear();
            return `${day}/${month}/${year}`;
        }

        // ✅ Set today's date in DD/MM/YYYY format
        let today = new Date();
        dateInput.value = formatDate(today);

        // ✅ Restrict input to only valid date format (DD/MM/YYYY)
        dateInput.addEventListener("input", function(e) {
            this.value = this.value.replace(/[^0-9/]/g, "").substring(0, 10);
        });

        // ✅ Convert DD/MM/YYYY to YYYY-MM-DD before form submission
        dateInput.form.addEventListener("submit", function(e) {
            let parts = dateInput.value.split("/");
            if (parts.length === 3) {
                let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert to YYYY-MM-DD
                dateInput.value = formattedDate;
            }
        });
    });

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


?>