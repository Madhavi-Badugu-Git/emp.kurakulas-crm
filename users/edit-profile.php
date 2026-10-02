<?php 
session_start(); // ✅ Ensure this is at the top!
// print_r($_SESSION); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="list";</script>';
    exit();
}

$user_id = $_GET['id'];
// echo $user_id;

// Fetch designation details
$query = mysqli_query($conn, "SELECT * FROM tbl_user WHERE id = '$user_id '");
if(mysqli_num_rows($query)>0){
    while($row = mysqli_fetch_assoc($query)){
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $mobile = $row['mobile'];
        $email_id = $row['email_id'];
        $password = $row['password'];
        $dob = $row['dob'];
        $father_name = $row['father_name'];
        $joining_date = $row['joining_date'];
        $avatar = $row['avatar'];
        $present_address = $row['present_address'];
        $permanent_address = $row['permanent_address'];
        $height = $row['height'];
        $weight = $row['weight'];
        $passport_no = $row['passport_no'];
        $passport_valid = $row['passport_valid'];
        $languages = $row['languages'];
        $hobbies = $row['hobbies'];
        $blood_group = $row['blood_group'];
        $emergency_no = $row['emergency_no'];
        $emergency_address = $row['emergency_address'];
        $reference_name = $row['reference_name'];
        $reference_relation = $row['reference_relation'];
        $reference_mobile = $row['reference_mobile'];
        $reference_address = $row['reference_address'];
        $reference_name2 = $row['reference_name2'];
        $reference_mobile2 = $row['reference_mobile2'];
        $reference_relation2 = $row['reference_relation2'];

        $reference_address2 = $row['reference_address2'];


    }
}
// $query = "SELECT * FROM tbl_user WHERE id = ?";
// $stmt = $conn->prepare($query);
// $stmt->bind_param("i", $user_id);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows == 0) {
//     echo '<script>alert("User not found!"); window.location.href="list";</script>';
//     exit();
// }

