<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

$loggedInUsername = $_SESSION['loggedInUser'];
$loggedInUserRank = $_SESSION['loggedInUserRank'];
$loggedInUserDepartment = $_SESSION['department_id'];
$loggedInUserDesignation = $_SESSION['designation_id'];
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
                                <h5 class="card-header">Team Portfolio</h5>

                                <!-- Filter Form -->
                                <?php if (in_array($loggedInUserRank, ['User'])) { ?>
                                <form method="GET" class="p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            // Fetch logged-in user's ID
                                            $getIdQuery = "SELECT id FROM tbl_user WHERE username = '$loggedInUsername'";
                                            $getIdResult = $conn->query($getIdQuery);

                                            if ($getIdRow = $getIdResult->fetch_assoc()) {
                                                $loggedInUserId = $getIdRow['id'];

                                                // Specify allowed designations
                                                $allowedDesignations = ["Manager"];
                                                $inClause = "'" . implode("','", $allowedDesignations) . "'";

                                                // Fetch users under allowed designations and who report to logged-in user
                                                $query = "
                                                    SELECT u.id, u.username, CONCAT(u.firstName, ' ', u.lastName) AS display_name, d.designation_name
                                                    FROM tbl_user u
                                                    JOIN tbl_designation d ON u.designation_id = d.id
                                                    JOIN tbl_department dep ON u.department_id = dep.id
                                                    WHERE d.designation_name IN ($inClause)
                                                    AND u.reportingTo = '$loggedInUserId'
                                                    AND u.department_id = '$loggedInUserDepartment'
                                                    AND dep.status = 1
                                                ";

                                                $result = $conn->query($query);
                                            ?>
                                            <select name="user_id" class="form-select" required>
                                                <option value="">Select User</option>
                                                <?php while ($row = $result->fetch_assoc()) { ?>
                                                    <option value="<?= $row['id']; ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $row['id']) ? 'selected' : ''; ?>>
                                                        <?= $row['display_name']; ?> (<?= $row['designation_name']; ?>)
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            } else {
                                                echo "<div class='alert alert-danger'>Invalid Session. User not found.</div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            <a href="bh_team.php" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                                <?php } ?>

                                <?php
                                $showTable = false;
                                $where = "WHERE p.status = '1'";

                                if (!empty($_GET['user_id'])) {
                                    $selectedUserId = intval($_GET['user_id']);
                                    $showTable = true;

                                    // Get selected user's username
                                    $userQuery = $conn->query("SELECT username FROM tbl_user WHERE id = $selectedUserId");
                                    $usernames = [];

                                    if ($userRow = $userQuery->fetch_assoc()) {
                                        $selectedUsername = $conn->real_escape_string($userRow['username']);
                                        $usernames[] = "'$selectedUsername'";

                                        // Now fetch all users created by selected user
                                        $createdUsersQuery = $conn->query("SELECT username FROM tbl_user WHERE createdBy = '$selectedUsername'");

                                        while ($createdRow = $createdUsersQuery->fetch_assoc()) {
                                            $usernames[] = "'" . $conn->real_escape_string($createdRow['username']) . "'";
                                        }
                                    }

                                    if (!empty($usernames)) {
                                        $usernameIn = implode(',', $usernames);
                                        $where .= " AND p.createdBy IN ($usernameIn)";
                                    }
                                }
                                ?>

                                <?php if ($showTable) { ?>
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Customer Name</th>
                                                <th>Company Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>State</th>
                                                <th>Location</th>
                                                <!-- <th>Created By</th>
                                                <th>Created At</th> -->
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM tbl_portfolio p $where ORDER BY p.created_at DESC";
                                            $query = $conn->query($sql);

                                            if ($query->num_rows > 0) {
                                                while ($row = $query->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td><?= $row['customer_name']; ?></td>
                                                <td><?= $row['company_name']; ?></td>
                                                <td><?= $row['Phone_number']; ?></td>
                                                <td><?= $row['email_id']; ?></td>
                                                <td><?= getStateName($conn, $row['state']); ?></td>
                                                <td><?= getLocationName($conn, $row['location']); ?></td>
                                                <!-- <td><?= $row['createdBy']; ?></td>
                                                <td><?= (!empty($row['created_at']) && $row['created_at'] != '0000-00-00 00:00:00') ? date('d-m-Y h:i A', strtotime($row['created_at'])) : ''; ?></td> -->
                                                <td>
                                                    <a href="view?id=<?= $row['id']; ?>">
                                                        <span class="badge bg-primary">View</span>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="9" class="text-center">No data found</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php } else { ?>
                                <div class="text-center p-4">
                                    <em>Please select a user to view portfolio data.</em>
                                </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
                <?php include('../includes/footer.php'); ?>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/script.php'); ?>
</body>
</html>
