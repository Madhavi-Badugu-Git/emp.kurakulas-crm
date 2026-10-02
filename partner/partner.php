<?php session_start(); 

include('../includes/dbConfig.php');

include('../includes/validation.php'); 

// $loggedInUser = $_SESSION['loggedInUser'];

// echo $loggedInUser;



if ($loggedInUser == 'superAdmin') {

    header("Location: superAdmin");

} else if($loggedInUser == 'Admin'){

    header("Location: admin");

}

// Fetch the designation name of the logged-in user
$designationName = '';
$loggedInUserId = $_SESSION['loggedInUserId']; // Assuming this is stored in session

$designationQuery = "
    SELECT d.designation_name 
    FROM tbl_user u 
    JOIN tbl_designation d ON u.designation_id = d.id 
    WHERE u.id = '$loggedInUserId' 
    LIMIT 1
";
$designationResult = $conn->query($designationQuery);
if ($designationResult && $designationResult->num_rows > 0) {
    $designationRow = $designationResult->fetch_assoc();
    $designationName = $designationRow['designation_name'];
}

// Define allowed designations and their corresponding links
$allowedDesignations = [
    'Managing Director' => 'md_team.php',
    'Director' => 'dir_team.php',
    'Business Head' => 'bh_team.php',
    'Regional Business Head' => 'rbh_team.php'
    
];

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

                                 <?php 

                                    if($loggedInUserRank == 'User'){

                                        ?>

                                    <div class="col-lg-3 col-md-3 col-6 mb-12">

                                        <div class="card h-100">

                                            <div class="card-body">

                                                <div

                                                    class="card-title d-flex align-items-center justify-content-center mb-4">

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>

                                                <a href="add">

                                                    <center>

                                                        <h4 class="card-title  mb-2">Add Partner</h4>

                                                    </center>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                     <?php

                                    }

                                    ?>

                                    <div class="col-lg-3 col-md-3 col-6 mb-12">

                                        <div class="card h-100">

                                            <div class="card-body">

                                                <div

                                                    class="card-title d-flex align-items-center justify-content-center mb-4">

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>

                                                <a href="list">

                                                <center>

                                                    <h4 class="card-title mb-3">My Partners</h4>

                                                </center>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                    <?php 

                                    if($loggedInUserRank == 'superAdmin'){

                                        ?>

                                        <div class="col-lg-3 col-md-3 col-6 mb-12">

                                            <div class="card h-100">

                                                <div class="card-body">

                                                    <div

                                                        class="card-title d-flex align-items-center justify-content-center mb-4">

                                                        <div class="avatar flex-shrink-0">

                                                            <img src="../assets/img/icons/unicons/chart-success.png"

                                                                alt="chart success" class="rounded">

                                                        </div>

                                                    </div>

                                                    <a href="team">

                                                    <center>

                                                        <h4 class="card-title mb-3"> Partner Team</h4>

                                                    </center>

                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                        <?php

                                    } else{
                                        // Check and display card
                                        if (array_key_exists($designationName, $allowedDesignations)) {
                                            $link = $allowedDesignations[$designationName];
                                            ?>
                                            <div class="col-lg-3 col-md-3 col-6 mb-12">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="card-title d-flex align-items-center justify-content-center mb-4">
                                                            <div class="avatar flex-shrink-0">
                                                                <img src="../assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded">
                                                            </div>
                                                        </div>
                                                        <a href="<?= $link ?>">
                                                            <center>
                                                                <h4 class="card-title mb-3">Partner Team</h4>
                                                            </center>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
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