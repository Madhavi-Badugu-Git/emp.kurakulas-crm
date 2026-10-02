<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Debugging: Check session values
// print_r($_SESSION);

$last_id = isset($_SESSION['last_id']) ? $_SESSION['last_id'] : null;

// if ($last_id) {
//     echo "<h3 style='color: green;'>Last ID: " . $last_id . "</h3>";
// } else {
//     echo "<h3 style='color: red;'>No last ID found</h3>";
// }
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
                                        <h5 class="mb-0">Attachments</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="last_id"
                                                value="<?= htmlspecialchars($last_id) ?>">
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label for="file_school" class="form-label">10th Marks Card</label>
                                                    </label><span
                                                    style="color:red;">*</span>
                                                    <input class="form-control" type="file" id="file_school"
                                                        name="file_school" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="file_intermediate"
                                                        class="form-label">Intermediate</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="file_intermediate"
                                                        name="file_intermediate" accept="image/*,application/pdf">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label for="file_degree" class="form-label">Degree
                                                        Certificate</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="file_degree"
                                                        name="file_degree" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="file_pg" class="form-label">PG Certificate</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="file_pg" name="file_pg"
                                                        accept="image/*,application/pdf">
                                                </div>
                                            </div>

                                            <div class="row mt-3">

                                                <div class="col-md-6">
                                                    <label for="file_aadhar" class="form-label">Aadhar Card</label>
                                                    </label><span
                                                    style="color:red;">*</span>
                                                    <input class="form-control" type="file" id="file_aadhar"
                                                        name="file_aadhar" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="file_pan" class="form-label">PAN Card</label>
                                                    </label><span
                                                    style="color:red;">*</span>
                                                    <input class="form-control" type="file" id="file_pan"
                                                        name="file_pan" accept="image/*,application/pdf">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label for="bank_passbook" class="form-label">Bank Passbook</label>
                                                    </label><span
                                                    style="color:red;">*</span>
                                                    <input class="form-control" type="file" id="bank_passbook"
                                                        name="bank_passbook" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="file_passport" class="form-label">Photocopy of
                                                        Passport</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="file_passport"
                                                        name="file_passport" accept="image/*,application/pdf">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="file_experience" class="form-label">Experience
                                                        Letter</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="file_experience"
                                                        name="file_experience" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="file_relieving" class="form-label">Relieving
                                                        Letter</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="file_relieving"
                                                        name="file_relieving" accept="image/*,application/pdf">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="file_resume" class="form-label">Resume
                                                    </label>
                                                    </label>
                                                    <input class="form-control" type="file" id="file_resume"
                                                        name="file_resume" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="file_joiningKit" class="form-label">Joining Kit
                                                    </label>
                                                    </label>
                                                    <input class="form-control" type="file" id="file_joiningKit"
                                                        name="file_joiningKit" accept="image/*,application/pdf">
                                                </div>
                                            </div>



                                            <div class="text-end">
                                                <input type="submit" name="update_bank_info"
                                                    class="btn btn-primary mt-3" value="Next">
                                                <input type="hidden" value="<?= $last_id ?>">
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
</body>

</html>
<?php
// Handle update
if (isset($_POST['update_bank_info'])) {

    $updated_at = date('Y-m-d H:i:s');

    if (isset($_POST['last_id']) && !empty($_POST['last_id'])) {
        $user_id = $_POST['last_id'];

        $allowedExts = array("jpeg", "jpg", "png", "pdf");
        $path = '../uploads/users-document/';

        // Function to handle file upload
        function uploadFile($file, $allowedExts, $path) {
            if (isset($file) && $file['error'] == 0) {
                $filename = $file['name'];
                $tmp = $file['tmp_name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                // Validate file extension
                if (in_array($ext, $allowedExts)) {
                    // Sanitize and rename file
                    $final_filename = date("dHis") . "-" . preg_replace("/[^a-zA-Z0-9.]/", "-", $filename);
                    $final_filename = strtolower($final_filename);
                    $destination = $path . $final_filename;

                    // Move file to upload folder
                    if (move_uploaded_file($tmp, $destination)) {
                        return $final_filename;
                    }
                }
            }
            return ''; // Return empty if upload fails
        }

        // Upload required documents
        $school = uploadFile($_FILES['file_school'], $allowedExts, $path);
        $aadharcard = uploadFile($_FILES['file_aadhar'], $allowedExts, $path);
        $pancard = uploadFile($_FILES['file_pan'], $allowedExts, $path);
        $passbook = uploadFile($_FILES['bank_passbook'], $allowedExts, $path);


        // Check if required files are uploaded
        if (empty($school) || empty($aadharcard) || empty($pancard) || empty($passbook)) {
            echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "10th Marks Card, Aadhar Card, Pan Card, Bank PassBook are required!",
                    position: "topRight",
                });
            </script>';
            exit;
        }

        // Upload optional documents
        $intermediate = uploadFile($_FILES['file_intermediate'], $allowedExts, $path);
        $degree = uploadFile($_FILES['file_degree'], $allowedExts, $path);
        $pg = uploadFile($_FILES['file_pg'], $allowedExts, $path);
        $experience_letter = uploadFile($_FILES['file_experience'], $allowedExts, $path);
        $relieving_letter = uploadFile($_FILES['file_relieving'], $allowedExts, $path);
        $passport = uploadFile($_FILES['file_passport'], $allowedExts, $path);
        $resume = uploadFile($_FILES['file_resume'], $allowedExts, $path);
        $joiningKit = uploadFile($_FILES['file_joiningKit'], $allowedExts, $path);


        // Update database with uploaded filenames
        mysqli_query($conn, "UPDATE tbl_user SET 
            school_marksCard='$school', 
            intermediate_marksCard='$intermediate', 
            degree_certificate='$degree', 
            pg_certificate='$pg',
            experience_letter='$experience_letter',
            relieving_letter='$relieving_letter',
            passport_document='$passport',
            aadhar_document='$aadharcard', 
            pancard_document='$pancard',
            bank_passbook='$passbook',
            resume_document='$resume',
            joiningKit_document='$joiningKit'
            
            WHERE id='$user_id'");

        ?>

<script>
$("document").ready(function() {
    iziToast.success({
        title: "Success",
        message: "Documents Uploaded Successfully",
        position: "topRight",
    });
    setTimeout(() => {
        window.location.href = "list";
    }, 1000);
});
</script>
<?php

    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Employee ID not found",
                position: "topRight",
            });
        </script>';
    }
}
?>