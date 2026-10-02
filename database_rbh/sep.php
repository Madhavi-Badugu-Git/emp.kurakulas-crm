<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

$loggedInUsername = $_SESSION['loggedInUser'];
$loggedInUserRank = $_SESSION['loggedInUserRank'];
$loggedInUserDesignation = $_SESSION['designation_id'];

// Get department ID
$deptResult = $conn->query("SELECT id, department_id FROM tbl_user WHERE username = '$loggedInUsername'");
$deptRow = $deptResult->fetch_assoc();
$loggedInUserId = $deptRow['id'];
$loggedInDeptId = $deptRow['department_id'];
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
                                    <h5 class="card-header">Team Database</h5>

                                    <!-- Filter Form -->
                                    <form method="GET" class="p-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php
                                                $query = "
                                                    SELECT u.id, CONCAT(u.firstName, ' ', u.lastName) AS name, d.designation_name
                                                    FROM tbl_user u
                                                    JOIN tbl_designation d ON u.designation_id = d.id
                                                    WHERE 
                                                        (
                                                            u.reportingTo = '$loggedInUserId' OR 
                                                            u.reportingTo IN (
                                                                SELECT id FROM tbl_user WHERE reportingTo = '$loggedInUserId'
                                                            )
                                                        )
                                                        AND d.designation_name IN ('Manager', 'Business Head')
                                                        AND u.department_id = '$loggedInDeptId'
                                                ";
                                                $result = $conn->query($query);
                                                ?>
                                                <select name="user_id" class="form-select" required>
                                                    <option value="">Select User</option>
                                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                                        <option value="<?= $row['id']; ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $row['id']) ? 'selected' : ''; ?>>
                                                            <?= $row['name']; ?> (<?= $row['designation_name']; ?>)
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary">Show Data</button>
                                                <a href="?" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>

                                    <?php
                                    if (!empty($_GET['user_id'])) {
                                        $selectedUserId = intval($_GET['user_id']);
                                        $userQuery = "SELECT id, username FROM tbl_user WHERE id = $selectedUserId";
                                        $userResult = $conn->query($userQuery);

                                        if ($userResult && $userResult->num_rows > 0) {
                                            $userRow = $userResult->fetch_assoc();
                                            $username = $conn->real_escape_string($userRow['username']);

                                            $query = "
                                                SELECT id, mobile_number, lead_name, email_id, company_name, alternative_mobile, state, location, customer_type, createdBy, created_at 
                                                FROM tbl_database 
                                                WHERE createdBy = '$username' AND customer_type = '36'
                                                ORDER BY created_at DESC
                                            ";
                                            $dataResult = $conn->query($query);

                                            if ($dataResult->num_rows > 0) {
                                                echo '<div class="table-responsive p-3">';
                                                echo '<table class="table table-bordered">';
                                                echo '<thead class="table-dark">
                                                        <tr>
                                                            <th>Lead Name</th>
                                                            <th>Mobile</th>
                                                            <th>Email</th>
                                                            <th>Company</th>
                                                           
                                                            <th>Customer Type</th>
                                                           
                                                            <th>Action</th>
                                                        </tr>
                                                      </thead>';
                                                echo '<tbody>';
                                                while ($row = $dataResult->fetch_assoc()) {
                                                    echo '<tr>
                                                        <td>' . htmlspecialchars($row['lead_name']) . '</td>
                                                        <td>' . htmlspecialchars($row['mobile_number']) . '</td>
                                                        <td>' . htmlspecialchars($row['email_id']) . '</td>
                                                        <td>' . htmlspecialchars($row['company_name']) . '</td>
                                                       
                                                        <td>' . getCustomerType($conn, $row['customer_type']) . '</td>
                                                        
                                                        <td>
                                                            <a class="dropdown-item text-primary p-0 mt-1 d-inline-block" href="../database/view?id=' . $row['id'] . '">
                                                                <i class="bx bx-user"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>';
                                                }
                                                echo '</tbody></table></div>';
                                            } else {
                                                echo '<div class="p-3">No database records found for this user.</div>';
                                            }
                                        } else {
                                            echo '<div class="p-3">Invalid user selected.</div>';
                                        }
                                    } else {
                                        echo '<div class="p-3">Please select a user to view their database entries.</div>';
                                    }
                                    ?>
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
