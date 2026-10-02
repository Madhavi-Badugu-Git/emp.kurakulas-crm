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
                                    <h5 class="card-header">DataBase SEP List</h5>

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
                                                    <a href="sep_list" class="btn btn-secondary">Reset</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Data Table -->
                                    <div class="table-responsive text-nowrap">
                                        <?php
                                        // Moved filter logic here to avoid undefined variable
                                        $where = "WHERE a.status = '1' AND a.customer_type = '36' ";

                                        if (!empty($_GET['mobile'])) {
                                            $mobile = mysqli_real_escape_string($conn, $_GET['mobile']);
                                            $where .= " AND a.mobile_number = '$mobile'";
                                        }

                                        if ($loggedInUserRank == 'superAdmin'){

                                             $sql = "SELECT * FROM tbl_appointment 
                                            WHERE status=1 AND customer_type = '36'
                                            ORDER BY lead_name ASC";
                                        } else{
                                            
                                            // Check if the user has any moved files in tbl_appointment_info
                                            $check_sql = "
                                                SELECT a.unique_id 
                                                FROM tbl_appointment a
                                                LEFT JOIN tbl_appointment_info i ON a.unique_id = i.unique_id
                                                WHERE 
                                                a.status = '1' AND i.appt_mobedToBh ='$loggedInUser'
                                                
                                            ";

                                            $check_result = mysqli_query($conn, $check_sql);

                                            if (mysqli_num_rows($check_result) > 0) {
                                                // If matching unique_id is found in tbl_appointment_info
                                                $sql = "
                                                    SELECT a.*, i.unique_id, i.appt_moved_by, i.appt_through, i.status AS info_status, i.created_at AS info_created_at, i.updated_at AS info_updated_at
                                                    FROM tbl_appointment a
                                                    LEFT JOIN tbl_appointment_info i ON a.unique_id = i.unique_id
                                                    $where AND (a.createdBy = '$loggedInUser' OR i.appt_mobedToBh = '$loggedInUser')
                                                    ORDER BY a.lead_name ASC
                                                ";
                                            } else {
                                                // If no matching unique_id is found
                                                $sql = "
                                                    SELECT * FROM tbl_appointment 
                                                    WHERE status = '1' 
                                                    AND customer_type = '36' 
                                                    AND createdBy = '$loggedInUser'
                                                    ORDER BY lead_name ASC
                                                ";
                                                // echo $sql;
                                            }
                                        }
                                        // Execute the query
                                        $result = mysqli_query($conn, $sql);
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
                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
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
                                                                        href="deleteAppointment?id=<?= $row['id']; ?>"
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