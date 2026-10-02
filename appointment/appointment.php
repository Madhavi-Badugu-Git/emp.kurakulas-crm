<?php session_start(); 

include('../includes/dbConfig.php');

include('../includes/validation.php'); 

// $loggedInUser = $_SESSION['loggedInUser'];

// echo $loggedInUser;



function getCustomerTypeCount($conn, $customerType, $loggedInUser, $loggedInUserRank) {

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

$total_sal  = getCustomerTypeCount($conn, '39', $loggedInUser, $loggedInUserRank);

$total_senp = getCustomerTypeCount($conn, '35', $loggedInUser, $loggedInUserRank);

$total_sep  = getCustomerTypeCount($conn, '36', $loggedInUser, $loggedInUserRank);

$total_nri  = getCustomerTypeCount($conn, '37', $loggedInUser, $loggedInUserRank);

$total_edu  = getCustomerTypeCount($conn, '38', $loggedInUser, $loggedInUserRank);



// MD Count starts

function getMdTeamCount($conn, $customerType, $loggedInUser, $loggedInUserRank) {

    $sql_md = "SELECT COUNT(id) AS total_count FROM tbl_appointment WHERE customer_type='$customerType' AND status = 1 AND createdBy != '$loggedInUser'";



    $result_md = mysqli_query($conn, $sql_md);

    if ($result_md) {

        $row = mysqli_fetch_assoc($result_md);

        return $row['total_count'];

    }

    return 0;

}

$team_md_sal  = getMdTeamCount($conn, '39', $loggedInUser, $loggedInUserRank);

$team_md_senp  = getMdTeamCount($conn, '35', $loggedInUser, $loggedInUserRank);

$team_md_sep  = getMdTeamCount($conn, '36', $loggedInUser, $loggedInUserRank);

$team_md_nri  = getMdTeamCount($conn, '37', $loggedInUser, $loggedInUserRank);

$team_md_edu  = getMdTeamCount($conn, '38', $loggedInUser, $loggedInUserRank);

// MD Count ends



// director count starts

function getDirTeamCount($conn, $customerType, $loggedInUser, $loggedInUserRank) {

    $sql_dir = "SELECT COUNT(id) AS total_count FROM tbl_appointment WHERE customer_type='$customerType' AND status = 1 AND createdBy NOT IN ('$loggedInUser', '10000')";



    $result_dir = mysqli_query($conn, $sql_dir);

    if ($result_dir) {

        $row = mysqli_fetch_assoc($result_dir);

        return $row['total_count'];

    }

    return 0;

}



$team_dir_sal  = getDirTeamCount($conn, '39', $loggedInUser, $loggedInUserRank);

$team_dir_senp  = getDirTeamCount($conn, '35', $loggedInUser, $loggedInUserRank);

$team_dir_sep  = getDirTeamCount($conn, '36', $loggedInUser, $loggedInUserRank);

$team_dir_nri  = getDirTeamCount($conn, '37', $loggedInUser, $loggedInUserRank);

$team_dir_edu  = getDirTeamCount($conn, '38', $loggedInUser, $loggedInUserRank);



// director Count ends



// rbh count starts

function getRbhTeamCount($conn, $customerType, $loggedInUser, $loggedInUserRank) {

    // Get RBH user ID and department

    $userQuery = "SELECT id, department_id FROM tbl_user WHERE username = '$loggedInUser'";

    $userResult = $conn->query($userQuery);

    if (!$userResult || $userResult->num_rows == 0) return 0;

    

    $userData = $userResult->fetch_assoc();

    $rbhId = $userData['id'];

    $deptId = $userData['department_id'];



    // Get direct report IDs (e.g., Business Heads and Managers)

    $directReports = [];

    $query1 = "SELECT id FROM tbl_user WHERE reportingTo = '$rbhId' AND department_id = '$deptId'";

    $res1 = $conn->query($query1);

    while ($row1 = $res1->fetch_assoc()) {

        $directReports[] = $row1['id'];



        // Get their subordinates (indirect reports)

        $subQuery = "SELECT id FROM tbl_user WHERE reportingTo = '{$row1['id']}' AND department_id = '$deptId'";

        $subRes = $conn->query($subQuery);

        while ($row2 = $subRes->fetch_assoc()) {

            $directReports[] = $row2['id'];

        }

    }



    if (empty($directReports)) return 0;



    // Convert IDs to a comma-separated string

    $teamIds = "'" . implode("','", array_unique($directReports)) . "'";



    // Get usernames of these team users

    $usernames = [];

    $getUsersQuery = "SELECT username FROM tbl_user WHERE id IN ($teamIds)";

    $userRes = $conn->query($getUsersQuery);

    while ($userRow = $userRes->fetch_assoc()) {

        $usernames[] = $userRow['username'];

    }



    if (empty($usernames)) return 0;



    $usernameList = "'" . implode("','", array_unique($usernames)) . "'";



    // Final count query

    $sql_rbh = "

        SELECT COUNT(id) AS total_count 

        FROM tbl_appointment 

        WHERE customer_type = '$customerType' 

        AND status = 1 

        AND createdBy IN ($usernameList)

    ";



    $result_rbh = mysqli_query($conn, $sql_rbh);

    if ($result_rbh) {

        $row = mysqli_fetch_assoc($result_rbh);

        return $row['total_count'];

    }

    return 0;

}



$team_rbh_sal  = getRbhTeamCount($conn, '39', $loggedInUser, $loggedInUserRank);

$team_rbh_senp = getRbhTeamCount($conn, '35', $loggedInUser, $loggedInUserRank);

$team_rbh_sep  = getRbhTeamCount($conn, '36', $loggedInUser, $loggedInUserRank);

$team_rbh_nri  = getRbhTeamCount($conn, '37', $loggedInUser, $loggedInUserRank);

$team_rbh_edu  = getRbhTeamCount($conn, '38', $loggedInUser, $loggedInUserRank);

// rbh count starts



// bh count starts

function getBhTeamCount($conn, $customerType, $loggedInUser, $loggedInUserRank) {

    // Get BH user ID

    $bhQuery = "SELECT id FROM tbl_user WHERE username = '$loggedInUser'";

    $bhResult = mysqli_query($conn, $bhQuery);

    if ($bhResult && mysqli_num_rows($bhResult) > 0) {

        $bhRow = mysqli_fetch_assoc($bhResult);

        $bhId = $bhRow['id'];



        // Get all managers reporting to this BH

        $mgrQuery = "SELECT id, username FROM tbl_user WHERE reportingTo = '$bhId'";

        $mgrResult = mysqli_query($conn, $mgrQuery);



        $managerUsers = [];

        while ($mgr = mysqli_fetch_assoc($mgrResult)) {

            $managerUsers[] = "'" . $mgr['id'] . "'";

            $managerUsers[] = "'" . $conn->real_escape_string($mgr['username']) . "'";

        }



        if (count($managerUsers) > 0) {

            $usersIn = implode(",", $managerUsers);

            $sql = "

                SELECT COUNT(id) AS total_count 

                FROM tbl_appointment 

                WHERE status = 1 AND customer_type = '$customerType' AND createdBy IN ($usersIn)

            ";

            $result = mysqli_query($conn, $sql);

            if ($result) {

                $row = mysqli_fetch_assoc($result);

                return $row['total_count'];

            }

        }

    }

    return 0;

}



$team_bh_sal  = getBhTeamCount($conn, '39', $loggedInUser, $loggedInUserRank);

$team_bh_senp = getBhTeamCount($conn, '35', $loggedInUser, $loggedInUserRank);

$team_bh_sep  = getBhTeamCount($conn, '36', $loggedInUser, $loggedInUserRank);

$team_bh_nri  = getBhTeamCount($conn, '37', $loggedInUser, $loggedInUserRank);

$team_bh_edu  = getBhTeamCount($conn, '38', $loggedInUser, $loggedInUserRank);



// bh count ends 



// query 

$query = "

    SELECT u.id, u.username, d.designation_name 

    FROM tbl_user u

    JOIN tbl_designation d ON u.designation_id = d.id

    WHERE u.username = '$loggedInUser'

";

$result = $conn->query($query);

$user = $result->fetch_assoc();



$teamLinks = [];



if ($user) {

    switch ($user['designation_name']) {

        case 'Managing Director':

            $teamLinks = [

                ['title' => 'Team SAL Appt', 'url' => '../appointment_md/sal', 'team_title' =>'Total SAL Appt', 'count' => $team_md_sal],

                ['title' => 'Team SENP Appt', 'url' => '../appointment_md/senp', 'team_title' =>'Total SENP Appt', 'count' => $team_md_senp],

                ['title' => 'Team SEP Appt', 'url' => '../appointment_md/sep', 'team_title' =>'Total SEP Appt', 'count' => $team_md_sep],

                ['title' => 'Team NRI Appt', 'url' => '../appointment_md/nri', 'team_title' =>'Total NRI Appt', 'count' => $team_md_nri],

                ['title' => 'Team Educational Appt', 'url' => '../appointment_md/edu', 'team_title' =>'Total Educational Appt', 'count' => $team_md_edu],

            ];

            break;

        case 'Director':

            $teamLinks = [

                ['title' => 'Team SAL Appt', 'url' => '../appointment_dir/sal', 'team_title' =>'Total SAL Appt', 'count' => $team_dir_sal],

                ['title' => 'Team SENP Appt', 'url' => '../appointment_dir/senp', 'team_title' =>'Total SENP Appt', 'count' => $team_dir_senp],

                ['title' => 'Team SEP Appt', 'url' => '../appointment_dir/sep', 'team_title' =>'Total SEP Appt', 'count' => $team_dir_sep],

                ['title' => 'Team NRI Appt', 'url' => '../appointment_dir/nri', 'team_title' =>'Total NRI Appt', 'count' => $team_dir_nri],

                ['title' => 'Team Educational Appt', 'url' => '../appointment_dir/edu', 'team_title' =>'Total Educational Appt', 'count' => $team_dir_edu],

            ];

            break;

        case 'Regional Business Head':

            $teamLinks = [

                ['title' => 'Team SAL Appt', 'url' => '../appointment_rbh/sal', 'team_title' =>'Total SAL Appt', 'count' => $team_rbh_sal],

                ['title' => 'Team SENP Appt', 'url' => '../appointment_rbh/senp', 'team_title' =>'Total SENP Appt', 'count' => $team_rbh_senp],

                ['title' => 'Team SEP Appt', 'url' => '../appointment_rbh/sep', 'team_title' =>'Total SEP Appt', 'count' => $team_rbh_sep],

                ['title' => 'Team NRI Appt', 'url' => '../appointment_rbh/nri', 'team_title' =>'Total NRI Appt', 'count' => $team_rbh_nri],

                ['title' => 'Team Educational Appt', 'url' => '../appointment_rbh/edu', 'team_title' =>'Total Educational Appt', 'count' => $team_rbh_edu],

            ];

            break;

        case 'Business Head':

            $teamLinks = [

                ['title' => 'Team SAL Appt', 'url' => '../appointment_bh/sal', 'team_title' =>'Total SAL Appt', 'count' => $team_bh_sal],

                ['title' => 'Team SENP Appt', 'url' => '../appointment_bh/senp', 'team_title' =>'Total SENP Appt', 'count' => $team_bh_senp],

                ['title' => 'Team SEP Appt', 'url' => '../appointment_bh/sep', 'team_title' =>'Total SEP Appt', 'count' => $team_bh_sep],

                ['title' => 'Team NRI Appt', 'url' => '../appointment_bh/nri', 'team_title' =>'Total NRI Appt', 'count' => $team_bh_nri],

                ['title' => 'Team Educational Appt', 'url' => '../appointment_bh/edu', 'team_title' =>'Total Educational Appt', 'count' => $team_bh_edu],

            ];

            break;

    }

}







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



                                    <!-- my database list -->

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

                                                <!-- <a href="../database/add"> -->

                                                    <a href="add">

                                                    <center>

                                                        <h5 class="card-title  mb-2">Add Appointment</h5>

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

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>



                                                <a href="sal_list">

                                                    <center>

                                                        <h5 class="card-title  mb-2">My SAL Appt</h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1 ">Total SAL Appt</p>

                                                </center>

                                                <a href="sal_list">

                                                    <center>

                                                        <h5 class="card-title  mb-2"><?= $total_sal ?></h5>

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

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>

                                                <a href="senp_list">

                                                    <center>

                                                        <h5 class="card-title  mb-2">My SENP Appt</h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1 ">Total SENP Appt</p>

                                                </center>

                                                <a href="senp_list">

                                                    <center>

                                                        <h5 class="card-title  mb-2"><?= $total_senp ?></h5>

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

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>

                                                <a href="sep_list">

                                                    <center>

                                                        <h5 class="card-title  mb-2">My SEP Appt</h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1 ">Total SEP Appt</p>

                                                </center>

                                                <a href="sep_list">

                                                    <center>

                                                        <h5 class="card-title  mb-2"><?= $total_sep ?></h5>

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

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>

                                                <a href="nri_list">

                                                    <center>

                                                        <h5 class="card-title  mb-2">My NRI Appt</h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1 ">Total NRI Appt</p>

                                                </center>

                                                <a href="nri_list">

                                                    <center>

                                                        <h5 class="card-title  mb-2"><?= $total_nri ?></h5>

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

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>

                                                <a href="edu_list">

                                                    <center>

                                                        <h5 class="card-title  mb-2">My Educational Appt</h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1 ">Total Educational Appt</p>

                                                </center>

                                                <a href="edu_list">

                                                    <center>

                                                        <h5 class="card-title  mb-2"><?= $total_edu ?></h5>

                                                    </center>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- my database list -->



                                    <!-- Team Links Card (Shown if valid teamLink exists) -->

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

                                                <a href="../appointment_team/sal_links">

                                                    <center>

                                                        <h5 class="card-title mb-3">Team SAL Appt</h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1 ">Total SAL Appt</p>

                                                </center>

                                                <a href="../appointment_team/sal_links">

                                                    <center>

                                                        <h5 class="card-title  mb-2"><?= $total_sal ?></h5>

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

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>

                                                <a href="../appointment_team/senp_links">

                                                    <center>

                                                        <h5 class="card-title mb-3">Team SENP Appt</h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1 ">Total SENP Appt</p>

                                                </center>

                                                <a href="../appointment_team/senp_links">

                                                    <center>

                                                        <h5 class="card-title  mb-2"><?= $total_senp ?></h5>

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

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>

                                                <a href="../appointment_team/sep_links">

                                                    <center>

                                                        <h5 class="card-title mb-3">Team SEP Appt</h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1 ">Total SEP Appt</p>

                                                </center>

                                                <a href="../appointment_team/sep_links">

                                                    <center>

                                                        <h5 class="card-title  mb-2"><?= $total_sep ?></h5>

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

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>

                                                <a href="../appointment_team/nri_links">

                                                    <center>

                                                        <h5 class="card-title mb-3">Team NRI Appt</h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1 ">Total NRI Appt</p>

                                                </center>

                                                <a href="../appointment_team/nri_links">

                                                    <center>

                                                        <h5 class="card-title  mb-2"><?= $total_nri ?></h5>

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

                                                    <div class="avatar flex-shrink-0">

                                                        <img src="../assets/img/icons/unicons/chart-success.png"

                                                            alt="chart success" class="rounded">

                                                    </div>

                                                </div>

                                                <a href="../appointment_team/edu_links">

                                                    <center>

                                                        <h5 class="card-title mb-3">Team Educational Appt</h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1 ">Total Educational Appt</p>

                                                </center>

                                                <a href="../appointment_team/edu_links">

                                                    <center>

                                                        <h5 class="card-title  mb-2"><?= $total_edu ?></h5>

                                                    </center>

                                                </a>

                                            </div>

                                        </div>

                                    </div>



                                    <!-- database team ends -->

                                    <?php

                                    } else if ($teamLinks != ''){

                                    ?>



                                    <?php foreach ($teamLinks as $link): ?>

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

                                                <a href="<?= $link['url']; ?>">

                                                    <center>

                                                        <h5 class="card-title mb-3"><?= $link['title']; ?></h5>

                                                    </center>

                                                </a>

                                                <center>

                                                    <p class="mb-1"><?= $link['team_title']; ?></p>

                                                </center>

                                                <a href="<?= $link['url']; ?>">

                                                    <center>

                                                        <h5 class="card-title mb-2">

                                                            <?= $link['count'] !== '' ? $link['count'] : '-' ?></h5>

                                                    </center>

                                                </a>

                                            </div>

                                        </div>

                                    </div> 

                                    <?php endforeach; ?>





                                    <?php

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