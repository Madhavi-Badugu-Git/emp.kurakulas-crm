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
<style>
.icon-color {
    color: rgb(114 123 132) !important;
    /* Default text color */
    text-decoration: none;
    /* Removes underline */
}

.icon-color:hover {
    color: #696cff !important;
    /* Change to pink on hover */
}
</style>

<body>

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>

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
                            <h4>My Links</h4>
                            <?php 
                            $sql = "SELECT * FROM `tbl_user` WHERE `id` = '$loggedInUserId'";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $account_icons = json_decode($row['account_icons']); // Decode JSON string into an array

                                    if (is_array($account_icons)) {
                                        foreach ($account_icons as $icon_id) {
                                            // Fetch icon details 
                                            $icon_query = "SELECT icon_name, username, password, icon_url, icon_image, icon_description FROM `tbl_account_icon` WHERE `id` = '$icon_id' ORDER BY icon_name ASC";
                                            $icon_result = mysqli_query($conn, $icon_query);

                                            if (mysqli_num_rows($icon_result) > 0) {
                                                while ($icon_row = mysqli_fetch_assoc($icon_result)) {
                                                    ?>
                                                <div class="col-lg-2 col-md-2 col-6 mb-4">
                                                    <div class="card h-100 text-center p-3">
                                                        <a href="<?= $icon_row['icon_url']; ?>" target="_blank">
                                                            <img src="../uploads/account_icons/<?= $icon_row['icon_image']; ?>"
                                                                alt="chart success" class="rounded"
                                                                style="height:70px;width:100%">
                                                        </a>
                                                        <p class="mb-0" style="font-size:18px!important">
                                                            <a href="<?= $icon_row['icon_url']; ?>" target="_blank"
                                                                class="icon-color">
                                                                <b><?= $icon_row['icon_name']; ?></b>
                                                            </a>
                                                        </p>
                                                        <p class="mb-0"><?= $icon_row['icon_description']; ?></p>
                                                        <p class="mb-0">UserName: <?= $icon_row['username']; ?></p>
                                                        <p class="mb-0">Password: <?= $icon_row['password']; ?></p>
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