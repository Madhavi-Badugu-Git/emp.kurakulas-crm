<?php 
session_start(); 
include('../includes/dbConfig.php'); 
include('../includes/validation.php'); 

// print_r($_SESSION);
$last_id = isset($_SESSION['last_id']) ? $_SESSION['last_id'] : null;
// if ($last_id) {
//     echo "<h3 style='color: green;'>Last ID: " . $last_id . "</h3>";
// } else {
//     echo "<h3 style='color: red;'>No last ID found</h3>";
// }
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default">

<?php include('../includes/header.php'); ?>

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

                        <!-- Experience Form -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Add Experience Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <input type="hidden" name="last_id"
                                                value="<?= htmlspecialchars($last_id) ?>">

                                            <!-- Experience Table -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">

                                                    <div class="table-responsive">
                                                        <table id="experienceTable"
                                                            class="table table-bordered text-center">
                                                            <thead class="table-dark">
                                                                <!-- <tr>
                                                                    <th style="width: 15%;">Organization</th>
                                                                    <th style="width: 10%;">From</th>
                                                                    <th style="width: 10%;">To</th>
                                                                    <th style="width: 15%;">Last Position</th>
                                                                    <th style="width: 15%;">Job Responsibility</th>
                                                                    <th style="width: 15%;">Superior & Phone</th>
                                                                    <th style="width: 10%;">Salary</th>
                                                                    <th style="width: 10%;">Reason</th>
                                                                    <th style="width: 5%;">Action</th>
                                                                </tr> -->
                                                                <tr>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Organization</th>
                                                                    <th style="width: 12%; min-width: 120px;">From</th>
                                                                    <th style="width: 12%; min-width: 120px;">To</th>
                                                                    <th style="width: 18%; min-width: 180px;">Last
                                                                        Position</th>
                                                                    <th style="width: 20%; min-width: 200px;">Job
                                                                        Responsibility</th>
                                                                    <th style="width: 20%; min-width: 200px;">Superior &
                                                                        Phone</th>
                                                                    <th style="width: 15%; min-width: 150px;">Salary
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">Reason
                                                                    </th>
                                                                    <th style="width: 6%; min-width: 60px;">Action</th>
                                                                </tr>

                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><input type="text"
                                                                            class="form-control form-control-sm"
                                                                            name="organization[]"></td>
                                                                    <td><input type="date"
                                                                            class="form-control form-control-sm"
                                                                            name="from_date[]"></td>
                                                                    <td><input type="date"
                                                                            class="form-control form-control-sm"
                                                                            name="to_date[]"></td>
                                                                    <td><input type="text"
                                                                            class="form-control form-control-sm"
                                                                            name="last_position[]"></td>
                                                                    <td><input type="text"
                                                                            class="form-control form-control-sm"
                                                                            name="job_responsibility[]"></td>
                                                                    <td><input type="text"
                                                                            class="form-control form-control-sm"
                                                                            name="superior_designation[]" maxlength="10"
                                                                            pattern="[0-9]{10}"
                                                                            oninput="this.value = this.value.replace(/\D/g, '')">
                                                                    </td>
                                                                    <td><input type="number"
                                                                            class="form-control form-control-sm"
                                                                            name="salary[]" min="0" step="0.01"></td>
                                                                    <td><input type="text"
                                                                            class="form-control form-control-sm"
                                                                            name="reason_for_leaving[]"></td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-primary exp-action-btn addExpRow">Add</button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
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
    // education Qualification
    $(document).ready(function() {
        $(document).on("click", ".exp-action-btn", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addExpRow")) {
                // Add a new row
                let newRow = `<tr>
                   <td><input type="text"
                                                                            class="form-control form-control-sm"
                                                                            name="organization[]"></td>
                                                                    <td><input type="date"
                                                                            class="form-control form-control-sm"
                                                                            name="from_date[]"></td>
                                                                    <td><input type="date"
                                                                            class="form-control form-control-sm"
                                                                            name="to_date[]"></td>
                                                                    <td><input type="text"
                                                                            class="form-control form-control-sm"
                                                                            name="last_position[]"></td>
                                                                    <td><input type="text"
                                                                            class="form-control form-control-sm"
                                                                            name="job_responsibility[]"></td>
                                                                    <td><input type="text"
                                                                            class="form-control form-control-sm"
                                                                            name="superior_designation[]" maxlength="10"
                                                                            pattern="[0-9]{10}"
                                                                            oninput="this.value = this.value.replace(/\D/g, '')">
                                                                    </td>
                                                                    <td><input type="number"
                                                                            class="form-control form-control-sm"
                                                                            name="salary[]" min="0" step="0.01"></td>
                                                                    <td><input type="text"
                                                                            class="form-control form-control-sm"
                                                                            name="reason_for_leaving[]"></td>
                    <td>
                        <button type="button" class="btn btn-primary exp-action-btn addExpRow">Add</button>
                    </td>
                </tr>`;

                $("#experienceTable").append(newRow);
                btn.removeClass("btn-primary addExpRow").addClass("btn-danger removeExpRow").text(
                    "Delete");
            } else if (btn.hasClass("removeExpRow")) {
                row.remove();
                updateLastEduRow();
            }
        });

        function updateLastEduRow() {
            let lastRow = $("#experienceTable tr:last");
            lastRow.find(".exp-action-btn").removeClass("btn-danger removeExpRow").addClass(
                "btn-primary addExpRow").text("Add");
        }
    });
    </script>



