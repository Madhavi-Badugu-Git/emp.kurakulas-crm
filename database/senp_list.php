<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 
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
                                    <h5 class="card-header">DataBase SENP List</h5>

                                    <!-- Filter Form -->
                                    <div class="row" style="margin-top:-25px;">
                                        <form method="GET" class="p-3 mt-0">
                                            <div class="row" style="margin-left:0px;">
                                                <div class="col-md-4">
                                                    <label for="mobile" class="form-label">Mobile Number</label>
                                                    <input type="text" class="form-control" name="mobile" id="mobile"
                                                        placeholder="658 799 8941" maxlength="10" pattern="[0-9]{10}"
                                                        oninput="this.value = this.value.replace(/\D/g, '')"
                                                        value="<?= isset($_GET['mobile']) ? htmlspecialchars($_GET['mobile']) : '' ?>" />
                                                </div>

                                                <div class="col-md-3 mt-6">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                    <a href="senp_list" class="btn btn-secondary">Reset</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Data Table -->
                                    <div class="table-responsive text-nowrap">
                                        <?php
                                        // Moved filter logic here to avoid undefined variable
                                        $where = "WHERE b.status = '1'";

                                        if (!empty($_GET['mobile'])) {
                                            $mobile = mysqli_real_escape_string($conn, $_GET['mobile']);
                                            $where .= " AND b.mobile_number = '$mobile'";
                                        }

                                        if ($loggedInUserRank == 'superAdmin'){
                                            $sql = mysqli_query($conn, "SELECT b.* FROM tbl_database b
                                            JOIN tbl_customer_type v ON b.customer_type = v.id
                                            $where AND b.customer_type = '35'
                                            ORDER BY v.customer_type ASC");
                                        } else{
                                            $sql = mysqli_query($conn, "SELECT b.* FROM tbl_database b
                                            JOIN tbl_customer_type v ON b.customer_type = v.id
                                            $where AND b.customer_type = '35' AND b.createdBy = '$loggedInUser'
                                            ORDER BY v.customer_type ASC");
                                        }
                                       

                                        ?>
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Mobile Number</th>
                                                    <th>Lead Name</th>
                                                    <th>Email Id</th>
                                                     <th>Created BY</th>
                                                    <th>ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $createdBy = $row['createdBy'];

                                                        $sql_user = "SELECT * FROM tbl_user WHERE username = '$createdBy'";
                                                        $result_user = mysqli_query($conn, $sql_user);

                                                        if ($result_user && mysqli_num_rows($result_user) > 0) {
                                                            $user = mysqli_fetch_assoc($result_user);
                                                            $fullName = $user['firstName'] . ' ' . $user['lastName'];
                                                            // echo $fullName;
                                                        } else {
                                                            $fullName = "Unknown User";
                                                            
                                                        }
                                                ?>
                                                <tr>
                                                    <td><?= $row['mobile_number']; ?></td>
                                                    <td><?= $row['lead_name']; ?></td>
                                                    <td><?= $row['email_id']; ?></td>
                                                     <td><?= $fullName; ?></td>
                                                    <td>

                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="view?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-user"></i> View</a></li>
                                                                            <li><a class="dropdown-item"
                                                                        href="edit?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-edit"></i> Edit</a></li>
                                                                <?php 
                                            if ($loggedInUserRank == 'superAdmin'){
                                            ?>
                                                              
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="deleteDataBase?id=<?= $row['id']; ?>"
                                                                        onclick="return confirm('Are you sure?');"><i
                                                                            class="bx bx-trash"></i> Delete</a></li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>

                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="5" class="text-center">No records found.</td></tr>';
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