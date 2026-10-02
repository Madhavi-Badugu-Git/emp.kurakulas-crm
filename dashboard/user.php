<?php 
session_start(); 
include('../includes/dbConfig.php');
include('../includes/validation.php');

if ($loggedInUser == 'superAdmin') {
    header("Location: superAdmin");
} else if ($loggedInUser == 'Admin') {
    header("Location: admin");
}

$loggedInUsername = $_SESSION['loggedInUser'];
$loggedInUserRank = $_SESSION['loggedInUserRank'];
$loggedInUserDepartment = $_SESSION['department_id'];
$loggedInUserDesignation = $_SESSION['designation_id'];

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


// database starts
// MD Count starts
function getMdTeamCount($conn, $customerType, $loggedInUser, $loggedInUserRank) {
    $sql_md = "SELECT COUNT(id) AS total_count FROM tbl_database WHERE customer_type='$customerType' AND status = 1 AND createdBy != '$loggedInUser'";

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
    $sql_dir = "SELECT COUNT(id) AS total_count FROM tbl_database WHERE customer_type='$customerType' AND status = 1 AND createdBy NOT IN ('$loggedInUser', '10000')";

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
        FROM tbl_database 
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
                FROM tbl_database 
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
                ['title' => 'Team SAL Data', 'url' => '../database_md/sal', 'team_title' =>'Total SAL Data', 'count' => $team_md_sal],
                ['title' => 'Team SENP Data', 'url' => '../database_md/senp', 'team_title' =>'Total SENP Data', 'count' => $team_md_senp],
                ['title' => 'Team SEP Data', 'url' => '../database_md/sep', 'team_title' =>'Total SEP Data', 'count' => $team_md_sep],
                ['title' => 'Team NRI Data', 'url' => '../database_md/nri', 'team_title' =>'Total NRI Data', 'count' => $team_md_nri],
                ['title' => 'Team Educational Data', 'url' => '../database_md/edu', 'team_title' =>'Total Educational Data', 'count' => $team_md_edu],
            ];
            break;
        case 'Director':
            $teamLinks = [
                ['title' => 'Team SAL Data', 'url' => '../database_dir/sal', 'team_title' =>'Total SAL Data', 'count' => $team_dir_sal],
                ['title' => 'Team SENP Data', 'url' => '../database_dir/senp', 'team_title' =>'Total SENP Data', 'count' => $team_dir_senp],
                ['title' => 'Team SEP Data', 'url' => '../database_dir/sep', 'team_title' =>'Total SEP Data', 'count' => $team_dir_sep],
                ['title' => 'Team NRI Data', 'url' => '../database_dir/nri', 'team_title' =>'Total NRI Data', 'count' => $team_dir_nri],
                ['title' => 'Team Educational Data', 'url' => '../database_dir/edu', 'team_title' =>'Total Educational Data', 'count' => $team_dir_edu],
            ];
            break;
        case 'Regional Business Head':
            $teamLinks = [
                ['title' => 'Team SAL Data', 'url' => '../database_rbh/sal', 'team_title' =>'Total SAL Data', 'count' => $team_rbh_sal],
                ['title' => 'Team SENP Data', 'url' => '../database_rbh/senp', 'team_title' =>'Total SENP Data', 'count' => $team_rbh_senp],
                ['title' => 'Team SEP Data', 'url' => '../database_rbh/sep', 'team_title' =>'Total SEP Data', 'count' => $team_rbh_sep],
                ['title' => 'Team NRI Data', 'url' => '../database_rbh/nri', 'team_title' =>'Total NRI Data', 'count' => $team_rbh_nri],
                ['title' => 'Team Educational Data', 'url' => '../database_rbh/edu', 'team_title' =>'Total Educational Data', 'count' => $team_rbh_edu],
            ];
            break;
        case 'Business Head':
            $teamLinks = [
                ['title' => 'Team SAL Data', 'url' => '../database_bh/sal', 'team_title' =>'Total SAL Data', 'count' => $team_bh_sal],
                ['title' => 'Team SENP Data', 'url' => '../database_bh/senp', 'team_title' =>'Total SENP Data', 'count' => $team_bh_senp ],
                ['title' => 'Team SEP Data', 'url' => '../database_bh/sep', 'team_title' =>'Total SEP Data', 'count' => $team_bh_sep],
                ['title' => 'Team NRI Data', 'url' => '../database_bh/nri', 'team_title' =>'Total NRI Data', 'count' => $team_bh_nri],
                ['title' => 'Team Educational Data', 'url' => '../database_bh/edu', 'team_title' =>'Total Educational Data', 'count' => $team_bh_edu],
            ];
            break;
    }
}
// database ends

