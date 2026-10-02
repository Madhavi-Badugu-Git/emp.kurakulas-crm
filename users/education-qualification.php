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
                                        <h5 class="mb-0">Add Education Qualification <span style="font-size:15px;">(Start with School Leaving Certificate or Equivalent)</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="last_id"
                                                value="<?= htmlspecialchars($last_id) ?>">

                                            <!-- education qualification table -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    
                                                    <table id="educationTable" class="table table-bordered">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th>Qualification</th>
                                                                <th>University/Institute</th>
                                                                <th>Year of Passing</th>
                                                                <th>% Marks</th>
                                                                <th>Major Subjects</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><input type="text" class="form-control"
                                                                        name="qualification[]" Placeholder="Qualification"></td>
                                                                <td><input type="text" class="form-control"
                                                                        name="university[]" placeholder="University/Institute"></td>
                                                                <td><input type="number" class="form-control"
                                                                        name="year_of_passing[]" min="1900" max="<?= date('Y'); ?>" placeholder="YYYY"></td>
                                                                <td><input type="number" class="form-control"
                                                                        name="marks[]" min="0" max="100" step="0.01" placeholder="% Marks">
                                                                </td>
                                                                <td><input type="text" class="form-control"
                                                                        name="major_subjects[]" Placeholder="Subjects"></td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-primary edu-action-btn addEduRow">Add</button>
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
    // education Qualification
    $(document).ready(function() {
        $(document).on("click", ".edu-action-btn", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addEduRow")) {
                // Add a new row
                let newRow = `<tr>
                    <td><input type="text" class="form-control" name="qualification[]" Placeholder="Qualification"></td>
                    <td><input type="text" class="form-control" name="university[]" placeholder="University/Institute"></td>
                    <td><input type="number" class="form-control" name="year_of_passing[]" min="1900" max="<?= date('Y'); ?>" placeholder="YYYY"></td>
                    <td><input type="text" class="form-control" name="marks[]" min="0" max="100" step="0.01" placeholder="% Marks"></td>
                    <td><input type="text" class="form-control" name="major_subjects[]" Placeholder="Subjects"></td>
                    <td>
                        <button type="button" class="btn btn-primary edu-action-btn addEduRow">Add</button>
                    </td>
                </tr>`;

                $("#educationTable").append(newRow);
                btn.removeClass("btn-primary addEduRow").addClass("btn-danger removeEduRow").text(
                    "Delete");
            } else if (btn.hasClass("removeEduRow")) {
                row.remove();
                updateLastEduRow();
            }
        });

        function updateLastEduRow() {
            let lastRow = $("#educationTable tr:last");
            lastRow.find(".edu-action-btn").removeClass("btn-danger removeEduRow").addClass(
                "btn-primary addEduRow").text("Add");
        }
    });
    </script>

</body>

</html>
<?php
if (isset($_POST['form_submit'])) {
    $created_at = date('Y-m-d H:i:s');

    // Check if at least one qualification is entered
    if (!empty($_POST['qualification']) && isset($_POST['last_id']) && count(array_filter($_POST['qualification'])) > 0) {
        $last_id = $_POST['last_id'];
        $all_success = true; // ✅ Track if all inserts succeed

        foreach ($_POST['qualification'] as $index => $qual) {
            if (empty($qual) || empty($_POST['university'][$index]) || empty($_POST['year_of_passing'][$index]) || empty($_POST['marks'][$index]) || empty($_POST['major_subjects'][$index])) {
                $all_success = false;
                break; // Stop if any field is empty
            }

            $university = $_POST['university'][$index];
            $year_of_passing = $_POST['year_of_passing'][$index];
            $marks = $_POST['marks'][$index];
            $major_subjects = $_POST['major_subjects'][$index];

            $edu_sql = "INSERT INTO `tbl_education_details`(`user_id`, `qualification`, `university`, `year_of_passing`, `marks`, `major_subjects`, `created_at`) 
                        VALUES ('$last_id', '$qual', '$university', '$year_of_passing', '$marks', '$major_subjects', '$created_at')";
            
            $result = mysqli_query($conn, $edu_sql);
            
            if (!$result) {
                $all_success = false;
                echo "<script>
                $(document).ready(function() {
                    iziToast.error({
                        title: 'Error',
                        message: 'Education Details Not Added: " . mysqli_error($conn) . "',
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
                    message: 'Education Details Added Successfully',
                    position: 'topRight',
                });

                setTimeout(function() {
                    window.location.href = 'experience.php'; // ✅ Redirect after 1 second
                }, 1000);
            });
            </script>";
        }
    } else {
        // ❌ Show error if no rows are added
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
