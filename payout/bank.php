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
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Add Bank Payout</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="loan_type">Loan Type</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-file"></i></span>
                                                        <select id="loan_type" name="loan_type" class="form-select">
                                                            <option value="">Select Loan Type</option>
                                                            <?php
                                                                $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['loan_type'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="vendor_bank_name">Vendor
                                                        Bank</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="vendor_bank_name" name="vendor_bank_name"
                                                            class="form-select">
                                                            <option value="">Select Vendor Bank</option>
                                                            <?php
                                                                $query = "SELECT id, vendor_bank_name FROM tbl_vendor_bank ORDER BY vendor_bank_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['vendor_bank_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="category_name">Category</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-category"></i></span>
                                                        <select id="category_name" name="category_name"
                                                            class="form-select">
                                                            <option value="">Select Category</option>
                                                            <?php
                                                                $query = "SELECT id, category_name FROM tbl_payout_category ORDER BY category_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['category_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="document_file" class="form-label">Payout Sheet
                                                    </label>
                                                    <input class="form-control" type="file" id="document_file"
                                                        name="document_file" accept="image/*,application/pdf">
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="mb-6 ">
                                                    <label class="form-label" for="document_name"> Document
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-file"></i></span>
                                                        <input type="text" class="form-control" name="document_name"
                                                            id="document_name" placeholder="Document Name" />
                                                    </div>
                                                </div>
                                            </div>


                                            <input type="submit" name="form_submit" class="btn btn-primary mt-3">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">Bank Payout List</h5>
                                    <!-- Filter Form -->
                                    <div class="row" style="margin-top:-25px;">
                                        <form method="GET" class="p-3 mt-0">
                                            <div class="row" style="margin-left:0px;">
                                                <!-- Vendor Bank Filter -->
                                                <div class="col-md-4">
                                                    <select name="vendor_bank" class="form-select">
                                                        <option value="">Select Vendor Bank</option>
                                                        <?php
                                                            $query = "SELECT id, vendor_bank_name FROM tbl_vendor_bank WHERE status=1 ORDER BY vendor_bank_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = (isset($_GET['vendor_bank']) && $_GET['vendor_bank'] == $row['id']) ? "selected" : "";
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['vendor_bank_name'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Loan Type Filter -->
                                                <div class="col-md-4">
                                                    <select name="loan_type" class="form-select">
                                                        <option value="">Select Loan Type</option>
                                                        <?php
                                                            $query = "SELECT id, loan_type FROM tbl_loan_type WHERE status=1 ORDER BY loan_type ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = (isset($_GET['loan_type']) && $_GET['loan_type'] == $row['id']) ? "selected" : "";
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['loan_type'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Filter & Reset Buttons -->
                                                <div class="col-md-3 mt-0">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                    <a href="bank" class="btn btn-secondary">Reset</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Vendor Bank</th>
                                                    <th>Loan Type</th>
                                                    <th>Category</th>
                                                    <th>Document Name</th>
                                                    <th>Payout Sheet</th>
                                                    <!--<th>STATUS</th>-->
                                                    <th>ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                              $where = "WHERE f.status='1'";

                                              if (!empty($_GET['vendor_bank'])) {
                                                 $where .= " AND f.vendor_bank_name='" . mysqli_real_escape_string($conn, $_GET['vendor_bank']) . "'";
                                             }
                                             if (!empty($_GET['loan_type'])) {
                                                 $where .= " AND f.loan_type='" . mysqli_real_escape_string($conn, $_GET['loan_type']) . "'";
                                             }

                                             $sql = mysqli_query($conn, "SELECT f.* FROM tbl_bank_payout f 
                                             JOIN tbl_vendor_bank v ON f.vendor_bank_name = v.id 
                                             $where ORDER BY v.vendor_bank_name ASC");

                                                // $sql = mysqli_query($conn, "SELECT * FROM tbl_bank_payout WHERE status='1'");

                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $status = $row['status'];
                                                ?>
                                                <tr>
                                                    <td><?= getVendorBank($conn, $row['vendor_bank_name']); ?></td>
                                                    <td><?= getLoanType($conn, $row['loan_type']); ?></td>
                                                    <td><?= getPayoutCategory($conn, $row['category_name']); ?></td>
                                                    <td><?= $row['document_name']; ?></td>
                                                    <td><a href="../uploads/bank-payout/<?= $row['document_file']; ?>"
                                                            target="_blank">View File</a></td>
                                                    <!--<td>-->
                                                    <!--    <?php if ($status == 1) { ?>-->
                                                    <!--    <span class="badge bg-primary">Active</span>-->
                                                    <!--    <?php } else { ?>-->
                                                    <!--    <span class="badge bg-danger">Inactive</span>-->
                                                    <!--    <?php } ?>-->
                                                    <!--</td>-->

                                                    <td>
                                                        <?php 
                                                   if($loggedInUserRank == 'superAdmin'){
                                                    ?>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="editBank?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="deleteBank?id=<?= $row['id']; ?>"
                                                                        onclick="return confirm('Are you sure?');"><i
                                                                            class="bx bx-trash"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                        <?php
                                                   } else{
                                                    ?>
                                                        <?php
                        if( $loggedInUserDesignation == '17' || $loggedInUserDesignation == '34'){
                            ?>

                                                        <a href="editBank?id=<?= $row['id']; ?>">
                                                            <span class="badge bg-primary">Edit</span>
                                                        </a>
                                                        <?php
                        }
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
<?php

if (isset($_POST['form_submit'])) {
     $loan_type = $_POST['loan_type'];
     $vendor_bank_name = $_POST['vendor_bank_name'];
     $document_name = $_POST['document_name'];
     $category_name = $_POST['category_name'];
   
     $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($loan_type) || empty($vendor_bank_name) || empty($document_name) || empty($category_name)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else{


    // File Upload Handling
    $target_dir = "../uploads/bank-payout/";

    $document_file = "default.png"; // Default file
    if (!empty($_FILES["document_file"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["document_file"]["name"]); // Generate unique filename
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = array("jpg", "jpeg", "png", "pdf");

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $target_file)) {
                $document_file = $file_name; // Save only the file name
            } else {
                echo '<script>
                    iziToast.error({
                        title: "Error",
                        message: "File upload failed!",
                        position: "topRight"
                    });
                </script>';
                exit();
            }
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Warning",
                    message: "Invalid file type. Only JPG, JPEG, PNG, PDF allowed.",
                    position: "topRight"
                });
            </script>';
            exit();
        }
    }

    // Insert into database
    $sql = "INSERT INTO `tbl_bank_payout`(`loan_type`, `vendor_bank_name`,`category_name`, `document_name`, `document_file`,`created_at`) 
            VALUES ('$loan_type','$vendor_bank_name','$category_name','$document_name','$document_file','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Bank Payout Added Successfully",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="bank"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="bank"; }, 1000);
        </script>';
    }

    }

}
?>