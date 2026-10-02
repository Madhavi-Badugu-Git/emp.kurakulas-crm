<?php session_start(); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 
// $loggedInUser = $_SESSION['loggedInUser'];
// echo $loggedInUser;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>
        alert("Invalid Request!");
        window.location.href = "list";
    </script>';
    exit();
}
$user_id = $_GET['id'];

$sql = mysqli_query($conn, "SELECT *, is_teamlead FROM tbl_user WHERE id='$user_id' AND status='1'");
if(mysqli_num_rows($sql)>0){
    while($row = mysqli_fetch_assoc($sql)){
        $username = $row['username'];
        $password = $row['password'];
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $avatar = $row['avatar'];
        $email_id = $row['email_id'];
        $mobile = $row['mobile'];
        $dob = $row['dob'];
        $father_name = $row['father_name'];
        $joining_date = $row['joining_date'];
        $department_id = $row['department_id'];
        $designation_id = $row['designation_id'];
        $rank = $row['rank'];
        $present_address = $row['present_address'];
        $permanent_address = $row['permanent_address'];
        $is_teamlead = $row['is_teamlead'];

        $passport_no = $row['passport_no'];
        $passport_valid = $row['passport_valid'];
        $languages = $row['languages'];
        $hobbies = $row['hobbies'];
        $blood_group = $row['blood_group'];
        $emergency_no = $row['emergency_no'];
        $emergency_address = $row['emergency_address'];
        $height = $row['height'];
        $weight = $row['weight'];

        $acc_holder_name = $row['acc_holder_name'];
        $bank_name = $row['bank_name'];
        $branch_name = $row['branch_name'];
        $account_number = $row['account_number'];
        $ifsc_code = $row['ifsc_code'];

        $school_marksCard = $row['school_marksCard'];
        $intermediate_marksCard = $row['intermediate_marksCard'];
        $degree_certificate = $row['degree_certificate'];
        $pg_certificate = $row['pg_certificate'];
        $experience_letter = $row['experience_letter'];
        $relieving_letter = $row['relieving_letter'];
        $passport_document = $row['passport_document'];
        $aadhar_document = $row['aadhar_document'];
        $pancard_document = $row['pancard_document'];
        $bank_passbook = $row['bank_passbook'];
        $resume_document = $row['resume_document'];
        $joiningKit_document = $row['joiningKit_document'];

        $reportingTo = $row['reportingTo'];
        $official_phone = $row['official_phone'];
        $official_email = $row['official_email'];
        $work_state = $row['work_state'];
        $work_location = $row['work_location'];
        
        $reference_name = $row['reference_name'];
        $reference_relation = $row['reference_relation'];
        $reference_mobile = $row['reference_mobile'];
        $reference_address = $row['reference_address'];
        $reference_name2 = $row['reference_name2'];
        $reference_relation2 = $row['reference_relation2'];
        $reference_mobile2 = $row['reference_mobile2'];
        $reference_address2 = $row['reference_address2'];
        
        $pf_number = $row['pf_number'];
        $esi_number = $row['esi_number'];
        $last_working_date = $row['last_working_date'];
        $appointment_letter = $row['appointment_letter'];
        $experience_letter_1 = $row['experience_letter_1'];
        $relieving_letter_1 = $row['relieving_letter_1'];
        $fnf_certificate = $row['fnf_certificate'];
        $hike_letter = $row['hike_letter'];
    }
}

// Fetch all departments
$departments = mysqli_query($conn, "SELECT id, department_name FROM tbl_department ORDER BY department_name ASC");

// Fetch current designation (for pre-selection)
$designations = [];
if (!empty($department_id)) {
    $designationQuery = mysqli_query($conn, "SELECT id, designation_name FROM tbl_designation WHERE department_id='$department_id'");
    while ($row = mysqli_fetch_assoc($designationQuery)) {
        $designations[] = $row;
    }
}


// Fetch all states
$states = mysqli_query($conn, "SELECT id, state_name FROM tbl_state ORDER BY state_name ASC");

