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
                                    <h5 class="card-header">Portfolio List</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Customer Name</th>
                                                    <th>Company Name</th>
                                                    <th>Mobile </th>
                                                    <th>State</th>
                                                    <th>Location</th>
                                                     <?php 
                                                   if($loggedInUserRank == 'superAdmin' || $loggedInUserDesignation == '13' || $loggedInUserDesignation == '17' || $loggedInUserDesignation == '34'){
                                                    ?>
                                                    <th>Created By</th>
                                                    <?php
                                                   }
                                                    ?>
                                                    <th>Actions</th>

                                                   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if($loggedInUserRank == 'superAdmin' || $loggedInUserDesignation == '13' || $loggedInUserDesignation == '17' || $loggedInUserDesignation == '34'){
                                                    $sql = mysqli_query($conn, "SELECT * FROM tbl_portfolio WHERE  status='1' ORDER BY customer_name ASC");
                                                } else if($loggedInUserRank == 'User'){
                                                    $sql = mysqli_query($conn, "SELECT * FROM tbl_portfolio WHERE createdBy='$loggedInUser' AND status='1' ORDER BY customer_name ASC");
                                                }
                                               
                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                       
                                                ?>
                                                <tr>
                                                    <td><?= $row['customer_name']; ?></td>
                                                    <td><?= $row['company_name']; ?></td>
                                                    <td>
                                                       <?= $row['Phone_number']; ?>
                                                    </td>
                                                    <td>
                                                       <?= getStateName($conn, $row['state']); ?>
                                                    </td>
                                                    <td>
                                                       <?= getLocationName($conn, $row['location']); ?>
                                                    </td>
                                                    <?php
                                                if($loggedInUserRank == 'superAdmin' || $loggedInUserDesignation == '13' || $loggedInUserDesignation == '17' || $loggedInUserDesignation == '34'){
                                                    ?>
                                                    <td>
                                                        <?= $row['createdBy']; ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td>
                                                    <?php
                                                if($loggedInUserRank == 'superAdmin' || $loggedInUserDesignation == '17' || $loggedInUserDesignation == '34'){
                                                    ?>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item text-primary"
                                                                        href="view?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-user"></i> View</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="edit?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-edit"></i> Edit</a></li>
                                                                             <?php
                                                if($loggedInUserRank == 'superAdmin'){
                                                    ?>
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="delete?id=<?= $row['id']; ?>"
                                                                        onclick="return confirm('Are you sure?');"><i
                                                                            class="bx bx-trash"></i> Delete</a></li>
                                                                             <?php } ?>
                                                            </ul>
                                                        </div>
                                                        <?php
                                                } else{
                                                    ?>
                                                     <a href="view?id=<?= $row['id']; ?>">
                                                            <span class="badge bg-primary">View</span>
                                                        </a>
                                                    <?php
                                                }
                                                ?>
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
