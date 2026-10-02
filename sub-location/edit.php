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

$subLocation_id = $_GET['id'];

// Fetch location details
$query = "SELECT * FROM tbl_sub_location WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $subLocation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Sub Location not found!"); window.location.href="add";</script>';
    exit();
}

$subLocation = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Sub Location</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="subLocation_id" value="<?= $subLocation['id']; ?>">
                                            <div class="row">
                                            <div class="col-md-6">
                                            <label class="form-label" for="state"> State</label><span
                                            style="color:red;"> *</span>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-briefcase"></i></span>
                                                    <select id="state" name="state" class="form-select" onchange="getStateName(this.value)">
                                                        <option value="">Select State</option>
                                                        <?php
                                                        $query = "SELECT id, state_name FROM tbl_state ORDER BY state_name ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $subLocation['state_id']) ? 'selected' : ''; // Preselect the department
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
                                                        <select id="location" name="location" class="form-select">
                                                            <option value="">Select Location</option>

                                                        <?php
                                                        $query = "SELECT id, location FROM tbl_location WHERE state_id = '".$subLocation['state_id']."' ORDER BY location ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $subLocation['location_id']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['location'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="sub_location"> Sub
                                                        Location</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map-pin"></i></span>
                                                        <input type="text" class="form-control" name="sub_location"
                                                            id="sub_location" value="<?= $subLocation['sub_location']; ?>" placeholder="Sub Location" />
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="submit" name="update_location" class="btn btn-primary mt-3" value="Update">
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
        // state name
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
    </script>
</body>
</html>

<?php
// Handle update
if (isset($_POST['update_location'])) {
    $subLocation_id = $_POST['subLocation_id'];
    $state = $_POST['state'];
    $location = $_POST['location'];
    $sub_location = $_POST['sub_location'];

    $created_at = date('Y-m-d H:i:s'); // Date format (Y-m-d H:i:s)

    // echo $created_at;
    // exit();

    // Validate required fields
    if (empty($state) || empty($location) || empty($sub_location)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else {
        // Update query with 5 placeholders, including updated_at
        $sql = "UPDATE tbl_sub_location SET sub_location = ?, state_id = ?, location_id = ?, updated_at = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters: "siiis" for string, int, int, string (updated_at as string), and int (subLocation_id as integer)
        $stmt->bind_param("siiis", $sub_location, $state, $location, $created_at, $subLocation_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Sub Location Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="add"; }, 1000);
            </script>';
        } else {
            // Log the error in case something goes wrong
            error_log("Error executing update query: " . $stmt->error);
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
}


?>
