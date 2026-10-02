<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="account_type";</script>';
    exit();
}

$appointment_id = $_GET['id'];

// Fetch department details
$query = "SELECT * FROM tbl_appointment WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Account Type not found!"); window.location.href="account_type";</script>';
    exit();
}

$appointment = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Appointment</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="appointment_id" value="<?= $appointment['id']; ?>">

                                            <div class="row">
                                                <div class="mb-6">
                                                    <label class="form-label" for="mobile_number">Mobile
                                                        Number</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" class="form-control" name="mobile_number"
                                                            id="mobile_number"
                                                            value="<?= $appointment['mobile_number']; ?>"
                                                            placeholder="658 799 8941" maxlength="10" readonly
                                                            pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')"
                                                            required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-0">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="lead_name"> Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="lead_name"
                                                            id="lead_name" value="<?= $appointment['lead_name']; ?>"
                                                            placeholder=" Name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="email_id">Email Id</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="email" class="form-control" name="email_id"
                                                            id="email_id" value="<?= $appointment['email_id']; ?>"
                                                            placeholder="Email Id" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="company_name">Company
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                       
                                                        <input type="text" class="form-control" name="company_name"
                                                            id="company_name" id="company_name"
                                                            value="<?= $appointment['company_name']; ?>"
                                                            placeholder="Company Name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="alternative_mobile">Alternative
                                                        Mobile</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" class="form-control"
                                                            name="alternative_mobile" id="alternative_mobile"
                                                            placeholder="658 799 8941" maxlength="10"
                                                            pattern="[0-9]{10}"
                                                            value="<?= $appointment['alternative_mobile']; ?>"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="state"> State</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map-alt"></i></span>
                                                        <select id="state" name="state" class="form-select"
                                                            onchange="getStateName(this.value)">
                                                            <option value="">Select State</option>

                                                            <?php
                                                        $query = "SELECT id, state_name FROM tbl_state ORDER BY state_name ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $appointment['state']) ? 'selected' : ''; 
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['state_name'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="location"> Location</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map"></i></span>
                                                        <select id="location" name="location" class="form-select"
                                                            onchange="getLocationName(this.value)">
                                                            <option value="">Select Location</option>
                                                            <?php
                                                        $query = "SELECT id, location FROM tbl_location WHERE state_id = '".$appointment['state']."' ORDER BY location ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $appointment['location']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['location'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="sub_location"> Sub
                                                        Location</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map-pin"></i></span>
                                                        <select id="sub_location" name="sub_location"
                                                            class="form-select"
                                                            onchange="getsubLocationName(this.value)">
                                                            <option value="">Select Sub Location</option>
                                                            <?php
                                                        $query = "SELECT id, sub_location FROM tbl_sub_location WHERE location_id = '".$appointment['location']."' ORDER BY sub_location ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $appointment['sub_location']) ? 'selected' : ''; 
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['sub_location'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="pin_code"> PIN Code</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-compass"></i></span>
                                                        <select id="pin_code" name="pin_code" class="form-select">
                                                            <option value="">Select PIN Code</option>
                                                            <?php
                                                        $query = "SELECT id, pincode FROM tbl_pincode WHERE sub_location_id = '".$appointment['sub_location']."' ORDER BY pincode ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $appointment['pin_code']) ? 'selected' : ''; 
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['pincode'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="source"> Source</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-code"></i></span>
                                                        <select id="source" name="source" class="form-select">
                                                            <option value="">Select Source</option>
                                                            <?php
                                                                $query = "SELECT id, source FROM tbl_data_source ORDER BY source ASC";
                                                                $result = $conn->query($query);

                                                                while ($row = $result->fetch_assoc()) {
                                                                    $selected = ($row['id'] == $appointment['source']) ? 'selected' : ''; 
                                                                    echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['source'].'</option>';
                                                                }
                                                                ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="visiting_card" class="form-label">Visiting Card
                                                    </label>
                                                    <input class="form-control" type="file" id="visiting_card"
                                                        name="visiting_card" accept="image/*,application/pdf">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="user_qualification">Qualification
                                                        </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-medal"></i></span>
                                                        
                                                        <input type="text" class="form-control" name="user_qualification" id="user_qualification" value="<?= $appointment['user_qualification']; ?>"
                                                            placeholder="Qualification" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="residental_address">Residential
                                                        Address</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-map"></i></span>
                                                        <textarea name="residental_address" id="residental_address"
                                                            class="form-control" rows="1"
                                                            placeholder="Residential Address"><?= $appointment['residental_address']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="mb-6">
                                                    <label class="form-label" for="customer_type">Type Of
                                                        Customer</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-group"></i></span>
                                                        <select id="customer_type" name="customer_type"
                                                            class="form-select" disabled>
                                                            <option value="">Select Type Of Customer</option>
                                                            <?php
                                                                $query = "SELECT id, customer_type FROM tbl_customer_type ORDER BY customer_type ASC";
                                                                $result = $conn->query($query);

                                                                while ($row = $result->fetch_assoc()) {
                                                                    $selected = ($row['id'] == $appointment['customer_type']) ? 'selected' : ''; 
                                                                    echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['customer_type'].'</option>';
                                                                }
                                                                ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            

                                            <div class="row mt-0">
                                                <div class="mb-6">
                                                    <label class="form-label" for="lead_name">Notes</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-message"></i></span>

                                                        <textarea name="database_notes" id="database_notes"
                                                            class="form-control"
                                                            placeholder="Notes"><?= $appointment['database_notes']; ?></textarea>
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
        // location name
        function getStateName(stateId) {
            if (stateId) {
                fetch("../info/get_location", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "state_id=" + stateId
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("location").innerHTML = data;
                    });
            } else {
                document.getElementById("location").innerHTML = '<option value="">Select Location</option>';
            }
        }
        //sub location name
        function getLocationName(locationId) {
            if (locationId) {
                fetch("../info/get_Sub_location", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "location_id=" + locationId
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("sub_location").innerHTML = data;
                    });
            } else {
                document.getElementById("sub_location").innerHTML = '<option value="">Select Sub Location</option>';
            }
        }
        // pincode
        function getsubLocationName(sublocationId) {
            if (sublocationId) {
                fetch("../info/get_pincode", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "sub_location_id=" + sublocationId
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("pin_code").innerHTML = data;
                    });
            } else {
                document.getElementById("pin_code").innerHTML = '<option value="">Select PIN Code</option>';
            }
        }
    </script>                                                     
