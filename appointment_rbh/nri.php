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
                                    <h5 class="card-header">Team Appointment</h5>

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
                                                        AND u.department_id = '$loggedInDeptId' ORDER BY name ASC
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
                                    $isFiltered = !empty($_GET['user_id']);
                                    if ($isFiltered) {
                                        $selectedUserId = intval($_GET['user_id']);

                                        // Get username of selected user
                                        $userQuery = "SELECT username FROM tbl_user WHERE id = $selectedUserId";
                                        $userResult = $conn->query($userQuery);

                                        if ($userResult && $userResult->num_rows > 0) {
                                            $userRow = $userResult->fetch_assoc();
                                            $username = $conn->real_escape_string($userRow['username']);

                                            // Appointments created by selected user
                                            $query = "
                                                SELECT id, mobile_number, lead_name, email_id, company_name, createdBy, created_at
                                                FROM tbl_appointment 
                                                WHERE customer_type = '37' AND createdBy = '$username'
                                                ORDER BY created_at DESC
                                            ";
                                            $dataResult = $conn->query($query);

                                            // Appointments moved to selected user
                                            $moveQuery = "
                                                SELECT a.id, a.mobile_number, a.lead_name, a.email_id, a.company_name, a.createdBy, a.created_at
                                                FROM tbl_appointment_info ai
                                                JOIN tbl_appointment a ON ai.appointment_id = a.id
                                                WHERE ai.unique_id = a.unique_id 
                                                AND a.customer_type = '37' 
                                                AND ai.appt_mobedToBh = $username
                                                ORDER BY ai.created_at DESC
                                            ";

                                            // echo $moveQuery;
                                            $moveResult = $conn->query($moveQuery);

                                            if (($dataResult && $dataResult->num_rows > 0) || ($moveResult && $moveResult->num_rows > 0)) {
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
                                                    </thead><tbody>';

                                                while ($row = $dataResult->fetch_assoc()) {
                                                    echo '<tr>
                                                        <td>' . htmlspecialchars($row['lead_name']) . '</td>
                                                        <td>' . htmlspecialchars($row['mobile_number']) . '</td>
                                                        <td>' . htmlspecialchars($row['email_id']) . '</td>
                                                        <td>' . htmlspecialchars($row['company_name']) . '</td>
                                                        <td>' . getUserFullName($conn, $row['createdBy']) . '</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item text-primary" href="../appointment/view?id=' . $row['id'] . '"><i class="bx bx-user"></i> View</a></li>
                                                                    <li><a class="dropdown-item" href="../appointment/MoveTo?id=' . $row['id'] . '"><i class="bx bx-file"></i> Move File</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>';
                                                }

                                                while ($row = $moveResult->fetch_assoc()) {
                                                    echo '<tr>
                                                        <td>' . htmlspecialchars($row['lead_name']) . '</td>
                                                        <td>' . htmlspecialchars($row['mobile_number']) . '</td>
                                                        <td>' . htmlspecialchars($row['email_id']) . '</td>
                                                        <td>' . htmlspecialchars($row['company_name']) . '</td>
                                                        <td>' . getUserFullName($conn, $row['createdBy']) . ' (Moved)</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item text-primary" href="../appointment/view?id=' . $row['id'] . '"><i class="bx bx-user"></i> View</a></li>
                                                                    <li><a class="dropdown-item" href="../appointment/MoveTo?id=' . $row['id'] . '"><i class="bx bx-file"></i> Move File</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>';
                                                }

                                                echo '</tbody></table></div>';
                                            } else {
                                                echo '<div class="p-3">No appointment records found for selected user.</div>';
                                            }
                                        } else {
                                            echo '<div class="p-3">Invalid user selected.</div>';
                                        }
                                    } else {
                                    
                                        // Get direct reports
                                        $directReportsQuery = "SELECT id FROM tbl_user WHERE reportingTo = '$loggedInUserId'";
                                        $directReportsResult = $conn->query($directReportsQuery);

                                        $directUserIds = [];
                                        $allUserIds = [];

                                        while ($row = $directReportsResult->fetch_assoc()) {
                                            $directUserIds[] = $row['id'];
                                            $allUserIds[] = $row['id']; // include in final list

                                            // Get second-level users
                                            $secondLevelQuery = "SELECT id FROM tbl_user WHERE reportingTo = '{$row['id']}'";
                                            $secondLevelResult = $conn->query($secondLevelQuery);
                                            while ($subRow = $secondLevelResult->fetch_assoc()) {
                                                $allUserIds[] = $subRow['id'];
                                            }
                                        }

                                        $usernameList = [];

                                        if (!empty($allUserIds)) {
                                            $userIdsStr = implode(",", array_map('intval', $allUserIds));
                                            $usernamesQuery = "SELECT username FROM tbl_user WHERE id IN ($userIdsStr)";
                                            $usernamesResult = $conn->query($usernamesQuery);

                                            while ($row = $usernamesResult->fetch_assoc()) {
                                                $usernameList[] = "'" . $conn->real_escape_string($row['username']) . "'";
                                            }
                                        }

                                        $usernameInClause = implode(",", $usernameList);

                                        $appointmentQuery = "
                                            SELECT id, mobile_number, lead_name, email_id, company_name, createdBy, created_at
                                            FROM tbl_appointment 
                                            WHERE customer_type = '37'
                                            AND createdBy IN ($usernameInClause)
                                            ORDER BY id DESC
                                        ";

                                        $appointmentResult = $conn->query($appointmentQuery);

                                        if ($appointmentResult && $appointmentResult->num_rows > 0) {
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
                                                </thead><tbody>';

                                            while ($row = $appointmentResult->fetch_assoc()) {
                                                echo '<tr>
                                                    <td>' . htmlspecialchars($row['lead_name']) . '</td>
                                                    <td>' . htmlspecialchars($row['mobile_number']) . '</td>
                                                    <td>' . htmlspecialchars($row['email_id']) . '</td>
                                                    <td>' . htmlspecialchars($row['company_name']) . '</td>
                                                    <td>' . getUserFullName($conn, $row['createdBy']) . '</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item text-primary" href="../appointment/view?id=' . $row['id'] . '"><i class="bx bx-user"></i> View</a></li>
                                                                <li><a class="dropdown-item" href="../appointment/MoveTo?id=' . $row['id'] . '"><i class="bx bx-file"></i> Move File</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>';
                                            }

                                            echo '</tbody></table></div>';
                                        } else {
                                            echo '<div class="p-3">No appointments found for your reporting team.</div>';
                                        }
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
