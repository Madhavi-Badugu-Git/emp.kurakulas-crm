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
                           
                            <div class="col-lg-12 col-md-12 order-1">
                                <div class="row">

                                    <div class="col-lg-3 col-md-3 col-6 mb-12">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div
                                                    class="card-title d-flex align-items-center justify-content-center mb-4">
                                                    <div class="flex-shrink-0">
                                                        <img src="../assets/img/icons/unicons/chart-success.png"
                                                            alt="chart success" class="rounded">
                                                    </div>
                                                </div>
                                                <a href="../insurance/add">
                                                    <center>
                                                        <h4 class="card-title  mb-2">Add Insurance</h4>
                                                    </center>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-6 mb-12">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div
                                                    class="card-title d-flex align-items-center justify-content-center mb-4">
                                                    <div class="flex-shrink-0">
                                                        <img src="../assets/img/icons/unicons/chart-success.png"
                                                            alt="chart success" class="rounded">
                                                    </div>
                                                </div>
                                                <a href="../insurance/list">
                                                    <center>
                                                        <h4 class="card-title  mb-2">My Insurance</h4>
                                                    </center>
                                                </a>
                                            </div>
                                        </div>
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

</body>


</html>