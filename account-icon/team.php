<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');

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
                                <h5 class="card-header">Team Account Icons </h5>

                                <!-- Filter Form -->
                                <form method="GET" class="p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            $allowedDesignations = ["Manager", "Director", "Business Head", "Regional Business Head", "Managing Director"];
                                            $inClause = "'" . implode("','", $allowedDesignations) . "'";
                                            $query = "SELECT u.id, CONCAT(u.firstName, ' ', u.lastName) AS name, d.designation_name 
                                                      FROM tbl_user u 
                                                      JOIN tbl_designation d ON u.designation_id = d.id 
                                                      WHERE d.designation_name IN ($inClause) AND u.status = 1";
                                            $result = $conn->query($query);
                                            ?>
                                            <select name="user_id" class="form-select">
                                                <option value="">Select User</option>
                                                <?php while ($row = $result->fetch_assoc()) { ?>
                                                    <option value="<?= $row['id']; ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $row['id']) ? 'selected' : ''; ?>>
                                                        <?= $row['name']; ?> (<?= $row['designation_name']; ?>)
                                                    </option>
                                                <?php } ?>
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
                                    $userQuery = $conn->query("SELECT account_icons FROM tbl_user WHERE id = $selectedUserId");

                                    if ($userQuery->num_rows > 0) {
                                        $userData = $userQuery->fetch_assoc();
                                        $iconIds = json_decode($userData['account_icons']);

                                        if (!empty($iconIds)) {
                                            $iconIdsStr = implode(',', array_map('intval', $iconIds));
                                            $iconQuery = $conn->query("SELECT * FROM tbl_account_icon WHERE id IN ($iconIdsStr)");

                                            echo '<div class="row p-3">';
                                            while ($icon = $iconQuery->fetch_assoc()) {
                                                ?>
                                                <div class="col-md-3 mb-4">
                                                    <div class="card h-100 text-center">
                                                        <div class="card-body">
                                                            <a href="<?= $icon['icon_url']; ?>" target="_blank">
                                                                <img src="../uploads/account_icons/<?= $icon['icon_image']; ?>" alt="<?= $icon['icon_name']; ?>" class="img-fluid mb-2" style="height: 70px;">
                                                            </a>
                                                            <h6><b><?= $icon['icon_name']; ?></b></h6>
                                                            <p><?= $icon['icon_description']; ?></p>
                                                            <p class="mb-0" >UserName: <?= $icon['username']; ?></p>
                                                            <p class="mb-0" >Password: <?= $icon['password']; ?></p>
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
