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

$connector_id = $_GET['id'];


$query = "SELECT * FROM tbl_connectors WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $connector_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Connector not found!"); window.location.href="list";</script>';
    exit();
}

$connector = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Connector</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="connector_id" value="<?= $connector['id']; ?>">

                                            <div class="row mt-0">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="full_name">Full
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="full_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="full_name"
                                                            id="full_name" placeholder="Full Name"
                                                            value="<?= $connector['full_name']; ?>" aria-label="Full Name"
                                                            aria-describedby="full_name2" />
                                                    </div>
                                                </div>

                                                <!-- <div class="col-md-6">
                                                    <label class="form-label" for="alias_name">Alias
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="alias_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="alias_name"
                                                            id="alias_name" placeholder="Alias Name"
                                                            value="<?= $partner['alias_name']; ?>"
                                                            aria-label="Alias Name" aria-describedby="alias_name2" />
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number"> Phone
                                                        No</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="Phone_number" name="Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"  value="<?= $connector['Phone_number']; ?>" 
                                                            aria-label="658 799 8941" aria-describedby="Phone_number2"
                                                            maxlength="10" pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="alternative_Phone_number">Alternative
                                                        Phone
                                                        No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="alternative_Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="alternative_Phone_number"
                                                            name="alternative_Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"  value="<?= $connector['alternative_Phone_number']; ?>" 
                                                            aria-label="658 799 8941"
                                                            aria-describedby="alternative_Phone_number2" maxlength="10"
                                                            pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="email_id"> Email</label>
                                                    <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="email" name="email_id" id="email_id"
                                                            class="form-control" placeholder="User Email"  value="<?= $connector['email_id']; ?>" 
                                                            aria-label="Partner Email" />
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="partnerType">Type
                                                        Of Connector</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <select id="partnerType" name="partnerType" class="form-select">
                                                            <option value="">Select Connector Type</option>
                                                           
                                                        <?php
                                                            $query = "SELECT id, partner_type FROM tbl_partner_type ORDER BY partner_type ASC";
                                                            $result = $conn->query($query);
                                                            while ($partner = $result->fetch_assoc()) {
                                                                $selected = ($partner['id'] == $connector['partnerType']) ? 'selected' : '';
                                                                echo '<option value="'.$partner['id'].'" '.$selected.'>'.$partner['partner_type'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="state">Branch State</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map-alt"></i></span>
                                                        <select id="state" name="state" class="form-select"
                                                            onchange="loadBranchLocation(this.value)">
                                                            <option value="">Select Branch State</option>
                                                            <?php
                                                            $query = "SELECT id, branch_state_name FROM tbl_branch_state ORDER BY branch_state_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($state = $result->fetch_assoc()) {
                                                                $selected = ($state['id'] == $connector['state']) ? 'selected' : '';
                                                                echo '<option value="'.$state['id'].'" '.$selected.'>'.$state['branch_state_name'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="location">Branch Location</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-map"></i></span>
                                                        <select id="location" name="location" class="form-select">
                                                            <option value="">Select Branch Location</option>
                                                            <?php
                                                        $query = "SELECT id, branch_location FROM tbl_branch_location WHERE branch_state_id = '".$connector['state']."' ORDER BY branch_location ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $connector['location']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['branch_location'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        

                                            <input type="submit" name="update_form" class="btn btn-primary mt-4"
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
        function loadBranchLocation(stateId) {
            if (stateId) {
                fetch("../info/get_branch_location", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "branch_state_id=" + stateId
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("location").innerHTML = data;
                    });
            } else {
                document.getElementById("location").innerHTML = '<option value="">Select Branch Location</option>';
            }
        }
    </script>
</body>

</html>

<?php
// Handle update
if (isset($_POST['update_form'])) {
    $connector_id = $_POST['connector_id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    // $alias_name = mysqli_real_escape_string($conn, $_POST['alias_name']);
    $Phone_number = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $alternative_Phone_number = mysqli_real_escape_string($conn, $_POST['alternative_Phone_number']);
    $email_id = mysqli_real_escape_string($conn, $_POST['email_id']);
    $partnerType = mysqli_real_escape_string($conn, $_POST['partnerType']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    $created_at = date('Y-m-d H:i:s');

    if (!empty($full_name)  && !empty($Phone_number) && !empty($partnerType)  && !empty($state) && !empty($location) ) {

        // Insert into database
       $sql = "INSERT INTO `tbl_connectors`(`full_name`, `Phone_number`, `alternative_Phone_number`, `email_id`, `partnerType`, `state`, `location`, `createdBy`,`created_at`) VALUES ('$full_name','$Phone_number','$alternative_Phone_number','$email_id','$partnerType','$state','$location','$loggedInUser','$created_at')";

       $sql = "UPDATE `tbl_connectors` SET `full_name`='$full_name',`Phone_number`='$Phone_number',`alternative_Phone_number`='$alternative_Phone_number',`email_id`='$email_id',`partnerType`='$partnerType',`state`='$state',`location`='$location',`updated_at`='$created_at' WHERE `id`='$connector_id'";


            if (mysqli_query($conn, $sql)) {
                echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Connectors Updated successfully!",
                    position: "topRight"
                });
                setTimeout(() => { window.location.href = "list"; }, 1000);
                </script>';
            } else {
                echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Failed to Update Connectors. Please try again.",
                    position: "topRight"
                });
                </script>';
            }
    } else {
        echo '<script>
        iziToast.warning({
            title: "Warning",
            message: "Please fill in all required fields.",
            position: "topRight"
        });
        </script>';
    }

}
?>