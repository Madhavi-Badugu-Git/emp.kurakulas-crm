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
                                        <h5 class="mb-0">Add Calling Type Of Loan</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <!-- <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-building"></i></span>
                                                    <select id="calling_bank" name="calling_bank" class="form-select">
                                                        <option value="">Select Calling Bank</option>
                                                        <?php
                                                                $query = "SELECT id, calling_bank FROM tbl_calling_bank ORDER BY calling_bank ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['calling_bank'].'</option>';
                                                                }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div> -->
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-money"></i></span>
                                                    <input type="text" class="form-control" name="calling_type_loan"
                                                        id="calling_type_loan" placeholder="Calling Loan Type"  />
                                                </div>
                                            </div>

                                            <input type="submit" name="form_submit" class="btn btn-primary">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">Calling Loan Type List</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Calling Loan Type</th>
                                                    <!-- <th>Calling Bank</th> -->

                                                    <th>STATUS</th>
                                                    <th>ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Fetch department data
                                                $sql = mysqli_query($conn, "SELECT * FROM tbl_calling_type_loan WHERE status='1' ORDER BY calling_type_loan ASC");

                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $status = $row['status'];
                                                ?>
                                                <tr>
                                                    <td><?= $row['calling_type_loan']; ?></td>
                                                    <!-- <td><?= getCallingBank($conn, $row['calling_bank_id']); ?></td> -->
                                                    <td>
                                                        <?php if ($status == 1) { ?>
                                                        <span class="badge bg-primary">Active</span>
                                                        <?php } else { ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="edit_type_loan?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="delete_loan_type?id=<?= $row['id']; ?>"
                                                                        onclick="return confirm('Are you sure?');"><i
                                                                            class="bx bx-trash"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
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
    //  $calling_bank = $_POST['calling_bank'];
     $calling_type_loan = $_POST['calling_type_loan'];
   
     $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($calling_type_loan) ) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else{

     // Check if type of loan already exists
     $check = mysqli_query($conn, "SELECT * FROM tbl_calling_type_loan WHERE calling_type_loan='$calling_type_loan' AND status='1'");
    
     if (mysqli_num_rows($check) > 0) {
         echo '<script>
             iziToast.warning({
                 title: "Error",
                 message: "Calling Loan Type already exists",
                 position: "topRight",
             });
         </script>';
         exit();
     }

    // Insert into database
    $sql = "INSERT INTO `tbl_calling_type_loan`(`calling_type_loan`,`created_at`) 
            VALUES ('$calling_type_loan','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Calling Type Loan Added Successfully",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="type_loan"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="type_loan"; }, 1000);
        </script>';
    }
    }
}
?>