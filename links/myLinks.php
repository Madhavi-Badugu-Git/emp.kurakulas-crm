<?php session_start(); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
// $loggedInUser = $_SESSION['loggedInUser'];
// echo $loggedInUser;

if ($loggedInUser == 'superAdmin') {
    header("Location: superAdmin");
} else if($loggedInUser == 'User') {
    header("Location: user");
}

?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>
<style>
    .icon-color {
        color: rgb(114 123 132) !important; /* Default text color */
        text-decoration: none; /* Removes underline */
    }

    .icon-color:hover {
        color: #696cff !important; /* Change to pink on hover */
    }
</style>
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


                        <div class="row">
                            <!-- <div class="col-xxl-8 mb-6 order-0">
                                <div class="card">
                                    <div class="d-flex align-items-start row">
                                        <div class="col-sm-7">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary mb-3">Congratulations
                                                    <?= $loggedInUserFirstName; ?>! 🎉</h5>
                                                <p class="mb-6">You have done 72% more sales today.<br>Check your new
                                                    badge in your profile.</p>

                                                <a href="javascript:;" class="btn btn-sm btn-outline-primary">View
                                                    Badges</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 text-center text-sm-left">
                                            <div class="card-body pb-0 px-0 px-md-6">
                                                <img src="../assets/img/illustrations/man-with-laptop.png" height="175"
                                                    class="scaleX-n1-rtl" alt="View Badge User">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-lg-12 col-md-12 order-1">
                                <div class="row">
                                <h4>My Links</h4>
                                <?php 
                                $sql = "SELECT * FROM `tbl_user` WHERE `id` = '$loggedInUserId'";
                                $result = mysqli_query($conn, $sql);
                                
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $manage_icons = json_decode($row['manage_icons']); // Decode JSON string into an array
                                
                                        if (is_array($manage_icons)) {
                                            foreach ($manage_icons as $icon_id) {
                                                // Fetch icon details from tbl_manage
                                                $icon_query = "SELECT icon_name, icon_url, icon_image, icon_description FROM `tbl_manage_icon` WHERE `id` = '$icon_id' ORDER BY icon_name ASC";
                                                $icon_result = mysqli_query($conn, $icon_query);
                                
                                                if (mysqli_num_rows($icon_result) > 0) {
                                                    while ($icon_row = mysqli_fetch_assoc($icon_result)) {
                                                        // echo "<p><strong>Icon Name:</strong> " . $icon_row['icon_name'] . "</p>";
                                                        // echo "<p><strong>Icon Url:</strong> " . $icon_row['icon_url'] . "</p>";
                                
                                                        // echo "<img src='" . $icon_row['icon_image'] . "' alt='" . $icon_row['icon_name'] . "' style='width:50px;height:50px;'><br>";
                                                        ?>
                                                                    <div class="col-lg-2 col-md-2 col-6 mb-6">
                                
                                                                        <div class="card h-100">
                                
                                                                            <div class="card-body">
                                                                                <div
                                                                                    class="card-title d-flex align-items-center justify-content-center mb-4">
                                                                                    <div class=" ">
                                                                                        <a href="<?= $icon_row['icon_url']; ?>" target="_blank">
                                                                                            <img src="../uploads/manage-icons/<?= $icon_row['icon_image']; ?>"
                                                                                                alt="chart success" class="rounded"
                                                                                                style="height:70px;width:100%"></a>
                                                                                    </div>
                                                                                </div>
                                                                                <center>
                                                                                    <p class="mb-0" style="font-size:18px!important">
                                                                                    <a href="<?= $icon_row['icon_url']; ?>" target="_blank"  class="icon-color" >
                                                                                        <b><?= $icon_row['icon_name']; ?></b>
                                                                                        </a>
                                                                                        </p>
                                                                                        <p class="mb-0" ><?= $icon_row['icon_description']; ?></p>
                                                                                </center>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                    }
                                                }
                                            }
                                        } else {
                                            echo "You do not have permission to access this link. Please contact the administrator for further assistance.";
                                        }
                                    }
                                }
                                ?>  
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