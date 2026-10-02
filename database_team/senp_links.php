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
                                    <h5 class="card-header">Team Self Employed Non Professionals - SENP Database</h5>

                                    <!-- Filter Form -->
                                    <form method="GET" class="p-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php
                                                // Fetch users with specific designations
                                                $allowedDesignations = ["Managing Director", "Director", "Manager", "Business Head", "Regional Business Head"];
                                                $inClause = "'" . implode("','", $allowedDesignations) . "'";
                                                $query = "
                                                    SELECT u.id, u.username, CONCAT(u.firstName, ' ', u.lastName) AS name, d.designation_name 
                                                    FROM tbl_user u 
                                                    JOIN tbl_designation d ON u.designation_id = d.id 
                                                    WHERE d.designation_name IN ($inClause) AND u.status = 1  
                                                    ORDER BY u.firstName, u.lastName
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
                                    // Filtering Logic
                                    $userCondition = "";
                                    $queryParams = $_GET;

                                    if (!empty($_GET['user_id'])) {
                                        $selectedUserId = intval($_GET['user_id']);
                                        $userQuery = "SELECT id, username FROM tbl_user WHERE id = $selectedUserId";
                                        $userResult = $conn->query($userQuery);

                                        if ($userResult && $userResult->num_rows > 0) {
                                            $userRow = $userResult->fetch_assoc();
                                            $userId = $userRow['id'];
                                            $username = $conn->real_escape_string($userRow['username']);

                                            // Corrected WHERE clause
                                            $userCondition = "AND (createdBy = '$userId' OR createdBy = '$username')";
                                        }
                                    }

                                    // Total Records Count for Pagination
                                    $countQuery = "
                                        SELECT COUNT(*) AS total 
                                        FROM tbl_database 
                                        WHERE customer_type = '35' $userCondition
                                    ";
                                    $countResult = $conn->query($countQuery);
                                    $totalRecords = $countResult->fetch_assoc()['total'];
                                    $totalPages = ceil($totalRecords / $recordsPerPage);

                                    // Fetch Database Records
                                    $dataQuery = "
                                        SELECT id, mobile_number, lead_name, email_id, company_name, createdBy 
                                        FROM tbl_database 
                                        WHERE customer_type = '35' $userCondition
                                        ORDER BY lead_name DESC  
                                        LIMIT $offset, $recordsPerPage
                                    ";
                                    $dataResult = $conn->query($dataQuery);

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
                                            echo '<tr>
                                                    <td>' . htmlspecialchars($row['lead_name']) . '</td>
                                                    <td>' . htmlspecialchars($row['mobile_number']) . '</td>
                                                    <td>' . htmlspecialchars($row['email_id']) . '</td>
                                                    <td>' . htmlspecialchars($row['company_name']) . '</td>
                                                    <td>' . getUserFullName($conn, $row['createdBy']) . '</td>
                                                    <td>
                                                        <a class="dropdown-item text-primary p-0 mt-1 d-inline-block" href="../database/view?id=' . $row['id'] . '">
                                                            <i class="bx bx-user"></i> View
                                                        </a>
                                                    </td>
                                                  </tr>';
                                        }
                                        echo '</tbody></table></div>';

                                        // Pagination Links
                                        echo '<nav class="mt-4">';
                                        echo '<ul class="pagination justify-content-center" style="flex-wrap: wrap; gap: 5px;">';
                                        renderPagination($page, $totalPages, $_GET);
                                        echo '</ul></nav>';
                                    } else {
                                        echo '<div class="p-3">No SENP database record found.</div>';
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
