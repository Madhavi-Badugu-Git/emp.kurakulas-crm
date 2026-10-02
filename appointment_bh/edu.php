<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

$loggedInUser = $_SESSION['loggedInUser'];
$loggedInUserId = $_SESSION['loggedInUserId'];

// Get department_id of logged-in user
$deptResult = $conn->query("SELECT department_id FROM tbl_user WHERE username = '$loggedInUser'");
$deptRow = $deptResult->fetch_assoc();
$loggedInDeptId = $deptRow['department_id'];

// Pagination settings
$recordsPerPage = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;
$isFiltered = !empty($_GET['user_id']);
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

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
                                    <h5 class="card-header">Team Appointment</h5>

                                    <!-- Filter Form -->
                                    <form method="GET" class="p-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php
                                                // Fetch only managers from same department
                                                $userQuery = "
                                                    SELECT u.id, CONCAT(u.firstName, ' ', u.lastName) AS name, d.designation_name 
                                                    FROM tbl_user u
                                                    JOIN tbl_designation d ON u.designation_id = d.id 
                                                    WHERE d.designation_name = 'Manager' 
                                                    AND u.department_id = $loggedInDeptId 
                                                    AND u.reportingTo = '$loggedInUserId'
                                                ";
                                                $userResult = $conn->query($userQuery);
                                                ?>
                                                <select name="user_id" class="form-select">
                                                    <option value="">Select User</option>
                                                    <?php while ($row = $userResult->fetch_assoc()) { ?>
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
                                    if ($isFiltered) {
                                        $selectedUserId = intval($_GET['user_id']);
                                        $sql = "
                                            SELECT a.*, CONCAT(u.firstName, ' ', u.lastName) AS createdByName 
                                            FROM tbl_appointment a
                                            JOIN tbl_user u ON a.createdBy = u.username
                                            WHERE u.id = $selectedUserId AND a.customer_type = '38'
                                            ORDER BY a.id DESC
                                            LIMIT $offset, $recordsPerPage
                                        ";
                                        $countQuery = "
                                            SELECT COUNT(*) AS total 
                                            FROM tbl_appointment a
                                            JOIN tbl_user u ON a.createdBy = u.username
                                            WHERE u.id = $selectedUserId AND a.customer_type = '38'
                                        ";
                                    } else {
                                        $sql = "
                                            SELECT a.*, CONCAT(u.firstName, ' ', u.lastName) AS createdByName 
                                            FROM tbl_appointment a
                                            JOIN tbl_user u ON a.createdBy = u.username
                                            WHERE u.reportingTo = '$loggedInUserId' AND a.customer_type = '38'
                                            ORDER BY a.id DESC
                                            LIMIT $offset, $recordsPerPage
                                        ";
                                        $countQuery = "
                                            SELECT COUNT(*) AS total 
                                            FROM tbl_appointment a
                                            JOIN tbl_user u ON a.createdBy = u.username
                                            WHERE u.reportingTo = '$loggedInUserId' AND a.customer_type = '38'
                                        ";
                                    }

                                    $dataResult = $conn->query($sql);
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
                                                    <th>Company</th>';
                                        if (!$isFiltered) {
                                            echo '<th>Created By</th>';
                                        }
                                        echo '<th>Action</th></tr></thead>';
                                        echo '<tbody>';
                                        while ($row = $dataResult->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($row['lead_name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['mobile_number']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['email_id']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['company_name']) . '</td>';
                                            if (!$isFiltered) {
                                                echo '<td>' . htmlspecialchars($row['createdByName']) . '</td>';
                                            }
                                            echo '<td><a class="btn btn-sm btn-primary" href="../appointment/view?id=' . $row['id'] . '">View</a></td>';
                                            echo '</tr>';
                                        }
                                        echo '</tbody></table></div>';

                                        // Pagination Links
                                        echo '<nav class="mt-4">';
                                        echo '<ul class="pagination justify-content-center">';
                                        for ($i = 1; $i <= $totalPages; $i++) {
                                            $active = ($i == $page) ? 'active' : '';
                                            $queryParams = $_GET;
                                            $queryParams['page'] = $i;
                                            $pageUrl = '?' . http_build_query($queryParams);
                                            echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $pageUrl . '">' . $i . '</a></li>';
                                        }
                                        echo '</ul></nav>';
                                    } else {
                                        echo '<div class="p-3">No Appointment records found.</div>';
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