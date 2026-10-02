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

$attendance_id = $_GET['id'];

// Fetch customer type details
$query = "SELECT * FROM tbl_user_attendance WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $attendance_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Company Type not found!"); window.location.href="add";</script>';
    exit();
}

$attendance = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Attendance</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <input type="hidden" name="attendance_id" value="<?= $attendance['id']; ?>">

                                            <div class="row">
                                            <div class="col-md-6">

                                                <label class="form-label" for="employee_id"> Employee ID
                                                </label>
                                                <div class="input-group input-group-merge">



                                                    <select id="employee_id" name="employee_id" class="form-select">
                                                        <option value="">Select Username</option>
                                                        <?php
                                                                $query = "SELECT id, username FROM tbl_user ORDER BY username ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $selected = ($row['id'] == $attendance['employee_id']) ?
                                                                    'selected' : ''; // Preselect the company
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.
                                                                $row['username'].'</option>';
                                                                    
                                                                }
                                                            ?>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="col-md-6">

                                                <label class="form-label" for="attendance_type_id"> Attendance Type ID
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                    <!-- <input type="text" class="form-control" name="bank_name"
                                                            id="bank_name" placeholder="Bank Name" /> -->
                                                    <select id="attendance_type_id" name="attendance_type_id"
                                                        class="form-select">
                                                        <option value="">Select attendance_type</option>
                                                        <?php
                                                                $query = "SELECT id, attendance_type FROM tbl_attendance_type ORDER BY attendance_type ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $selected = ($row['id'] == $attendance['attendance_type_id']) ?'selected' : ''; // Preselect the company
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.
                                                                $row['attendance_type'].'</option>';
                                                                }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="date">Date</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                                    <input type="date" class="form-control" name="date" id="date" value="<?= $attendance['date']; ?>"
                                                        placeholder="Date" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                                <label class="form-label" for="gross_salary"> Gross Salary
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                    <!-- <input type="text" class="form-control" name="bank_name"
                                                            id="bank_name" placeholder="Bank Name" /> -->
                                                    <select id="gross_salary" name="gross_salary" class="form-select">
                                                        <option value="">Select GrossSalary</option>
                                                        <?php
                                                                $query = "SELECT id, grossSalary FROM tbl_gross_salary ORDER BY grossSalary ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $selected = ($row['id'] == $attendance['gross_salary']) ?
                                                                    'selected' : ''; // Preselect the company
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.
                                                                $row['grossSalary'].'</option>';
                                                                }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">

                                                <label class="form-label" for="total_working_day">Total Working Day
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                    <input type="text" class="form-control" name="total_working_day"
                                                        id="total_working_day" value="<?= $attendance['total_working_day']; ?>" 
                                                         placeholder=" Total Working Day" />
                                                </div>

                                            </div>
                                            <div class="col-md-6">

                                                <label class="form-label" for="employee_working_day"> Employee Working
                                                    day
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                    <input type="text" class="form-control" name="employee_working_day"
                                                        id="employee_working_day" value="<?= $attendance['employee_working_day']; ?>" placeholder="Employee Working day " />
                                                </div>

                                            </div>
                                        </div>


                                        <div class="row mt-3">
                                            <div class="col-md-6">

                                                <label class="form-label" for="present_day">present_day
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                    <input type="text" class="form-control" name="present_day"
                                                        id="present_day" value="<?= $attendance['present_day']; ?>" placeholder=" present_day " />
                                                </div>

                                            </div>
                                            <div class="col-md-6">

                                                <label class="form-label" for="casual_leave"> casual_leave
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                    <input type="text" class="form-control" name="casual_leave"
                                                        id="casual_leave" value="<?= $attendance['casual_leave']; ?>" placeholder="casual_leave " />
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">

                                                <label class="form-label" for="deduction_amount">Deduction Amount
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                    <input type="text" class="form-control" name="deduction_amount"
                                                        id="deduction_amount" value="<?= $attendance['deduction_amount']; ?>" placeholder=" Deduction Amount " />
                                                </div>

                                            </div>
                                            <div class="col-md-6">

                                                <label class="form-label" for="net_salary"> Net Salary
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                    <!-- <input type="text" class="form-control" name="bank_name"
                                                            id="bank_name" placeholder="Bank Name" /> -->
                                                    <select id="net_salary" name="net_salary" class="form-select">
                                                        <option value="">Select NetSalary</option>
                                                        <?php
                                                                $query = "SELECT id, netSalary FROM tbl_net_salary ORDER BY netSalary ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $selected = ($row['id'] == $attendance['net_salary']) ?
                                                                    'selected' : ''; // Preselect the company
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.
                                                                $row['netSalary'].'</option>';
                                                                }
                                                            ?>
                                                    </select>
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

</body>

</html>

<?php
// Handle update
if (isset($_POST['update_form'])) {
    $attendance_id = $_POST['attendance_id'];
    $employee_id = $_POST['employee_id'];
    
    $date = $_POST['date'];
    $attendance_type_id = $_POST['attendance_type_id'];
    $gross_salary = $_POST['gross_salary'];
    $total_working_day = $_POST['total_working_day'];
    $employee_working_day = $_POST['employee_working_day'];
    $present_day = $_POST['present_day'];
    $casual_leave = $_POST['casual_leave'];
    $deduction_amount = $_POST['deduction_amount'];
    $net_salary = $_POST['net_salary'];
    

    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($attendance_id) || empty($attendance) ) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
    } else {
        // Update query
        $sql = "UPDATE tbl_user_attendance SET employee_id = ?, date = ?, attendance_type_id = ?, gross_salary = ?, 
        total_working_day = ?, employee_working_day = ?, present_day = ?, casual_leave = ?, deduction_amount = ?, net_salary = ?, updated_at='$created_at' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssi", $employee_id, $date, $attendance_type_id, $gross_salary, $total_working_day, 
        $employee_working_day, $present_day, $casual_leave, $deduction_amount, $net_salary, $attendance_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Attendance Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="list"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Something Went Wrong, Please Try Again",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="list"; }, 1000);
            </script>';
        }
    }
}
?>