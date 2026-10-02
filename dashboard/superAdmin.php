<?php session_start(); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
// $loggedInUser = $_SESSION['loggedInUser'];
// echo $loggedInUser;

if ($loggedInUser == 'Admin') {
    header("Location: admin");
} else if($loggedInUser == 'User'){
    header("Location: user");
}

function getCustomerTypeCount($conn, $customerType, $loggedInUser, $loggedInUserRank) {
    if ($loggedInUserRank == 'superAdmin') {
        $sql = "SELECT COUNT(id) AS total_count FROM tbl_database WHERE customer_type='$customerType' AND status = 1";
    } else {
        $sql = "SELECT COUNT(id) AS total_count FROM tbl_database WHERE customer_type='$customerType' AND status = 1 AND createdBy='$loggedInUser'";
    }

    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_count'];
    }
    return 0;
}
$total_sal  = getCustomerTypeCount($conn, '39', $loggedInUser, $loggedInUserRank);
$total_senp = getCustomerTypeCount($conn, '35', $loggedInUser, $loggedInUserRank);
$total_sep  = getCustomerTypeCount($conn, '36', $loggedInUser, $loggedInUserRank);
$total_nri  = getCustomerTypeCount($conn, '37', $loggedInUser, $loggedInUserRank);
$total_edu  = getCustomerTypeCount($conn, '38', $loggedInUser, $loggedInUserRank);

function getCustomerTypeCountAppt($conn, $customerType, $loggedInUser, $loggedInUserRank) {
    if ($loggedInUserRank == 'superAdmin') {
        $sql = "SELECT COUNT(id) AS total_count FROM tbl_appointment WHERE customer_type='$customerType' AND status = 1";
    } else {
        $sql = "SELECT COUNT(id) AS total_count FROM tbl_appointment WHERE customer_type='$customerType' AND status = 1 AND createdBy='$loggedInUser'";
    }

    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_count'];
    }
    return 0;
}
$total_sal_1  = getCustomerTypeCountAppt($conn, '39', $loggedInUser, $loggedInUserRank);
$total_senp_1 = getCustomerTypeCountAppt($conn, '35', $loggedInUser, $loggedInUserRank);
$total_sep_1  = getCustomerTypeCountAppt($conn, '36', $loggedInUser, $loggedInUserRank);
$total_nri_1  = getCustomerTypeCountAppt($conn, '37', $loggedInUser, $loggedInUserRank);
$total_edu_1  = getCustomerTypeCountAppt($conn, '38', $loggedInUser, $loggedInUserRank);

?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>