</body>

</html>

<?php
// Handle update
if (isset($_POST['update_form'])) {
    $appointment_id = $_POST['appointment_id'];
    $mobile_number = $_POST['mobile_number'];
    $lead_name = $_POST['lead_name'];
    $email_id = $_POST['email_id'];
    $company_name = $_POST['company_name'];
    $alternative_mobile = $_POST['alternative_mobile'];
    $state = $_POST['state'];
    $location = $_POST['location'];
    $sub_location = $_POST['sub_location'];
    $pin_code = $_POST['pin_code'];
    $source = $_POST['source'];
    $residental_address = $_POST['residental_address'];
    $user_qualification = $_POST['user_qualification'];

    $database_notes = $_POST['database_notes'];
    $created_at = date('Y-m-d H:i:s');


    $target_dir = "../uploads/appointment/";

    // Get current file from database
    $existingFile = $appointment['visiting_card']; // assuming you've fetched `$appointment`

    $visiting_card = $existingFile; // default to existing file

    if (!empty($_FILES["visiting_card"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["visiting_card"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = array("jpg", "jpeg", "png", "pdf");

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["visiting_card"]["tmp_name"], $target_file)) {
                // Optionally delete old file if not default
                if ($existingFile != "default.png" && file_exists($target_dir . $existingFile)) {
                    unlink($target_dir . $existingFile);
                }
                $visiting_card = $file_name;
            } else {
                echo '<script>
                    iziToast.error({
                        title: "Error",
                        message: "File upload failed!",
                        position: "topRight"
                    });
                </script>';
                exit();
            }
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Warning",
                    message: "Invalid file type. Only JPG, PNG, PDF allowed.",
                    position: "topRight"
                });
            </script>';
            exit();
        }
    }

    // Update query
    $sql = "UPDATE tbl_appointment SET lead_name = ?, email_id = ?, company_name = ?, alternative_mobile = ?, state = ?, location = ?, sub_location = ?, pin_code = ?, source = ?, visiting_card = ?, user_qualification = ?, residental_address = ?,  database_notes = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiiissssssi", $lead_name, $email_id, $company_name, $alternative_mobile, $state, $location, $sub_location, $pin_code, $source, $visiting_card, $user_qualification, $residental_address, $database_notes, $appointment_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Appointment Updated Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="appointment"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="appointment"; }, 1000);
        </script>';
    }
}
?>