// appointment starts
// MD Count starts
function getMdTeamCountAppt($conn, $customerType, $loggedInUser, $loggedInUserRank) {
    $sql_md = "SELECT COUNT(id) AS total_count FROM tbl_appointment WHERE customer_type='$customerType' AND status = 1 AND createdBy != '$loggedInUser'";

    $result_md = mysqli_query($conn, $sql_md);
    if ($result_md) {
        $row = mysqli_fetch_assoc($result_md);
        return $row['total_count'];
    }
    return 0;
}
$team_md_sal_1  = getMdTeamCountAppt($conn, '39', $loggedInUser, $loggedInUserRank);
$team_md_senp_1  = getMdTeamCountAppt($conn, '35', $loggedInUser, $loggedInUserRank);
$team_md_sep_1  = getMdTeamCountAppt($conn, '36', $loggedInUser, $loggedInUserRank);
$team_md_nri_1  = getMdTeamCountAppt($conn, '37', $loggedInUser, $loggedInUserRank);
$team_md_edu_1  = getMdTeamCountAppt($conn, '38', $loggedInUser, $loggedInUserRank);
// MD Count ends

// director count starts
function getDirTeamCountAppt($conn, $customerType, $loggedInUser, $loggedInUserRank) {
    $sql_dir = "SELECT COUNT(id) AS total_count FROM tbl_appointment WHERE customer_type='$customerType' AND status = 1 AND createdBy NOT IN ('$loggedInUser', '10000')";

    $result_dir = mysqli_query($conn, $sql_dir);
    if ($result_dir) {
        $row = mysqli_fetch_assoc($result_dir);
        return $row['total_count'];
    }
    return 0;
}

$team_dir_sal_1  = getDirTeamCountAppt($conn, '39', $loggedInUser, $loggedInUserRank);
$team_dir_senp_1  = getDirTeamCountAppt($conn, '35', $loggedInUser, $loggedInUserRank);
$team_dir_sep_1  = getDirTeamCountAppt($conn, '36', $loggedInUser, $loggedInUserRank);
$team_dir_nri_1  = getDirTeamCountAppt($conn, '37', $loggedInUser, $loggedInUserRank);
$team_dir_edu_1  = getDirTeamCountAppt($conn, '38', $loggedInUser, $loggedInUserRank);

// director Count ends

