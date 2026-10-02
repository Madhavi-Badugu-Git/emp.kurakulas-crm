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
                                        <h5 class="mb-0">Add DataBase</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <!-- MOBILE NUMBER -->
                                            <div class="row">
                                                <div class="mb-6">
                                                    <label class="form-label" for="mobile_number">Mobile Number</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" class="form-control" name="mobile_number"
                                                            id="mobile_number" placeholder="658 799 8941" maxlength="10"
                                                            pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')"
                                                            required />
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- OTHER FIELDS (HIDDEN INITIALLY) -->
                                            <div id="otherFields" style="display: none;">
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="lead_name">Lead Name</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-user"></i></span>
                                                            <input type="text" class="form-control" name="lead_name"
                                                                id="lead_name" placeholder="Lead Name" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="email_id">Email Id</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-envelope"></i></span>
                                                            <input type="email" class="form-control" name="email_id"
                                                                id="email_id" placeholder="Email Id" />
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
                                                            <select id="company_name" name="company_name"
                                                                class="form-select">
                                                                <option value="">Select Company</option>
                                                                <?php
                                                            $query = "SELECT id, company_name FROM tbl_company_name ORDER BY company_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo '<option value="'.$row['id'].'">'.$row['company_name'].'</option>';
                                                            }
                                                            ?>
                                                            </select>
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
                                                                    class="bx bx-map-pin"></i></span>
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
                                                                    class="bx bx-compass"></i></span>
                                                            <select id="pin_code" name="pin_code" class="form-select">
                                                                <option value="">Select PIN Code</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="source"> Source</label><span
                                                            style="color:red;"> *</span>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-code"></i></span>
                                                            <select id="source" name="source" class="form-select">
                                                                <option value="">Select Source</option>
                                                                <?php
                                                               $query = "SELECT id, source FROM tbl_data_source ORDER BY source ASC";
                                                               $result = $conn->query($query);
                                                               while ($row = $result->fetch_assoc()) {
                                                                   echo '<option value="'.$row['id'].'">'.$row['source'].'</option>';
                                                               }
                                                           ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="customer_type"> Type Of
                                                            Customer</label><span style="color:red;"> *</span>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-group"></i></span>
                                                            <select id="customer_type" name="customer_type"
                                                                class="form-select"
                                                                >
                                                                <option value="">Select Type Of Customer</option>
                                                                <?php
                                                               $query = "SELECT id, customer_type FROM tbl_customer_type ORDER BY customer_type ASC";
                                                               $result = $conn->query($query);
                                                               while ($row = $result->fetch_assoc()) {
                                                                   echo '<option value="'.$row['id'].'">'.$row['customer_type'].'</option>';
                                                               }
                                                           ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                       

                                                    </div>

                                                    <!-- bank relation details -->
                                                    <div class="row mt-5">
                                                        <h5> Relationship With Bank</h5>
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead class="table-dark">
                                                                        <tr>
                                                                            <th style="width: 20%; min-width: 200px;">
                                                                                Bank Name
                                                                            </th>
                                                                            <th style="width: 20%; min-width: 200px;">
                                                                                Type of
                                                                                Loan</th>
                                                                            <th style="width: 15%; min-width: 150px;">
                                                                                Loan
                                                                                Amount</th>
                                                                            <th style="width: 15%; min-width: 150px;">
                                                                                ROI (%)
                                                                            </th>
                                                                            <th style="width: 15%; min-width: 150px;">
                                                                                Tenure
                                                                                (Months)</th>
                                                                            <th style="width: 15%; min-width: 150px;">
                                                                                EMI</th>
                                                                            <th style="width: 15%; min-width: 150px;">
                                                                                First EMI
                                                                                Date</th>
                                                                            <th style="width: 15%; min-width: 150px;">
                                                                                Last EMI
                                                                                Date</th>
                                                                            <th style="width: 20%; min-width: 200px;">
                                                                                Loan
                                                                                Account Number</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="loanTable">
                                                                        <tr>
                                                                            <td>

                                                                                <select id="bank_name"
                                                                                    name="bank_name[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Bank
                                                                                    </option>
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
                                                                                <select id="loan_type"
                                                                                    name="loan_type[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Loan
                                                                                    </option>
                                                                                    <?php
                                                                    $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['loan_type'].'</option>';
                                                                    }
                                                                ?>
                                                                                </select>
                                                                            </td>
                                                                            <td><input type="number"
                                                                                    class="form-control"
                                                                                    name="loan_amount[]" min="0"
                                                                                    placeholder="Amount"></td>
                                                                            <td>

                                                                                <select id="roi" name="roi[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select ROI</option>
                                                                                    <?php
                                                                    $query = "SELECT id, roi_name FROM tbl_roi ORDER BY roi_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['roi_name'].'</option>';
                                                                    }
                                                                ?>
                                                                            </td>
                                                                            <td>

                                                                                <select id="tenure" name="tenure[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Tenure
                                                                                    </option>
                                                                                    <?php
                                                                    $query = "SELECT id, tenure_name FROM tbl_tenure ORDER BY tenure_name ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['tenure_name'].'</option>';
                                                                    }
                                                                ?>
                                                                            </td>
                                                                            <td><input type="number"
                                                                                    class="form-control" name="emi[]"
                                                                                    min="0" placeholder="EMI"></td>
                                                                            <td><input type="date" class="form-control"
                                                                                    name="first_emi_date[]"></td>
                                                                            <td><input type="date" class="form-control"
                                                                                    name="last_emi_date[]"></td>
                                                                            <td><input type="text" class="form-control"
                                                                                    name="loan_account_name[]" placeholder="Account Number"></td>
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

                                                     <!-- Vehicle details -->
                                                     <div class="row mt-5">
                                                        <h5> Vehicle</h5>
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead class="table-dark">
                                                                        <tr>
                                                                            <th style="width: 20%; min-width: 200px;">
                                                                                Vehicle Number
                                                                            </th>
                                                                            <th style="width: 20%; min-width: 200px;">
                                                                                Make</th>
                                                                            <th style="width: 15%; min-width: 150px;">
                                                                                Modal</th>
                                                                            <th style="width: 15%; min-width: 150px;">
                                                                                MAN Year
                                                                            </th>
                                                                            <th style="width: 15%; min-width: 150px;">
                                                                                Engine Number</th>
                                                                            <th style="width: 15%; min-width: 150px;">
                                                                                Chases Number</th>
                                                                          
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="vehicleTable">
                                                                        <tr>
                                                                            <td>
                                                                            <input type="text" class="form-control"
                                                                            name="vehicle_number[]" placeholder="Vehicle Number">
                                                                            </td>
                                                                            <td>
                                                                                <select id="vehicle_make"
                                                                                    name="vehicle_make[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Vehicle Make
                                                                                    </option>
                                                                                    <?php
                                                                                    $query = "SELECT id, vehical_make FROM tbl_vehical_make ORDER BY vehical_make ASC";
                                                                                    $result = $conn->query($query);
                                                                                    while ($row = $result->fetch_assoc()) {
                                                                                        echo '<option value="'.$row['id'].'">'.$row['vehical_make'].'</option>';
                                                                                    }
                                                                                ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                            <select id="vehical_modal"
                                                                                    name="vehical_modal[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Vehicle Modal
                                                                                    </option>
                                                                                    <?php
                                                                                    $query = "SELECT id, vehical_modal FROM tbl_vehical_modal ORDER BY vehical_modal ASC";
                                                                                    $result = $conn->query($query);
                                                                                    while ($row = $result->fetch_assoc()) {
                                                                                        echo '<option value="'.$row['id'].'">'.$row['vehical_modal'].'</option>';
                                                                                    }
                                                                                ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>

                                                                                <select id="manufacture_year" name="manufacture_year[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select MAN Year</option>
                                                                                    <?php
                                                                                    $query = "SELECT id, manufacture_year FROM tbl_manufacture_year ORDER BY manufacture_year ASC";
                                                                                    $result = $conn->query($query);
                                                                                    while ($row = $result->fetch_assoc()) {
                                                                                        echo '<option value="'.$row['id'].'">'.$row['manufacture_year'].'</option>';
                                                                                    }
                                                                                ?>
                                                                            </td>
                                                                           
                                                                            <td><input type="text"
                                                                                    class="form-control" name="engine_number[]" placeholder="Engine No"
                                                                                    ></td>
                                                                            <td><input type="text" class="form-control"
                                                                                    name="chases_number[]" placeholder="Chases No"></td>
                                                                            
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
                                                </div>

                                                <input type="submit" name="form_submit" class="btn btn-primary mt-3"
                                                    value="Submit">
                                            </div>
                                        </form>

                                        <!-- EXISTING DATA TABLE -->
                                        <div id="mobileExistsContainer" style="display: none;" class="mt-3">
                                            <h6>Existing Records:</h6>
                                            <table class="table table-bordered" id="existingDataTable">
                                                <thead>
                                                    <tr>
                                                        <th>Mobile</th>
                                                        <th>Lead Name</th>
                                                        <th>Email</th>
                                                        <th>Company</th>
                                                        <th>Alt. Mobile</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include('../includes/footer.php'); ?>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <?php include('../includes/script.php'); ?>

    <script>
    let mobileExists = false;

    // Prevent form submit on Enter inside mobile input
    document.getElementById('mobile_number').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    // Check mobile existence on blur
    document.getElementById('mobile_number').addEventListener('blur', function() {
        const mobile = this.value.trim();
        if (mobile.length === 10) {
            fetch('check_mobile.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'mobile=' + mobile
                })
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('mobileExistsContainer');
                    const formSection = document.getElementById('otherFields');

                    if (data.length > 0) {
                        mobileExists = true;
                        container.style.display = 'block';
                        formSection.style.display = 'none';

                        let html = '<div class="row"><h5>DataBase Details</h5>';
                        data.forEach(row => {
                            html += `
                    <div class="col-md-6">
                        <table class="table table-borderless w-auto m-0">
                            <tbody>
                                <tr><td class="fw-bold">Mobile Number:</td><td>${row.mobile_number}</td></tr>
                                <tr><td class="fw-bold">Lead Name:</td><td>${row.lead_name}</td></tr>
                                <tr><td class="fw-bold">Email ID:</td><td>${row.email_id}</td></tr>
                                <tr><td class="fw-bold">Company Name:</td><td>${row.company_name}</td></tr>
                                <tr><td class="fw-bold">Alternative Mobile:</td><td>${row.alternative_mobile}</td></tr>
                            </tbody>
                        </table>
                    </div>`;
                        });
                        html += '</div>';
                        container.innerHTML = html;
                    } else {
                        mobileExists = false;
                        container.style.display = 'none';
                        formSection.style.display = 'block';
                    }
                });
        }
    });

    // Prevent form submission if mobile exists
    document.querySelector('form').addEventListener('submit', function(e) {
        if (mobileExists) {
            e.preventDefault();
            iziToast.warning({
                title: 'Error',
                message: 'Mobile number already exists!',
                position: 'topRight',
            });
        }
    });

    // bank relation table
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
                            <td><input type="number" class="form-control" name="emi[]" min="0" placeholder="EMI"></td>
                            <td><input type="date" class="form-control" name="first_emi_date[]"></td>
                            <td><input type="date" class="form-control" name="last_emi_date[]"></td>
                            <td><input type="text" class="form-control" name="loan_account_name[]" placeholder="Account Number"></td>
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
    // bank relation table

    // vehicle table 
    $(document).on("click", ".action-btn-1", function() {
        let btn = $(this);
        let row = btn.closest("tr");

        if (btn.hasClass("addRow-1")) {
            let newRow = `<tr>
                           <td>
                                                                            <input type="text" class="form-control"
                                                                            name="vehicle_number[]" placeholder="Vehicle Number">
                                                                            </td>
                                                                            <td>
                                                                                <select id="vehicle_make"
                                                                                    name="vehicle_make[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Vehicle Make
                                                                                    </option>
                                                                                    <?php
                                                                                    $query = "SELECT id, vehical_make FROM tbl_vehical_make ORDER BY vehical_make ASC";
                                                                                    $result = $conn->query($query);
                                                                                    while ($row = $result->fetch_assoc()) {
                                                                                        echo '<option value="'.$row['id'].'">'.$row['vehical_make'].'</option>';
                                                                                    }
                                                                                ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                            <select id="vehical_modal"
                                                                                    name="vehical_modal[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Vehicle Modal
                                                                                    </option>
                                                                                    <?php
                                                                                    $query = "SELECT id, vehical_modal FROM tbl_vehical_modal ORDER BY vehical_modal ASC";
                                                                                    $result = $conn->query($query);
                                                                                    while ($row = $result->fetch_assoc()) {
                                                                                        echo '<option value="'.$row['id'].'">'.$row['vehical_modal'].'</option>';
                                                                                    }
                                                                                ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>

                                                                                <select id="manufacture_year" name="manufacture_year[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select MAN Year</option>
                                                                                    <?php
                                                                                    $query = "SELECT id, manufacture_year FROM tbl_manufacture_year ORDER BY manufacture_year ASC";
                                                                                    $result = $conn->query($query);
                                                                                    while ($row = $result->fetch_assoc()) {
                                                                                        echo '<option value="'.$row['id'].'">'.$row['manufacture_year'].'</option>';
                                                                                    }
                                                                                ?>
                                                                            </td>
                                                                           
                                                                            <td><input type="text"
                                                                                    class="form-control" name="engine_number[]" placeholder="Engine No"
                                                                                    ></td>
                                                                            <td><input type="text" class="form-control"
                                                                                    name="chases_number[]" placeholder="Chases No"></td>
                            <td>
                                <button type="button" class="btn btn-primary action-btn-1 addRow-1">Add</button>
                            </td>
                        </tr>`;

            $("#vehicleTable").append(newRow);
            btn.removeClass("btn-primary addRow-1").addClass("btn-danger removeRow-1").text("Delete");
        } else if (btn.hasClass("removeRow-1")) {
            row.remove();
            updateLastRow();
        }
    });

    function updateLastRow() {
        let lastRow = $("#vehicleTable tr:last");
        lastRow.find(".action-btn-1").removeClass("btn-danger removeRow-1").addClass("btn-primary addRow-1").text(
            "Add");
    }
    // vehicle table


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
if (isset($_POST['form_submit'])) {
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
    $customer_type = $_POST['customer_type'];
    $created_at = date('Y-m-d H:i:s');

    if (empty($mobile_number)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Mobile Number is required",
                position: "topRight",
            });
        </script>';
        exit();
    }

    $check = mysqli_query($conn, "SELECT * FROM tbl_database WHERE mobile_number='$mobile_number' AND status='1'");
    if (mysqli_num_rows($check) > 0) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Mobile Number already exists",
                position: "topRight",
            });
        </script>';
        exit();
    }

    $sql = "INSERT INTO `tbl_database`(`mobile_number`,`lead_name`,`email_id`,`company_name`,`alternative_mobile`,`state`,`location`,`sub_location`,`pin_code`,`source`,`customer_type`,`created_at`) 
            VALUES ('$mobile_number','$lead_name','$email_id','$company_name','$alternative_mobile','$state','$location','$sub_location','$pin_code','$source','$customer_type','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "DataBase Added Successfully",
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
        </script>';
    }
}
?>