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
                                        <h5 class="mb-0">Add Bank Statement </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="dsa_name">Account DSA Name</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user-voice"></i></span>
                                                        <select id="dsa_name" name="dsa_name" class="form-select"
                                                            >
                                                            <option value="">Select Account DSA</option>
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
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number">Month / Year</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="month" id="month_year" name="month_year" class="form-control" placeholder="MM-YYYY">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="file" class="form-label">Upload File
                                                    </label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-file"></i></span>
                                                        <input class="form-control" type="file" id="file"
                                                        name="file" accept="image/*,application/pdf">
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="password">Password
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                    class="bx bx-lock"></i></span>
                                                       <input type="password" name="password" id="password" class="form-control" placeholder="Enter password">
                                                        <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword()">
                                                            <i class="bx bx-show" id="toggleIcon"></i>
                                                        </span>
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
                                    <h5 class="card-header">Bank Statement List</h5>
                                    <!-- Filter Form -->
                                    <div class="row" style="margin-left:0px;">
                                        <form method="GET">
                                            <div class="row">

                                                <!-- Account DSA Filter -->
                                                <div class="col-md-3">
                                                    <label class="form-label">Account DSA Name</label>
                                                    <select name="dsa_name" class="form-select">
                                                        <option value="">Select Account DSA Name</option>
                                                        <?php
                                                        $query = "SELECT id, dsa_name FROM tbl_account_dsa WHERE status=1 ORDER BY dsa_name ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = (!empty($_GET['dsa_name']) && $_GET['dsa_name'] == $row['id']) ? 'selected' : '';
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['dsa_name'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Account Bank Filter -->
                                                <div class="col-md-3">
                                                    <label class="form-label">Account Bank Name</label>
                                                    <select name="bank_name" class="form-select">
                                                        <option value="">Select Account Bank Name</option>
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

                                                <!-- Month / Year -->
                                                <div class="col-md-3">
                                                    <label class="form-label">Month / Year</label>
                                                    <input type="month" name="month_year" class="form-control"
                                                        value="<?= !empty($_GET['month_year']) ? $_GET['month_year'] : '' ?>">
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
                                                    <th>Account DSA Name</th>
                                                    <th>Account Bank Name</th>
                                                    <th>Month Year</th>
                                                    <th>Password</th>
                                                    <th>File View/ Download</th>
                                                   
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

                                            if (!empty($_GET['dsa_name'])) {
                                                $dsa = mysqli_real_escape_string($conn, $_GET['dsa_name']);
                                                $where .= " AND b.dsa_name = '$dsa'";
                                            }

                                            if (!empty($_GET['bank_name'])) {
                                                $bank = mysqli_real_escape_string($conn, $_GET['bank_name']);
                                                $where .= " AND b.bank_name = '$bank'";
                                            }

                                            if (!empty($_GET['month_year'])) {
                                                $monthYear = mysqli_real_escape_string($conn, $_GET['month_year']);
                                                $where .= " AND b.month_year = '$monthYear'";
                                            }
                                            // ---------------- PAGINATION ----------------
                                            $limit = 50;
                                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                            if ($page < 1) $page = 1;
                                            $offset = ($page - 1) * $limit;

                                            $countQuery = "
                                                SELECT COUNT(*) AS total 
                                                FROM tbl_bank_statement b
                                                $where
                                            ";
                                            $countResult = mysqli_query($conn, $countQuery);
                                            $totalRows = mysqli_fetch_assoc($countResult)['total'];
                                            $totalPages = ceil($totalRows / $limit);

                                            // ---------------- MAIN QUERY ----------------
                                            $sql = mysqli_query($conn, "
                                                SELECT 
                                                   *
                                                FROM tbl_bank_statement b
                                               
                                                $where
                                                ORDER BY b.id ASC
                                                LIMIT $offset, $limit
                                            ");

                                            // ---------------- DATA DISPLAY ----------------
                                            if (mysqli_num_rows($sql) > 0) {
                                                while ($row = mysqli_fetch_assoc($sql)) {
                                            ?>
                                                <tr>
                                                   <td><?= fetchColumnValue($conn, 'tbl_account_dsa', 'id', $row['dsa_name'], 'dsa_name'); ?></td>
                                                   <td><?= fetchColumnValue($conn, 'tbl_account_bank', 'id', $row['bank_name'], 'bank_name'); ?></td>
                                                    <td>
                                                        <?= (!empty($row['month_year']) && $row['month_year'] !== '0000-00-00')
                                                            ? date('F - Y', strtotime($row['month_year']))
                                                            : '' ?>
                                                    </td>

                                                    <td><?= $row['password']; ?></td>

                                                    <td>
                                                        <?php if (!empty($row['uploaded_file']) && $row['uploaded_file'] !== 'default.png') { ?>
                                                            <a href="../uploads/bank_statement/<?= $row['uploaded_file']; ?>" target="_blank">
                                                                View
                                                            </a>
                                                            |
                                                            <a href="../uploads/bank_statement/<?= $row['uploaded_file']; ?>" download>
                                                                Download
                                                            </a>
                                                        <?php } else { ?>
                                                            <span class="text-danger">No File Uploaded</span>
                                                        <?php } ?>
                                                    </td>

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
                                                                <!--<i class="bx bx-edit"></i>&nbsp; -->
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
    <script>
    function togglePassword() {
        const password = document.getElementById("password");
        const icon = document.getElementById("toggleIcon");

        if (password.type === "password") {
            password.type = "text";
            icon.classList.remove("bx-show");
            icon.classList.add("bx-hide");
        } else {
            password.type = "password";
            icon.classList.remove("bx-hide");
            icon.classList.add("bx-show");
        }
    }
    </script>
</body>
</html>

<?php

if (isset($_POST['form_submit'])) {

    $dsa_name = mysqli_real_escape_string($conn, $_POST['dsa_name']);
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $month_year = mysqli_real_escape_string($conn, $_POST['month_year']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $created_at = date('Y-m-d H:i:s');

    if ( empty($dsa_name) || empty($bank_name) || empty($month_year) || empty($_FILES['file']['name'])) {
        echo '<script>
            iziToast.warning({
                title: "Warning",
                message: "Please fill all required fields.",
                position: "topRight"
            });
        </script>';
        exit();
    }

    $target_dir = "../uploads/bank_statement/";
    $file_name = time() . "_" . basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = array("jpg", "jpeg", "png", "pdf");

    if (!in_array($file_type, $allowed_types)) {
        echo '<script>
            iziToast.warning({
                title: "Warning",
                message: "Invalid file type. Only JPG, PNG, PDF allowed.",
                position: "topRight"
            });
        </script>';
        exit();
    }

    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "File upload failed!",
                position: "topRight"
            });
        </script>';
        exit();
    }

    $file = $file_name;

    $sql = "INSERT INTO `tbl_bank_statement`
    (`dsa_name`, `bank_name`, `month_year`, `uploaded_file`, `password`, `createdBy`, `created_at`)
    VALUES
    ('$dsa_name','$bank_name','$month_year','$file','$password','$loggedInUser','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Bank statement Added successfully!",
                position: "topRight"
            });
            setTimeout(() => { window.location.href = "add"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Failed to Add Bank statement. Please try again.",
                position: "topRight"
            });
        </script>';
    }

}
?>
