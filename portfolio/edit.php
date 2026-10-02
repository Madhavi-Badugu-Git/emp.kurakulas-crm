<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="list";</script>';
    exit();
}

$portfolio_id = $_GET['id'];

// Fetch location details
$query = "SELECT * FROM tbl_portfolio WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Portfolio not found!"); window.location.href="list";</script>';
    exit();
}

$portfolio = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Portfolio</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="portfolio_id" value="<?= $portfolio['id']; ?>">
                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="customer_name">Customer
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="customer_name"
                                                            id="customer_name" value="<?= $portfolio['customer_name']; ?>" placeholder="Customer Name" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">

                                                <div class="col-md-6">
                                                    <label class="form-label" for="company_name"> Company
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <input type="text" class="form-control" name="company_name"
                                                            id="company_name" value="<?= $portfolio['company_name']; ?>" placeholder="Company Name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number"> Phone
                                                        No</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="Phone_number" name="Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941" value="<?= $portfolio['Phone_number']; ?>"
                                                            aria-label="658 799 8941" aria-describedby="Phone_number2"
                                                            maxlength="10" pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="alternative_Phone_number">Alternative
                                                        Phone
                                                        No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="alternative_Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="alternative_Phone_number"
                                                            name="alternative_Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941" value="<?= $portfolio['alternative_Phone_number']; ?>"
                                                            aria-label="658 799 8941"
                                                            aria-describedby="alternative_Phone_number2" maxlength="10"
                                                            pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="email_id"> Email</label>

                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="email" name="email_id" id="email_id"
                                                            class="form-control" placeholder="User Email" value="<?= $portfolio['email_id']; ?>"
                                                            aria-label="Partner Email" />
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
                                                            $selected = ($row['id'] == $portfolio['state']) ? 'selected' : ''; // Preselect the department
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
                                                                class="bx bx-id-card"></i></span>
                                                        <select id="location" name="location" class="form-select"
                                                            onchange="getLocationName(this.value)">
                                                            <option value="">Select Location</option>
                                                            <?php
                                                        $query = "SELECT id, location FROM tbl_location WHERE state_id = '".$portfolio['state']."' ORDER BY location ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $portfolio['location']) ? 'selected' : ''; // Corrected condition
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
                                                                class="bx bx-building"></i></span>
                                                        <select id="sub_location" name="sub_location"
                                                            class="form-select"
                                                            onchange="getsubLocationName(this.value)">
                                                            <option value="">Select Sub Location</option>
                                                            <?php
                                                        $query = "SELECT id, sub_location FROM tbl_sub_location WHERE location_id = '".$portfolio['location']."' ORDER BY sub_location ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $portfolio['sub_location']) ? 'selected' : ''; // Corrected condition
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
                                                                class="bx bx-building"></i></span>
                                                        <select id="pin_code" name="pin_code" class="form-select">
                                                            <option value="">Select PIN Code</option>
                                                            <?php
                                                        $query = "SELECT id, pincode FROM tbl_pincode WHERE sub_location_id = '".$portfolio['sub_location']."' ORDER BY pincode ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $portfolio['pin_code']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['pincode'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="customer_type">Type Of Customer
                                                    </label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="customer_type2" class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="customer_type" name="customer_type"
                                                            class="form-select" onchange="loadCustomerType(this.value)">
                                                            <option value="">Select Customer Type</option>
                                                            
                                                         <?php
                                                        $query = "SELECT id, customer_type FROM tbl_customer_type ORDER BY customer_type ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $portfolio['customer_type']) ? 'selected' : ''; // Preselect the department
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['customer_type'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="industry_type"> Industry
                                                        Type</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-buildings"></i></span>
                                                        <select id="industry_type" name="industry_type"
                                                            class="form-select" onchange="loadIndustryType(this.value)">
                                                            <option value="">Select Industry Type</option>
                                                            <?php
                                                        $query = "SELECT id, industry_name FROM tbl_industry_type WHERE customer_id = '".$portfolio['customer_type']."' ORDER BY industry_name ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $portfolio['industry_type']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['industry_name'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="business_type"> Business Type
                                                    </label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-buildings"></i></span>

                                                        <select id="business_type" name="business_type"
                                                            class="form-select">
                                                            <option value="">Select Business Type</option>
                                                            <?php
                                                        $query = "SELECT id, business_name FROM tbl_business_type WHERE industry_id = '".$portfolio['industry_type']."' ORDER BY business_name ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $portfolio['business_type']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['business_name'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <label class="form-label" for="birth_date">Date of
                                                        Birth</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="text" class="form-control" name="birth_date"
                                                            id="birth_date" value="<?= $portfolio['birth_date']; ?>" placeholder="DD/MM/YYYY"
                                                            aria-describedby="birth_date2" />
                                                    </div>
                                                </div>
                                            </div>
                                           <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="address"> Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text">
                                                            <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="address" id="address"
                                                            class="form-control"
                                                            placeholder=" Address"><?= $portfolio['address']; ?></textarea>
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
        // industry
        function loadCustomerType(customerId) {
            if (customerId) {
                fetch("../info/get_industry_type", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "customer_id=" + customerId
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("industry_type").innerHTML = data;
                    });
            } else {
                document.getElementById("industry_type").innerHTML = '<option value="">Select Industry Type</option>';
            }
        }
        // business
        function loadIndustryType(industryId) {
            if (industryId) {
                fetch("../info/get_business_type", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "industry_id=" + industryId
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("business_type").innerHTML = data;
                    });
            } else {
                document.getElementById("business_type").innerHTML = '<option value="">Select Business Type</option>';
            }
        }
        // date of birth 
    document.addEventListener("DOMContentLoaded", function() {
        let dateInput = document.getElementById("birth_date");

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
    $portfolio_id = mysqli_real_escape_string($conn, $_POST['portfolio_id']);
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $Phone_number = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $alternative_Phone_number = mysqli_real_escape_string($conn, $_POST['alternative_Phone_number']);
    $email_id = mysqli_real_escape_string($conn, $_POST['email_id']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $sub_location = mysqli_real_escape_string($conn, $_POST['sub_location']);
    $pin_code = mysqli_real_escape_string($conn, $_POST['pin_code']);
    $customer_type = mysqli_real_escape_string($conn, $_POST['customer_type']);
    $industry_type = mysqli_real_escape_string($conn, $_POST['industry_type']);
    $business_type = mysqli_real_escape_string($conn, $_POST['business_type']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $updated_at = date('Y-m-d H:i:s'); // Current timestamp

    // Validate required fields
    if (
        empty($customer_name) || empty($company_name) || empty($Phone_number) || empty($state) ||
        empty($location) || empty($sub_location) || empty($pin_code) || empty($customer_type) ||
        empty($industry_type) || empty($business_type)
    ) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    }

    // Update query
    $sql = "UPDATE `tbl_portfolio` 
            SET `customer_name` = ?, 
                `company_name` = ?, 
                `Phone_number` = ?, 
                `alternative_Phone_number` = ?, 
                `email_id` = ?, 
                `state` = ?, 
                `location` = ?, 
                `sub_location` = ?, 
                `pin_code` = ?, 
                `customer_type` = ?, 
                `industry_type` = ?, 
                `business_type` = ?, 
                `birth_date` = ?,
                `address` = ?,
                `updated_at` = ? 
            WHERE `id` = ?";

    $stmt = $conn->prepare($sql);

    // Corrected bind_param() with 15 strings + 1 integer
    $stmt->bind_param(
        "sssssssssssssssi",  // 15 strings + 1 integer
        $customer_name, $company_name, $Phone_number, $alternative_Phone_number, 
        $email_id, $state, $location, $sub_location, $pin_code, $customer_type, 
        $industry_type, $business_type, $birth_date, $address, $updated_at, $portfolio_id // 1 integer at the end
    );

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Portfolio Updated Successfully!",
                position: "topRight"
            });
            setTimeout(() => { window.location.href="list"; }, 1000);
        </script>';
    } else {
        // Log error
        error_log("Error executing update query: " . $stmt->error);
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Something went wrong. Please try again.",
                position: "topRight"
            });
        </script>';
    }
}
?>

