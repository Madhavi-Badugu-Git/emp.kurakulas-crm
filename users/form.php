<?php session_start(); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
// $loggedInUser = $_SESSION['loggedInUser'];
// echo $loggedInUser;

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
                                                    <label class="form-label" for="employee_no">Employee
                                                        No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="employee_no2" class="input-group-text"><i
                                                                class="bx bx-hash"></i></span>
                                                        <input type="text" class="form-control" name="employee_no"
                                                            id="employee_no" placeholder="Employee No"
                                                            aria-label="John Doe" aria-describedby="employee_no2" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">

                                                    <label class="form-label" for="rank">Role</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="rank" name="rank" class="form-select">
                                                            <option value="">Select Role</option>
                                                            <option value="Admin">Admin</option>
                                                            <option value="User">User</option>
                                                            <!-- <?php
                                                                $query = "SELECT id, role_name FROM tbl_role ORDER BY role_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['role_name'].'</option>';
                                                                }
                                                            ?> -->
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="first_name">First
                                                        Name</label>
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
                                                    <label class="form-label" for="Phone_number">Phone
                                                        No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="Phone_number" name="Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            aria-label="658 799 8941"
                                                            aria-describedby="Phone_number2" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="email_id">Email</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="text" name="email_id" id="email_id"
                                                            class="form-control" placeholder="User Email"
                                                            aria-label="User Email" aria-describedby="email_id2" />
                                                        <span id="email_id2"
                                                            class="input-group-text">@example.com</span>
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
                                                        <input type="date" class="form-control" name="joining_date"
                                                            id="joining_date" aria-describedby="joining_date2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="department">Department</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="department" name="department"
                                                            onchange="loadDesignation(this.value)" class="form-select">
                                                            <option value="">Select Department</option>
                                                            <?php
                                                                $query = "SELECT id, department_name FROM tbl_department ORDER BY department_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['department_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="designation">Designation</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-id-card"></i>
                                                        </span>
                                                        <select id="designation" name="designation" class="form-select">
                                                            <option value="">Select Designation</option>
                                                        </select>
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
                                                        <input type="date" class="form-control" name="birth_date"
                                                            id="birth_date" aria-describedby="birth_date2" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="post_applied">Post Applied For Post
                                                        Of
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="post_applied2" class="input-group-text"><i
                                                                class="bx bx-task"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="post_applied"
                                                            id="post_applied" placeholder="Post Applied For Post Of"
                                                            aria-label="Post Applied For Post Of"
                                                            aria-describedby="post_applied2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="formFile" class="form-label">Image
                                                    </label>
                                                    <input class="form-control" type="file" id="formFile" name="file">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="formFile" class="form-label">Height
                                                            </label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="post_applied2" class="input-group-text"><i
                                                                        class="bx bx-ruler"></i>
                                                                </span>
                                                                <input class="form-control" type="text" id="height"
                                                                    name="height" placeholder="Height (cm)"
                                                                    aria-label="Height"
                                                                    aria-describedby="post_applied2">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="formFile" class="form-label">Weight
                                                            </label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="post_applied2" class="input-group-text"><i
                                                                        class="bx bx-dumbbell"></i>
                                                                </span>
                                                                <input class="form-control" type="text" id="weight"
                                                                    name="weight" placeholder="Weight (kg)"
                                                                    aria-label="Weight"
                                                                    aria-describedby="post_applied2">
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

                                            <!-- family details table -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label">Family Details</label>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Age</th>
                                                                <th>Sex</th>
                                                                <th>Relation</th>
                                                                <th>Mobile No</th>
                                                                <th>Occupation</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="familyTable">
                                                            <tr>
                                                                <td><input type="text" class="form-control"
                                                                        name="family_name[]"></td>
                                                                <td><input type="number" class="form-control"
                                                                        name="family_age[]"></td>
                                                                <td>
                                                                    <select class="form-control" name="family_sex[]">
                                                                        <option>Male</option>
                                                                        <option>Female</option>
                                                                    </select>
                                                                </td>
                                                                <td><input type="text" class="form-control"
                                                                        name="family_relation[]"></td>
                                                                <td><input type="text" class="form-control"
                                                                        name="family_mobile[]"></td>
                                                                <td><input type="text" class="form-control"
                                                                        name="family_occupation[]"></td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-primary action-btn addRow">Add</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- education qualification table -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label">Education Qualification</label>
                                                    <table id="educationTable" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Qualification</th>
                                                                <th>University/Institute</th>
                                                                <th>Year of Passing</th>
                                                                <th>% Marks</th>
                                                                <th>Major Subjects</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><input type="text" class="form-control"
                                                                        name="qualification[]"></td>
                                                                <td><input type="text" class="form-control"
                                                                        name="university[]"></td>
                                                                <td><input type="number" class="form-control"
                                                                        name="year_of_passing[]"></td>
                                                                <td><input type="text" class="form-control"
                                                                        name="marks[]">
                                                                </td>
                                                                <td><input type="text" class="form-control"
                                                                        name="major_subjects[]"></td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-primary edu-action-btn addEduRow">Add</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <input type="submit" name="form_submit" class="btn btn-primary mt-2">
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
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $(document).on("click", ".action-btn", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addRow")) {
                // Add a new row
                let newRow = `<tr>
                    <td><input type="text" class="form-control" name="family_name[]"></td>
                    <td><input type="number" class="form-control" name="family_age[]"></td>
                    <td>
                        <select class="form-control" name="family_sex[]">
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="family_relation[]"></td>
                    <td><input type="text" class="form-control" name="family_mobile[]"></td>
                    <td><input type="text" class="form-control" name="family_occupation[]"></td>
                    <td>
                        <button type="button" class="btn btn-primary action-btn addRow">Add</button>
                    </td>
                </tr>`;

                $("#familyTable").append(newRow);
                btn.removeClass("btn-primary addRow").addClass("btn-danger removeRow").text("Delete");
            } else if (btn.hasClass("removeRow")) {
                row.remove();
                updateLastRow();
            }
        });

        function updateLastRow() {
            let lastRow = $("#familyTable tr:last");
            lastRow.find(".action-btn").removeClass("btn-danger removeRow").addClass("btn-primary addRow").text(
                "Add");
        }
    });

    // education Qualification
    $(document).ready(function() {
        $(document).on("click", ".edu-action-btn", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addEduRow")) {
                // Add a new row
                let newRow = `<tr>
                    <td><input type="text" class="form-control" name="qualification[]"></td>
                    <td><input type="text" class="form-control" name="university[]"></td>
                    <td><input type="number" class="form-control" name="year_of_passing[]"></td>
                    <td><input type="text" class="form-control" name="marks[]"></td>
                    <td><input type="text" class="form-control" name="major_subjects[]"></td>
                    <td>
                        <button type="button" class="btn btn-primary edu-action-btn addEduRow">Add</button>
                    </td>
                </tr>`;

                $("#educationTable").append(newRow);
                btn.removeClass("btn-primary addEduRow").addClass("btn-danger removeEduRow").text(
                    "Delete");
            } else if (btn.hasClass("removeEduRow")) {
                row.remove();
                updateLastEduRow();
            }
        });

        function updateLastEduRow() {
            let lastRow = $("#educationTable tr:last");
            lastRow.find(".edu-action-btn").removeClass("btn-danger removeEduRow").addClass(
                "btn-primary addEduRow").text("Add");
        }
    });
    </script>




