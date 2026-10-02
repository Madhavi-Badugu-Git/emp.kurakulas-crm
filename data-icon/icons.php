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
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-6 mb-4">
                                <div class="card h-100 text-center p-3">
                                    <img src="../assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded mx-auto mb-3" style="height:40px;">
                                    <a href="add">
                                        <h5 class="mb-2">Add Icons</h5>
                                    </a>
                                </div>
                            </div>
                            <?php
                            if($loggedInUserRank == 'superAdmin'){
                                ?>
                            <div class="col-lg-3 col-md-3 col-6 mb-4">
                                <div class="card h-100 text-center p-3">
                                    <img src="../assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded mx-auto mb-3" style="height:40px;">
                                    <a href="team">
                                        <h5 class="mb-2">Team Links</h5>
                                    </a>
                                </div>
                            </div>
                            <?php } ?>
                            
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