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
                                        <h5 class="mb-0">Add Calling Sub-Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-phone"></i></span>
                                                    <select id="calling_status" name="calling_status" class="form-select">
                                                        <option value="">Select Calling Status</option>
                                                        <?php
                                                                $query = "SELECT id, calling_status FROM tbl_calling_status ORDER BY calling_status ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['calling_status'].'</option>';
                                                                }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-phone-call"></i></span>
                                                    <input type="text" class="form-control" name="calling_sub_status"
                                                        id="calling_sub_status" placeholder="Calling Sub-Status"  />
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
                                    <h5 class="card-header">Calling Sub-Status List</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Calling Sub-Status</th>
                                                    <th>Calling Status</th>

                                                    <th>STATUS</th>
                                                    <th>ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Fetch department data
                                                $sql = mysqli_query($conn, "SELECT * FROM tbl_calling_sub_status WHERE status='1' ORDER BY calling_sub_status ASC");

                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $status = $row['status'];
                                                ?>
                                                <tr>
                                                    <td><?= $row['calling_sub_status']; ?></td>
                                                    <td><?= getCallingStatus($conn, $row['calling_status_id']); ?></td>
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
                                                                        href="editSubStatus?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="deleteSubStatus?id=<?= $row['id']; ?>"
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
     $calling_status = $_POST['calling_status'];
     $calling_sub_status = $_POST['calling_sub_status'];
   
     $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($calling_status) || empty($calling_sub_status) ) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else{

     // Check if Assessment Year already exists
     $check = mysqli_query($conn, "SELECT * FROM tbl_calling_sub_status WHERE calling_status_id='$calling_status' AND calling_sub_status='$calling_sub_status' AND status='1'");
    
     if (mysqli_num_rows($check) > 0) {
         echo '<script>
             iziToast.warning({
                 title: "Error",
                 message: "Both Calling Status, Calling Sub Status already exists",
                 position: "topRight",
             });
         </script>';
         exit();
     }

    // Insert into database
    $sql = "INSERT INTO `tbl_calling_sub_status`(`calling_status_id`, `calling_sub_status`,`created_at`) 
            VALUES ('$calling_status','$calling_sub_status','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Calling Sub-Status Added Successfully",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="addSubStatus"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="addSubStatus"; }, 1000);
        </script>';
    }
    }
}
?>