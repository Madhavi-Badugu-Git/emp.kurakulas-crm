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

$query = "SELECT * FROM tbl_partner WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $get_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Calling Status not found!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$database = $result->fetch_assoc();
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

                                            <input type="hidden" name="get_id" value="<?= $database['id']; ?>">

                                            <div class="row mt-5">
                                                <h5> Calling Status Details</h5>
                                                <div class="col-md-12">

                                                    <div class="row mt-3">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="calling_status"> Calling
                                                                Status</label><span style="color:red;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-phone"></i></span>
                                                                <select id="calling_status" name="calling_status"
                                                                    class="form-select"
                                                                    onchange="getPartnerCallingStatus(this.value)">
                                                                    <option value="">Select Calling Status</option>
                                                                    <?php
                                                               $query = "SELECT id, calling_status FROM tbl_partner_calling_status ORDER BY calling_status ASC";
                                                               $result = $conn->query($query);
                                                               while ($row = $result->fetch_assoc()) {
                                                                   echo '<option value="'.$row['id'].'">'.$row['calling_status'].'</option>';
                                                               }
                                                           ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label" for="calling_sub_status"> Calling
                                                                Sub
                                                                Status</label><span style="color:red;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-phone"></i></span>
                                                                <select id="calling_sub_status"
                                                                    name="calling_sub_status" class="form-select">
                                                                    <option value="">Select Calling Sub Status</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
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
                                                    </div>
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
    function getPartnerCallingStatus(subStatusId) {
        if (subStatusId) {
            fetch("../info/get_partner_calling_sub_status", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "calling_status_id=" + subStatusId
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("calling_sub_status").innerHTML = data;
                });
        } else {
            document.getElementById("calling_sub_status").innerHTML =
                '<option value="">Select Partner Calling Sub Status</option>';
        }
    }
    </script>
</body>

</html>

<?php

    if (isset($_POST['submit_form'])) {
    $get_id = $_POST['get_id'];
    $calling_status = $_POST['calling_status'];
    $calling_sub_status = $_POST['calling_sub_status'];
    $notes = $_POST['notes'];
    $created_at = date('Y-m-d H:i:s');

    $calling_sql = "INSERT INTO `tbl_partner_calling_status_details`(`partner_id`, `calling_status`, `calling_sub_status`,`notes`,`createdBy`, `created_at`) 
                             VALUES ('$get_id', '$calling_status', '$calling_sub_status','$notes','$loggedInUser','$created_at')";

    // Show single success or error message after loop
   
    if (mysqli_query($conn, $calling_sql)) {

        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Calling Status Added Successfully",
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