// Fetch current locations (for pre-selection)
$locations = [];
if (!empty($work_state)) {
    $locationQuery = mysqli_query($conn, "SELECT id, location FROM tbl_location WHERE state_id='$work_state'");
    while ($row = mysqli_fetch_assoc($locationQuery)) {
        $locations[] = $row;
    }
}

// branch state
$branch_states = mysqli_query($conn, "SELECT id, branch_state_name FROM tbl_branch_state ORDER BY branch_state_name ASC");

// branch location
$branch_locations = [];
if (!empty($work_state)) {
    $locationQuery = mysqli_query($conn, "SELECT id, branch_location FROM tbl_branch_location WHERE branch_state_id='$work_state'");
    while ($row = mysqli_fetch_assoc($locationQuery)) {
        $branch_locations[] = $row;
    }
}

?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>
<style>
.table tr td {
    padding: 5px 0px !important;
}
</style>

<body>

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
                        <div class="col-xl-12">
                            <!-- <h6 class="text-muted">Filled Pills</h6> -->
                            <div class="nav-align-top mb-6">
                                <ul class="nav nav-pills mb-4 nav-fill" role="tablist">
                                    <li class="nav-item mb-1 mb-sm-0">
                                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-home"
                                            aria-controls="navs-pills-justified-home" aria-selected="true"><span
                                                class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-user bx-sm me-1_5 align-text-bottom"></i>
                                                Profile
                                                <i class="bx bx-user bx-sm d-sm-none"></i></button>
                                    </li>
                                    <li class="nav-item mb-1 mb-sm-0">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-profile"
                                            aria-controls="navs-pills-justified-profile" aria-selected="false"><span
                                                class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-group bx-sm me-1_5 align-text-bottom"></i>
                                                Family</span><i class="bx bx-group bx-sm d-sm-none"></i></button>
                                    </li>
                                    <li class="nav-item mb-1 mb-sm-0">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-education"
                                            aria-controls="navs-pills-justified-education" aria-selected="false"><span
                                                class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-book bx-sm me-1_5 align-text-bottom"></i>
                                                Education</span><i class="bx bx-book bx-sm d-sm-none"></i></button>
                                    </li>
                                    <li class="nav-item mb-1 mb-sm-0">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-experience"
                                            aria-controls="navs-pills-justified-experience" aria-selected="false"><span
                                                class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-briefcase bx-sm me-1_5 align-text-bottom"></i>
                                                Experience</span><i
                                                class="bx bx-briefcase bx-sm d-sm-none"></i></button>
                                    </li>
                                     <li class="nav-item mb-1 mb-sm-0">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-reference"
                                            aria-controls="navs-pills-justified-reference" aria-selected="true"><span
                                                class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-user bx-sm me-1_5 align-text-bottom"></i>
                                                Reference
                                                <i class="bx bx-user bx-sm d-sm-none"></i></button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-bank"
                                            aria-controls="navs-pills-justified-bank" aria-selected="false"><span
                                                class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-buildings bx-sm me-1_5 align-text-bottom"></i>
                                                Bank </span><i
                                                class="bx bx-buildings bx-sm d-sm-none"></i></button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-attachment"
                                            aria-controls="navs-pills-justified-attachment" aria-selected="false"><span
                                                class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-file bx-sm me-1_5 align-text-bottom"></i>
                                                Attachment</span><i
                                                class="bx bx-file bx-sm d-sm-none"></i></button>
                                    </li>
                                    <?php
                                    if($loggedInUserRank == 'superAdmin'){
                                        ?>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-companyInfo"
                                            aria-controls="navs-pills-justified-companyInfo" aria-selected="false"><span
                                                class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-building bx-sm me-1_5 align-text-bottom"></i>
                                                Company Info</span><i
                                                class="bx bx-building bx-sm d-sm-none"></i></button>
                                    </li>
                                    
                                     <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-companyDocument"
                                            aria-controls="navs-pills-justified-companyDocument"
                                            aria-selected="false"><span class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-building bx-sm me-1_5 align-text-bottom"></i>
                                                Company Documents</span><i
                                                class="bx bx-building bx-sm d-sm-none"></i></button>
                                    </li>
                                    <?php
                                    }  else  if($loggedInUserRank == 'Admin'){
                                    ?>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-companyDocument"
                                            aria-controls="navs-pills-justified-companyDocument"
                                            aria-selected="false"><span class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-building bx-sm me-1_5 align-text-bottom"></i>
                                                Company Documents</span><i
                                                class="bx bx-building bx-sm d-sm-none"></i></button>
                                    </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                                <div class="tab-content">
                                    <!-- Personal Details -->
                                    <div class="tab-pane fade show active" id="navs-pills-justified-home"
                                        role="tabpanel">
                                        <?php 
                                            if ($loggedInUserRank == 'superAdmin' || $loggedInUserRank == 'Admin'){
                                            ?>
                                        <div class="text-end">
                                            <a href="edit-profile?id=<?= $user_id ?>"
                                                class="btn btn-primary btn btn-sm">Edit Profile</a>
                                        </div>
                                        <?php } ?>
                                        <div class="" style="padding-bottom:10px!important;">
                                            <div class="d-flex align-items-start align-items-sm-center">
                                                <img src="../uploads/users/<?= $avatar; ?>" alt="user-avatar"
                                                    class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless w-auto m-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-bold">Employee ID :</td>
                                                            <td> <?= $username; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Password :</td>
                                                            <td> <?= $password; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Rank :</td>
                                                            <td> <?= $rank; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Name :</td>
                                                            <td> <?= $firstName . " " . $lastName; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Personal Mobile:</td>
                                                            <td> <?= $mobile; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Personal Email:</td>
                                                            <td> <?= $email_id; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Date of Birth :</td>
                                                            <td> <?= $dob; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Father's Name :</td>
                                                            <td> <?= $father_name; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Department Name :</td>
                                                            <td> <?= getDepartmentName($conn, $department_id); ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Designation Name :</td>
                                                            <td> <?= getDesignationName($conn, $designation_id); ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Joining Date :</td>
                                                            <td> <?= $joining_date; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Present Address :</td>
                                                            <td> <?= $present_address; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Permanent Address :</td>
                                                            <td> <?= $permanent_address; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless w-auto m-0">

                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-bold">Reporting To :</td>
                                                            <td> <?= getUserName($conn, $reportingTo); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Office Mobile :</td>
                                                            <td> <?= $official_phone; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Office Email :</td>
                                                            <td> <?= $official_email; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Branch State :</td>
                                                            <td> <?= getBranchState($conn, $work_state); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Branch Location :</td>
                                                            <td> <?= getBranchLocation($conn, $work_location); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Passport Number :</td>
                                                            <td> <?= $passport_no; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Passport Valid Upto :</td>
                                                            <td> <?= $passport_valid; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Languages :</td>
                                                            <td> <?= $languages; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Hobbies:</td>
                                                            <td> <?= $hobbies; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Blood Group :</td>
                                                            <td> <?= $blood_group; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Emergency Number :</td>
                                                            <td> <?= $emergency_no; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Emergency Address :</td>
                                                            <td> <?= $emergency_address; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Height :</td>
                                                            <td> <?= $height; ?> cm
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Weight :</td>
                                                            <td> <?= $weight; ?> Kg</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Personal Details -->

                                    <!-- Family Details -->
                                    <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <h5 class="m-0">Family Details</h5>
                                                </tr>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Age</th>
                                                    <th>Sex</th>
                                                    <th>Relation</th>
                                                    <th>Mobile</th>
                                                    <th>Occupation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $sql_family = mysqli_query($conn, "SELECT * FROM `tbl_family_details` WHERE user_id='$user_id'");

                                                if (mysqli_num_rows($sql_family) > 0) {
                                                    while ($row_family = mysqli_fetch_assoc($sql_family)) {
                                                        ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row_family['name']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['age']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['sex']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['relation']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['mobile']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['occupation']); ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <!-- Family Details -->

                                    <!-- Education Details  -->
                                    <div class="tab-pane fade" id="navs-pills-justified-education" role="tabpanel">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <h5 class="m-0">Education Details</h5>
                                                </tr>
                                                <tr>
                                                    <th>Qualification</th>
                                                    <th>University / Institute</th>
                                                    <th>Year Of Passing</th>
                                                    <th>Marks</th>
                                                    <th>Major Subjects</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $sql_family = mysqli_query($conn, "SELECT * FROM `tbl_education_details` WHERE user_id='$user_id'");

                                                if (mysqli_num_rows($sql_family) > 0) {
                                                    while ($row_family = mysqli_fetch_assoc($sql_family)) {
                                                        ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row_family['qualification']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['university']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['year_of_passing']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['marks']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['major_subjects']); ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                     <!-- Education Details  -->

                                    <!-- experience Details  -->
                                    <div class="tab-pane fade" id="navs-pills-justified-experience" role="tabpanel">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <h5 class="m-0">Experience Details</h5>
                                                </tr>
                                                <tr>
                                                    <th rowspan="2">Organization</th>
                                                    <th colspan="2">Period</th>
                                                    <th rowspan="2">Position</th>
                                                    <th rowspan="2">Responsibility</th>
                                                    <th rowspan="2">Designation Of Superior & Phone Number</th>
                                                    <th rowspan="2"> Gross Salary Drawn</th>
                                                    <th rowspan="2">Reason For Leaving</th>
                                                </tr>
                                                <tr>
                                                    <th>From</th>
                                                    <th>To</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $sql_family = mysqli_query($conn, "SELECT * FROM `tbl_experience_details` WHERE user_id='$user_id'");

                                                if (mysqli_num_rows($sql_family) > 0) {
                                                    while ($row_family = mysqli_fetch_assoc($sql_family)) {
                                                        ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row_family['organization']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['from_date']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['to_date']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['last_position']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['job_responsibility']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['superior_designation']); ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($row_family['salary']); ?></td>
                                                    <td><?= htmlspecialchars($row_family['reason_for_leaving']); ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>


                                    </div>
                                       <!-- experience Details  -->
                                       
                                     <!-- reference Details -->
                                    <div class="tab-pane fade" id="navs-pills-justified-reference" role="tabpanel">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless w-auto m-0">
                                                    <tbody>
                                                        <h5>Reference 1</h5>
                                                        <tr>
                                                            <td class="fw-bold">Name:</td>
                                                            <td> <?= $reference_name; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Relation:</td>
                                                            <td> <?= $reference_relation; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"> Mobile No:</td>
                                                            <td> <?= $reference_mobile; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Address :</td>
                                                            <td> <?= $reference_address; ?></td>
                                                        </tr>



                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless w-auto m-0">
                                                    <tbody>

                                                        <h5>Reference 2</h5>
                                                        <tr>
                                                            <td class="fw-bold">Name:</td>
                                                            <td> <?= $reference_name2; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Relation:</td>
                                                            <td> <?= $reference_relation2; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"> Mobile No:</td>
                                                            <td> <?= $reference_mobile2; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Address :</td>
                                                            <td> <?= $reference_address2; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- reference Details -->  

                                    <!-- bank  -->
                                    <div class="tab-pane fade" id="navs-pills-justified-bank" role="tabpanel">
                                        <div class="row">
                                             <?php 
                                            if ($loggedInUserRank == 'superAdmin' || $loggedInUserRank == 'Admin'){
                                            ?>
                                             <div class="text-end">
                                                <a href="edit-bank?id=<?= $user_id ?>" class="btn btn-primary btn btn-sm">Edit Bank</a>
                                                
                                            </div>
                                             <?php } ?>
                                            <div class="col-md-6">
                                                <table class="table table-borderless w-auto m-0">
                                                    <thead>
                                                        <h5>Bank Details</h5>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-bold">Account Holder Name :</td>
                                                            <td> <?= $acc_holder_name; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Bank Name :</td>
                                                            <td> <?= getBankName($conn, $bank_name); ?>
                                                                <!-- <?= $bank_name; ?> -->
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Branch Name :</td>
                                                            <td> <?= $branch_name; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Account Number:</td>
                                                            <td> <?= $account_number; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">IFSC Code :</td>
                                                            <td> <?= $ifsc_code; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                               
                                            </div>

                                        </div>

                                    </div>
                                    <!-- bank -->

                                    <!-- attachment -->
                                    <div class="tab-pane fade" id="navs-pills-justified-attachment" role="tabpanel">
                                         <?php 
                                            if ($loggedInUserRank == 'superAdmin' || $loggedInUserRank == 'Admin'){
                                            ?>
                                        <div class="text-end">
                                                <a href="edit-upload_document?id=<?= $user_id ?>" class="btn btn-primary btn btn-sm">Edit Upload Document</a>
                                                
                                        </div>
                                         <?php } ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                
                                                <table class="table table-borderless w-auto m-0">

                                                    <tbody>
                                                        <h5>Attachment</h5>
                                                    <tr>
                                                            <td class="fw-bold">10th Marks Card :</td>
                                                            <td><?= displayFile($school_marksCard); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Intermediate Marks Card :</td>
                                                            <td><?= displayFile($intermediate_marksCard); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Degree Certificate:</td>
                                                            <td><?= displayFile($degree_certificate); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">PG Certificate:</td>
                                                            <td><?= displayFile($pg_certificate); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Experience Letter:</td>
                                                            <td><?= displayFile($experience_letter); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Relieving Letter:</td>
                                                            <td><?= displayFile($relieving_letter); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Bank PassBook:</td>
                                                            <td><?= displayFile($bank_passbook); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Aadhar Card Document:</td>
                                                            <td><?= displayFile($aadhar_document); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">PAN Card Document:</td>
                                                            <td><?= displayFile($pancard_document); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Passport Document:</td>
                                                            <td><?= displayFile($passport_document); ?></td>
                                                        </tr>
                                                         <tr>
                                                            <td class="fw-bold">Resume Document:</td>
                                                            <td><?= displayFile($resume_document); ?></td>
                                                        </tr>
                                                         <tr>
                                                            <td class="fw-bold">Joining Document:</td>
                                                            <td><?= displayFile($joiningKit_document); ?></td>
                                                        </tr>
                                                       
                                                    </tbody>

                                                </table>
                                            </div>

                                        </div>

                                    </div>
                                    
                                    <!-- company Info -->
                                    <div class="tab-pane fade" id="navs-pills-justified-companyInfo" role="tabpanel">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="employee_no">Employee
                                                        No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="employee_no2" class="input-group-text"><i
                                                                class="bx bx-hash"></i></span>
                                                        <input type="number" class="form-control" name="employee_no"
                                                            id="employee_no" value="<?= $username; ?>"
                                                            placeholder="Employee No" aria-label="Employee No"
                                                            aria-describedby="employee_no2" min="1"
                                                            oninput="this.value = Math.abs(this.value)" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="reportingTo">Reporting To</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <select id="reportingTo" name="reportingTo" class="form-select">
                                                            <option value="">Select Reporting To</option>
                                                            <?php
                                                            // $query = "SELECT id, firstName, lastName FROM tbl_user ORDER BY firstName ASC";
                                                            // $query = "SELECT id, firstName, lastName FROM tbl_user WHERE designation_id IN (17, 19, 20, 22, 23, 24, 25, 28, 29, 34) AND status = '1' ORDER BY firstName ASC";
                                                            $query = "SELECT id, firstName, lastName, username FROM tbl_user WHERE is_teamlead = 1 AND status = '1' ORDER BY firstName ASC";
                                                            $result = $conn->query($query);

                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = ($row['id'] == $reportingTo) ? 'selected' : ''; // Preselect the stored reportingTo user
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['firstName'].' '.$row['lastName'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row mt-2">
                                                <!-- Department Dropdown -->
                                                <div class="col-md-6">
                                                    <label class="form-label" for="department">Department</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="department" name="department" class="form-select">
                                                            <option value="">Select Department</option>
                                                            <?php while ($row = mysqli_fetch_assoc($departments)): ?>
                                                            <option value="<?= $row['id']; ?>"
                                                                <?= ($row['id'] == $department_id) ? 'selected' : ''; ?>>
                                                                <?= $row['department_name']; ?>
                                                            </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Designation Dropdown (Updated via AJAX) -->
                                                <div class="col-md-6">
                                                    <label class="form-label" for="designation">Designation</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <select id="designation" name="designation" class="form-select">
                                                            <option value="">Select Designation</option>
                                                            <?php foreach ($designations as $row): ?>
                                                            <option value="<?= $row['id']; ?>"
                                                                <?= ($row['id'] == $designation_id) ? 'selected' : ''; ?>>
                                                                <?= $row['designation_name']; ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="official_phone">Official Phone
                                                        No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="official_phone2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="official_phone" name="official_phone"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            value="<?= $official_phone; ?>" aria-label="658 799 8941"
                                                            aria-describedby="official_phone2" maxlength="10"
                                                            pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="official_email">Official
                                                        Email</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="email" name="official_email" id="official_email"
                                                            value="<?= $official_email; ?>" class="form-control"
                                                            placeholder="Official Email" aria-label="User Email"
                                                            aria-describedby="email_id2" />
                                                    </div>

                                                </div>
                                            </div>

                                           <div class="row mt-2">
                                                <!-- branch state Dropdown -->
                                                <div class="col-md-6">
                                                    <label class="form-label" for="work_state">Branch State</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="work_state" name="work_state" class="form-select">

                                                            <option value="">Select Branch State</option>
                                                            <?php while ($row = mysqli_fetch_assoc($branch_states)): ?>
                                                            <option value="<?= $row['id']; ?>"
                                                                <?= ($row['id'] == $work_state) ? 'selected' : ''; ?>>
                                                                <?= $row['branch_state_name']; ?>
                                                            </option>
                                                            <?php endwhile; ?>
                                                        </select>

                                                    </div>
                                                </div>

                                                <!-- Branch Location Dropdown (Updated via AJAX) -->
                                                <div class="col-md-6">
                                                    <label class="form-label" for="work_location">Branch Location</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <select id="work_location" name="work_location"
                                                            class="form-select">
                                                            <option value="">Select Branch Location</option>
                                                            <?php foreach ($branch_locations as $row): ?>
                                                            <option value="<?= $row['id']; ?>"
                                                                <?= ($row['id'] == $work_location) ? 'selected' : ''; ?>>
                                                                <?= $row['branch_location']; ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                       
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Team Lead Toggle Button -->
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label">Team Lead</label>
                                                    <div class="d-flex align-items-center gap-3 mt-1">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="is_teamlead" name="is_teamlead" value="1" <?= ($is_teamlead == 1) ? 'checked' : ''; ?> style="width: 48px; height: 24px; cursor: pointer;">
                                                            <label class="form-check-label ms-2 fw-bold" for="is_teamlead" id="teamlead_status_label">
                                                                <?= ($is_teamlead == 1) ? 'ON' : 'OFF' ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="password">Passward
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="password2" class="input-group-text"><i
                                                                class="bx bx-lock-alt"></i>

                                                        </span>
                                                        <input type="password" class="form-control" name="password"
                                                            id="password" placeholder="Password"
                                                            value="<?= $password; ?>" aria-label="Password"
                                                            aria-describedby="password2" onclick="togglePassword()" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <input type="submit" name="update_comp_info"
                                                    class="btn btn-primary mt-3" value="Submit">
                                                <input type="hidden" value="<?= $user_id ?>">
                                            </div>

                                        </form>
                                    </div>
                                    
                                    
                                    <!-- company Document -->
                                    <div class="tab-pane fade" id="navs-pills-justified-companyDocument"
                                        role="tabpanel">
                                        <div class="row">
                                        <h5>Company Document Info</h5>
                                            <div class="col-md-6">

                                                <table class="table table-borderless w-auto m-0">

                                                    <tbody>
                                                       
                                                        <tr>
                                                        <td class="fw-bold">PF Number : </td>
                                                        <td><?= $pf_number; ?></td>
                                                        </tr>
                                                        <tr>
                                                        <td class="fw-bold">ESI Number : </td>
                                                        <td><?= $esi_number; ?></td>
                                                        </tr>
                                                        <tr>
                                                        <td class="fw-bold">Last Working Date : </td>
                                                        <td><?= date("d-m-Y", strtotime($last_working_date)); ?></td>

                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Appointment Letter : </td>
                                                            <td> <?= displayFile($appointment_letter); ?></td>
                                                        </tr>
                                                       
                                                    </tbody>

                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless w-auto m-0">
                                                    <tbody>
                                                        
                                                        <tr>
                                                            <td class="fw-bold">Relieving Letter : </td>
                                                            <td><?= displayFile($relieving_letter_1); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Experience Letter  :  </td>
                                                            <td><?= displayFile($experience_letter_1); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">FNF Letter : </td>
                                                            <td><?= displayFile($fnf_certificate); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Hike Letter : </td>
                                                            <td><?= displayFile($hike_letter); ?></td>
                                                        </tr>

                                                    </tbody>

                                                </table>
                                                </div>
                                        </div>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                        <h5 class="mt-4"> Company Document Info</h5>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="pf_number">PF Number</label>
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="pf_number2" class="input-group-text"><i
                                                                class="bx bx-calculator"></i></span>
                                                        <input type="text" id="pf_number" name="pf_number"
                                                            class="form-control phone-mask" placeholder="PF Number"
                                                            aria-label="PF Number" aria-describedby="pf_number2"
                                                           />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="esi_number">ESI Number</label>
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="esi_number2" class="input-group-text"><i
                                                                class="bx bx-calculator"></i></span>
                                                        <input type="text" id="esi_number" name="esi_number"
                                                            class="form-control phone-mask" placeholder="ESI Number"
                                                            aria-label="ESI Number" aria-describedby="esi_number2"
                                                            />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="last_working_date">Last Working Date
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="last_working_date2" class="input-group-text"><i
                                                                class="bx bx-calendar-check"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="last_working_date"
                                                            id="last_working_date" placeholder="DD/MM/YYYY"
                                                            aria-describedby="last_working_date2" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="appointment_letter" class="form-label">Appointment
                                                        Letter</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="appointment_letter"
                                                        name="appointment_letter" accept="image/*,application/pdf">
                                                </div>

                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="relieving_letter_1"
                                                        class="form-label">Relieving Letter</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="relieving_letter_1"
                                                        name="relieving_letter_1" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="experience_letter_1"
                                                        class="form-label">Experiece Letter</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="experience_letter_1"
                                                        name="experience_letter_1" accept="image/*,application/pdf">
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="fnf_certificate"
                                                        class="form-label">FNF</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="fnf_certificate"
                                                        name="fnf_certificate" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="hike_letter"
                                                        class="form-label">Hike Letter</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="hike_letter"
                                                        name="hike_letter" accept="image/*,application/pdf">
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <input type="submit" name="comp_document"
                                                    class="btn btn-primary mt-3" value="Submit">
                                                <!-- <input type="hidden" value="<?= $user_id ?>"> -->
                                                <input type="hidden" name="user_id" value="<?= $user_id ?>">
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
    $(document).ready(function() {
        // Prevent form submission on enter key
        $("#designationForm").submit(function(event) {
            event.preventDefault();
        });

        // Function to load designations dynamically
        $('#department').change(function() {
            var department_id = $(this).val();
            $.ajax({
                url: '../info/get_designation_1.php',
                type: 'POST',
                data: {
                    department_id: department_id
                },
                success: function(response) {
                    $('#designation').html(response);
                }
            });
        });

        $('#work_state').change(function() {
            var branch_state_id = $(this).val();
            $.ajax({
                url: '../info/get_branch_location',
                type: 'POST',
                data: {
                    branch_state_id: branch_state_id
                },
                success: function(response) {
                    $('#work_location').html(response);
                }
            });
        });
    });

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
    
     // last working date
    document.addEventListener("DOMContentLoaded", function() {
        let dateInput = document.getElementById("last_working_date");

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

    // Team Lead Toggle - Update label
    document.addEventListener('DOMContentLoaded', function() {
        const teamleadCheckbox = document.getElementById('is_teamlead');
        const statusLabel = document.getElementById('teamlead_status_label');
        
        if (teamleadCheckbox && statusLabel) {
            teamleadCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    statusLabel.textContent = 'ON';
                    statusLabel.style.color = '#28a745';
                } else {
                    statusLabel.textContent = 'OFF';
                    statusLabel.style.color = '#dc3545';
                }
            });
        }
    });
    </script>

</body>

</html>
<?php
// Handle update
if (isset($_POST['update_comp_info'])) {
    $employee_no = $_POST['employee_no'];
   
    $department = $_POST['department'];
    $designation = $_POST['designation'];  
    $reportingTo = $_POST['reportingTo'];
    $official_phone = $_POST['official_phone'];
    $official_email = $_POST['official_email'];
    $work_state = $_POST['work_state'];
    $work_location = $_POST['work_location'];
    $password = $_POST['password'];
    $is_teamlead = isset($_POST['is_teamlead']) ? 1 : 0;
    // $user_id = $_POST['user_id'];

    $updated_at = date('Y-m-d H:i:s');

   
    $sql = "UPDATE tbl_user 
    SET `username`= '$employee_no', `password` = '$password', `employee_no` = '$employee_no', `department_id` = '$department', `designation_id` = '$designation', `reportingTo` = '$reportingTo', `official_phone` = '$official_phone', `official_email` = '$official_email', `work_state` = '$work_state', `work_location` = '$work_location', `is_teamlead` = '$is_teamlead', `updated_at` = '$updated_at' WHERE `id` = '$user_id'";
    // echo $sql;


    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Company Information updated successfully!",
                position: "topRight",
            });
           
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

    // } else {
    //     echo '<script>
    //         iziToast.warning({
    //             title: "Error",
    //             message: "User ID not found",
    //             position: "topRight",
    //         });
    //     </script>';
    // }

   
}

if (isset($_POST['comp_document'])) {
    $user_id = $_POST['user_id'];
    $pf_number = $_POST['pf_number'];
    $esi_number = $_POST['esi_number'];
    $last_working_date = $_POST['last_working_date'];

    function uploadFile($fileInputName, $existingFileName) {
        $targetDir = "../uploads/users-document/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
    
        if (!empty($_FILES[$fileInputName]['name'])) {
            $originalName = basename($_FILES[$fileInputName]['name']);
            $uniqueName = time() . "_" . $originalName;
            $targetFilePath = $targetDir . $uniqueName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    
            $allowedTypes = ["jpg", "jpeg", "png", "pdf"];
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFilePath)) {
                    return $uniqueName; // ✅ Return only file name
                }
            }
        }
        return $existingFileName; // Keep old if no new file
    }
    

    // Fetch current file paths
    $query = mysqli_query($conn, "SELECT * FROM tbl_user WHERE id='$user_id'");
    $row = mysqli_fetch_assoc($query);

    // Upload files or keep existing
    // $appointment_letter = uploadFile('appointment_letter', $row['appointment_letter']);
    $appointment_letter = uploadFile('appointment_letter', $row['appointment_letter']);

    $relieving_letter_1 = uploadFile('relieving_letter_1', $row['relieving_letter_1']);
    $experience_letter_1 = uploadFile('experience_letter_1', $row['experience_letter_1']);
    $fnf_certificate = uploadFile('fnf_certificate', $row['fnf_certificate']);
    $hike_letter = uploadFile('hike_letter', $row['hike_letter']);

    $sql = "UPDATE tbl_user 
        SET pf_number='$pf_number', 
            esi_number='$esi_number', 
            last_working_date='$last_working_date', 
            appointment_letter='$appointment_letter', 
            relieving_letter_1='$relieving_letter_1', 
            experience_letter_1='$experience_letter_1', 
            fnf_certificate='$fnf_certificate', 
            hike_letter='$hike_letter' 
        WHERE id='$user_id'";


    if (mysqli_query($conn, $sql)) {
        echo '<script>
        iziToast.success({
            title: "Success",
            message: "Company Documents Added Successfully",
            position: "topRight",
        });
        </script>';
    } else {
        echo '<script>
        iziToast.warning({
            title: "Error",
            message: "Database Error. Please Try Again",
            position: "topRight",
        });
        </script>';
    }
}

?>