</body>

</html>
<?php
if (isset($_POST['form_submit'])) {
    $created_at = date('Y-m-d H:i:s');
    $last_id = $_POST['last_id'];

    // Check if at least one organization is filled
    if (!empty($_POST['organization']) && count(array_filter($_POST['organization'], 'strlen')) > 0) {
        $all_success = true;

        foreach ($_POST['organization'] as $index => $org) {
            if (empty($org)) continue; // Skip empty rows

            $from_date = $_POST['from_date'][$index] ?? '';
            $to_date = $_POST['to_date'][$index] ?? '';
            $last_position = $_POST['last_position'][$index] ?? '';
            $job_responsibility = $_POST['job_responsibility'][$index] ?? '';
            $superior_designation = $_POST['superior_designation'][$index] ?? '';
            $salary = $_POST['salary'][$index] ?? '';
            $reason_for_leaving = $_POST['reason_for_leaving'][$index] ?? '';

            $exp_sql = "INSERT INTO `tbl_experience_details`
                        (`user_id`, `organization`, `from_date`, `to_date`, `last_position`, `job_responsibility`, `superior_designation`, `salary`, `reason_for_leaving`, `created_at`) 
                        VALUES ('$last_id', '$org', '$from_date', '$to_date', '$last_position', '$job_responsibility', '$superior_designation', '$salary', '$reason_for_leaving', '$created_at')";

            if (!mysqli_query($conn, $exp_sql)) {
                $all_success = false;
                echo "<script>
                $(document).ready(function() {
                    iziToast.error({
                        title: 'Error',
                        message: 'Experience Details Not Added: " . mysqli_error($conn) . "',
                        position: 'topRight',
                    });
                });
                </script>";
                break;
            }
        }

        if ($all_success) {
            echo "<script>
            $(document).ready(function() {
                iziToast.success({
                    title: 'Success',
                    message: 'Experience Details Added Successfully',
                    position: 'topRight',
                });

                setTimeout(function() {
                    window.location.href = 'additionalInfo.php'; // ✅ Redirect after 1 second
                }, 1000);
            });
            </script>";
        }
    } else {
        echo "<script>
                $(document).ready(function() {
                    iziToast.error({
                        title: 'Error',
                        message: 'All Fields Are Required: " . mysqli_error($conn) . "',
                        position: 'topRight',
                    });
                });
                </script>";
    }
}

?>