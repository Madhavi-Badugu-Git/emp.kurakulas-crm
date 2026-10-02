<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

$loggedInUser = $_SESSION['loggedInUser'];
// echo $loggedInUser;

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
                                    <h5 class="card-header">Partner List</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <th>Status</th>
                                                    <!-- <th>Actions</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                               if($loggedInUserRank == 'superAdmin'){
                                                    $sql = mysqli_query($conn, "SELECT * FROM tbl_partner WHERE status='0' ORDER BY full_name ASC");
                                               } else if($loggedInUserRank == 'User'){
                                                    $sql = mysqli_query($conn, "SELECT * FROM tbl_partner WHERE createdBy='$loggedInUser' AND status='0' ORDER BY full_name ASC");
                                               }
                                               
                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $status = $row['status'];
                                                ?>
                                                <tr>
                                                    <td><?= $row['full_name']; ?></td>
                                                    <td><?= $row['Phone_number']; ?></td>
                                                    <td><?= $row['email_id']; ?></td>

                                                    <td>
                                                        <?php if ($status == 1) { ?>
                                                        <a href="status.php?id=<?= $row['id']; ?>">
                                                        <span class="badge bg-success">Active</span>
                                                        </a>
                                                        <?php } else { ?>
                                                            <a href="status.php?id=<?= $row['id']; ?>">
                                                        <span class="badge bg-danger">Inactive</span>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                    
                                                </tr>
                                                <?php
                                                    }
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
