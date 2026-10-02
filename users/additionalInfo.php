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
                                        <h5 class="mb-0">Additional Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="last_id"
                                                value="<?= htmlspecialchars($last_id) ?>">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="passport_no">Passport No.</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-id-card"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="passport_no"
                                                            id="passport_no" placeholder="Passport No" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="passport_valid">Valid Up to</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-credit-card"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="passport_valid"
                                                            id="passport_valid" placeholder="Valid Up to" />
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
                                                            id="languages" placeholder="Languages...." />
                                                    </div>
                                                </div>
                                                <div class="mb-6">
                                                    <label class="form-label" for="hobbies">Your
                                                        Hobbies</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-joystick"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="hobbies"
                                                            id="hobbies" placeholder="Hobbies...." />
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
                                                            id="blood_group" placeholder="Blood Group" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="emergency_no">Contact Person in case
                                                        of Emergency</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-phone-call"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="emergency_no"
                                                            id="emergency_no" placeholder="Emergency Number"  maxlength="10" pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="mb-6">
                                                    <label class="form-label" for="emergency_address"> Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text">
                                                            <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="emergency_address" id="emergency_address"
                                                            class="form-control" placeholder="Address"></textarea>
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
                                                            id="reference_name" placeholder="Name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="reference_relation">Relation</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-group"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="reference_relation"
                                                        id="reference_relation" placeholder="Relation" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="reference_mobile">Mobile No</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-phone-call"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="reference_mobile"
                                                        id="reference_mobile" placeholder="Mobile No" maxlength="10" pattern="[0-9]{10}"
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
                                                            class="form-control" placeholder="Address"></textarea>
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
                                                            id="reference_name2" placeholder="Name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="reference_relation2">Relation</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-group"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="reference_relation2"
                                                        id="reference_relation2" placeholder="Relation" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="reference_mobile2">Mobile No</label>
                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text"><i class="bx bx-phone-call"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="reference_mobile2"
                                                        id="reference_mobile2" placeholder="Mobile No" maxlength="10" pattern="[0-9]{10}"
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
                                                            class="form-control" placeholder="Address"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <input type="submit" name="update_additional_info"
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
if (isset($_POST['update_additional_info'])) {
    include('../includes/dbConfig.php'); // Ensure database connection is included

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

    if (!empty($_POST['last_id'])) {
        $user_id = $_POST['last_id'];

        $sql = "UPDATE tbl_user 
                SET passport_no = ?, passport_valid = ?, languages = ?, hobbies = ?, 
                    blood_group = ?, emergency_no = ?, emergency_address = ?, 
                    reference_name = ?, reference_relation = ?, reference_mobile = ?, 
                    reference_address = ?,  reference_name2 = ?, reference_relation2 = ?, reference_mobile2 = ?, reference_address2 = ?, updated_at = ? 
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssssi", $passport_no, $passport_valid, $languages, $hobbies, 
                          $blood_group, $emergency_no, $emergency_address, $reference_name, 
                          $reference_relation, $reference_mobile, $reference_address,  $reference_name2, $reference_relation2, $reference_mobile2, $reference_address2, $updated_at, $user_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "User information updated successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="addBank.php"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Something went wrong, please try again",
                    position: "topRight",
                });
            </script>';
        }
        $stmt->close();
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "User ID not found",
                position: "topRight",
            });
        </script>';
    }
    $conn->close();
}
?>
