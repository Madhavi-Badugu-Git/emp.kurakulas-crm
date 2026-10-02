<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');


$loggedInUser = $_SESSION['loggedInUser'];
$loggedInUserRank = $_SESSION['loggedInUserRank'];

// Pagination settings
$recordsPerPage = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;
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
                                    <h5 class="card-header">Team MD NRI Appointment</h5>

                                    <!-- Filter Form -->
                                    <form method="GET" class="p-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php
                                            $allowedDesignations = ["Director", "Manager", "Business Head", "Regional Business Head"];
                                            $inClause = "'" . implode("','", $allowedDesignations) . "'";
                                            $query = "
                                                SELECT u.id, u.username, CONCAT(u.firstName, ' ', u.lastName) AS name, d.designation_name 
                                                FROM tbl_user u 
                                                JOIN tbl_designation d ON u.designation_id = d.id 
                                                WHERE d.designation_name IN ($inClause)  AND u.status=1 ORDER BY name ASC
                                            ";
                                            $result = $conn->query($query);
                                            ?>
                                                <select name="user_id" class="form-select">
                                                    <option value="">Select User</option>
                                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                                    <option value="<?= $row['id']; ?>"
                                                        <?= (isset($_GET['user_id']) && $_GET['user_id'] == $row['id']) ? 'selected' : ''; ?>>
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
                                    $whereCondition = "WHERE a.customer_type = '37'";
                                    if (!empty($_GET['user_id'])) {
                                        $selectedUserId = intval($_GET['user_id']);
                                        
                                        // Get selected user ID and username
                                        $userQuery = "SELECT id, username FROM tbl_user WHERE id = $selectedUserId";
                                        $selectedUserResult = $conn->query($userQuery);

                                        if ($selectedUserResult && $selectedUserResult->num_rows > 0) {
                                            $selectedUser = $selectedUserResult->fetch_assoc();
                                            $userId = $selectedUser['id'];
                                            $username = $conn->real_escape_string($selectedUser['username']);
                                            
                                            // Filter based on selected user
                                            $whereCondition .= " AND (a.createdBy = '$userId' OR a.createdBy = '$username' OR i.appt_mobedToBh ='$username')";
                                        } else {
                                            echo '<div class="p-3">Invalid user selected.</div>';
                                        }
                                    }

                                    // Main data query
                                    $query = "
                                        SELECT 
                                            a.id, a.mobile_number, a.lead_name, a.email_id, a.company_name, a.createdBy, a.customer_type, a.created_at, 
                                            i.id AS info_id, i.unique_id, i.appt_moved_by, i.appt_movedToRbh, i.appt_mobedToBh, 
                                            i.appt_through, i.status, i.created_at AS info_created_at, i.updated_at AS info_updated_at,
                                            CONCAT(u.firstName, ' ', u.lastName) AS fullName
                                        FROM tbl_appointment a
                                        LEFT JOIN tbl_appointment_info i ON a.id = i.appointment_id
                                        LEFT JOIN tbl_user u ON a.createdBy = u.username OR a.createdBy = u.id
                                        $whereCondition
                                        ORDER BY a.id DESC
                                        LIMIT $offset, $recordsPerPage
                                    ";
                                    $countQuery = "
                                            SELECT COUNT(*) AS total 
                                            FROM tbl_appointment a
                                            JOIN tbl_user u ON a.createdBy = u.username
                                            WHERE u.reportingTo = '$loggedInUserId' AND a.customer_type = '37'
                                    ";

                                    $dataResult = $conn->query($query);
                                    $countResult = $conn->query($countQuery);
                                    $totalRecords = $countResult->fetch_assoc()['total'];
                                    $totalPages = ceil($totalRecords / $recordsPerPage);

                                    if ($dataResult->num_rows > 0) {
                                        echo '<div class="table-responsive p-3">';
                                        echo '<table class="table table-bordered">';
                                        echo '<thead class="table-dark">
                                                <tr>
                                                    <th>Lead Name</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <th>Company</th>
                                                    <th>Created By</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>';
                                        echo '<tbody>';
                                        while ($row = $dataResult->fetch_assoc()) {
                                            // Handle empty fullName case
                                            $fullName = !empty($row['fullName']) ? $row['fullName'] : "Unknown User";
                                            echo '<tr>
                                                    <td>' . htmlspecialchars($row['lead_name']) . '</td>
                                                    <td>' . htmlspecialchars($row['mobile_number']) . '</td>
                                                    <td>' . htmlspecialchars($row['email_id']) . '</td>
                                                    <td>' . htmlspecialchars($row['company_name']) . '</td>
                                                    <td>' . htmlspecialchars($fullName) . '</td>
                                                    <td>
                                                        <a class="dropdown-item text-primary p-0 mt-1 d-inline-block" href="../appointment/view?id=' . $row['id'] . '">
                                                            <i class="bx bx-user"></i> View
                                                        </a>
                                                    </td>

                                                    </tr>';
                                        }
                                        echo '</tbody></table></div>';
                                    } else {
                                        echo '<div class="p-3">No Appointment records found for this user.</div>';
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