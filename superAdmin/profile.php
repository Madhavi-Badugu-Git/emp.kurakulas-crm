<?php session_start(); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
// $loggedInUser = $_SESSION['loggedInUser'];
// echo $loggedInUser;

$sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE username='$loggedInUser' AND status='1'");
if(mysqli_num_rows($sql)>0){
    while($row = mysqli_fetch_assoc($sql)){
        $username = $row['username'];
        $password = $row['password'];
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $avatar = $row['avatar'];
        $email_id = $row['email_id'];
        $mobile = $row['mobile'];


    }
}

?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>

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
                        <div class="row">
                            <div class="col-md-12">

                                <div class="card mb-6">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <!-- Account -->
                                        <div class="card-body">
                                            <!-- <div
                                                class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                                <img src="../uploads/profile/<?= $avatar; ?>" alt="user-avatar"
                                                class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                                                <div class="button-wrapper">
                                                    <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                                        <span class="d-none d-sm-block">Upload new photo</span>
                                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                                        <input type="file" id="upload" name="file_1"
                                                            class="account-file-input" hidden
                                                            accept="image/png, image/jpeg, image/jpg" />
                                                    </label>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary account-image-reset mb-4">
                                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Reset</span>
                                                    </button>

                                                    <div>Allowed JPG, GIF or PNG. Max size of 20K</div>
                                                </div>
                                            </div> -->
                                            <div
                                                class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                                <img src="../uploads/profile/<?= $avatar; ?>" alt="user-avatar"
                                                    class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                                                <div class="button-wrapper">
                                                    <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                                        <span class="d-none d-sm-block">Upload new photo</span>
                                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                                        <input type="file" id="upload" name="file_1"
                                                            class="account-file-input" hidden
                                                            accept="image/png, image/jpeg, image/jpg" />
                                                    </label>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary account-image-reset mb-4"
                                                        id="resetBtn">
                                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Reset</span>
                                                    </button>
                                                    <div>Allowed JPG, GIF or PNG. Max size of 20K</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-4">
                                            <!-- <form id="formAccountSettings" method="POST" onsubmit="return false"> -->
                                            <div class="row g-6">
                                                <div class="col-md-6">
                                                    <label for="firstName" class="form-label">First Name</label>
                                                    <input class="form-control" type="text" id="firstName"
                                                        name="firstName" value="<?= $firstName; ?>" autofocus />
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="lastName" class="form-label">Last Name</label>
                                                    <input class="form-control" type="text" name="lastName"
                                                        id="lastName" value="<?= $lastName; ?>" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">E-mail</label>
                                                    <input class="form-control" type="text" id="email" name="email"
                                                        value="<?= $email_id; ?>" placeholder="john.doe@example.com" />
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="phoneNumber">Phone Number</label>
                                                    <div class="input-group input-group-merge">
                                                        <!-- <span class="input-group-text">US (+1)</span> -->
                                                        <input type="text" id="phoneNumber" name="phoneNumber"
                                                            value="<?= $mobile; ?>" class="form-control"
                                                            placeholder="202 555 0111" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="password">Change Password</label>
                                                    <div class="input-group input-group-merge">
                                                        <!-- <span class="input-group-text">US (+1)</span> -->
                                                        <input type="text" id="password" name="password"
                                                            value="<?= $password; ?>" class="form-control" />
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="mt-6">
                                                <!-- <button type="submit" class="btn btn-primary me-3">Save changes</button> -->
                                                <input type="submit" name="form_submit" class="btn btn-primary me-3">
                                                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                            </div>
                                            <!-- </form> -->
                                        </div>
                                        <!-- /Account -->
                                    </form>
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
    document.getElementById('upload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('uploadedAvatar').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('resetBtn').addEventListener('click', function() {
        document.getElementById('uploadedAvatar').src = "../uploads/profile/<?= $avatar; ?>";
        document.getElementById('upload').value = "";
    });
    </script>
</body>

</html>
<?php

if (isset($_POST['form_submit'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $password = $_POST['password'];

    $created_at = date('Y-m-d H:i:s');
  
    if (isset($_FILES['file_1']['name']) and $_FILES['file_1']['name'] != '' ) {
        $filename_1 = $_FILES['file_1']['name'];

        $tmp_1 = $_FILES['file_1']['tmp_name'];
      
        $path = '../uploads/profile/';

        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
        // get uploaded file's extension
        $ext_1 = strtolower(pathinfo($filename_1, PATHINFO_EXTENSION));
       
        // can upload same image using rand function
        $final_image_1 = date("dmyHis") . $filename_1;

        $final_image_1 = str_replace(' ', '-', $final_image_1);

        $final_image_1 = strtolower($final_image_1);

        // check's valid format
        if (in_array($ext_1, $valid_extensions)) {
            $path_1 = $path . strtolower($final_image_1);

            if (move_uploaded_file($tmp_1, $path_1)) {

                $sql = "UPDATE `tbl_user` SET `firstName`='$firstName',`lastName`='$lastName',`mobile`='$phoneNumber',`email_id`='$email',`password`='$password',`updated_at`='$created_at',`avatar`='$final_image_1' WHERE username='$loggedInUser'";
                // echo $sql;
                // exit();
                $success = mysqli_query($conn, $sql);

                if ($success) {
?>
<script>
$("document").ready(function() {
    iziToast.success({
        title: "Success",
        message: "Profile Information Updated Successfully",
        position: "topRight",
    });
});
</script>
<?php
                } else {
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
            }
        }
    } else {
        $sql = "UPDATE `tbl_user` SET `firstName`='$firstName',`lastName`='$lastName',`mobile`='$phoneNumber',`email_id`='$email',`password`='$password',`updated_at`='$created_at' WHERE username='$loggedInUser'";
        // echo $sql;
        $success = mysqli_query($conn, $sql);

        if ($success) {
            ?>
<script>
$("document").ready(function() {
    iziToast.success({
        title: "Success",
        message: "Profile Information Updated Successfully",
        position: "topRight",
    });
});
</script>
<?php
        } else {
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
    }
}


?>