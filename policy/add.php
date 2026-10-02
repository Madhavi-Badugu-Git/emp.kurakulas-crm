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

                        <?php 
      $designationQuery = "
    SELECT 
        d.id AS designation_id, d.designation_name, d.department_id, d.status, d.created_at, d.updated_at,
        u.id AS user_id, u.username, u.rank
    FROM tbl_user u
    LEFT JOIN tbl_designation d ON d.id = u.designation_id
    WHERE 
        u.username = '$loggedInUser'
        AND (
            d.designation_name IN ('Managing Director', 'Director', 'Regional Business Head', 'Business Head')
            OR u.rank = 'superAdmin'
        )
";


            $designationResult = $conn->query($designationQuery);

            if ($designationResult && $designationResult->num_rows > 0) {
                if ($row = $designationResult->fetch_assoc()) {
                ?>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Add Policy </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="loan_type">Loan Type </label>
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
                                                    <label class="form-label" for="vendor_bank">Vendor Bank </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="vendor_bank" name="vendor_bank" class="form-select">
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
                                                <div class="col-mb-3 ">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-file"></i></span>
                                                        <input class="form-control" type="file" id="file" name="file"
                                                            accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-mb-3 ">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-message"></i></span>
                                                        <textarea name="content" id="content" class="form-control"
                                                            placeholder="Enter Content........"></textarea>
                                                    </div>
                                                </div>
                                            </div>


                                            <input type="submit" name="form_submit" Value="Submit"
                                                class="btn btn-primary mt-3">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                }
            } 
          ?>

                        <div class="row">
                            <div class="col-xl">
                                <div class="card ">

                                    <h5 class="card-header" style="margin-bottom:-20px;"> Policy List</h5>
                                    <form method="GET" class="p-3 mt-0">
                                        <div class="row" style="margin-left:0px;">

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


                                            <div class="col-md-3 mt-0">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                                <a href="?" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>

                                                    <th>Vendor Bank</th>
                                                    <th>Loan Type</th>

                                                    <th>Images</th>
                                                    <th>Content</th>
                                                    <?php
                                                    if($loggedInUserRank == 'superAdmin'){
                                                        ?>
                                                    <th>ACTIONS</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $where = "WHERE p.status='1' ";

                                                // Pagination starts
                                                $limit = 50; // Records per page
                                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                                if ($page < 1) $page = 1;
                                                $offset = ($page - 1) * $limit;

                                                // Count total records
                                                $countQuery = "SELECT COUNT(id) AS total FROM tbl_policy WHERE status='1'";

                                                // $countQuery = "SELECT COUNT(*) as total FROM tbl_agent_data $where";

                                                $countResult = mysqli_query($conn, $countQuery);
                                                $totalRows = mysqli_fetch_assoc($countResult)['total'];
                                                $totalPages = ceil($totalRows / $limit);
                                                // Pagination ends

                                                if (!empty($_GET['vendor_bank'])) {
                                                    $where .= " AND p.vendor_bank_id='" . mysqli_real_escape_string($conn, $_GET['vendor_bank']) . "'";
                                                }
                                                if (!empty($_GET['loan_type'])) {
                                                    $where .= " AND p.loan_type_id='" . mysqli_real_escape_string($conn, $_GET['loan_type']) . "'";
                                                }

                                                $sql = mysqli_query($conn, "
                                                    SELECT 
                                                        p.* 
                                                    FROM tbl_policy p
                                                    LEFT JOIN tbl_vendor_bank v ON p.vendor_bank_id = v.id
                                                    LEFT JOIN tbl_loan_type l ON p.loan_type_id = l.id
                                                    $where
                                                    ORDER BY p.id DESC LIMIT $offset, $limit
                                                ");
                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $status = $row['status'];
                                                ?>
                                                <tr>

                                                    <td><?= getVendorBank($conn, $row['vendor_bank_id']); ?></td>
                                                    <td><?= getLoanType($conn, $row['loan_type_id']); ?></td>
                                                    <td><a href="../uploads/policy/<?= $row['image'] ?>"
                                                            target="_blank"><img
                                                                src="../uploads/policy/<?= $row['image'] ?>" alt=""
                                                                style="height:60px;"></a></td>

                                                    <td
                                                        style="width:400px; word-wrap: break-word; white-space: normal; text-align:justify">
                                                        <?= nl2br(htmlspecialchars($row['content'])); ?>
                                                    </td>
                                                    <?php
                                                    if($loggedInUserRank == 'superAdmin'){
                                                        ?>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="edit?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="delete?id=<?= $row['id']; ?>"
                                                                        onclick="return confirm('Are you sure?');"><i
                                                                            class="bx bx-trash"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                                }
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
    $vendor_bank = $_POST['vendor_bank'];
    $loan_type = $_POST['loan_type'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $created_at = date('Y-m-d H:i:s');
    
    // Check if file is uploaded
    $file_uploaded = !empty($_FILES['file']['name']);
    
    // Validate required fields
    // if (empty($vendor_bank) || empty($loan_type) || !$file_uploaded) {
    if (empty($vendor_bank) || empty($loan_type)) {

        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Vendor Bank, Loan Type Fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    }
    
    $brocher = 'default.png'; // Default value for the uploaded file
    $filename = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $path = '../uploads/policy/';
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $valid_extensions = ['jpeg', 'jpg', 'png'];
    
    // Validate file type
    // if (!in_array($ext, $valid_extensions)) {
    //     echo '<script>
    //         iziToast.warning({
    //             title: "Error",
    //             message: "Invalid file format. Only JPEG, JPG, PNG allowed.",
    //             position: "topRight",
    //         });
    //     </script>';
    //     exit();
    // }
    
    // Process file upload
    $final_image = date("dHis") . '-' . strtolower(str_replace(' ', '-', $filename));
    if (move_uploaded_file($tmp, $path . $final_image)) {
        $brocher = $final_image;
    } 
    // else {
    //     echo '<script>
    //         iziToast.warning({
    //             title: "Error",
    //             message: "Image upload failed. Please try again.",
    //             position: "topRight",
    //         });
    //     </script>';
    //     exit();
    // }

    $sql = "INSERT INTO `tbl_policy`(`vendor_bank_id`, `loan_type_id`, `image`, `content`, `created_at`,`createdBy`) VALUES ('$vendor_bank','$loan_type','$brocher','$content','$created_at','$loggedInUser')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Policy Added Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="add"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Something went wrong. Please try again.",
                position: "topRight",
            });
        </script>';
    }
}
?>