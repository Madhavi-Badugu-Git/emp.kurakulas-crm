<?php 
session_start(); // ✅ Ensure this is at the top!
// print_r($_SESSION); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 

// echo $loggedInUser;
// exit();
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>

<body>


    <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">

            <!-- side menu -->
            <?php include('../includes/sideMenu.php'); ?>
            <!-- side menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">


                        <!-- Basic Layout -->
                        <div class="row">

                            <div class="col-xl">
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Add Portfolio </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="customer_name">Customer
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="customer_name"
                                                            id="customer_name" placeholder="Customer Name" />
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
                                                            id="company_name" placeholder="Company Name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number"> Phone
                                                        No</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="Phone_number" name="Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
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
                                                            class="form-control phone-mask" placeholder="658 799 8941"
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
                                                            class="form-control" placeholder="User Email"
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
                                                                class="bx bx-id-card"></i></span>
                                                        <select id="location" name="location" class="form-select"
                                                            onchange="getLocationName(this.value)">
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
                                                                class="bx bx-building"></i></span>
                                                        <select id="sub_location" name="sub_location"
                                                            class="form-select"
                                                            onchange="getsubLocationName(this.value)">
                                                            <option value="">Select Sub Location</option>
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
                                                        $query = "SELECT id, customer_type FROM tbl_customer_type WHERE status = 1 ORDER BY customer_type ASC;";
                                                        $result = $conn->query($query);
                                                        
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo '<option value="'.$row['id'].'">'.$row['customer_type'].'</option>'; // Corrected key
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
                                                            id="birth_date" placeholder="DD/MM/YYYY"
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
                                                            placeholder=" Address"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- document table details -->
                                            <div class="row mt-5">
                                                <h5>Document Details</h5>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th style="width: 50%; min-width: 500px;">Document
                                                                        Name
                                                                    </th>
                                                                    <th style="width: 35%; min-width: 350px;">File
                                                                        Upload</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="documentTable">
                                                                <tr>
                                                                    <td><input type="text" class="form-control"
                                                                            name="document_name[]" min="0"
                                                                            placeholder="Document Name"></td>
                                                                    <td><input type="file" class="form-control"
                                                                            name="upload_file[]" accept=".jpg,.jpeg,.png,.pdf"></td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-primary action-btn-1 addRow-1">Add</button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- loan table details -->
                                            <div class="row mt-5">
                                                <h5>Fill all Details</h5>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th style="width: 20%; min-width: 200px;">Bank Name
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">Type of
                                                                        Loan</th>
                                                                    <th style="width: 15%; min-width: 150px;">Loan
                                                                        Amount</th>
                                                                    <th style="width: 15%; min-width: 150px;">ROI (%)
                                                                    </th>
                                                                    <th style="width: 15%; min-width: 150px;">Tenure
                                                                        (Months)</th>
                                                                    <th style="width: 15%; min-width: 150px;">EMI</th>
                                                                    <th style="width: 15%; min-width: 150px;">First EMI
                                                                        Date</th>
                                                                    <th style="width: 15%; min-width: 150px;">Last EMI
                                                                        Date</th>
                                                                    <th style="width: 20%; min-width: 200px;">Loan
                                                                        Account Number</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="loanTable">
                                                                <tr>
                                                                    <td>

                                                                        <select id="bank_name" name="bank_name[]"
                                                                            class="form-select">
                                                                            <option value="">Select Bank </option>
                                                                            <?php
                                                                    $query = "SELECT id, bank_name FROM tbl_portfolio_bank ORDER BY bank_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                    }
                                                                ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select id="loan_type" name="loan_type[]"
                                                                            class="form-select">
                                                                            <option value="">Select Loan</option>
                                                                            <?php
                                                                    $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['loan_type'].'</option>';
                                                                    }
                                                                ?>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number" class="form-control"
                                                                            name="loan_amount[]" min="0"
                                                                            placeholder="Amount"></td>
                                                                    <td>
                                                                        
                                                                        <select id="roi" name="roi[]"
                                                                            class="form-select">
                                                                            <option value="">Select ROI</option>
                                                                            <?php
                                                                    $query = "SELECT id, roi_name FROM tbl_roi ORDER BY CAST(roi_name AS UNSIGNED) ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['roi_name'].'</option>';
                                                                    }
                                                                ?>
                                                                    </td>
                                                                    <td>
                                                                        <!-- <input type="number" class="form-control"
                                                                            name="tenure[]" min="1"> -->
                                                                        <select id="tenure" name="tenure[]"
                                                                            class="form-select">
                                                                            <option value="">Select Tenure</option>
                                                                            <?php
                                                                    $query = "SELECT id, tenure_name FROM tbl_tenure ORDER BY CAST(tenure_name AS UNSIGNED) ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['tenure_name'].'</option>';
                                                                    }
                                                                ?>
                                                                    </td>
                                                                    <td><input type="number" class="form-control"
                                                                            name="emi[]" min="0"></td>
                                                                    <td><input type="date" class="form-control"
                                                                            name="first_emi_date[]"></td>
                                                                    <td><input type="date" class="form-control"
                                                                            name="last_emi_date[]"></td>
                                                                    <td><input type="text" class="form-control"
                                                                            name="loan_account_name[]"></td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-primary action-btn addRow">Add</button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                           

                                            <div class="text-end">
                                                <input type="submit" name="form_submit" value="Submit"
                                                    class="btn btn-primary mt-2">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include('../includes/footer.php'); ?>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

    </div>
    <!-- / Layout wrapper -->
    <?php include('../includes/script.php'); ?>
    <script>
    $(document).ready(function() {
        // upload document table
        $(document).on("click", ".action-btn-1", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addRow-1")) {
                let newRow = `<tr>
                        <td><input type="text" class="form-control" name="document_name[]" placeholder="Document Name"></td>
                        <td><input type="file" class="form-control" name="upload_file[]" accept=".jpg,.jpeg,.png,.pdf"></td>
                        <td>
                            <button type="button" class="btn btn-primary action-btn-1 addRow-1">Add</button>
                        </td>
                    </tr>`;

                $("#documentTable").append(newRow);
                btn.removeClass("btn-primary addRow-1").addClass("btn-danger removeRow-1").text(
                    "Delete");
            } else if (btn.hasClass("removeRow-1")) {
                row.remove();
                updateLastRow();
            }
        });

        function updateLastRow() {
            let lastRow = $("#documentTable tr:last");
            lastRow.find(".action-btn-1").removeClass("btn-danger removeRow-1").addClass("btn-primary addRow-1")
                .text("Add");
        }

        // loan table
        $(document).on("click", ".action-btn", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addRow")) {
                let newRow = `<tr>
                            <td>
                                <select name="bank_name[]" class="form-select">
                                    <option value="">Select Bank</option>
                                    <?php
                                        $query = "SELECT id, bank_name FROM tbl_portfolio_bank ORDER BY bank_name ASC";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="loan_type[]" class="form-select">
                                    <option value="">Select Loan</option>
                                    <?php
                                        $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="'.$row['id'].'">'.$row['loan_type'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td><input type="number" class="form-control" name="loan_amount[]" min="0" placeholder="Amount"></td>
                            <td> <select id="roi" name="roi[]"
                                                                            class="form-select">
                                                                            <option value="">Select ROI</option>
                                                                            <?php
                                                                    $query = "SELECT id, roi_name FROM tbl_roi ORDER BY roi_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['roi_name'].'</option>';
                                                                    }
                                                                ?></td>
                            <td> <select id="tenure" name="tenure[]"
                                                                            class="form-select">
                                                                            <option value="">Select Tenure</option>
                                                                            <?php
                                                                    $query = "SELECT id, tenure_name FROM tbl_tenure ORDER BY tenure_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['tenure_name'].'</option>';
                                                                    }
                                                                ?></td>
                            <td><input type="number" class="form-control" name="emi[]" min="0"></td>
                            <td><input type="date" class="form-control" name="first_emi_date[]"></td>
                            <td><input type="date" class="form-control" name="last_emi_date[]"></td>
                            <td><input type="text" class="form-control" name="loan_account_name[]"></td>
                            <td>
                                <button type="button" class="btn btn-primary action-btn addRow">Add</button>
                            </td>
                        </tr>`;

                $("#loanTable").append(newRow);
                btn.removeClass("btn-primary addRow").addClass("btn-danger removeRow").text("Delete");
            } else if (btn.hasClass("removeRow")) {
                row.remove();
                updateLastRow();
            }
        });

        function updateLastRow() {
            let lastRow = $("#loanTable tr:last");
            lastRow.find(".action-btn").removeClass("btn-danger removeRow").addClass("btn-primary addRow").text(
                "Add");
        }
    });

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
if (isset($_POST['form_submit'])) {
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
    $created_at = date('Y-m-d H:i:s');

    if (!empty($customer_name) && !empty($company_name) && !empty($Phone_number) && !empty($state) && !empty($location) && !empty($sub_location) && !empty($pin_code) && !empty($customer_type) && !empty($industry_type) && !empty($business_type)) {
       
        // Insert into tbl_portfolio
        $sql = "INSERT INTO `tbl_portfolio`(`customer_name`,`company_name`, `Phone_number`, `alternative_Phone_number`, `email_id`, `state`, `location`, `sub_location`, `pin_code`,`customer_type`,`industry_type`,`business_type`,`birth_date`,`address`,`createdBy`, `created_at`) 
                VALUES ('$customer_name','$company_name','$Phone_number','$alternative_Phone_number','$email_id','$state','$location','$sub_location','$pin_code','$customer_type','$industry_type','$business_type','$birth_date','$address','$loggedInUser','$created_at')";

        if (mysqli_query($conn, $sql)) {
            $last_id = mysqli_insert_id($conn); // Get last inserted portfolio ID
            
            // Insert loan details into tbl_portfolio_loan_details
            if (!empty($_POST['bank_name'])) {
                foreach ($_POST['bank_name'] as $key => $bank_name) {
                    $loan_type = mysqli_real_escape_string($conn, $_POST['loan_type'][$key]);
                    $loan_amount = mysqli_real_escape_string($conn, $_POST['loan_amount'][$key]);
                    $roi = mysqli_real_escape_string($conn, $_POST['roi'][$key]);
                    $tenure = mysqli_real_escape_string($conn, $_POST['tenure'][$key]);
                    $emi = mysqli_real_escape_string($conn, $_POST['emi'][$key]);
                    $first_emi_date = mysqli_real_escape_string($conn, $_POST['first_emi_date'][$key]);
                    $last_emi_date = mysqli_real_escape_string($conn, $_POST['last_emi_date'][$key]);
                    $loan_account_name = mysqli_real_escape_string($conn, $_POST['loan_account_name'][$key]);

                    $loan_sql = "INSERT INTO `tbl_portfolio_loan_details`(`portfolio_id`, `bank_name`, `loan_type`, `loan_amount`, `roi`, `tenure`, `emi`, `first_emi_date`, `last_emi_date`, `loan_account_name`, `created_at`) 
                                 VALUES ('$last_id', '$bank_name', '$loan_type', '$loan_amount', '$roi', '$tenure', '$emi', '$first_emi_date', '$last_emi_date', '$loan_account_name', '$created_at')";
                    
                    mysqli_query($conn, $loan_sql);
                }
            }

            // Handle document uploads with file type validation
            if (!empty($_POST['document_name']) && isset($_FILES['upload_file'])) {
                $uploadDir = "../uploads/portfolio/";
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf']; // Allowed file types

                foreach ($_POST['document_name'] as $index => $document_name) {
                    if (!empty($_FILES['upload_file']['name'][$index])) {
                        $fileExtension = strtolower(pathinfo($_FILES['upload_file']['name'][$index], PATHINFO_EXTENSION));

                        if (in_array($fileExtension, $allowedExtensions)) {
                            $fileName = time() . "_" . basename($_FILES['upload_file']['name'][$index]);
                            $filePath = $uploadDir . $fileName; // Full path for file upload

                            if (move_uploaded_file($_FILES['upload_file']['tmp_name'][$index], $filePath)) {
                                // Store only the file name in the database
                                $doc_sql = "INSERT INTO `tbl_portfolio_documents_details`(`portfolio_id`, `document_name`, `upload_file`,`created_at`) 
                                            VALUES ('$last_id', '$document_name', '$fileName','$created_at')";
                                mysqli_query($conn, $doc_sql);
                            }
                        } else {
                            echo '<script>
                            iziToast.error({
                                title: "Error",
                                message: "Invalid file type. Only images and PDFs are allowed.",
                                position: "topRight"
                            });
                            </script>';
                        }
                    }
                }
            }

            echo '<script>
            iziToast.success({
                title: "Success",
                message: "Portfolio, Loan, and Document Details Added Successfully!",
                position: "topRight"
            });
            setTimeout(() => { window.location.href = "list"; }, 1000);
            </script>';
        } else {
            echo '<script>
            iziToast.error({
                title: "Error",
                message: "Failed to Add Portfolio. Please try again.",
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
