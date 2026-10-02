<?php 
session_start(); 
// print_r($_SESSION); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 

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


                        <!-- Basic Layout -->
                        <div class="row">

                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">Inactive User List</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Full Name</th>
                                                    <th>Username</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <th>STATUS</th>
                                                    <!-- <th>ACTIONS</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                               
                                                $sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE rank != 'SuperAdmin' AND status = '0'");
                                                if(mysqli_num_rows($sql)>0){
                                                    while($row = mysqli_fetch_assoc($sql)){
                                                        // $username = $row['username'];
                                                        // $password = $row['password'];
                                                        $firstName = $row['firstName'];
                                                        $lastName = $row['lastName'];
                                                        // $avatar = $row['avatar'];
                                                        $email_id = $row['email_id'];
                                                        $mobile = $row['mobile'];
                                                ?>
                                                <tr>
                                                     <td><?= $row['firstName'] . " " . $row['lastName']; ?></td>
                                                    <td><?= $row['username']; ?></td>
                                                  <td><?= $row['mobile']; ?></td>
                                                  <td><?= $row['email_id']; ?></td>

                                                    <!-- 
                                                    <td>
                                                        <img src="../uploads/user1.jpg" class="rounded-circle"
                                                            width="30">
                                                        <img src="../uploads/user2.jpg" class="rounded-circle"
                                                            width="30">
                                                    </td> -->
                                                    <td>
                                                    <a href="active.php?id=<?= $row['id']; ?>">
                                                        <span class="badge bg-danger">InActive</span>
                                                    </a>
                                                    </td>
                                                    <!-- <td>
                                                    <a href="view.php?id=<?= $row['id']; ?>">
                                                    <span class="badge bg-primary">View</span>
                                                    </a>
                                                    </td> -->
                                                </tr>
                                                <?php
                                                
                                                    }
                                                }
                                                ?>


                                            </tbody>
                                        </table>
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