// $user = $result->fetch_assoc();


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
                                        <h5 class="mb-0">Edit User Details</h5>
                                        <!-- <small class="text-muted float-end">Merged input group</small> -->
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">


                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="first_name">First
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="first_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="first_name"
                                                            id="first_name" placeholder="First Name"
                                                            value="<?= $firstName; ?>" aria-label="John Doe"
                                                            aria-describedby="first_name2" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="last_name">Last
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="last_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="last_name"
                                                            id="last_name" placeholder="Last Name"
                                                            value="<?= $lastName; ?>" aria-label="John Doe"
                                                            aria-describedby="last_name2" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number">Personal Phone
                                                        No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="Phone_number" name="Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            value="<?= $mobile; ?>" aria-label="658 799 8941"
                                                            aria-describedby="Phone_number2" maxlength="10"
                                                            pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="email_id">Personal Email</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="email" name="email_id" id="email_id"
                                                            class="form-control" placeholder="User Email"
                                                            value="<?= $email_id; ?>" aria-label="User Email"
                                                            aria-describedby="email_id2" />

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
                                                            value="<?= $father_name; ?>"
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
                                                            id="joining_date" value="<?= $joining_date; ?>"
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
                                                        <input type="text" class="form-control" name="birth_date"
                                                            id="birth_date" value="<?= $dob; ?>"
                                                            aria-describedby="birth_date2" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <label for="formFile" class="form-label">Image
                                                    </label>
                                                    <input class="form-control" type="file" id="formFile" name="file">

                                                </div>
                                            </div>
                                            <div class="row mt-3">

                                                <!-- <div class="col-md-6">
                                                    <div class="row"> -->
                                                        <div class="col-md-6">
                                                            <label for="formFile" class="form-label">Height
                                                            </label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="post_applied2" class="input-group-text"><i
                                                                        class="bx bx-ruler"></i>
                                                                </span>
                                                                <input class="form-control" type="number" id="height"
                                                                    name="height" value="<?= $height; ?>"
                                                                    placeholder="Height (cm)" aria-label="Height"
                                                                    aria-describedby="post_applied2" min="0">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="formFile" class="form-label">Weight
                                                            </label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="post_applied2" class="input-group-text"><i
                                                                        class="bx bx-dumbbell"></i>
                                                                </span>
                                                                <input class="form-control" type="number" id="weight"
                                                                    name="weight" value="<?= $weight; ?>"
                                                                    placeholder="Weight (kg)" aria-label="Weight"
                                                                    aria-describedby="post_applied2" min="0" step="0.1">
                                                            </div>
                                                        </div>
                                                    <!-- </div>
                                                </div> -->
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
                                                            placeholder="Present Address"><?= $present_address; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="permanent_address">Permanent Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text">
                                                            <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="permanent_address" id="permanent_address"
                                                            class="form-control"
                                                            placeholder="Permanent Address"><?= $permanent_address; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="mt-3">Additional Information</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="passport_no">Passport No.</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-id-card"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="passport_no"
                                                            id="passport_no" value="<?= $passport_no; ?>" placeholder="Passport No" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="passport_valid">Valid Up to</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-credit-card"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="passport_valid"
                                                            id="passport_valid" value="<?= $passport_valid; ?>" placeholder="Valid Up to" />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row mt-2">
                                                <div class="mb-6">
                                                    <label class="form-label" for="languages">Languages Known</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-book"></i>

                                                        </span>
                                                        <input type="text" class="form-control" name="languages"
                                                            id="languages" value="<?= $languages; ?>" placeholder="Languages...." />
                                                    </div>
                                                </div>
                                                <div class="mb-6">
                                                    <label class="form-label" for="hobbies">Your
                                                        Hobbies</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-joystick"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="hobbies"
                                                            id="hobbies" value="<?= $hobbies; ?>" placeholder="Hobbies...." />
                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="mb-0">Emergency Details</h5>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="blood_group">Blood Group</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i
                                                                class="bx bx-droplet"></i></span>
                                                        <input type="text" class="form-control" name="blood_group"
                                                            id="blood_group" value="<?= $blood_group; ?>" placeholder="Blood Group" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="emergency_no">Contact Person in case
                                                        of Emergency</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-phone-call"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="emergency_no"
                                                            id="emergency_no" value="<?= $emergency_no; ?>" placeholder="Emergency Number"  maxlength="10" pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="mb-6">
                                                    <label class="form-label" for="emergency_address">Emergency Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text">
                                                            <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="emergency_address" id="emergency_address"
                                                            class="form-control"  placeholder="Emergency Address"><?= $emergency_address; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="mb-0">References: 1</h5>
                                            <div class="row mt-2">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="reference_name">Name</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="reference_name"
                                                            id="reference_name"  value="<?= $reference_name; ?>" placeholder="Name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="reference_relation">Relation</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-group"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="reference_relation"  
                                                        id="reference_relation" value="<?= $reference_relation; ?>" placeholder="Relation" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="reference_mobile">Mobile No</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-phone-call"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="reference_mobile"
                                                        id="reference_mobile" value="<?= $reference_mobile; ?>" placeholder="Mobile No" maxlength="10" pattern="[0-9]{10}"
                                                        oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="mb-6">
                                                    <label class="form-label" for="reference_address"> Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text">
                                                            <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="reference_address" id="reference_address"
                                                            class="form-control" placeholder="Address"><?= $reference_address; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <h5 class="mb-0">References: 2</h5>
                                            <div class="row mt-2">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="reference_name2">Name</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="reference_name2"
                                                            id="reference_name2" value="<?= $reference_name2; ?>" placeholder="Name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="reference_relation2">Relation</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-group"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="reference_relation2"
                                                        id="reference_relation2" value="<?= $reference_relation2; ?>" placeholder="Relation" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="reference_mobile2">Mobile No</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-phone-call"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="reference_mobile2"
                                                        id="reference_mobile2" value="<?= $reference_mobile2; ?>" placeholder="Mobile No" maxlength="10" pattern="[0-9]{10}"
                                                        oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="mb-6">
                                                    <label class="form-label" for="reference_address2"> Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text">
                                                            <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="reference_address2" id="reference_address2"
                                                            class="form-control" placeholder="Address"><?= $reference_address2; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <input type="submit" name="form_submit" value="Submit"
                                                    class="btn btn-primary mt-2">
                                                <!-- <input type="hidden" value="<?= $last_id ?>"> -->
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
if (isset($_POST['form_submit'])) {

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $Phone_number = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $email_id = mysqli_real_escape_string($conn, $_POST['email_id']);
    $father_name = mysqli_real_escape_string($conn, $_POST['father_name']);
    $joining_date = mysqli_real_escape_string($conn, $_POST['joining_date']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $present_address = mysqli_real_escape_string($conn, $_POST['present_address']);
    $permanent_address = mysqli_real_escape_string($conn, $_POST['permanent_address']);
    $height = mysqli_real_escape_string($conn, $_POST['height']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);

    $passport_no = mysqli_real_escape_string($conn, $_POST['passport_no']);
    $passport_valid = mysqli_real_escape_string($conn, $_POST['passport_valid']);
    $languages = mysqli_real_escape_string($conn, $_POST['languages']);
    $hobbies = mysqli_real_escape_string($conn, $_POST['hobbies']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $emergency_no = mysqli_real_escape_string($conn, $_POST['emergency_no']);
    $emergency_address = mysqli_real_escape_string($conn, $_POST['emergency_address']);
    $reference_name = mysqli_real_escape_string($conn, $_POST['reference_name']);
    $reference_relation = mysqli_real_escape_string($conn, $_POST['reference_relation']);
    $reference_mobile = mysqli_real_escape_string($conn, $_POST['reference_mobile']);
    $reference_address = mysqli_real_escape_string($conn, $_POST['reference_address']);

    $reference_name2 = mysqli_real_escape_string($conn, $_POST['reference_name2']);
    $reference_relation2 = mysqli_real_escape_string($conn, $_POST['reference_relation2']);
    $reference_mobile2 = mysqli_real_escape_string($conn, $_POST['reference_mobile2']);
    $reference_address2 = mysqli_real_escape_string($conn, $_POST['reference_address2']);

    $updated_at = date('Y-m-d H:i:s');

    if (!empty($first_name) && !empty($email_id) && !empty($Phone_number)) {
        $brocher = ''; // Default empty

        // Check if a file is uploaded
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
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
            } else {
                echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Invalid file format. Only JPG, JPEG, and PNG allowed.",
                    position: "topRight"
                });
                </script>';
                exit;
            }
        }

      

            // Update query: Conditionally update avatar only if a new image is uploaded
            if (!empty($brocher)) {
                $sql = "UPDATE tbl_user SET firstName = '$first_name', lastName = '$last_name', mobile = '$Phone_number', email_id = '$email_id', dob = '$birth_date', father_name = '$father_name', joining_date = '$joining_date', present_address = '$present_address', permanent_address = '$permanent_address', height = '$height', weight = '$weight', avatar='$brocher', passport_no = '$passport_no', passport_valid = '$passport_valid', languages = '$languages', hobbies = '$hobbies', blood_group = '$blood_group', emergency_no = '$emergency_no', emergency_address = '$emergency_address', reference_name = '$reference_name', reference_relation = '$reference_relation', reference_mobile = '$reference_mobile', reference_address = '$reference_address', reference_name2 = '$reference_name2', reference_relation2 = '$reference_relation2', reference_mobile2 = '$reference_mobile2', reference_address2 = '$reference_address2', updated_at = '$updated_at' WHERE id = '$user_id'";
            } else {
                $sql = "UPDATE tbl_user SET firstName = '$first_name', lastName = '$last_name', mobile = '$Phone_number', email_id = '$email_id', dob = '$birth_date', father_name = '$father_name', joining_date = '$joining_date', present_address = '$present_address', permanent_address = '$permanent_address', height = '$height', weight = '$weight',  passport_no = '$passport_no', passport_valid = '$passport_valid', languages = '$languages', hobbies = '$hobbies', blood_group = '$blood_group', emergency_no = '$emergency_no', emergency_address = '$emergency_address', reference_name = '$reference_name', reference_relation = '$reference_relation', reference_mobile = '$reference_mobile', reference_address = '$reference_address', reference_name2 = '$reference_name2', reference_relation2 = '$reference_relation2', reference_mobile2 = '$reference_mobile2', reference_address2 = '$reference_address2', updated_at = '$updated_at' WHERE id = '$user_id'";
            }

            if (mysqli_query($conn, $sql)) {
                echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "User details updated successfully!",
                    position: "topRight"
                });
                setTimeout(() => { window.location.href = "list"; }, 1000);
                </script>';
            } else {
                echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Failed to update user details. Please try again.",
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