</body>

</html>

<?php

if (isset($_POST['form_submit'])) {
    $employee_no = $_POST['employee_no'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    $Phone_number = $_POST['Phone_number'];
    $email_id = $_POST['email_id'];
    $father_name = $_POST['father_name'];
    $joining_date = $_POST['joining_date'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $birth_date = $_POST['birth_date'];
    $post_applied = $_POST['post_applied'];
    $present_address = $_POST['present_address'];
    $permanent_address = $_POST['permanent_address'];

    $rank = $_POST['rank'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];


    $created_at = date('Y-m-d H:i:s');
    if ($employee_no != '' && $first_name != '' && $rank != '') {
       
        $sql = "INSERT INTO `tbl_user`(`username`, `firstName`, `lastName`, `mobile`, `email_id`, `password`, `dob`, `employee_no`, `father_name`, `joining_date`, `department_id`, `designation_id`, `post_applied`, `present_address`, `permanent_address`,`rank`, `avatar`,`height`,`weight`, `created_at`) VALUES ('$employee_no','$first_name','$last_name','$Phone_number','$email_id','12345678','$birth_date','$employee_no','$father_name','$joining_date','$department','$designation','$post_applied','$present_address','$permanent_address','$rank','','$height','$weight','$created_at')";
        // echo $sql;
        // exit();

        if (mysqli_query($conn, $sql)) {
            $last_id = mysqli_insert_id($conn);

            // family table insert
            if (!empty($_POST['family_name'])) {
                foreach ($_POST['family_name'] as $index => $name) {
                    $age = $_POST['family_age'][$index];
                    $sex = $_POST['family_sex'][$index];
                    $relation = $_POST['family_relation'][$index];
                    $mobile = $_POST['family_mobile'][$index];
                    $occupation = $_POST['family_occupation'][$index];

                    $family_sql = "INSERT INTO `tbl_family_details`(`user_id`, `name`, `age`, `sex`, `relation`, `mobile`, `occupation`, `created_at`) 
                    VALUES ('$last_id', '$name', '$age', '$sex', '$relation', '$mobile', '$occupation', '$created_at')";
                    
                    mysqli_query($conn, $family_sql);
                }
            } else{
                echo "No family member added";
            }

            // Insert Education Details
            if (!empty($_POST['qualification'])) {
                foreach ($_POST['qualification'] as $index => $qual) {
                    $university = $_POST['university'][$index];
                    $year_of_passing = $_POST['year_of_passing'][$index];
                    $marks = $_POST['marks'][$index];
                    $major_subjects = $_POST['major_subjects'][$index];

                    $edu_sql = "INSERT INTO `tbl_education_details`(`user_id`, `qualification`, `university`, `year_of_passing`, `marks`, `major_subjects`, `created_at`) 
                    VALUES ('$last_id', '$qual', '$university', '$year_of_passing', '$marks', '$major_subjects', '$created_at')";
                    
                    mysqli_query($conn, $edu_sql);
                }
            }

            $allowedExts = array("txt", "gif", "jpeg", "jpg", "png", "pdf");
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            $imageFileType = $_FILES["file"]["type"];

            $brocher = '';
            if (isset($_FILES['file']['name']) != '') {

                $filename = $_FILES['file']['name'];
                $tmp = $_FILES['file']['tmp_name'];

                $path = '../uploads/users/';
                $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions
                // get uploaded file's extension
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                // can upload same image using rand function
                $final_image = date("dHis") . $filename;
                $final_image = str_replace(' ', '-', $final_image);
                $final_image = strtolower($final_image);
                // check's valid format
                if (in_array($ext, $valid_extensions)) {
                    $path = $path . strtolower($final_image);
                    if (move_uploaded_file($tmp, $path)) {
                        $brocher = $final_image;
                        ?>
<script>
$("document").ready(function() {
    iziToast.success({
        title: "Success",
        message: "User Information Added Successfully",
        position: "topRight",
    });
});
</script>
<?php

                    } else {
                        $brocher = 'default.png';
                        ?>
<script>
$("document").ready(function() {
    iziToast.success({
        title: "Success",
        message: "User Information Added Successfully",
        position: "topRight",
    });
});
</script>
<?php
                    }
                } else {
                    $brocher = 'default.png';
                }
            } else {
                $brocher = 'default.png';
            }
            mysqli_query($conn, "UPDATE tbl_user SET avatar='$brocher' where id='$last_id'");

            ?>
            <script>
            $("document").ready(function() {
                iziToast.success({
                    title: "Success",
                    message: "User Information Added Successfully",
                    position: "topRight",
                });
            });
            </script>
            <?php
          
        } else{
            ?>
<script>
$("document").ready(function() {
    iziToast.warning({
        title: "Error",
        message: "Something Went Wrong, Please Try Again",
        position: "topRight",
    });
});
</script>
<?php
        }
    } else {
        ?>
<script>
$("document").ready(function() {
    iziToast.warning({
        title: "Error",
        message: "All Fields Are Required",
        position: "topRight",
    });
});
</script>
<?php
    }
}


?>

