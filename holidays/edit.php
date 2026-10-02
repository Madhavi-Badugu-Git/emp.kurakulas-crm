<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="add";</script>';
    exit();
}

$holiday_id = $_GET['id'];

// Fetch department details
$query = "SELECT * FROM tbl_emp_holidays WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $holiday_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Holiday Id not found!"); window.location.href="add";</script>';
    exit();
}

$holiday = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Holiday</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="holiday_id" value="<?= $holiday['id']; ?>">
                                           
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="occasion_date">Occasion Date</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                                        <input type="text" class="form-control" name="occasion_date"
                                                            id="occasion_date"
                                                            value="<?= !empty($holiday['occasion_date']) ? date('d/m/Y', strtotime($holiday['occasion_date'])) : ''; ?>"
                                                            placeholder="DD/MM/YYYY" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="occasion_name"> Occasion Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-party"></i></span>
                                                        <input type="text" class="form-control" name="occasion_name"
                                                            id="occasion_name" value="<?= $holiday['occasion_name']; ?>" placeholder="Enter Occasion Name" />
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <input type="submit" name="update_form" class="btn btn-primary mt-3"
                                                value="Update">
                                        </form>
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
// Handle update
if (isset($_POST['update_form'])) {
    $holiday_id = $_POST['holiday_id'];
    $occasion_name = $_POST['occasion_name'] ?? '';
    $occasion_date = $_POST['occasion_date'] ?? '';

    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($occasion_name) || empty($occasion_date)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Occasion Name And Date fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    }

    // Update query
    $sql = "UPDATE tbl_emp_holidays SET occasion_name = ?, occasion_date = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $occasion_name, $occasion_date, $holiday_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Occasion Updated Successfully",
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
?>
