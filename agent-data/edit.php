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

$agent_id = $_GET['id'];


$query = "SELECT * FROM tbl_agent_data WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Connector not found!"); window.location.href="list";</script>';
    exit();
}

$agent = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Agent Data</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="agent_id" value="<?= $agent['id']; ?>">

                                            <div class="row mt-0">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="full_name">Full
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="full_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="full_name"
                                                            id="full_name" placeholder="Full Name"
                                                            value="<?= $agent['full_name']; ?>" aria-label="Full Name"
                                                            aria-describedby="full_name2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="company_name">Company
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="company_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="company_name"
                                                            id="company_name" value="<?= $agent['company_name']; ?>"
                                                            placeholder="Company Name" aria-label="Company Name"
                                                            aria-describedby="company_name2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number"> Phone
                                                        No</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="Phone_number" name="Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            value="<?= $agent['Phone_number']; ?>"
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
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            value="<?= $agent['alternative_Phone_number']; ?>"
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
                                                            class="form-control" placeholder="User Email"
                                                            value="<?= $agent['email_id']; ?>"
                                                            aria-label="Partner Email" />
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="partnerType">Type
                                                        Of Partner</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <select id="partnerType" name="partnerType" class="form-select">
                                                            <option value="">Select Partner Type</option>

                                                            <?php
                                                            $query = "SELECT id, partner_type FROM tbl_partner_type ORDER BY partner_type ASC";
                                                            $result = $conn->query($query);
                                                            while ($partner = $result->fetch_assoc()) {
                                                                $selected = ($partner['id'] == $agent['partnerType']) ? 'selected' : '';
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
                                                                $selected = ($state['id'] == $agent['state']) ? 'selected' : '';
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
                                                        $query = "SELECT id, branch_location FROM tbl_branch_location WHERE branch_state_id = '".$agent['state']."' ORDER BY branch_location ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $agent['location']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['branch_location'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="address">Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"> <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="address" id="address" class="form-control"
                                                            rows="1" placeholder="Address"><?= $agent['address']; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="visiting_card" class="form-label">Visiting Card
                                                    </label>
                                                    <input class="form-control" type="file" id="visiting_card"
                                                        name="visiting_card" accept="image/*,application/pdf">
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
    $agent_id = $_POST['agent_id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $Phone_number = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $alternative_Phone_number = mysqli_real_escape_string($conn, $_POST['alternative_Phone_number']);
    $email_id = mysqli_real_escape_string($conn, $_POST['email_id']);
    $partnerType = mysqli_real_escape_string($conn, $_POST['partnerType']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $updated_at = date('Y-m-d H:i:s');

    // Get the existing visiting card from DB
    $query = mysqli_query($conn, "SELECT visiting_card FROM tbl_agent_data WHERE id = '$agent_id'");
    $row = mysqli_fetch_assoc($query);
    $visiting_card = $row['visiting_card']; // Default to existing file

    // **File Upload Handling**
    if (isset($_FILES['visiting_card']) && $_FILES['visiting_card']['error'] == 0) {
        $path = '../uploads/agent-data/'; // Upload directory
        $valid_extensions = ['jpeg', 'jpg', 'png', 'pdf']; // Allowed formats
        $filename = $_FILES['visiting_card']['name'];
        $tmp = $_FILES['visiting_card']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $valid_extensions)) {
            $final_image = time() . '-' . strtolower(str_replace(' ', '-', $filename));
            if (move_uploaded_file($tmp, $path . $final_image)) {
                $visiting_card = $final_image; // Store new file name
            }
        } else {
            echo '<script>
            iziToast.error({
                title: "Error",
                message: "Invalid file format. Only JPG, JPEG, PNG, and PDF allowed.",
                position: "topRight"
            });
            </script>';
            exit;
        }
    }

    // **Update the database**
    $sql = "UPDATE `tbl_agent_data` SET 
        `full_name`='$full_name', 
        `company_name`='$company_name', 
        `Phone_number`='$Phone_number', 
        `alternative_Phone_number`='$alternative_Phone_number', 
        `email_id`='$email_id', 
        `partnerType`='$partnerType', 
        `state`='$state', 
        `location`='$location', 
        `address`='$address', 
        `visiting_card`='$visiting_card', 
        `updated_at`='$updated_at' 
        WHERE `id`='$agent_id'";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
        iziToast.success({
            title: "Success",
            message: "Agent Data Updated Successfully!",
            position: "topRight"
        });
        setTimeout(() => { window.location.href = "list"; }, 1000);
        </script>';
    } else {
        echo '<script>
        iziToast.error({
            title: "Error",
            message: "Failed to Update Agent Data. Please try again.",
            position: "topRight"
        });
        </script>';
    }
}
?>
