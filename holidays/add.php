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
                                                    if($loggedInUserRank == 'superAdmin' || $loggedInUserRank == 'Admin'){
                                                        ?>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Add Holidays</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="occasion_date">Occasion Date </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                            <input type="text" class="form-control" name="occasion_date"
                                                                id="occasion_date" placeholder="DD/MM/YYYY"
                                                                />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="occasion_name"> Occasion Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-party"></i></span>
                                                        <input type="text" class="form-control" name="occasion_name"
                                                            id="occasion_name" placeholder="Enter Occasion Name" />
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <input type="submit" name="form_submit" class="btn btn-primary mt-3">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
 <?php
                                                    }
                                                    ?>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">Holiday List</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <?php
                                                    if($loggedInUserRank == 'superAdmin' || $loggedInUserRank == 'Admin'){
                                                        ?>
                                                    <th>Actions</th>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = mysqli_query($conn, "SELECT * FROM tbl_emp_holidays WHERE status='1' ORDER BY occasion_date ASC");
                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $status = $row['status'];
                                                ?>
                                                <tr>
                                                    <td><?= $row['occasion_name']; ?></td>
                                                   <td><?= (!empty($row['occasion_date']) && $row['occasion_date'] != '0000-00-00') ? date('d/m/Y', strtotime($row['occasion_date'])) : ''; ?></td>


                                                    <td>
                                                        <?php if ($status == 1) { ?>
                                                        <span class="badge bg-primary">Active</span>
                                                        <?php } else { ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                        <?php } ?>
                                                    </td>
                                                    <?php
                                                    if($loggedInUserRank == 'superAdmin' ){
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
                                                                            <?php 
                                                                            if($loggedInUserRank == 'superAdmin'){
                                                                                ?>
                                                                                 <li><a class="dropdown-item text-danger"
                                                                        href="delete?id=<?= $row['id']; ?>"
                                                                        onclick="return confirm('Are you sure?');"><i
                                                                            class="bx bx-trash"></i> Delete</a></li>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <?php
                                                    } else if($loggedInUserRank == 'Admin'){
                                                        ?>
                                                        <td><a class="dropdown-item"
                                                                        href="edit?id=<?= $row['id']; ?>"> <span class="badge bg-primary">Edit</span></a></td>
                                                        <?php
                                                    }
                                                    ?>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let dateInput = document.getElementById("occasion_date");

            // Function to format date as DD/MM/YYYY
            function formatDate(date) {
                let d = new Date(date);
                let day = ("0" + d.getDate()).slice(-2);
                let month = ("0" + (d.getMonth() + 1)).slice(-2);
                let year = d.getFullYear();
                return `${day}/${month}/${year}`;
            }

            // ✅ If there's a prefilled value (e.g., from database), format it properly
            if (dateInput.value) {
                dateInput.value = formatDate(new Date(dateInput.value));
            }

            // ✅ Restrict input to only valid date format (DD/MM/YYYY)
            dateInput.addEventListener("input", function() {
                this.value = this.value.replace(/[^0-9/]/g, "").substring(0, 10);
            });

            // ✅ Convert DD/MM/YYYY to YYYY-MM-DD before form submission
            dateInput.form.addEventListener("submit", function() {
                let parts = dateInput.value.split("/");
                if (parts.length === 3) {
                    let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert to YYYY-MM-DD
                    dateInput.value = formattedDate;
                }
            });
        });
    </script>
</body>

</html>
<?php

if (isset($_POST['form_submit'])) {
     $occasion_name = $_POST['occasion_name'];
     $occasion_date = $_POST['occasion_date'];
     
     $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($occasion_name) || empty($occasion_date) ) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Occasion Name And Date fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else{

    // Check if Assessment Year already exists
    $check = mysqli_query($conn, "SELECT * FROM tbl_emp_holidays WHERE occasion_name='$occasion_name' AND occasion_date='$occasion_date' AND status='1'");
    
    if (mysqli_num_rows($check) > 0) {
         echo '<script>
             iziToast.warning({
                 title: "Error",
                 message: "Both Occasion Name And Date already exists",
                 position: "topRight",
             });
         </script>';
         exit();
    }

    // Insert into database
    $sql = "INSERT INTO `tbl_emp_holidays`(`occasion_name`,`occasion_date`,`created_at`) 
            VALUES ('$occasion_name','$occasion_date','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Occasion Added Successfully",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="add"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="add"; }, 1000);
        </script>';
    }

    }

}
?>