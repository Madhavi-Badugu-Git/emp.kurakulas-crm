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
                                       
                                        <div class="card-body pt-4">
                                           
                                            <div class="row g-6">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="password">Change Password</label>
                                                    <div class="input-group input-group-merge">
                                                        <input type="text" id="password" name="password"
                                                            value="<?= $password; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-6">
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
   
</body>

</html>
<?php

if (isset($_POST['form_submit'])) {
   
    $password = $_POST['password'];

    $created_at = date('Y-m-d H:i:s');
  
    $sql = "UPDATE `tbl_user` SET `password`='$password',`updated_at`='$created_at' WHERE username='$loggedInUser'";
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
    <?php
    if($loggedInUserRank == 'superAdmin'){
        ?>
    setTimeout(() => {
        window.location.href = "superAdmin";
    }, 1000);
    <?php
    } else if($loggedInUserRank == 'Admin'){
?>
    setTimeout(() => {
        window.location.href = "admin";
    }, 1000);

    <?php
    } else{
        ?>
    setTimeout(() => {
        window.location.href = "user";
    }, 1000);
    <?php
    }
    ?>
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


?>