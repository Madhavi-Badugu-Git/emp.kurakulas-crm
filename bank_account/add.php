<?php 
session_start(); // ✅ Ensure this is at the top!
// print_r($_SESSION); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

// echo $loggedInUser;
// exit();
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>

<body>


    <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
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
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Add Bank Account Details </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row mt-2">
                                                
                                                <div class="col-md-6">
                                                    <label class="form-label" for="bank_name">Account Bank Name</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="bank_name" name="bank_name" class="form-select"
                                                            >
                                                            <option value="">Select Account Bank</option>
                                                            <?php
                                                                $query = "SELECT id, bank_name FROM tbl_account_bank ORDER BY bank_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" >Branch Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map-pin"></i></span>
                                                        <input type="text" id="branch_name" name="branch_name" class="form-control" placeholder="Branch Name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" >Account Number</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map-pin"></i></span>
                                                        <input type="text" id="account_no" name="account_no" class="form-control" placeholder="Account Number"  inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="account_name">Account Name</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user-voice"></i></span>
                                                        <select id="account_name" name="account_name" class="form-select"
                                                            >
                                                            <option value="">Select Account Name</option>
                                                            <?php
                                                                $query = "SELECT id, dsa_name FROM tbl_account_dsa ORDER BY dsa_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['dsa_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number">MICR Code</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="text" id="micr_code" name="micr_code" class="form-control" placeholder="MICR Code"  style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number">IFSC Code</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="text" id="ifsc_code" name="ifsc_code" class="form-control" placeholder="IFSC Code"  style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            

                                            <!-- <div class="text-end"> -->
                                                <input type="submit" name="form_submit" value="Submit"
                                                    class="btn btn-primary mt-3">
                                            <!-- </div> -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">Bank Account List</h5>
                                    <!-- Filter Form -->
                                    <div class="row" style="margin-left:0px;">
                                        <form method="GET">
                                            <div class="row">

                                                <!-- Account DSA Filter -->
                                                <div class="col-md-4">
                                                    <label class="form-label">Account  Name</label>
                                                    <select name="account_name" class="form-select">
                                                        <option value="">Select Account Name</option>
                                                        <?php
                                                        $query = "SELECT id, dsa_name FROM tbl_account_dsa WHERE status=1 ORDER BY dsa_name ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = (!empty($_GET['account_name']) && $_GET['account_name'] == $row['id']) ? 'selected' : '';
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['dsa_name'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Account Bank Filter -->
                                                <div class="col-md-4">
                                                    <label class="form-label"> Bank Name</label>
                                                    <select name="bank_name" class="form-select">
                                                        <option value="">Select  Bank Name</option>
                                                        <?php
                                                        $query = "SELECT id, bank_name FROM tbl_account_bank WHERE status=1 ORDER BY bank_name ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = (!empty($_GET['bank_name']) && $_GET['bank_name'] == $row['id']) ? 'selected' : '';
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['bank_name'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Buttons -->
                                                <div class="col-md-3 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                                    <a href="add" class="btn btn-secondary">Reset</a>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                    <div class="table-responsive text-nowrap mt-3">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Bank Name</th>
                                                    <th>Branch Name</th>
                                                    <th>Account Name</th>
                                                    <th>Account Number</th>
                                                    <th>MICR Code</th>
                                                    <th>IFSC Code</th>
                                                   
                                                    <th>Created By</th>
                                                    <th>Created At</th>

                                                    <?php if ($loggedInUserRank == 'superAdmin') { ?>
                                                        <th>Actions</th>
                                                    <?php } elseif($loggedInUserDesignation == '17' || $loggedInUserDesignation == '34'){ ?>
                                                          <th>Actions</th>
                                                      <?php  }?>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <?php
                                            // ---------------- WHERE CONDITIONS ----------------
                                            $where = "WHERE 1";

                                            if (!empty($_GET['account_name'])) {
                                                $dsa = mysqli_real_escape_string($conn, $_GET['account_name']);
                                                $where .= " AND b.account_name = '$dsa'";
                                            }

                                            if (!empty($_GET['bank_name'])) {
                                                $bank = mysqli_real_escape_string($conn, $_GET['bank_name']);
                                                $where .= " AND b.bank_name = '$bank'";
                                            }

                                            
                                            // ---------------- PAGINATION ----------------
                                            $limit = 50;
                                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                            if ($page < 1) $page = 1;
                                            $offset = ($page - 1) * $limit;

                                            $countQuery = "
                                                SELECT COUNT(*) AS total 
                                                FROM tbl_bank_account_details b
                                                $where
                                            ";
                                            $countResult = mysqli_query($conn, $countQuery);
                                            $totalRows = mysqli_fetch_assoc($countResult)['total'];
                                            $totalPages = ceil($totalRows / $limit);

                                            // ---------------- MAIN QUERY ----------------
                                            $sql = mysqli_query($conn, "
                                                SELECT 
                                                   *
                                                FROM tbl_bank_account_details b
                                               
                                                $where
                                                ORDER BY b.id ASC
                                                LIMIT $offset, $limit
                                            ");

                                            // ---------------- DATA DISPLAY ----------------
                                            if (mysqli_num_rows($sql) > 0) {
                                                while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                                <tr>
                                                   <td><?= fetchColumnValue($conn, 'tbl_account_dsa', 'id', $row['account_name'], 'dsa_name'); ?></td>
                                                    <td><?= $row['branch_name']; ?></td>

                                                   <td><?= fetchColumnValue($conn, 'tbl_account_bank', 'id', $row['bank_name'], 'bank_name'); ?></td>
                                                   

                                                    <td><?= $row['account_number']; ?></td>

                                                    <td><?= $row['micr_code']; ?></td>
                                                    <td><?= $row['ifsc_code']; ?></td>


                                                    <td><?= getCreatedByName($conn, $row['createdBy']); ?></td>
                                                    <td>
                                                        <?= !empty($row['created_at'])
                                                            ? date('d/m/Y', strtotime($row['created_at']))
                                                            : '' ?>
                                                    </td>

                                                    <?php if ($loggedInUserRank == 'superAdmin') { ?>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item text-info" href="edit?id=<?= $row['id']; ?>">
                                                                        <i class="bx bx-edit"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger"
                                                                    href="delete?id=<?= $row['id']; ?>"
                                                                    onclick="return confirm('Are you sure?');">
                                                                        <i class="bx bx-trash"></i> Delete
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <?php } elseif($loggedInUserDesignation == '17' || $loggedInUserDesignation == '34'){
                                                        ?>
                                                        <td>
                                                            <a href="edit?id=<?= $row['id']; ?>" class="btn btn-primary me-2 text-white">
                                                                <!-- <i class="bx bx-edit"></i>&nbsp;  -->
                                                                Edit
                                                            </a>
                                                        </td>
                                                        <?php
                                                    } ?>
                                                </tr>
                                            <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="7" class="text-center text-danger">No Records Found.</td></tr>';
                                            }
                                            ?>
                                            </tbody>
                                        </table>

                                        <?php include('../includes/pagination.php'); ?>
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

<?php

if (isset($_POST['form_submit'])) {

    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name']);
    $account_no = mysqli_real_escape_string($conn, $_POST['account_no']);

    $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
    $micr_code = mysqli_real_escape_string($conn, $_POST['micr_code']);
    $ifsc_code = mysqli_real_escape_string($conn, $_POST['ifsc_code']);

    $created_at = date('Y-m-d H:i:s');

    if ( empty($bank_name) || empty($branch_name) || empty($account_no) || empty($account_name) || empty($micr_code) || empty($ifsc_code)) {
        echo '<script>
            iziToast.warning({
                title: "Warning",
                message: "Please fill all required fields.",
                position: "topRight"
            });
        </script>';
        exit();
    }

    

    $sql = "INSERT INTO `tbl_bank_account_details`
    (`bank_name`, `branch_name`, `account_number`, `account_name`, `micr_code`, `ifsc_code`, `createdBy`, `created_at`)
    VALUES
    ('$bank_name','$branch_name','$account_no','$account_name','$micr_code','$ifsc_code','$loggedInUser','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Bank Account Details Added successfully!",
                position: "topRight"
            });
            setTimeout(() => { window.location.href = "add"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Failed to Add Bank Account Details. Please try again.",
                position: "topRight"
            });
        </script>';
    }

}
?>
