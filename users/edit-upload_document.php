<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


if(isset($_GET['id'])){
    $user_id = $_GET['id'];
    // echo $user_id;

    // Fetch department details
$query = mysqli_query($conn, "SELECT * FROM tbl_user WHERE id = '$user_id '");
if(mysqli_num_rows($query)>0){
    while($row = mysqli_fetch_assoc($query)){
        $user_id = $row['id'];
        // $bank_name = $row['bank_name'];
        // $branch_name = $row['branch_name'];
        // $account_number = $row['account_number'];
        // $ifsc_code = $row['ifsc_code'];

    }
}
}


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
                                        <h5 class="mb-0">Edit Upload Document</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="user_id"
                                                value="<?= htmlspecialchars($user_id) ?>">
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label for="file_school" class="form-label">10th Marks Card</label>
                                                    </label>
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
                                                    </label>
                                                    <input class="form-control" type="file" id="file_aadhar"
                                                        name="file_aadhar" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="file_pan" class="form-label">PAN Card</label>
                                                    </label>
                                                    <input class="form-control" type="file" id="file_pan"
                                                        name="file_pan" accept="image/*,application/pdf">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label for="bank_passbook" class="form-label">Bank Passbook</label>
                                                    </label>
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
                                                <input type="submit" name="update_documents"
                                                    class="btn btn-primary mt-3" value="Next">
                                                <input type="hidden" value="<?= $user_id ?>">
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
if (isset($_POST['update_documents'])) {

    $user_id = intval($_POST['user_id']);
    $updated_at = date("Y-m-d H:i:s"); // Define updated_at timestamp

    // Fetch current images (if no new file is uploaded, we keep existing ones)
    $stmt = $conn->prepare("SELECT school_marksCard, intermediate_marksCard, degree_certificate, pg_certificate, aadhar_document, pancard_document, bank_passbook, passport_document, experience_letter, relieving_letter, resume_document, joiningKit_document FROM tbl_user WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($school_marks, $intermediate_marks, $degree_certificate, $pg_certificate, $aadhar_document, $pancard_document, $passbook_document, $passport_document, $experience_letter, $relieving_letter, $resume_document, $joiningKit_document);
    $stmt->fetch();
    $stmt->close();

    // Keep existing file names unless a new file is uploaded
    $school = $school_marks;
    $intermediate = $intermediate_marks;
    $degree = $degree_certificate;
    $pg = $pg_certificate;
    $aadhar = $aadhar_document;
    $pan = $pancard_document;
    $bank = $passbook_document;
    $passport = $passport_document;
    $experience = $experience_letter;
    $relieving = $relieving_letter;
    $resume = $resume_document;
    $joining_kit = $joiningKit_document;

    $path = '../uploads/users-document/';
    $valid_extensions = ['jpeg', 'jpg', 'png', 'pdf'];

    // Function to handle file upload
    function uploadFile($file_input, $existing_file) {
        global $path, $valid_extensions;

        if (!empty($_FILES[$file_input]['name'])) {
            $filename = $_FILES[$file_input]['name'];
            $tmp = $_FILES[$file_input]['tmp_name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $valid_extensions)) {
                $final_image = date("dHis") . '-' . strtolower(str_replace(' ', '-', $filename));
                if (move_uploaded_file($tmp, $path . $final_image)) {
                    return $final_image; // Return new file name
                }
            } else {
                echo '<script>
                    iziToast.warning({
                        title: "Error",
                        message: "Invalid file format for ' . $file_input . '. Only JPEG, JPG, PNG, and PDF allowed.",
                        position: "topRight",
                    });
                </script>';
                exit();
            }
        }
        return $existing_file; // Keep old file if no new file uploaded
    }

    // Upload files only if new ones are selected
    $school = uploadFile('file_school', $school);
    $intermediate = uploadFile('file_intermediate', $intermediate);
    $degree = uploadFile('file_degree', $degree);
    $pg = uploadFile('file_pg', $pg);
    $aadhar = uploadFile('file_aadhar', $aadhar);
    $pan = uploadFile('file_pan', $pan);
    $bank = uploadFile('bank_passbook', $bank);
    $passport = uploadFile('file_passport', $passport);
    $experience = uploadFile('file_experience', $experience);
    $relieving = uploadFile('file_relieving', $relieving);
    $resume = uploadFile('file_resume', $resume);
    $joining_kit = uploadFile('file_joiningKit', $joining_kit);

    // ✅ Update the database with the new values
    $stmt = $conn->prepare("UPDATE `tbl_user` SET `school_marksCard`=?, `intermediate_marksCard`=?, `degree_certificate`=?, `pg_certificate`=?, `aadhar_document`=?, `pancard_document`=?, `bank_passbook`=?, `passport_document`=?, `experience_letter`=?, `relieving_letter`=?, `resume_document`=?, `joiningKit_document`=?, `updated_at`=? WHERE id=?");
    $stmt->bind_param("sssssssssssssi", $school, $intermediate, $degree, $pg, $aadhar, $pan, $bank, $passport, $experience, $relieving, $resume, $joining_kit, $updated_at, $user_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Documents Updated Successfully",
                position: "topRight",
            });
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
        </script>';
    }

    $stmt->close();
    $conn->close();
}
?>

