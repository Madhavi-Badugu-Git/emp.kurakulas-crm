<?php 
// echo $loggedInUserDesignation;
$sql = mysqli_query($conn, "SELECT * FROM `tbl_designation` WHERE `id` = '$loggedInUserDesignation'");

if ($sql && mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
    $designation = $row['designation_name'];
} else {
    $designation = "No designation found.";
}

?>

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0   d-xl-none ">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="bx bx-menu bx-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">

                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="../uploads/users/<?= $loggedInUserAvatar; ?>" alt class="w-px-40 h-auto rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="../uploads/users/<?= $loggedInUserAvatar; ?>" alt
                                            class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?= $loggedInUserFirstName; ?></h6>
                                    <?php  
                                    if ($loggedInUserRank == 'superAdmin'){
                                        ?>
                                    <small class="text-muted"><?= $loggedInUserRank; ?></small>
                                    <?php 
                                    } else{
                                        ?>
                                        <small class="text-muted"><?= $designation; ?></small>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <?php  
                    if ($loggedInUserRank == 'superAdmin') {
                        ?>
                    <li>
                        <a class="dropdown-item" href="../dashboard/profile">
                            <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="../dashboard/login-activity">
                            <i class="bx bx-log-in bx-md me-3"></i><span>LogIn Activity</span>
                        </a>
                    </li>
                    <?php
                    }
                    else if ($loggedInUserRank == 'Admin') {
                        ?>
                    <li>
                        <a class="dropdown-item" href="../dashboard/profile">
                            <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                        </a>
                    </li>
                     <li>
                        <a class="dropdown-item" href="../Attendance/add">
                            <i class="bx bx-calendar bx-md me-3"></i><span>Add Attendance</span>
                        </a>
                    </li>
                     <li>
                        <a class="dropdown-item" href="../Attendance/list">
                            <i class="bx bx-calendar bx-md me-3"></i><span>Attendance List</span>
                        </a>
                    </li>
                    <?php
                    } else{
                        if($loggedInUserRank == 'User'){
                            ?>
                  
                    <li>
                        <a class="dropdown-item" href="../users/view?id=<?= $loggedInUserId; ?>">
                            <i class="bx bx-user bx-md me-3"></i><span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="../dashboard/change-password">
                            <i class="bx bx-user bx-md me-3"></i><span>Change Password</span>
                        </a>
                    </li>
                    <?php
                        }
                    }
                    ?>
                    <li>
                        <a class="dropdown-item" href="../holidays/add">
                            <i class="bx bx-calendar bx-md me-3"></i><span>Emp Holidays</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="../logout">
                            <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>