<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

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
                                        <h5 class="mb-0">Add Pincode</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
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
                                                                    echo '<option value="'.$row['id'].'">'.$row['state_name'].'</option>';
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
                                                        <select id="location" name="location" class="form-select" onchange="getLocationName(this.value)">
                                                            <option value="">Select Location</option>

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
                                                                <select id="sub_location" name="sub_location" class="form-select"
                                                            >
                                                            <option value="">Select State</option>
                                                            </select>
                                                       
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="pin_code"> PIN Code
                                                        </label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-pin"></i></span>
                                                            
                                                        <input type="text" class="form-control" name="pin_code"
                                                            id="pin_code" placeholder="PIN Code" maxlength="6"
                                                            pattern="[0-9]{6}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <div class="text-end"> -->
                                            <input type="submit" name="form_submit" class="btn btn-primary mt-3" value="Submit">
                                            <!-- </div> -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">PIN Code List</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>PIN Code</th>
                                                    <th>State</th>
                                                    <th>Location</th>
                                                    <th>Sub Location</th>
                                                    <th>STATUS</th>
                                                    <th>ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Fetch department data
                                                $sql = mysqli_query($conn, "SELECT * FROM tbl_pincode WHERE status='1' ORDER BY state_id, location_id, sub_location_id, pincode ASC");

                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $status = $row['status'];
                                                ?>
                                                <tr>
                                                    <td><?= $row['pincode']; ?></td>
                                                    <td><?= getStateName($conn, $row['state_id']); ?></td>
                                                    <td><?= getLocationName($conn, $row['location_id']); ?></td>
                                                    <td><?= getSubLocation($conn, $row['sub_location_id']); ?></td>

                                                    <td>
                                                        <?php if ($status == 1) { ?>
                                                        <span class="badge bg-primary">Active</span>
                                                        <?php } else { ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="edit.php?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="delete.php?id=<?= $row['id']; ?>"
                                                                        onclick="return confirm('Are you sure?');"><i
                                                                            class="bx bx-trash"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
        }
    }
    ?>
                                            </tbody>
                                        </table>
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

         // location name
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
    </script>
</body>

</html>
<?php

if (isset($_POST['form_submit'])) {
     $state = $_POST['state'];
     $location = $_POST['location'];
     $sub_location = $_POST['sub_location'];
     $pin_code = $_POST['pin_code'];
   
     $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($location) || empty($state) || empty($sub_location) || empty($pin_code)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else{

   

    // Insert into database
    // $sql = "INSERT INTO `tbl_sub_location`(`sub_location`, `state_id`,`location_id`,`created_at`) 
    //         VALUES ('$sub_location','$state','$location','$created_at')";

            $sql = "INSERT INTO `tbl_pincode`(`pincode`, `state_id`, `location_id`, `sub_location_id`, `created_at`) VALUES ('$pin_code','$state','$location','$sub_location','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: " PIN Code Added Successfully",
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

}
?>