<body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
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
                        <!-- /Welcome Row -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            
                                            <div class="col-lg-8 col-md-8 col-sm-8">
                                                <h5 class="card-title text-primary mb-3">
                                                    Welcome <?= $loggedInUserFirstName; ?> 🎉
                                                </h5>
                                                <p class="mb-0">
                                                    Welcome, Superadmin! Easily manage users, track system
                                                    performance, configure settings, and ensure security — all from a
                                                    powerful, centralized dashboard.
                                                </p>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 text-center">
                                                <img src="../assets/img/illustrations/man-with-laptop.png"
                                                    alt="View Badge User"
                                                    class="img-fluid"
                                                    style="max-height:120px;">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Welcome Row -->

                        <div class="row ">
                            <!-- database -->
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                        <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                        <h5 class="mb-1 ">Total SAL Data</h5>
                                        <a href="../database_team/sal_links">
                                            <h4  class="mb-0"><?= $total_sal ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                        <img src="../assets/img/icons/unicons/chart-success.png"
                                                    class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                        <h5 class="mb-1">Total SENP Data</h5>
                                        <a href="../database_team/senp_links">
                                            <h4 class="mb-0"><?= $total_senp ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                             <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                        <img src="../assets/img/icons/unicons/chart-success.png"
                                                    class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                        <h5 class="mb-1">Total SEP Data</h5>
                                        <a href="../database_team/sep_links">
                                            <h4 class="mb-0"><?= $total_sep ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                        <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                        <h5 class="mb-1 ">Total NRI Data </h5>
                                        <a href="../database_team/nri_links">
                                            <h4 class="mb-0"><?= $total_nri ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                        <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                        <h5 class="mb-1 ">Total Educational Data </h5>
                                        <a href="../database_team/edu_links">
                                            <h4 class="mb-0"><?= $total_edu ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- database -->

                            <!-- appointment -->
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                       <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                            <h5 class="mb-1">Total SAL Appt</h5>
                                        <a href="../appointment_team/sal_links">
                                                <h4 class="mb-0"><?= $total_sal_1 ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                       <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                            <h5 class="mb-1">Total SENP Appt</h5>
                                        <a href="../appointment_team/senp_links">
                                            <h4 class="mb-0"><?= $total_senp_1 ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                       <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                        <h5 class="mb-1 ">Total SEP Appt</h5>
                                        <a href="../appointment_team/sep_links">
                                            <h4 class="mb-0"><?= $total_sep_1 ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                        <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                            <h5 class="mb-1">Total NRI Appt</h5>
                                        <a href="../appointment_team/nri_links">
                                            <h4 class="mb-0"><?= $total_nri_1 ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                        <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                            <h5 class="mb-1 ">Total Educational Appt</h5>
                                        <a href="../appointment_team/edu_links">
                                            <h4 class="mb-0"><?= $total_edu_1 ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- appointment -->

                            <!-- partner -->
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                        <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                        
                                        <h5 class="mb-1 ">Total Partners</h5>
                                        <?php 
                                        $sql_count = "SELECT COUNT(id) AS total_count FROM tbl_partner";
                                        $result_count = mysqli_query($conn, $sql_count);

                                        if ($result_count) {
                                            $row_count = mysqli_fetch_assoc($result_count);
                                            $total_partner = $row_count['total_count']; // Correct key
                                        } else {
                                            $total_partner = 0; // Default if query fails
                                        }
                                        ?>
                                        <a href="../partner/list">
                                        <h4 class="mb-0"><?= $total_partner; ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- partner -->

                            <!-- connector -->
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                        <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                        <h5 class="mb-1 ">Total Connectors</h5>
                                        <?php 
                                        $sql_count = "SELECT COUNT(id) AS total_count FROM tbl_connectors";
                                        $result_count = mysqli_query($conn, $sql_count);

                                        if ($result_count) {
                                            $row_count = mysqli_fetch_assoc($result_count);
                                            $total_connectors = $row_count['total_count']; // Correct key
                                        } else {
                                            $total_connectors = 0; // Default if query fails
                                        }
                                        ?>
                                        <a href="../connectors/list">
                                        <h4 class="mb-0"><?= $total_connectors; ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- connector -->

                            <!-- agent -->
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                       <img src="../assets/img/icons/unicons/chart-success.png" class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                        <h5 class="mb-1 ">Total Agents</h5>
                                        <?php 
                                        $sql_count = "SELECT COUNT(id) AS total_count FROM tbl_agent_data";
                                        $result_count = mysqli_query($conn, $sql_count);

                                        if ($result_count) {
                                            $row_count = mysqli_fetch_assoc($result_count);
                                            $total_agent = $row_count['total_count']; // Correct key
                                        } else {
                                            $total_agent = 0; // Default if query fails
                                        }
                                        ?>
                                        <a href="../agent-data/list">
                                        <h4 class="mb-0"><?= $total_agent; ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- agent -->
                            <!-- total portfolio -->
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card h-100 text-center">
                                    <div class="card-body">
                                        <img src="../assets/img/icons/unicons/chart-success.png"
                                                    class="rounded mb-2"
                                            style="height:30px;" alt="chart">
                                            <h5 class="mb-1 ">Total Portfolio</h5>
                                        <?php 
                                        $sql_count = "SELECT COUNT(id) AS total_count FROM tbl_portfolio";
                                        $result_count = mysqli_query($conn, $sql_count);

                                        if ($result_count) {
                                            $row_count = mysqli_fetch_assoc($result_count);
                                            $total_portfolio = $row_count['total_count']; // Correct key
                                        } else {
                                            $total_portfolio = 0; // Default if query fails
                                        }
                                        ?>
                                        <a href="../portfolio/list">
                                            <h4><?= $total_portfolio; ?></h4>
                                        </a>
                                        <?php 
                                        $sql_amount = "SELECT SUM(loan_amount) AS total_loan_amount FROM tbl_portfolio_loan_details";
                                        $result_amount = mysqli_query($conn, $sql_amount);
                                        if($result_amount){
                                            $row_amount = mysqli_fetch_assoc($result_amount);
                                            $total_loan_amount = $row_amount['total_loan_amount'];
                                        } else{
                                            $total_loan_amount = 0;
                                        }
                                        ?>
                                            <h6 class="mb-1 ">Total Amount :
                                                <br><?= $total_loan_amount; ?>&#8377;</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- total portfolio -->
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
    $(document).ready(function() {
        iziToast.success({
            title: "Welcome Back",
            message: "Welcome Back To Super Admin Dashboard",
            position: "topRight"
        });
    });
    </script>


</body>


</html>