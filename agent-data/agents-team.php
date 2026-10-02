<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

$loggedInUser = $_SESSION['loggedInUser'];

// Check if user is superAdmin
$query = mysqli_query($conn, "SELECT rank FROM tbl_user WHERE username = '$loggedInUser'");
$userData = mysqli_fetch_assoc($query);
$loggedInUserRank = $userData['rank'];

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
                            <div class="col-xl">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5 class="card-header">Search Agent Name</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Filter Dropdown (Visible only for superAdmin) -->
                                            <?php if($loggedInUserRank == 'superAdmin'): ?>
                                                <form method="GET" class="p-3 mt-2">
                                                    <select name="filter_user" id="filter_user" class="form-control" onchange="this.form.submit()">
                                                        <option value="">Select a User</option>
                                                        <?php
                                                        $designation_ids = [1, 2, 4, 5, 7, 8, 10, 11, 13, 15];
                                                        $ids_string = implode(',', $designation_ids);
                                                        $userQuery = mysqli_query($conn, "SELECT username, firstName, lastName FROM tbl_user WHERE designation_id IN ($ids_string) AND status='1'");
                                                        while ($userRow = mysqli_fetch_assoc($userQuery)) {
                                                            $selected = ($_GET['filter_user'] ?? '') == $userRow['username'] ? 'selected' : '';
                                                            echo "<option value='{$userRow['username']}' $selected>{$userRow['firstName']} {$userRow['lastName']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Agent List Table -->
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Full Name</th>
                                                    <th>Company Name</th>
                                                    <th>Mobile</th>
                                                    <th>Connector Type</th>
                                                    <th>Branch State</th>
                                                    <th>Branch Location</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($loggedInUserRank == 'superAdmin' && isset($_GET['filter_user']) && !empty($_GET['filter_user'])) {
                                                    $selectedUser = $_GET['filter_user'];

                                                    // Fetch all users who report to the selected user
                                                    $reportingQuery = mysqli_query($conn, "SELECT username FROM tbl_user WHERE reportingTo = '$selectedUser'");
                                                    $reportingUsers = [$selectedUser]; // Include the selected user
                                                    while ($reportRow = mysqli_fetch_assoc($reportingQuery)) {
                                                        $reportingUsers[] = $reportRow['username'];
                                                    }
                                                    $reportingUsersString = "'" . implode("','", $reportingUsers) . "'";

                                                    // Fetch agents created by the selected user and their reporting users
                                                    $agentQuery = mysqli_query($conn, "SELECT * FROM tbl_agent_data WHERE createdBy IN ($reportingUsersString) AND status='1' ORDER BY full_name ASC");
                                                } else {
                                                    // Default query for non-superAdmin users
                                                    $agentQuery = mysqli_query($conn, "SELECT * FROM tbl_agent_data WHERE createdBy='$loggedInUser' AND status='1' ORDER BY full_name ASC");
                                                }

                                                if (mysqli_num_rows($agentQuery) > 0) {
                                                    while ($row = mysqli_fetch_assoc($agentQuery)) {
                                                ?>
                                                        <tr>
                                                            <td><?= $row['full_name']; ?></td>
                                                            <td><?= $row['company_name']; ?></td>
                                                            <td><?= $row['Phone_number']; ?></td>
                                                            <td><?= getPartnerType($conn, $row['partnerType']); ?></td>
                                                            <td><?= getBranchState($conn, $row['state']); ?></td>
                                                            <td><?= getBranchLocation($conn, $row['location']); ?></td>
                                                            <td>
                                                                <a href="view?id=<?= $row['id']; ?>" class="badge bg-primary">View</a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7' class='text-center'>No Agents Found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

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
</body>

</html>
