<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');

// Logged-in RBH info
$loggedInUser = $_SESSION['loggedInUser'];
$rbhQuery = $conn->query("SELECT id, department_id FROM tbl_user WHERE username = '$loggedInUser'");
$rbhData = $rbhQuery->fetch_assoc();
$rbhId = $rbhData['id'];
$rbhDeptId = $rbhData['department_id'];
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
                                <h5 class="card-header">Team Manage Icons</h5>

                                <!-- Filter Form -->
                                <form method="GET" class="p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            // Get BHs reporting to RBH in same department
                                            $bhQuery = "
                                                SELECT u.id, CONCAT(u.firstName, ' ', u.lastName) AS name, d.designation_name
                                                FROM tbl_user u
                                                JOIN tbl_designation d ON u.designation_id = d.id
                                                WHERE u.department_id = $rbhDeptId
                                                    AND u.reportingTo = $rbhId
                                                    AND d.designation_name = 'Business Head'
                                            ";
                                            $bhResult = $conn->query($bhQuery);

                                            // Store BH IDs
                                            $bhIds = [];
                                            while ($bh = $bhResult->fetch_assoc()) {
                                                $bhIds[] = $bh['id'];
                                                $users[] = $bh;
                                            }

                                            // Get Managers reporting to those BHs
                                            if (!empty($bhIds)) {
                                                $bhIdsStr = implode(',', $bhIds);
                                                $managerQuery = "
                                                    SELECT u.id, CONCAT(u.firstName, ' ', u.lastName) AS name, d.designation_name
                                                    FROM tbl_user u
                                                    JOIN tbl_designation d ON u.designation_id = d.id
                                                    WHERE u.department_id = $rbhDeptId
                                                        AND u.reportingTo IN ($bhIdsStr)
                                                        AND d.designation_name = 'Manager'
                                                ";
                                                $managerResult = $conn->query($managerQuery);

                                                while ($mgr = $managerResult->fetch_assoc()) {
                                                    $users[] = $mgr;
                                                }
                                            }
                                            ?>
                                            <select name="user_id" class="form-select">
                                                <option value="">Select User</option>
                                                <?php
                                                if (!empty($users)) {
                                                    foreach ($users as $user) {
                                                        ?>
                                                        <option value="<?= $user['id']; ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $user['id']) ? 'selected' : ''; ?>>
                                                            <?= $user['name']; ?> (<?= $user['designation_name']; ?>)
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary">Show Icons</button>
                                            <a href="?" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>

                                <?php
                                if (!empty($_GET['user_id'])) {
                                    $selectedUserId = intval($_GET['user_id']);
                                    $userQuery = $conn->query("SELECT manage_icons FROM tbl_user WHERE id = $selectedUserId");

                                    if ($userQuery->num_rows > 0) {
                                        $userData = $userQuery->fetch_assoc();
                                        $iconIds = json_decode($userData['manage_icons']);

                                        if (!empty($iconIds)) {
                                            $iconIdsStr = implode(',', array_map('intval', $iconIds));
                                            $iconQuery = $conn->query("SELECT * FROM tbl_manage_icon WHERE id IN ($iconIdsStr)");

                                            echo '<div class="row p-3">';
                                            while ($icon = $iconQuery->fetch_assoc()) {
                                                ?>
                                                <div class="col-md-3 mb-4">
                                                    <div class="card h-100 text-center">
                                                        <div class="card-body">
                                                            <a href="<?= $icon['icon_url']; ?>" target="_blank">
                                                                <img src="../uploads/manage-icons/<?= $icon['icon_image']; ?>" alt="<?= $icon['icon_name']; ?>" class="img-fluid mb-2" style="height: 70px;">
                                                            </a>
                                                            <h6><b><?= $icon['icon_name']; ?></b></h6>
                                                            <p><?= $icon['icon_description']; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            echo '</div>';
                                        } else {
                                            echo '<div class="p-3">No icons assigned to this user.</div>';
                                        }
                                    } else {
                                        echo '<div class="p-3">User not found.</div>';
                                    }
                                } else {
                                    echo '<div class="p-3">Please select a user to view their icons.</div>';
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
