<?php
session_start();
// print_r($_SESSION); 
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');
// echo $loggedInUser;


if ($loggedInUserRank == 'Admin') {

    $sql_admin = "SELECT * FROM tbl_user WHERE status='1' AND rank = 'User'";



}
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>

<body>

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">

            <!-- side menu -->
            <?php include('../includes/sideMenu.php'); ?>
            <!-- side menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">


                        <!-- Basic Layout -->
                        <div class="row">

                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">Active User List</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Full Name</th>
                                                    <th>Employee Id</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <?php
                                                    if ($loggedInUserRank == 'superAdmin') {
                                                        ?>
                                                        <th>STATUS</th>
                                                        <th>ACTIONS</th>
                                                    <?php
                                                    } else if ($loggedInUserRank == 'Admin') {
                                                        ?>
                                                            <th>ACTIONS</th>
                                                        <?php
                                                    } else {
                                                        ?>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th>State</th>
                                                            <th>Location</th>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php

                                                $sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE  status = '1' AND rank != 'superadmin' ORDER BY firstName ASC, lastName ASC");
                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $username = $row['username'];
                                                        $password = $row['password'];
                                                        $firstName = $row['firstName'];
                                                        $lastName = $row['lastName'];
                                                        $avatar = $row['avatar'];
                                                        $official_phone = $row['official_phone'];
                                                        $official_email = $row['official_email'];
                                                        $department_id = $row['department_id'];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $firstName . " " . $lastName; ?></td>
                                                            <td><?= $row['username']; ?></td>
                                                            <td><?= $row['official_phone']; ?></td>
                                                            <td><?= $row['official_email']; ?></td>
                                                            <?php
                                                            if ($loggedInUserRank == 'superAdmin') {
                                                                ?>
                                                                <td>
                                                                    <a href="inActive.php?id=<?= $row['id']; ?>">
                                                                        <span class="badge bg-success">Active</span>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                            type="button" data-bs-toggle="dropdown">
                                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li><a class="dropdown-item text-primary"
                                                                                    href="view.php?id=<?= $row['id']; ?>"><i
                                                                                        class="bx bx-user"></i> View</a></li>

                                                                            

                                                                            <li><a class="dropdown-item text-info"
                                                                                    href="permission?id=<?= $row['id']; ?>"><i
                                                                                        class="bx bx-lock"></i>Emp Manage
                                                                                    Permissions</a></li>

                                                                            <li><a class="dropdown-item text-warning"
                                                                                    href="dataPermission?id=<?= $row['id']; ?>"><i
                                                                                        class="bx bx-lock"></i> Emp Data
                                                                                    Permissions</a></li>

                                                                            <li><a class="dropdown-item text-success"
                                                                                    href="workPermission?id=<?= $row['id']; ?>"><i
                                                                                        class="bx bx-lock"></i> Emp Work
                                                                                    Permissions</a></li>
                                                                            <?php
                                                                            if (in_array($department_id, ['17', '14'])) {
                                                                                ?>
                                                                                <li><a class="dropdown-item text-primary"
                                                                                        href="accountPermission?id=<?= $row['id']; ?>"><i
                                                                                            class="bx bx-lock"></i> Emp Account
                                                                                        Permissions</a></li>
                                                                            <?php } ?>
                                                                            <li><a class="dropdown-item text-danger"
                                                                                    href="delete.php?id=<?= $row['id']; ?>"
                                                                                    onclick="return confirm('Are you sure?');"><i
                                                                                        class="bx bx-trash"></i> Delete</a></li>

                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            <?php
                                                            } else if ($loggedInUserRank == 'Admin') {


                                                                ?>
                                                                    <td>
                                                                        <a href="view.php?id=<?= $row['id']; ?>">
                                                                            <span class="badge bg-primary">View</span>
                                                                        </a>
                                                                    </td>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                    <td><?= getDepartmentName($conn, $row['department_id']); ?></td>
                                                                    <td><?= getDesignationName($conn, $row['designation_id']); ?></td>
                                                                    <td><?= getStateName($conn, $row['work_state']); ?></td>
                                                                    <td><?= getLocationName($conn, $row['work_location']); ?></td>
                                                                <?php
                                                            }
                                                            ?>
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
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include('../includes/footer.php'); ?>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

    </div>
    <!-- / Layout wrapper -->
    <?php include('../includes/script.php'); ?>

</body>

</html>