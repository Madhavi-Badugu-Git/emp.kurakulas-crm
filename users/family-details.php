<?php 
session_start(); // ✅ Start session at the top
include('../includes/dbConfig.php');
include('../includes/validation.php'); 

// Debugging: Check session values
// print_r($_SESSION);

$last_id = isset($_SESSION['last_id']) ? $_SESSION['last_id'] : null;

// if ($last_id) {
//     echo "<h3 style='color: green;'>Last ID: " . $last_id . "</h3>";
// } else {
//     echo "<h3 style='color: red;'>No last ID found</h3>";
// }
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

<body>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Side Menu -->
            <?php include('../includes/sideMenu.php'); ?>

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Basic Layout -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Add Family Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="last_id"
                                                value="<?= htmlspecialchars($last_id) ?>">

                                            <!-- Family Details Table -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <table class="table table-bordered">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Age</th>
                                                                <th>Sex</th>
                                                                <th>Relation</th>
                                                                <th>Mobile No</th>
                                                                <th>Occupation</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="familyTable">
                                                            <tr>
                                                                <td><input type="text" class="form-control"
                                                                        name="family_name[]"></td>
                                                                <td><input type="number" class="form-control"
                                                                        name="family_age[]"></td>
                                                                <td>
                                                                    <select class="form-control" name="family_sex[]">
                                                                        <option>Male</option>
                                                                        <option>Female</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <!-- <input type="text" class="form-control"
                                                                        name="family_relation[]"> -->
                                                                    <select class="form-control"
                                                                        name="family_relation[]">
                                                                        <option value="">Select</option>
                                                                        <?php
        $relation_query = "SELECT family_relation FROM tbl_family_relation WHERE status='1' ORDER BY family_relation ASC";
        $relation_result = mysqli_query($conn, $relation_query);
        
        while ($row = mysqli_fetch_assoc($relation_result)) {
            echo '<option value="' . $row['family_relation'] . '">' . $row['family_relation'] . '</option>';
        }
        ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                        name="family_mobile[]"  maxlength="10" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/\D/g, '')">
                                                                    </td>
                                                                <td><input type="text" class="form-control"
                                                                        name="family_occupation[]"></td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-primary action-btn addRow">Add</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <input type="submit" name="form_submit" value="Next"
                                                    class="btn btn-primary mt-2">
                                                <input type="hidden" value="<?= $last_id ?>">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include('../includes/footer.php'); ?>

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>

    </div>

    <?php include('../includes/script.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $(document).on("click", ".action-btn", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addRow")) {
                let newRow = `<tr>
                    <td><input type="text" class="form-control" name="family_name[]"></td>
                    <td><input type="number" class="form-control" name="family_age[]"></td>
                    <td>
                        <select class="form-control" name="family_sex[]">
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </td>
                    <td>
                   <select class="form-control"
                                                                        name="family_relation[]">
                                                                        <option value="">Select</option>
                                                                        <?php
        $relation_query = "SELECT family_relation FROM tbl_family_relation WHERE status='1' ORDER BY family_relation ASC";
        $relation_result = mysqli_query($conn, $relation_query);
        
        while ($row = mysqli_fetch_assoc($relation_result)) {
            echo '<option value="' . $row['family_relation'] . '">' . $row['family_relation'] . '</option>';
        }
        ?>
                                                                    </select>
                    </td>
                    <td><input type="text" class="form-control" name="family_mobile[]" maxlength="10" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/\D/g, '')"></td>
                    <td><input type="text" class="form-control" name="family_occupation[]"></td>
                    <td>
                        <button type="button" class="btn btn-primary action-btn addRow">Add</button>
                    </td>
                </tr>`;

                $("#familyTable").append(newRow);
                btn.removeClass("btn-primary addRow").addClass("btn-danger removeRow").text("Delete");
            } else if (btn.hasClass("removeRow")) {
                row.remove();
                updateLastRow();
            }
        });

        function updateLastRow() {
            let lastRow = $("#familyTable tr:last");
            lastRow.find(".action-btn").removeClass("btn-danger removeRow").addClass("btn-primary addRow").text(
                "Add");
        }
    });
    </script>

</body>

</html>
<?php
if (isset($_POST['form_submit'])) {
    $created_at = date('Y-m-d H:i:s');

    if (!empty($_POST['family_name']) && isset($_POST['last_id'])) {
        $last_id = $_POST['last_id'];
        $all_success = true; // ✅ Track if all inserts succeed

        foreach ($_POST['family_name'] as $index => $name) {
            $age = $_POST['family_age'][$index] ?? '';
            $sex = $_POST['family_sex'][$index] ?? '';
            $relation = $_POST['family_relation'][$index] ?? '';
            $mobile = $_POST['family_mobile'][$index] ?? '';
            $occupation = $_POST['family_occupation'][$index] ?? '';

            $family_sql = "INSERT INTO `tbl_family_details`(`user_id`, `name`, `age`, `sex`, `relation`, `mobile`, `occupation`, `created_at`) 
            VALUES ('$last_id', '$name', '$age', '$sex', '$relation', '$mobile', '$occupation', '$created_at')";
            
            $result = mysqli_query($conn, $family_sql);
            
            if (!$result) {
                $all_success = false;
                echo "<script>
                $(document).ready(function() {
                    iziToast.error({
                        title: 'Error',
                        message: 'User Family Details Not Added: " . mysqli_error($conn) . "',
                        position: 'topRight',
                    });
                });
                </script>";
                break; // Stop loop on first error
            }
        }

        if ($all_success) {
            echo "<script>
            $(document).ready(function() {
                iziToast.success({
                    title: 'Success',
                    message: 'User Family Details Added Successfully',
                    position: 'topRight',
                });
                setTimeout(function() {
            window.location.href = 'education-qualification.php'; // ✅ Redirect after 2 seconds
        }, 1000);
            });
            </script>";
        }

        // unset($_SESSION['last_id']);
    } else {
        echo "<script>
        $(document).ready(function() {
            iziToast.warning({
                title: 'Error',
                message: 'All Fields Are Required',
                position: 'topRight',
            });
        });
        </script>";
    }
}
?>