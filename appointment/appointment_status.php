<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// echo $loggedInUser;

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$get_id = $_GET['id'];

// echo $get_id;
// exit();

$query = "SELECT * FROM tbl_appointment WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $get_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Calling Status Id not found!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$appt = $result->fetch_assoc();
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

                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="get_id" value="<?= $appt['id']; ?>">

                                            <div class="row mt-5">
                                                <h5> Appointment Status Details</h5>
                                                <div class="col-md-12">
                                                    <div class="row mt-5">
                                                        <!-- <h5> Calling Status</h5> -->
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="appt_bank"> Appointment Bank</label><span style="color:red;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-building"></i></span>
                                                                <select id="appt_bank" name="appt_bank"
                                                                    class="form-select">
                                                                    <option value="">Select Appointment Bank</option>
                                                                    <?php
                                                               $query = "SELECT id, bank_name FROM tbl_appointment_bank ORDER BY bank_name ASC";
                                                               $result = $conn->query($query);
                                                               while ($row = $result->fetch_assoc()) {
                                                                   echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                               }
                                                           ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="appt_product"> Appointment Product</label><span style="color:red;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-cart"></i></span>
                                                                <select id="appt_product" name="appt_product"
                                                                    class="form-select">
                                                                    <option value="">Select Appointment Product</option>
                                                                    <?php
                                                               $query = "SELECT id, product_name FROM tbl_appointment_product ORDER BY product_name ASC";
                                                               $result = $conn->query($query);
                                                               while ($row = $result->fetch_assoc()) {
                                                                   echo '<option value="'.$row['id'].'">'.$row['product_name'].'</option>';
                                                               }
                                                           ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="appt_status"> Appointment
                                                                Status</label><span style="color:red;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-calendar-check"></i></span>
                                                                <select id="appt_status" name="appt_status"
                                                                    class="form-select"
                                                                    onchange="getAppointmentStatus(this.value)">
                                                                    <option value="">Select Appointment Status</option>
                                                                    <?php
                                                               $query = "SELECT id, appt_status FROM tbl_appointment_status ORDER BY appt_status ASC";
                                                               $result = $conn->query($query);
                                                               while ($row = $result->fetch_assoc()) {
                                                                   echo '<option value="'.$row['id'].'">'.$row['appt_status'].'</option>';
                                                               }
                                                           ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="appt_sub_status"> Appointment
                                                                Sub
                                                                Status</label><span style="color:red;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-calendar-exclamation"></i></span>
                                                                <select id="appt_sub_status"
                                                                    name="appt_sub_status" class="form-select">
                                                                    <option value="">Select Appointment Sub Status</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 mt-3">
                                                        <label class="form-label" for="notes">Notes
                                                        </label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"> <i
                                                                    class="bx bx-message"></i>
                                                            </span>
                                                            <textarea name="notes" id="notes"
                                                                class="form-control" rows="2"
                                                                placeholder="Notes"></textarea>
                                                        </div>
                                                    </div>

                                                    <!-- Datetime Field (Initially Hidden) -->
                                                    <div class="row mt-3" id="datetimeContainer" style="display: none;">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="followup_datetime">Calling
                                                                Date & Time</label><span style="color:red;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-calendar"></i></span>
                                                                <input type="datetime-local" id="followup_datetime"
                                                                    name="followup_datetime" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Datetime Field (Initially Hidden) -->

                                                    
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <input type="submit" name="submit_form" class="btn btn-primary mt-10"
                                                    value="Save">
                                            </div>
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
    function getSubStatus(subStatusId) {
        if (subStatusId) {
            fetch("../info/get_calling_sub_status", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "calling_status_id=" + subStatusId
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("calling_sub_status").innerHTML = data;

                    // Attach onchange listener after loading new options
                    setTimeout(() => {
                        document.getElementById("calling_sub_status").addEventListener("change",
                            handleSubStatusChange);
                    }, 100);
                });
        } else {
            document.getElementById("calling_sub_status").innerHTML =
                '<option value="">Select Calling Sub Status</option>';
            document.getElementById("datetimeContainer").style.display = "none";
        }
    }

    function handleSubStatusChange() {
        const subStatusText = this.options[this.selectedIndex].text.toLowerCase();

        if (["follow up", "call back", "appointment fixed"].includes(subStatusText)) {
            document.getElementById("datetimeContainer").style.display = "flex";
        } else {
            document.getElementById("datetimeContainer").style.display = "none";
        }

       
    }

    // Add initial event listener (in case options are already there)
    document.addEventListener("DOMContentLoaded", function() {
        const subStatusSelect = document.getElementById("calling_sub_status");
        if (subStatusSelect) {
            subStatusSelect.addEventListener("change", handleSubStatusChange);
        }
    });

    // appt sub status
    function getAppointmentStatus(subStatusId) {
        if (subStatusId) {
            fetch("../info/get_appointment_status", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "appt_status_id=" + subStatusId
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("appt_sub_status").innerHTML = data;
                });
        } else {
            document.getElementById("appt_sub_status").innerHTML =
                '<option value="">Select Appointment Sub Status</option>';
        }
    }
    </script>
</body>

</html>

<?php
if (isset($_POST['submit_form'])) {
    $get_id = $_POST['get_id'];
    $appt_bank = $_POST['appt_bank'];
    $appt_product = $_POST['appt_product'];
    $appt_status = $_POST['appt_status'];
    $appt_sub_status = $_POST['appt_sub_status'];
    $notes = $_POST['notes'];
    $created_at = date('Y-m-d H:i:s');

    // Insert into tbl_appointment_calling_status
    $sql = "INSERT INTO `tbl_appointment_calling_status` 
                    (`appt_id`, `appt_bank`, `appt_product`, `appt_status`, `appt_sub_status`, `notes`, `createdBy`, `created_at`) 
                    VALUES ('$get_id', '$appt_bank', '$appt_product', '$appt_status', '$appt_sub_status', '$notes', '$loggedInUser', '$created_at')";

if (mysqli_query($conn, $sql)) {

    echo '<script>
        iziToast.success({
            title: "Success",
            message: "Appointment Status Added Successfully",
            position: "topRight"
        });
       
         setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
    </script>';
} else {
    echo '<script>
        iziToast.error({
            title: "Error",
            message: "Some rows failed to insert. Please try again.",
            position: "topRight"
        });
        setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
    </script>';
}
}
?>
