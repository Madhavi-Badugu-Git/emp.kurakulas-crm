<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');


$loggedInUser = $_SESSION['loggedInUser'];
$loggedInUserRank = $_SESSION['loggedInUserRank'];

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
                                    <h5 class="card-header">Partner Team </h5>

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
                                     // Build the main query
                                    $whereCondition = "WHERE p.status = '1'";
                                    if (!empty($_GET['user_id'])) {
                                        $selectedUserId = intval($_GET['user_id']);
                                        $userQuery = "SELECT id, username FROM tbl_user WHERE id = $selectedUserId";
                                        $selectedUserResult = $conn->query($userQuery);

                                         if ($selectedUserResult && $selectedUserResult->num_rows > 0) {
                                        $selectedUser = $selectedUserResult->fetch_assoc();
                                        $userId = $selectedUser['id'];
                                        $username = $conn->real_escape_string($selectedUser['username']);

                                       // Step 1: Get all users reporting directly or indirectly to the selected user
                                        function getReportingUsers($conn, $parentUserId, &$collectedUsers = []) {
                                            $query = "SELECT id, username FROM tbl_user WHERE reportingTo = $parentUserId AND status = 1";
                                            $result = $conn->query($query);

                                            while ($row = $result->fetch_assoc()) {
                                                $collectedUsers[] = [
                                                    'id' => $row['id'],
                                                    'username' => $row['username']
                                                ];
                                                // Recursive call for indirect reports
                                                getReportingUsers($conn, $row['id'], $collectedUsers);
                                            }
                                        }

                                        // Get only direct reports
                                        $reportingUsersQuery = "
                                            SELECT id, username 
                                            FROM tbl_user 
                                            WHERE reportingTo = $userId AND status = 1
                                        ";
                                        $reportingResult = $conn->query($reportingUsersQuery);

                                        $allUserIds = [];
                                        $allUsernames = [];

                                        while ($row = $reportingResult->fetch_assoc()) {
                                            $allUserIds[] = $row['id'];
                                            $allUsernames[] = "'" . $conn->real_escape_string($row['username']) . "'";
                                        }

                                        if (!empty($allUserIds) || !empty($allUsernames)) {
                                            $idsIn = !empty($allUserIds) ? implode(",", $allUserIds) : "NULL";
                                            $usernamesIn = !empty($allUsernames) ? implode(",", $allUsernames) : "NULL";

                                            $whereCondition .= " AND (p.createdBy IN ($idsIn) OR p.createdBy IN ($usernamesIn))";
                                        } else {
                                            // No direct reports, so force an empty result
                                            $whereCondition .= " AND 1=0";
                                        }

                                    }
                                    }

                                    // Pagination starts
                                    $limit = 50; // Records per page
                                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                    if ($page < 1) $page = 1;
                                    $offset = ($page - 1) * $limit;

                                    // Count total records
                                    $countQuery = "SELECT COUNT(*) as total FROM tbl_partner p 
                                                
                                                $whereCondition";
                                    $countResult = mysqli_query($conn, $countQuery);
                                    $totalRows = mysqli_fetch_assoc($countResult)['total'];
                                    $totalPages = ceil($totalRows / $limit);
                                    // Pagination ends

                                    // Main data query
                                    $query = "
                                        SELECT 
                                            p.id, p.Phone_number, p.full_name, p.alias_name, p.email_id, p.createdBy
                                        FROM tbl_partner p
                                        LEFT JOIN tbl_user u ON p.createdBy = u.username OR p.createdBy = u.id
                                        $whereCondition
                                        ORDER BY p.id DESC LIMIT $offset, $limit
                                       
                                    ";
                                  
                                    $dataResult = $conn->query($query);

                                    if ($dataResult->num_rows > 0) {
                                        echo '<div class="table-responsive p-3">';
                                        echo '<table class="table table-bordered">';
                                        echo '<thead class="table-dark">
                                                <tr>
                                                    <th>Full Name</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                             
                                                    <th>Created By</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>';
                                        echo '<tbody>';
                                        while ($row = $dataResult->fetch_assoc()) {
                                           
                                            echo '<tr>
                                                    <td>' . htmlspecialchars($row['full_name']) . '</td>
                                                    <td>' . htmlspecialchars($row['Phone_number']) . '</td>
                                                    <td>' . htmlspecialchars($row['email_id']) . '</td>
                                                   
                                                    <td>' . getUserFullName($conn, $row['createdBy']) . '</td>
                                                    <td>
                                                        <a class="dropdown-item text-primary p-0 mt-1 d-inline-block" href="view?id=' . $row['id'] . '">
                                                            <i class="bx bx-user"></i> View
                                                        </a>
                                                    </td>
                                                  </tr>';
                                        }
                                        echo '</tbody></table></div>';
                                        
                                    } else {
                                        echo '<div class="p-3">No Partner records found for this user.</div>';
                                    }
                                   
                                ?>
                                 <?php include('../includes/pagination.php'); ?>
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