// rbh count starts
function getRbhTeamCountAppt($conn, $customerType, $loggedInUser, $loggedInUserRank) {
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

$team_rbh_sal_1  = getRbhTeamCountAppt($conn, '39', $loggedInUser, $loggedInUserRank);
$team_rbh_senp_1 = getRbhTeamCountAppt($conn, '35', $loggedInUser, $loggedInUserRank);
$team_rbh_sep_1  = getRbhTeamCountAppt($conn, '36', $loggedInUser, $loggedInUserRank);
$team_rbh_nri_1  = getRbhTeamCountAppt($conn, '37', $loggedInUser, $loggedInUserRank);
$team_rbh_edu_1  = getRbhTeamCountAppt($conn, '38', $loggedInUser, $loggedInUserRank);
// rbh count starts

// bh count starts
function getBhTeamCountAppt($conn, $customerType, $loggedInUser, $loggedInUserRank) {
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

$team_bh_sal_1  = getBhTeamCountAppt($conn, '39', $loggedInUser, $loggedInUserRank);
$team_bh_senp_1 = getBhTeamCountAppt($conn, '35', $loggedInUser, $loggedInUserRank);
$team_bh_sep_1  = getBhTeamCountAppt($conn, '36', $loggedInUser, $loggedInUserRank);
$team_bh_nri_1  = getBhTeamCountAppt($conn, '37', $loggedInUser, $loggedInUserRank);
$team_bh_edu_1  = getBhTeamCountAppt($conn, '38', $loggedInUser, $loggedInUserRank);

// bh count ends 


$query = "
    SELECT u.id, u.username, d.designation_name 
    FROM tbl_user u
    JOIN tbl_designation d ON u.designation_id = d.id
    WHERE u.username = '$loggedInUser'
";
$result = $conn->query($query);
$user = $result->fetch_assoc();

$teamLinks_1 = [];

if ($user) {
    switch ($user['designation_name']) {
        case 'Managing Director':
            $teamLinks_1 = [
                ['title' => 'Team SAL Appt', 'url' => '../appointment_md/sal', 'team_title' =>'Total SAL Appt', 'count' => $team_md_sal_1],
                ['title' => 'Team SENP Appt', 'url' => '../appointment_md/senp', 'team_title' =>'Total SENP Appt', 'count' => $team_md_senp_1],
                ['title' => 'Team SEP Appt', 'url' => '../appointment_md/sep', 'team_title' =>'Total SEP Appt', 'count' => $team_md_sep_1],
                ['title' => 'Team NRI Appt', 'url' => '../appointment_md/nri', 'team_title' =>'Total NRI Appt', 'count' => $team_md_nri_1],
                ['title' => 'Team Educational Appt', 'url' => '../appointment_md/edu', 'team_title' =>'Total Educational Appt', 'count' => $team_md_edu_1],
            ];
            break;
        case 'Director':
            $teamLinks_1 = [
                ['title' => 'Team SAL Appt', 'url' => '../appointment_dir/sal', 'team_title' =>'Total SAL Appt', 'count' => $team_dir_sal_1],
                ['title' => 'Team SENP Appt', 'url' => '../appointment_dir/senp', 'team_title' =>'Total SENP Appt', 'count' => $team_dir_senp_1],
                ['title' => 'Team SEP Appt', 'url' => '../appointment_dir/sep', 'team_title' =>'Total SEP Appt', 'count' => $team_dir_sep_1],
                ['title' => 'Team NRI Appt', 'url' => '../appointment_dir/nri', 'team_title' =>'Total NRI Appt', 'count' => $team_dir_nri_1],
                ['title' => 'Team Educational Appt', 'url' => '../appointment_dir/edu', 'team_title' =>'Total Educational Appt', 'count' => $team_dir_edu_1],
            ];
            break;
        case 'Regional Business Head':
            $teamLinks_1 = [
                ['title' => 'Team SAL Appt', 'url' => '../appointment_rbh/sal', 'team_title' =>'Total SAL Appt', 'count' => $team_rbh_sal_1],
                ['title' => 'Team SENP Appt', 'url' => '../appointment_rbh/senp', 'team_title' =>'Total SENP Appt', 'count' => $team_rbh_senp_1],
                ['title' => 'Team SEP Appt', 'url' => '../appointment_rbh/sep', 'team_title' =>'Total SEP Appt', 'count' => $team_rbh_sep_1],
                ['title' => 'Team NRI Appt', 'url' => '../appointment_rbh/nri', 'team_title' =>'Total NRI Appt', 'count' => $team_rbh_nri_1],
                ['title' => 'Team Educational Appt', 'url' => '../appointment_rbh/edu', 'team_title' =>'Appt Educational Appt', 'count' => $team_rbh_edu_1],
            ];
            break;
        case 'Business Head':
            $teamLinks_1 = [
                ['title' => 'Team SAL Appt', 'url' => '../appointment_bh/sal', 'team_title' =>'Total SAL Appt', 'count' => $team_bh_sal_1],
                ['title' => 'Team SENP Appt', 'url' => '../appointment_bh/senp', 'team_title' =>'Total SENP Appt', 'count' => $team_bh_senp_1 ],
                ['title' => 'Team SEP Appt', 'url' => '../appointment_bh/sep', 'team_title' =>'Total SEP Appt', 'count' => $team_bh_sep_1],
                ['title' => 'Team NRI Appt', 'url' => '../appointment_bh/nri', 'team_title' =>'Total NRI Appt', 'count' => $team_bh_nri_1],
                ['title' => 'Team Educational Appt', 'url' => '../appointment_bh/edu', 'team_title' =>'Total Educational Appt', 'count' => $team_bh_edu_1],
            ];
            break;
    }
}
// appointment ends

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include('../includes/sideMenu.php'); ?>
            <div class="layout-page">
                <?php include('../includes/navbar.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-xxl-8 mb-6 order-0">
                                <div class="card">
                                    <div class="d-flex align-items-start row">
                                        <div class="col-sm-7">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary mb-3">Welcome
                                                    <?= $loggedInUserFirstName; ?>! 🎉</h5>
                                                <p class="mb-6">Access your dashboard to manage your profile, track
                                                    activities, and stay updated all in one place.</p>
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
                            </div>
                            <div class="col-lg-12 col-md-12 order-1">
                                <div class="row">
                                    <!-- my portfolio -->
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
                                                <center>
                                                    <h5 class="mb-1">Total My Portfolio</h5>
                                                </center>
                                                <?php 
                                                $sql_count = "SELECT COUNT(id) AS total_count FROM tbl_portfolio WHERE createdBy = '$loggedInUser'";
                                                $result_count = mysqli_query($conn, $sql_count);
                                                $total_portfolio = ($result_count) ? mysqli_fetch_assoc($result_count)['total_count'] : 0;
                                                ?>
                                                <a href="../portfolio/list">
                                                    <center>
                                                        <h4 class="card-title mb-0"><?= $total_portfolio; ?></h4>
                                                    </center>
                                                </a>

                                                <?php 
                                                $sql_amount = "SELECT SUM(loan_amount) AS total_loan_amount FROM tbl_portfolio_loan_details WHERE portfolio_id IN (SELECT id FROM tbl_portfolio WHERE createdBy = '$loggedInUser')";
                                                $result_amount = mysqli_query($conn, $sql_amount);
                                                $total_loan_amount = ($result_amount) ? mysqli_fetch_assoc($result_amount)['total_loan_amount'] : 0;
                                                ?>
                                                <center>
                                                    <p class="mb-1">Total Amount : <br>
                                                        <?= number_format($total_loan_amount, 2); ?> &#8377;</p>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- my portfolio -->

                                    <!-- team portfolio -->
                                    <?php 
                                        // Step 1: Get logged in user's ID and designation
                                        $userQuery = mysqli_query($conn, "SELECT id, username, designation_id FROM tbl_user WHERE username = '$loggedInUsername'");
                                        $userData = mysqli_fetch_assoc($userQuery);

                                        $loggedInUserId = $userData['id'];

                                        // Step 2: Check designation name
                                        $designationQuery = mysqli_query($conn, "SELECT designation_name FROM tbl_designation WHERE id = '{$userData['designation_id']}'");
                                        $designationData = mysqli_fetch_assoc($designationQuery);

                                        $designationName = $designationData['designation_name'];

                                        // Step 3: Check if user has allowed designation
                                        $allowedDesignations = ['Business Head', 'Regional Business Head', 'Managing Director', 'Director'];

                                        if (in_array($designationName, $allowedDesignations)) {
                                            // Show the card only for allowed designations
                                        ?>
                                        <div class="col-lg-3 col-md-3 col-6 mb-12">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="card-title d-flex align-items-center justify-content-center mb-4">
                                                        <div class="avatar flex-shrink-0">
                                                            <img src="../assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded">
                                                        </div>
                                                    </div>
                                                    <center>
                                                        <h5 class="mb-1">Total Team Portfolio</h5>
                                                    </center>

                                                    <?php 
                                                    $usernames = [];

                                                    if (in_array($designationName, ['Business Head', 'Regional Business Head'])) {
                                                        // Get users reporting to this user
                                                        $teamQuery = mysqli_query($conn, "SELECT username FROM tbl_user WHERE reportingTo = '$loggedInUserId'");
                                                        while ($teamUser = mysqli_fetch_assoc($teamQuery)) {
                                                            $usernames[] = "'" . mysqli_real_escape_string($conn, $teamUser['username']) . "'";
                                                        }
                                                    } else {
                                                        // Managing Director, Director → only their own data
                                                        $usernames[] = "'" . mysqli_real_escape_string($conn, $loggedInUsername) . "'";
                                                    }

                                                    if (!empty($usernames)) {
                                                        $usernames_in = implode(",", $usernames);

                                                        // Total portfolio count
                                                        $sql_count = "SELECT COUNT(id) AS total_count FROM tbl_portfolio WHERE createdBy IN ($usernames_in)";
                                                        $result_count = mysqli_query($conn, $sql_count);
                                                        $total_portfolio = ($result_count) ? mysqli_fetch_assoc($result_count)['total_count'] : 0;

                                                        // Total loan amount
                                                        $sql_amount = "SELECT SUM(ld.loan_amount) AS total_loan_amount 
                                                                    FROM tbl_portfolio_loan_details ld 
                                                                    JOIN tbl_portfolio p ON ld.portfolio_id = p.id 
                                                                    WHERE p.createdBy IN ($usernames_in)";
                                                        $result_amount = mysqli_query($conn, $sql_amount);
                                                        $total_loan_amount = ($result_amount) ? mysqli_fetch_assoc($result_amount)['total_loan_amount'] : 0;
                                                    } else {
                                                        $total_portfolio = 0;
                                                        $total_loan_amount = 0;
                                                    }

                                                    // Step 4: Decide portfolio link based on designation
                                                    if ($designationName == 'Business Head') {
                                                        $portfolio_link = "../portfolio/bh_team";
                                                    } elseif ($designationName == 'Regional Business Head') {
                                                        $portfolio_link = "../portfolio/rbh_team";
                                                    } elseif (in_array($designationName, ['Managing Director'])) {
                                                        $portfolio_link = "../portfolio/md_team";
                                                    } else {
                                                        $portfolio_link = "../portfolio/list"; // fallback (should not happen)
                                                    }
                                                    ?>

                                                    <a href="<?= $portfolio_link; ?>">
                                                        <center>
                                                            <h4 class="card-title mb-0"><?= $total_portfolio; ?></h4>
                                                        </center>
                                                    </a>

                                                    <center>
                                                        <p class="mb-1">Total Amount :<br>
                                                            <?= number_format($total_loan_amount, 2); ?> &#8377;
                                                        </p>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                        } // end if allowed designation
                                    ?>
                                    <!-- team portfolio -->

                                    <!-- database team starts -->
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
                                                <!-- <center>
                                                    <p class="mb-1"><?= $link['team_title']; ?></p>
                                                </center> -->
                                                <a href="<?= $link['url']; ?>">
                                                    <center>
                                                        <h4 class="card-title mb-2">
                                                            <?= $link['count'] !== '' ? $link['count'] : '-' ?></h4>
                                                    </center>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <!-- database team ends -->

                                     <!-- appointment team starts -->
                                    <?php foreach ($teamLinks_1 as $link): ?>
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
                                                <!-- <center>
                                                    <p class="mb-1"><?= $link['team_title']; ?></p>
                                                </center> -->
                                                <a href="<?= $link['url']; ?>">
                                                    <center>
                                                        <h4 class="card-title mb-2">
                                                            <?= $link['count'] !== '' ? $link['count'] : '-' ?></h4>
                                                    </center>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <!-- appointment team ends -->

                                    <?php if($loggedInUserDepartment != '16' && $loggedInUserDesignation != '21'){
                                        ?>
                                       
                                    <!-- partner -->
                                    <div class="col-lg-3 col-md-3 col-6 mb-12">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div
                                                    class="card-title d-flex  align-items-center justify-content-center mb-4">
                                                    <div class="avatar flex-shrink-0">
                                                        <img src="../assets/img/icons/unicons/chart-success.png"
                                                            alt="chart success" class="rounded">
                                                    </div>
                                                </div>
                                                <center><h5 class="mb-1 ">Total Partners</h5></center>
                                                <?php 
                                                if($loggedInUserDesignation == '13'){
                                                    $sql_count = "SELECT COUNT(id) AS total_count FROM tbl_partner ";
                                                } else{
                                                    $sql_count = "SELECT COUNT(id) AS total_count FROM tbl_partner WHERE createdBy='$loggedInUser'";
                                                }
                                               
                                                $result_count = mysqli_query($conn, $sql_count);

                                                if ($result_count) {
                                                    $row_count = mysqli_fetch_assoc($result_count);
                                                    $total_partner = $row_count['total_count']; // Correct key
                                                } else {
                                                    $total_partner = 0; // Default if query fails
                                                }
                                                ?>
                                                  <a href="../partner/list">
                                                <center><h4 class="card-title mb-0"><?= $total_partner; ?></h4></center>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- connectors -->
                                    <div class="col-lg-3 col-md-3 col-6 mb-12">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div
                                                    class="card-title d-flex  align-items-center justify-content-center mb-4">
                                                    <div class="avatar flex-shrink-0">
                                                        <img src="../assets/img/icons/unicons/chart-success.png"
                                                            alt="chart success" class="rounded">
                                                    </div>
                                                </div>
                                                <center><h5 class="mb-1 ">Total Connectors</h5></center>
                                                <?php 
                                                $sql_count = "SELECT COUNT(id) AS total_count FROM tbl_connectors WHERE createdBy='$loggedInUser'";
                                                $result_count = mysqli_query($conn, $sql_count);

                                                if ($result_count) {
                                                    $row_count = mysqli_fetch_assoc($result_count);
                                                    $total_connectors = $row_count['total_count']; // Correct key
                                                } else {
                                                    $total_connectors = 0; // Default if query fails
                                                }
                                                ?>
                                                <a href="../connectors/list">
                                                <center><h4 class="card-title mb-0"><?= $total_connectors; ?></h4></center>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- agent -->
                                    <div class="col-lg-3 col-md-3 col-6 mb-12">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div
                                                    class="card-title d-flex  align-items-center justify-content-center mb-4">
                                                    <div class="avatar flex-shrink-0">
                                                        <img src="../assets/img/icons/unicons/chart-success.png"
                                                            alt="chart success" class="rounded">
                                                    </div>
                                                </div>
                                                <center><h5 class="mb-1 ">Total Agents</h5></center>
                                                <?php 
                                                $sql_count = "SELECT COUNT(id) AS total_count FROM tbl_agent_data WHERE createdBy='$loggedInUser'";
                                                $result_count = mysqli_query($conn, $sql_count);

                                                if ($result_count) {
                                                    $row_count = mysqli_fetch_assoc($result_count);
                                                    $total_agent = $row_count['total_count']; // Correct key
                                                } else {
                                                    $total_agent = 0; // Default if query fails
                                                }
                                                ?>
                                                <a href="../agent-data/list">
                                                <center><h4 class="card-title mb-0"><?= $total_agent; ?></h4></center>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include('../includes/footer.php'); ?>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <?php include('../includes/script.php'); ?>
    <script>
    $(document).ready(function() {
        iziToast.success({
            title: "Welcome Back",
            message: "Welcome Back To Dashboard",
            position: "topRight"
        });
    });
    </script>
</body>

</html>