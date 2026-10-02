<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 
// echo $loggedInUser;
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

                                            <div class="row align-items-end">
                                                <!-- Mobile Number Input -->
                                                <div class="col-md-6">
                                                    <label class="form-label" for="mobile_number">Mobile
                                                        Number</label><span style="color:red;"> *</span>
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

                                                <!-- Submit Button -->
                                                <div class="col-md-1 text-end">
                                                    <button type="button" id="checkMobileBtn"
                                                        class="btn btn-primary w-100">Submit</button>
                                                </div>
                                                <div id="mobileStatusMessage" class="mt-1"
                                                    style="display:none; font-weight: 500;"></div>
                                            </div>

                                            <!-- OTHER FIELDS (HIDDEN INITIALLY) -->
                                            <div id="otherFields" style="display: none;">
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="lead_name"> Name</label><span
                                                            style="color:red;"> *</span>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-user"></i></span>
                                                            <input type="text" class="form-control" name="lead_name"
                                                                id="lead_name" placeholder=" Name" />
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

                                                            <input type="text" class="form-control" name="company_name"
                                                                id="company_name" placeholder="Company Name" />
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
                                                        <label class="form-label" for="state"> State</label>
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
                                                        <label class="form-label" for="location"> Location</label>
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
                                                            Location</label>
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
                                                        <label class="form-label" for="pin_code"> PIN Code</label>
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
                                                        <label class="form-label" for="source"> Source</label>
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

                                                            <input type="text" class="form-control"
                                                                name="user_qualification" id="user_qualification"
                                                                placeholder="Qualification" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="residental_address">Residential
                                                            Address</label><span style="color:red;"> *</span>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-map"></i></span>
                                                            <textarea name="residental_address" id="residental_address"
                                                                rows="1" class="form-control"
                                                                placeholder="Residential Address"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- customer type dropdown -->
                                                <div class="row mt-3">
                                                    <div class="mb-6">
                                                        <label class="form-label" for="customer_type">Type Of
                                                            Customer</label><span style="color:red;"> *</span>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-group"></i></span>
                                                            <select id="customer_type" name="customer_type"
                                                                class="form-select">
                                                                <option value="">Select Type Of Customer</option>
                                                                <?php
                                                                $query = "SELECT id, customer_type FROM tbl_customer_type ORDER BY customer_type ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'" data-text="'.$row['customer_type'].'">'.$row['customer_type'].'</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- customer type dropdown -->

                                                <!-- Fields for Salaried-SAL -->
                                                <div id="salaried_fields" style="display: none;">
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="sal_company_type">Type Of
                                                                Company</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-buildings"></i></span>
                                                                <select id="sal_company_type" name="sal_company_type"
                                                                    class="form-select">
                                                                    <option value="">Select Type Of Company</option>
                                                                    <?php
                                                                        $query = "SELECT id, company_type FROM tbl_company_type ORDER BY company_type ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['company_type'].'">'.$row['company_type'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="sal_birth_date">Date of
                                                                Birth</label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="birth_date2" class="input-group-text"><i
                                                                        class="bx bx-calendar"></i></span>
                                                                <input type="text" class="form-control"
                                                                    name="sal_birth_date" id="sal_birth_date"
                                                                    placeholder="DD/MM/YYYY"
                                                                    aria-describedby="birth_date2" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="sal_grossSalary">Gross
                                                                Salary</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-dollar-circle"></i></span>
                                                                <select id="sal_grossSalary" name="sal_grossSalary"
                                                                    class="form-select">
                                                                    <option value="">Select Gross Salary</option>
                                                                    <?php
                                                                        $query = "SELECT id, grossSalary FROM tbl_senp_grosssalary ORDER BY grossSalary ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['grossSalary'].'">'.$row['grossSalary'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label"
                                                                for="gross_sal_amount">Amount</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-money"></i></span>
                                                                <input type="text" class="form-control"
                                                                    name="gross_sal_amount" id="gross_sal_amount"
                                                                    placeholder="Amount" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">

                                                        <div class="col-md-6">
                                                            <label class="form-label" for="sal_netSalary">Net
                                                                Salary</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-wallet"></i></span>
                                                                <select id="sal_netSalary" name="sal_netSalary"
                                                                    class="form-select">
                                                                    <option value="">Select Net Salary</option>
                                                                    <?php
                                                                        $query = "SELECT id, netSalary FROM tbl_senp_netsalary ORDER BY netSalary ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['netSalary'].'">'.$row['netSalary'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label"
                                                                for="net_sal_amount">Amount</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-money"></i></span>
                                                                <input type="text" class="form-control"
                                                                    name="net_sal_amount" id="net_sal_amount"
                                                                    placeholder="Amount" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">

                                                        <div class="col-md-6">
                                                            <label class="form-label" for="sal_designation_name">Salary
                                                                Designation</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-wallet"></i></span>
                                                                <select id="sal_designation_name"
                                                                    name="sal_designation_name" class="form-select">
                                                                    <option value="">Select Salary Designation</option>
                                                                    <?php
                                                                        $query = "SELECT id, designation_name FROM tbl_salaried_designation ORDER BY designation_name ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['designation_name'].'">'.$row['designation_name'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="sal_official_email">Official
                                                                Mail
                                                                Id</label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="official_email2" class="input-group-text"><i
                                                                        class="bx bx-envelope"></i></span>
                                                                <input type="email" class="form-control"
                                                                    name="sal_official_email" id="sal_official_email"
                                                                    placeholder="Offial Mail Id"
                                                                    aria-describedby="official_email2" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">

                                                        <div class="col-md-6">
                                                            <label class="form-label"
                                                                for="sal_salary_payment_type">Salary
                                                                Payment Type</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-wallet"></i></span>
                                                                <select id="sal_salary_payment_type"
                                                                    name="sal_salary_payment_type" class="form-select">
                                                                    <option value="">Select Salary Payment Type</option>
                                                                    <?php
                                                                        $query = "SELECT id, salary_payment_type FROM tbl_salary_payment_type ORDER BY salary_payment_type ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['salary_payment_type'].'">'.$row['salary_payment_type'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label"
                                                                for="sal_present_experience">Present
                                                                Experience</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-timer"></i></span>
                                                                <select id="sal_present_experience"
                                                                    name="sal_present_experience" class="form-select">
                                                                    <option value="">Select Present Experience</option>
                                                                    <?php
                                                                        $query = "SELECT id, present_experience FROM tbl_present_experience ORDER BY present_experience ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['present_experience'].'">'.$row['present_experience'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="sal_total_experience">Total
                                                                Experience</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-timer"></i></span>
                                                                <select id="sal_total_experience"
                                                                    name="sal_total_experience" class="form-select">
                                                                    <option value="">Select Total Experience</option>
                                                                    <?php
                                                                        $query = "SELECT id, total_experience FROM tbl_total_experience ORDER BY total_experience ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['total_experience'].'">'.$row['total_experience'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="office_address1">Office
                                                                Address</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-map"></i></span>
                                                                <textarea name="office_address1" id="office_address1"
                                                                    rows="1" class="form-control"
                                                                    placeholder="Office Address"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <!-- Fields for SENP -->
                                                <div id="senp_fields" style="display: none;">
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_industry_name">Type Of
                                                                Industry</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-buildings"></i></span>
                                                                <select id="senp_industry_name"
                                                                    name="senp_industry_name" class="form-select"
                                                                    onchange="getSenpBusiness(this.value)">
                                                                    <option value="">Select Type Of Industry</option>
                                                                    <?php
                                                                        $query = "SELECT id, industry_name FROM tbl_senp_industry_type ORDER BY industry_name ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['industry_name'].'">'.$row['industry_name'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_business_name"> Type Of
                                                                Business</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-bar-chart-alt-2"></i></span>
                                                                <select id="senp_business_name"
                                                                    name="senp_business_name" class="form-select">
                                                                    <option value="">Select Type Of Business</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_company_type">Type Of
                                                                Company</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-buildings"></i></span>
                                                                <select id="senp_company_type" name="senp_company_type"
                                                                    class="form-select">
                                                                    <option value="">Select Type Of Company</option>
                                                                    <?php
                                                                        $query = "SELECT id, company_type FROM tbl_company_type ORDER BY company_type ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['company_type'].'">'.$row['company_type'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_nature_business">Nature
                                                                Of
                                                                Business</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-briefcase"></i></span>
                                                                <input type="text" class="form-control"
                                                                    name="senp_nature_business"
                                                                    id="senp_nature_business"
                                                                    placeholder="Nature Of Business" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label"
                                                                for="senp_incorporaton_date">Incorporation Date</label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="incorporaton_date2"
                                                                    class="input-group-text"><i
                                                                        class="bx bx-calendar"></i></span>
                                                                <input type="text" class="form-control"
                                                                    name="senp_incorporaton_date"
                                                                    id="senp_incorporaton_date" placeholder="DD/MM/YYYY"
                                                                    aria-describedby="incorporaton_date2" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_vintage_year">Vintage
                                                                Year</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-buildings"></i></span>
                                                                <select id="senp_vintage_year" name="senp_vintage_year"
                                                                    class="form-select">
                                                                    <option value="">Select Vintage Year</option>
                                                                    <?php
                                                                        $query = "SELECT id, vintage_year FROM tbl_vintage_year ORDER BY vintage_year ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['vintage_year'].'">'.$row['vintage_year'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_factory_address">Factory
                                                                Address</label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="incorporaton_date2"
                                                                    class="input-group-text"><i
                                                                        class="bx bx-map"></i></span>

                                                                <textarea name="senp_factory_address"
                                                                    id="senp_factory_address" rows="1"
                                                                    placeholder="Factory Address"
                                                                    class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_factory_pincode">Factory
                                                                Address Pincode</label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="factory_pincode2" class="input-group-text"><i
                                                                        class="bx bx-compass"></i></span>
                                                                <input type="text" class="form-control"
                                                                    name="senp_factory_pincode"
                                                                    id="senp_factory_pincode"
                                                                    placeholder="Factory Address Pincode"
                                                                    aria-describedby="factory_pincode2" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_gst_number">GST
                                                                No</label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="senp_gst_number2" class="input-group-text"><i
                                                                        class="bx bx-receipt"></i></span>
                                                                <input type="text" class="form-control"
                                                                    name="senp_gst_number" id="senp_gst_number"
                                                                    placeholder="GST No"
                                                                    aria-describedby="senp_gst_number2" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label"
                                                                for="senp_company_pan_number">Company
                                                                PAN No</label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="company_pan_number2"
                                                                    class="input-group-text"><i
                                                                        class="bx bx-id-card"></i></span>
                                                                <input type="text" class="form-control"
                                                                    name="senp_company_pan_number"
                                                                    id="senp_company_pan_number"
                                                                    placeholder="Company PAN No"
                                                                    aria-describedby="company_pan_number2" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- senp details table starts-->
                                                    <div class="row mt-3">
                                                        <h5> SENP Details</h5>
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead class="table-dark">
                                                                        <tr>
                                                                            <th style="width: 21%; min-width: 210px;">
                                                                                Financial Year
                                                                            </th>
                                                                            <th style="width: 21%; min-width: 210px;">
                                                                                Assessment Year
                                                                            </th>
                                                                            <th style="width: 21%; min-width: 210px;">
                                                                                Turnover
                                                                            </th>
                                                                            <th style="width: 21%; min-width: 210px;">
                                                                                Depreciation</th>
                                                                            <th style="width: 21%; min-width: 210px;">
                                                                                PBT</th>
                                                                            <th style="width: 21%; min-width: 210px;">
                                                                                PAT
                                                                            </th>

                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="assessmentTable">
                                                                        <tr>
                                                                            <td>
                                                                                <select name="financial_year[]"
                                                                                    class="form-select"
                                                                                    onchange="getAssessmentYear(this)">
                                                                                    <option value="">Select Financial
                                                                                        Year</option>
                                                                                    <?php
                                                                                        $query = "SELECT id, financial_year FROM tbl_financial_year ORDER BY financial_year ASC";
                                                                                        $result = $conn->query($query);
                                                                                        while ($row = $result->fetch_assoc()) {
                                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['financial_year'].'">'.$row['financial_year'].'</option>';
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select name="assessment_year[]"
                                                                                    class="form-select assessment_year">
                                                                                    <option value="">Select Assessment
                                                                                        Year</option>
                                                                                </select>
                                                                            </td>

                                                                            <td>
                                                                                <input name="turnover[]" type="text"
                                                                                    class="form-control"
                                                                                    placeholder="Turnover ">
                                                                            </td>

                                                                            <td><input name="depreciation[]" type="text"
                                                                                    class="form-control"
                                                                                    placeholder=" Depreciation">
                                                                            </td>
                                                                            <td><input name="pbt[]" type="text"
                                                                                    class="form-control"
                                                                                    placeholder="PBT">
                                                                            </td>
                                                                            <td><input name="pat[]" type="text"
                                                                                    class="form-control"
                                                                                    placeholder="PAT">
                                                                            </td>
                                                                            <td>
                                                                                <button type="button"
                                                                                    class="btn btn-primary action-btn-2 addRow-2">Add</button>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- senp details table ends-->
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_website">Website</label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="senp_website2" class="input-group-text"><i
                                                                        class="bx bx-globe"></i></span>
                                                                <input type="url" class="form-control"
                                                                    name="senp_website" id="senp_website"
                                                                    placeholder="Website"
                                                                    aria-describedby="senp_website2" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_rating_type">Type Of
                                                                Rating</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-star"></i></span>
                                                                <select id="senp_rating_type" name="senp_rating_type"
                                                                    class="form-select"
                                                                    onchange="getSenpRating(this.value)">
                                                                    <option value="">Select Type Of Rating</option>
                                                                    <?php
                                                                        $query = "SELECT id, rating_type FROM tbl_type_rating ORDER BY rating_type ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['rating_type'].'">'.$row['rating_type'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_rating_name">
                                                                Rating</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bxs-star"></i></span>
                                                                <select id="senp_rating_name" name="senp_rating_name"
                                                                    class="form-select">
                                                                    <option value="">Select Rating</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_branches">Number Of
                                                                Branches</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-network-chart"></i></span>
                                                                <select id="senp_branches" name="senp_branches"
                                                                    class="form-select">
                                                                    <option value="">Select Number of Branches</option>
                                                                    <?php
                                                                        $query = "SELECT id, branches FROM tbl_senp_branches ORDER BY branches ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['branches'].'">'.$row['branches'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="senp_employees">Number Of
                                                                Employees</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-user"></i></span>
                                                                <select id="senp_employees" name="senp_employees"
                                                                    class="form-select">
                                                                    <option value="">Select Number of Employees</option>
                                                                    <?php
                                                                        $query = "SELECT id, employees FROM tbl_senp_employees ORDER BY employees ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['employees'].'">'.$row['employees'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">

                                                            <label class="form-label" for="office_address1">Office
                                                                Address</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-map"></i></span>
                                                                <textarea name="office_address1" id="office_address1"
                                                                    rows="1" class="form-control"
                                                                    placeholder="Office Address"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="mb-6">
                                                            <label class="form-label" for="branch_address1">Branch
                                                                Address</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-map"></i></span>
                                                                <textarea name="branch_address1" id="branch_address1"
                                                                    rows="1" class="form-control"
                                                                    placeholder="Branch Address"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Fields for SEP -->
                                                <div id="sep_fields" style="display: none;">
                                                    <div class="mb-6">
                                                        <label class="form-label" for="sep_type_professional">Type
                                                            Of
                                                            Professional</label><span style="color:red;"> *</span>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-certification"></i></span>
                                                            <select id="sep_type_professional"
                                                                name="sep_type_professional" class="form-select">
                                                                <option value="">Select Type Of
                                                                    Professional</option>
                                                                <option value="doctor-prof" data-text="doctor-prof">
                                                                    DOCTOR</option>
                                                                <option value="ca-prof" data-text="ca-prof">CA</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Doctor Fields -->
                                                    <div id="doctor_fields" style="display: none;">
                                                        <!-- <h2>Hii doctor</h2> -->
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label"
                                                                    for="doctor_qualification">Qualification</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-medal"></i></span>
                                                                    <select id="doctor_qualification"
                                                                        name="doctor_qualification" class="form-select">
                                                                        <option value="">Select Qualification</option>
                                                                        <?php
                                                                        $query = "SELECT id, qualification FROM tbl_sep_doctor_qualification ORDER BY qualification ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['qualification'].'">'.$row['qualification'].'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="doctor_year_pass">Year of
                                                                    Pass</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-calendar"></i></span>
                                                                    <select id="doctor_year_pass"
                                                                        name="doctor_year_pass" class="form-select">
                                                                        <option value="">Select Year of
                                                                            Pass</option>
                                                                        <?php
                                                                        $query = "SELECT id, year_pass FROM tbl_sep_doctor_yearpass ORDER BY year_pass ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['year_pass'].'">'.$row['year_pass'].'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label"
                                                                    for="doctor_specialisation">Specialisation</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-analyse"></i></span>
                                                                    <select id="doctor_specialisation"
                                                                        name="doctor_specialisation"
                                                                        class="form-select">
                                                                        <option value="">Select Specialisation</option>
                                                                        <?php
                                                                        $query = "SELECT id, specialisation FROM tbl_sep_doctor_specialisation ORDER BY specialisation ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['specialisation'].'">'.$row['specialisation'].'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label"
                                                                    for="doctor_university">University
                                                                </label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-buildings"></i></span>
                                                                    <select id="doctor_university"
                                                                        name="doctor_university" class="form-select">
                                                                        <option value="">Select University</option>
                                                                        <?php
                                                                        $query = "SELECT id, university FROM tbl_sep_doctor_university ORDER BY university ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['university'].'">'.$row['university'].'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="office_address1">Office
                                                                    Address</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-map"></i></span>
                                                                    <textarea name="office_address1"
                                                                        id="office_address1" rows="2"
                                                                        class="form-control"
                                                                        placeholder="Office Address"></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label" for="branch_address1">Branch
                                                                    Address</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-map"></i></span>
                                                                    <textarea name="branch_address1"
                                                                        id="branch_address1" rows="2"
                                                                        class="form-control"
                                                                        placeholder="Branch Address"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="ca_fields" style="display: none;" class="row mt-0">
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="firm_name">Firm
                                                                    Name</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-buildings"></i></span>
                                                                    <input type="text" class="form-control"
                                                                        name="firm_name" id="firm_name"
                                                                        placeholder="Firm Name" />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label" for="ca_year_pass">Year of
                                                                    Pass</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-calendar"></i></span>
                                                                    <select id="ca_year_pass" name="ca_year_pass"
                                                                        class="form-select">
                                                                        <option value="">Select Year of
                                                                            Pass</option>
                                                                        <?php
                                                                        $query = "SELECT id, year_pass FROM tbl_sep_ca_yearpass ORDER BY year_pass ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['year_pass'].'">'.$row['year_pass'].'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="mb-6">
                                                                <label class="form-label" for="ca_number">CA
                                                                    Number</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-buildings"></i></span>
                                                                    <input type="text" class="form-control"
                                                                        name="ca_number" id="ca_number"
                                                                        placeholder="CA Number" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row ">
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="office_address1">Office
                                                                    Address</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-map"></i></span>
                                                                    <textarea name="office_address1"
                                                                        id="office_address1" rows="2"
                                                                        class="form-control"
                                                                        placeholder="Office Address"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="branch_address1">Branch
                                                                    Address</label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-map"></i></span>
                                                                    <textarea name="branch_address1"
                                                                        id="branch_address1" rows="2"
                                                                        class="form-control"
                                                                        placeholder="Branch Address"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Fields for SEP -->

                                                <!-- Fields for NRI -->
                                                <div id="nri_fields" style="display: none;">
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="nri_country">Country</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-globe"></i></span>
                                                                <select id="nri_country" name="nri_country"
                                                                    class="form-select">
                                                                    <option value="">Select Country</option>
                                                                    <?php
                                                                        $query = "SELECT id, country FROM tbl_nri_country ORDER BY country ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['country'].'">'.$row['country'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="nri_gross_salary">Gross
                                                                Salary</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-money"></i></span>
                                                                <input type="text" class="form-control"
                                                                    name="nri_gross_salary" id="nri_gross_salary"
                                                                    placeholder="Gross Salary" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- Fields for NRI -->

                                                <!-- Fields for Education -->
                                                <div id="education_fields" style="display: none;">
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="educational_institute">Type
                                                                Of
                                                                Institution</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-buildings"></i></span>
                                                                <select id="educational_institute"
                                                                    name="educational_institute" class="form-select">
                                                                    <option value="">Select Type Of Institution</option>
                                                                    <?php
                                                                        $query = "SELECT id, institute FROM tbl_educational_institute ORDER BY institute ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['institute'].'">'.$row['institute'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="educational_students">Number
                                                                Of
                                                                Students</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-user"></i></span>

                                                                <select id="educational_students"
                                                                    name="educational_students" class="form-select">
                                                                    <option value="">Select Number Of Students</option>
                                                                    <?php
                                                                        $query = "SELECT id, no_students FROM tbl_educational_no_students ORDER BY no_students ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['no_students'].'">'.$row['no_students'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="edu_company_type">Type Of
                                                                Company</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-buildings"></i></span>
                                                                <select id="edu_company_type" name="edu_company_type"
                                                                    class="form-select">
                                                                    <option value="">Select Type Of Company</option>
                                                                    <?php
                                                                        $query = "SELECT id, company_type FROM tbl_company_type ORDER BY company_type ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['company_type'].'">'.$row['company_type'].'</option>';
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="educational_strength">Number
                                                                Of Strength
                                                            </label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-group"></i></span>

                                                                <input type="text" class="form-control"
                                                                    name="educational_strength"
                                                                    id="educational_strength" placeholder="Number Of Strength
                                                                " />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="office_address1">Office
                                                                Address</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-map"></i></span>
                                                                <textarea name="office_address1" id="office_address1"
                                                                    rows="2" class="form-control"
                                                                    placeholder="Office Address"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="branch_address1">Branch
                                                                Address</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-map"></i></span>
                                                                <textarea name="branch_address1" id="branch_address1"
                                                                    rows="2" class="form-control"
                                                                    placeholder="Branch Address"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Fields for Education -->

                                                <!-- Bank Account Details -->
                                                <div class="row mt-5">
                                                    <h5> Bank Account Details</h5>
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th style="width: 20%; min-width: 200px;">
                                                                            Bank Name
                                                                        </th>
                                                                        <th style="width: 20%; min-width: 200px;">
                                                                            Type Of Account
                                                                        </th>
                                                                        <th style="width: 20%; min-width: 200px;">
                                                                            Account Number
                                                                        </th>
                                                                        <th style="width: 20%; min-width: 200px;">
                                                                            Branch Name
                                                                        </th>
                                                                        <th style="width: 20%; min-width: 200px;">
                                                                            IFSC Code
                                                                        </th>

                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="bankAccTable">
                                                                    <tr>
                                                                        <td>
                                                                            <select name="b_bank_name[]"
                                                                                class="form-select">
                                                                                <option value="">Select Bank
                                                                                </option>
                                                                                <?php
                                                                        $query = "SELECT id, bank_name FROM tbl_bank ORDER BY bank_name ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                        }
                                                                    ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select name="b_account_type[]"
                                                                                class="form-select">
                                                                                <option value="">Select Account Type
                                                                                </option>
                                                                                <?php
                                                                    $query = "SELECT id, account_type FROM tbl_bank_account_type ORDER BY account_type ASC";
                                                                    $result = $conn->query($query);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<option value="'.$row['id'].'">'.$row['account_type'].'</option>';
                                                                    }
                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="b_account_no[]"
                                                                                placeholder="Account Number">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="b_branch_name[]"
                                                                                placeholder="Branch Name">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="b_ifsc_code[]"
                                                                                placeholder="IFSC Code"
                                                                                oninput="this.value = this.value.toUpperCase();">
                                                                        </td>

                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-primary action-btn2 addRow2">Add</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Bank Account Details -->

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
                                                                            <select name="r_bank_name[]"
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
                                                                            <select name="r_loan_type[]"
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
                                                                        <td><input type="number" class="form-control"
                                                                                name="r_loan_amount[]" min="0"
                                                                                placeholder="Amount"></td>
                                                                        <td>

                                                                            <select name="r_roi[]" class="form-select">
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

                                                                            <select name="r_tenure[]"
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
                                                                        <td><input type="number" class="form-control"
                                                                                name="r_emi[]" min="0"
                                                                                placeholder="EMI">
                                                                        </td>
                                                                        <td><input type="date" class="form-control"
                                                                                name="first_emi_date[]"></td>
                                                                        <td><input type="date" class="form-control"
                                                                                name="last_emi_date[]"></td>
                                                                        <td><input type="text" class="form-control"
                                                                                name="loan_account_name[]"
                                                                                placeholder="Account Number"></td>
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
                                                <!-- bank relation details -->

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
                                                                                name="vehicle_number[]"
                                                                                placeholder="Vehicle Number">
                                                                        </td>
                                                                        <td>
                                                                            <select name="vehicle_make[]"
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
                                                                            <select name="vehical_modal[]"
                                                                                class="form-select">
                                                                                <option value="">Select Vehicle
                                                                                    Modal
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

                                                                            <select name="manufacture_year[]"
                                                                                class="form-select">
                                                                                <option value="">Select MAN Year
                                                                                </option>
                                                                                <?php
                                                                                    $query = "SELECT id, manufacture_year FROM tbl_manufacture_year ORDER BY manufacture_year ASC";
                                                                                    $result = $conn->query($query);
                                                                                    while ($row = $result->fetch_assoc()) {
                                                                                        echo '<option value="'.$row['id'].'">'.$row['manufacture_year'].'</option>';
                                                                                    }
                                                                                ?>
                                                                        </td>

                                                                        <td><input type="text" class="form-control"
                                                                                name="engine_number[]"
                                                                                placeholder="Engine No"></td>
                                                                        <td><input type="text" class="form-control"
                                                                                name="chases_number[]"
                                                                                placeholder="Chases No"></td>

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
                                                <!-- Vehicle details -->

                                                <!-- Property Details -->
                                                <div class="row mt-5">
                                                    <h5> Property Details</h5>
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th style="width: 20%; min-width: 200px;">
                                                                            Type Of Property
                                                                        </th>
                                                                        <th style="width: 20%; min-width: 200px;">
                                                                            Area
                                                                        </th>
                                                                        <th style="width: 20%; min-width: 200px;">
                                                                            Land In Sq. Yards
                                                                        </th>
                                                                        <th style="width: 20%; min-width: 200px;">
                                                                            SFT
                                                                        </th>
                                                                        <th style="width: 20%; min-width: 200px;">
                                                                            Market Value
                                                                        </th>

                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="propertyTable">
                                                                    <tr>
                                                                        <td>
                                                                            <select name="p_property_type[]"
                                                                                class="form-select">
                                                                                <option value="">Select Property Type
                                                                                </option>
                                                                                <?php
                                                                        $query = "SELECT id, property_type FROM tbl_bank_property_type ORDER BY property_type ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'">'.$row['property_type'].'</option>';
                                                                        }
                                                                    ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="p_area[]" placeholder="Area">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="p_lands[]"
                                                                                placeholder=" Land In Sq. Yards">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="p_sft[]" placeholder="SFT">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="p_market_value[]"
                                                                                placeholder="Market Value">
                                                                        </td>

                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-primary action-btn3 addRow3">Add</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Property Details -->

                                                <!-- credit card details -->
                                                <div class="row mt-5">
                                                    <h5> Credit Card Details</h5>
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th style="width: 40%; min-width: 400px;">
                                                                            Bank Name
                                                                        </th>

                                                                        <th style="width: 40%; min-width: 400px;">
                                                                            Limit
                                                                        </th>

                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="creditCardTable">
                                                                    <tr>
                                                                        <td>
                                                                            <select name="c_bank_name[]"
                                                                                class="form-select">
                                                                                <option value="">Select Bank
                                                                                </option>
                                                                                <?php
                                                                        $query = "SELECT id, bank_name FROM tbl_credit_card_bank ORDER BY bank_name ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                        }
                                                                    ?>
                                                                            </select>
                                                                        </td>

                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="c_limit[]" placeholder="Limit">
                                                                        </td>

                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-primary action-btn4 addRow4">Add</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- credit card details -->

                                                <!-- calling fields -->
                                                <div class="row mt-5">
                                                    <h5> Calling Status</h5>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="calling_type_loan"> Calling Type
                                                            Loan</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-file"></i></span>
                                                            <select id="calling_type_loan" name="calling_type_loan[]"
                                                                class="form-select">
                                                                <option value="">Select Calling Type Loan</option>
                                                                <?php
                                                               $query = "SELECT id, calling_type_loan FROM tbl_calling_type_loan ORDER BY calling_type_loan ASC";
                                                               $result = $conn->query($query);
                                                               while ($row = $result->fetch_assoc()) {
                                                                   echo '<option value="'.$row['id'].'">'.$row['calling_type_loan'].'</option>';
                                                               }
                                                           ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="calling_bank"> Calling
                                                            Bank</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-phone"></i></span>
                                                            <select id="calling_bank" name="calling_bank[]"
                                                                class="form-select">
                                                                <option value="">Select Calling Bank</option>
                                                                <?php
                                                               $query = "SELECT id, calling_bank FROM tbl_calling_bank ORDER BY calling_bank ASC";
                                                               $result = $conn->query($query);
                                                               while ($row = $result->fetch_assoc()) {
                                                                   echo '<option value="'.$row['id'].'">'.$row['calling_bank'].'</option>';
                                                               }
                                                           ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="calling_status"> Calling
                                                            Status</label><span style="color:red;"> *</span>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-phone"></i></span>
                                                            <select id="calling_status" name="calling_status[]"
                                                                class="form-select" onchange="getSubStatus(this.value)">
                                                                <option value="">Select Calling Status</option>
                                                                <?php
                                                               $query = "SELECT id, calling_status FROM tbl_calling_status ORDER BY calling_status ASC";
                                                               $result = $conn->query($query);
                                                               while ($row = $result->fetch_assoc()) {
                                                                   echo '<option value="'.$row['id'].'">'.$row['calling_status'].'</option>';
                                                               }
                                                           ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="calling_sub_status"> Calling Sub
                                                            Status</label><span style="color:red;"> *</span>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-phone"></i></span>
                                                            <select id="calling_sub_status" name="calling_sub_status[]"
                                                                class="form-select">
                                                                <option value="">Select Calling Sub Status</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Datetime Field (Initially Hidden) -->
                                                <div class="row mt-3" id="datetimeContainer" style="display: none;">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="followup_datetime">Follow-Up Date & Time</label><span style="color:red;"> *</span>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                                            <input type="datetime-local" id="followup_datetime" name="followup_datetime[]" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- calling fields -->

                                                <div class="row mt-3">
                                                    <div class="mb-6">
                                                        <label class="form-label"
                                                            for="database_notes">Notes</label><span style="color:red;">
                                                            *</span>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-message"></i></span>

                                                            <textarea name="database_notes" id="database_notes"
                                                                class="form-control" placeholder="Notes"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="submit" name="form_submit" class="btn btn-primary mt-3"
                                                    value="Submit">
                                            </div>
                                        </form>

                                        <!-- If Mobile Exists -->
                                        <div id="existingFields" style="display: none;">
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
        document.getElementById("checkMobileBtn").addEventListener("click", function () {
            const mobile = document.getElementById("mobile_number").value.trim();
            const statusMessage = document.getElementById("mobileStatusMessage");

            if (mobile.length !== 10) {
                statusMessage.style.display = "block";
                statusMessage.style.color = "red";
                statusMessage.textContent = "Please enter a valid 10-digit mobile number.";
                return;
            }

            fetch("check_mobile.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "mobile=" + encodeURIComponent(mobile)
            })
            .then(response => response.json())
            .then(data => {
                const existingDiv = document.getElementById("existingFields");
                existingDiv.innerHTML = "";

                if (data.length > 0) {
                    const row = data[0];
                    const isNri = row.customer_type === '37'; 
                    const isEdu = row.customer_type === '38'; 
                    const isSal = row.customer_type === '39'; 
                    const isSenp = row.customer_type === '35'; 
                    const isSep = row.customer_type === '36'; 

                    statusMessage.style.display = "block";
                    statusMessage.style.color = "green";
                    statusMessage.textContent = "Mobile number already exists.";

                     window.location.href = "view.php?id=" + encodeURIComponent(row.id);
                    
                    // existingDiv.innerHTML = html;
                    // existingDiv.style.display = "block";
                    // document.getElementById("otherFields").style.display = "none";
                } else {
                    statusMessage.style.display = "block";
                    statusMessage.style.color = "red";
                    statusMessage.textContent = "Mobile number not found. You can enter new details.";

                    document.getElementById("existingFields").style.display = "none";
                    document.getElementById("otherFields").style.display = "block";
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
            });
        });


        // Block form submission if mobile exists
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

        // bank account table
        $(document).on("click", ".action-btn2", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addRow2")) {
                let newRow = `<tr>
                                <td>
                                    <select name="b_bank_name[]"
                                        class="form-select">
                                        <option value="">Select Bank
                                        </option>
                                                <?php
                                        $query = "SELECT id, bank_name FROM tbl_bank ORDER BY bank_name ASC";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                        }
                                    ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="b_account_type[]"
                                            class="form-select">
                                            <option value="">Select Account Type
                                            </option>
                                            <?php
                                        $query = "SELECT id, account_type FROM tbl_bank_account_type ORDER BY account_type ASC";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="'.$row['id'].'">'.$row['account_type'].'</option>';
                                        }
                                    ?>
                                        </select>
                                </td>
                                <td>
                                        <input type="text" class="form-control"
                                            name="b_account_no[]"
                                            placeholder="Account Number">
                                </td>
                                <td>
                                        <input type="text" class="form-control"
                                            name="b_branch_name[]"
                                            placeholder="Branch Name">
                                </td>
                                <td>
                                        <input type="text" class="form-control"
                                            name="b_ifsc_code[]"
                                            placeholder="IFSC Code" oninput="this.value = this.value.toUpperCase();">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary action-btn2 addRow2">Add</button>
                                    </td>
                            </tr>`;

                $("#bankAccTable").append(newRow);
                btn.removeClass("btn-primary addRow2").addClass("btn-danger removeRow2").text("Delete");
            } else if (btn.hasClass("removeRow2")) {
                row.remove();
                updateLastRow();
            }
        });

        function updateLastRow() {
            let lastRow = $("#bankAccTable tr:last");
            lastRow.find(".action-btn2").removeClass("btn-danger removeRow2").addClass("btn-primary addRow2").text(
                "Add");
        }
        // bank account table

        // bank relation table
        $(document).on("click", ".action-btn", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addRow")) {
                let newRow = `<tr>
                                <td>
                                    <select name="r_bank_name[]" class="form-select">
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
                                    <select name="r_loan_type[]" class="form-select">
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
                                <td><input type="number" class="form-control" name="r_loan_amount[]" min="0" placeholder="Amount"></td>
                                <td> <select name="r_roi[]"
                                                                                class="form-select">
                                                                                <option value="">Select ROI</option>
                                                                                <?php
                                                                        $query = "SELECT id, roi_name FROM tbl_roi ORDER BY roi_name ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'">'.$row['roi_name'].'</option>';
                                                                        }
                                                                    ?></td>
                                <td> <select name="r_tenure[]"
                                                                                class="form-select">
                                                                                <option value="">Select Tenure</option>
                                                                                <?php
                                                                        $query = "SELECT id, tenure_name FROM tbl_tenure ORDER BY tenure_name ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'">'.$row['tenure_name'].'</option>';
                                                                        }
                                                                    ?></td>
                                <td><input type="number" class="form-control" name="r_emi[]" min="0" placeholder="EMI"></td>
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
                                                                                    <select
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
                                                                                <select
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

                                                                                    <select name="manufacture_year[]"
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

        // Property table
        $(document).on("click", ".action-btn3", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addRow3")) {
                let newRow = `<tr>
                            <td>
                                                                            <select name="p_property_type[]"
                                                                                        class="form-select">
                                                                                        <option value="">Select Property Type
                                                                                        </option>
                                                                                        <?php
                                                                            $query = "SELECT id, property_type FROM tbl_bank_property_type ORDER BY property_type ASC";
                                                                            $result = $conn->query($query);
                                                                            while ($row = $result->fetch_assoc()) {
                                                                                echo '<option value="'.$row['id'].'">'.$row['property_type'].'</option>';
                                                                            }
                                                                        ?>
                                                                                    </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control"
                                                                                    name="p_area[]" 
                                                                                    placeholder="Area">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control"
                                                                                    name="p_lands[]" 
                                                                                    placeholder=" Land In Sq. Yards">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control"
                                                                                    name="p_sft[]" 
                                                                                    placeholder="SFT">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control"
                                                                                    name="p_market_value[]"
                                                                                    placeholder="Market Value" >
                                                                            </td>
                                <td>
                                    <button type="button" class="btn btn-primary action-btn3 addRow3">Add</button>
                                </td>
                            </tr>`;

                $("#propertyTable").append(newRow);
                btn.removeClass("btn-primary addRow3").addClass("btn-danger removeRow3").text("Delete");
            } else if (btn.hasClass("removeRow3")) {
                row.remove();
                updateLastRow();
            }
        });

        function updateLastRow() {
            let lastRow = $("#propertyTable tr:last");
            lastRow.find(".action-btn3").removeClass("btn-danger removeRow3").addClass("btn-primary addRow3").text(
                "Add");
        }
        // Property table

        // credit card details
        $(document).on("click", ".action-btn4", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addRow4")) {
                let newRow = `<tr>
                            <td>
                                                                                <select 
                                                                                    name="c_bank_name[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Bank
                                                                                    </option>
                                                                                    <?php
                                                                            $query = "SELECT id, bank_name FROM tbl_credit_card_bank ORDER BY bank_name ASC";
                                                                            $result = $conn->query($query);
                                                                            while ($row = $result->fetch_assoc()) {
                                                                                echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                            }
                                                                        ?>
                                                                                </select>
                                                                            </td>
                                                                        
                                                                            <td>
                                                                                <input type="text" class="form-control"
                                                                                    name="c_limit[]"
                                                                                    placeholder="Limit">
                                                                            </td>                                           
                                <td>
                                    <button type="button" class="btn btn-primary action-btn4 addRow4">Add</button>
                                </td>
                            </tr>`;

                $("#creditCardTable").append(newRow);
                btn.removeClass("btn-primary addRow4").addClass("btn-danger removeRow4").text("Delete");
            } else if (btn.hasClass("removeRow4")) {
                row.remove();
                updateLastRow();
            }
        });

        function updateLastRow() {
            let lastRow = $("#creditCardTable tr:last");
            lastRow.find(".action-btn4").removeClass("btn-danger removeRow4").addClass("btn-primary addRow4").text(
                "Add");
        }
        //   credit card details

        // assessment year
        $(document).on("click", ".action-btn-2", function() {
            let btn = $(this);
            let row = btn.closest("tr");

            if (btn.hasClass("addRow-2")) {
                let newRow = `<tr>
                <td>
                    <select name="financial_year[]"
                            class="form-select"
                            onchange="getAssessmentYear(this)">
                        <option value="">Select Financial Year</option>
                        <?php
                            $query = "SELECT id, financial_year FROM tbl_financial_year ORDER BY financial_year ASC";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="'.$row['id'].'" data-text="'.$row['financial_year'].'">'.$row['financial_year'].'</option>';
                            }
                        ?>
                    </select>
                </td>
                <td>
                    <select name="assessment_year[]"
                            class="form-select assessment_year">
                        <option value="">Select Assessment Year</option>
                    </select>
                </td>
                <td>
                    <input name="turnover[]" type="text" class="form-control"
                        
                        placeholder="Turnover ">
                </td>

                <td><input  name="depreciation[]" type="text" class="form-control"
                        placeholder=" Depreciation">
                </td>
                <td><input  name="pbt[]" type="text" class="form-control"
                        placeholder="PBT">
                </td>
                <td><input name="pat[]" type="text" class="form-control"
                        placeholder="PAT">
                </td>
                <td>
                    <button type="button" class="btn btn-primary action-btn-2 addRow-2">Add</button>
                </td>
                </tr>`;

                $("#assessmentTable").append(newRow);
                btn.removeClass("btn-primary addRow-2").addClass("btn-danger removeRow-2").text("Delete");
            } else if (btn.hasClass("removeRow-2")) {
                row.remove();
                updateLastRow();
            }
        });

        function updateLastRow() {
            let lastRow = $("#assessmentTable tr:last");
            lastRow.find(".action-btn-2").removeClass("btn-danger removeRow-2").addClass("btn-primary addRow-2").text(
                "Add");
        }
        // assessment year

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

        // customer type dropdown
        document.getElementById('customer_type').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedText = selectedOption.getAttribute('data-text');

            // Hide all
            document.getElementById('senp_fields').style.display = 'none';
            document.getElementById('sep_fields').style.display = 'none';
            document.getElementById('salaried_fields').style.display = 'none';
            document.getElementById('nri_fields').style.display = 'none';
            document.getElementById('education_fields').style.display = 'none';


            // Show based on type
            if (selectedText === 'Self Employed Non Professionals-SENP') {
                document.getElementById('senp_fields').style.display = 'block';
            } else if (selectedText === 'Self Employed Professionals-SEP') {
                document.getElementById('sep_fields').style.display = 'block';
            } else if (selectedText === 'Salaried-SAL') {
                document.getElementById('salaried_fields').style.display = 'block';
            } else if (selectedText === 'NRI') {
                document.getElementById('nri_fields').style.display = 'block';
            } else if (selectedText === 'Educational') {
                document.getElementById('education_fields').style.display = 'block';
            }
        });

        // type_professional
        document.getElementById('sep_type_professional').addEventListener('change', function() {
            const selectedValue = this.value;

            // Hide all
            document.getElementById('doctor_fields').style.display = 'none';
            document.getElementById('ca_fields').style.display = 'none'; // If added later

            // Show based on value
            if (selectedValue === 'doctor-prof') {
                document.getElementById('doctor_fields').style.display = 'block';
            } else if (selectedValue === 'ca-prof') {
                document.getElementById('ca_fields').style.display = 'block';
            }
        });

        // business
        function getSenpBusiness(businessId) {
            if (businessId) {
                fetch("../info/get_senp_business", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "industry_id=" + businessId
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("senp_business_name").innerHTML = data;
                    });
            } else {
                document.getElementById("senp_business_name").innerHTML =
                    '<option value="">Select Type Of Business</option>';
            }
        }

        // assessment year
        function getAssessmentYear(selectElement) {
            const financial_year_id = selectElement.value;
            const row = selectElement.closest('tr');
            const assessmentSelect = row.querySelector('.assessment_year');

            if (financial_year_id) {
                fetch("../info/get_assessment_year", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "financial_year_id=" + financial_year_id
                    })
                    .then(response => response.text())
                    .then(data => {
                        assessmentSelect.innerHTML = data;
                    });
            } else {
                assessmentSelect.innerHTML = '<option value="">Select Assessment Year</option>';
            }
        }

        // rating
        function getSenpRating(ratingId) {
            if (ratingId) {
                fetch("../info/get_senp_rating", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "rating_type_id=" + ratingId
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("senp_rating_name").innerHTML = data;
                    });
            } else {
                document.getElementById("senp_rating_name").innerHTML = '<option value="">Select Rating</option>';
            }
        }

        // calling_sub_status
        // function getSubStatus(subStatusId) {
        //     if (subStatusId) {
        //         fetch("../info/get_calling_sub_status", {
        //                 method: "POST",
        //                 headers: {
        //                     "Content-Type": "application/x-www-form-urlencoded"
        //                 },
        //                 body: "calling_status_id=" + subStatusId
        //             })
        //             .then(response => response.text())
        //             .then(data => {
        //                 document.getElementById("calling_sub_status").innerHTML = data;
        //             });
        //     } else {
        //         document.getElementById("calling_sub_status").innerHTML =
        //             '<option value="">Select Calling Sub Status</option>';
        //     }
        // }

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
                            document.getElementById("calling_sub_status").addEventListener("change", handleSubStatusChange);
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
        document.addEventListener("DOMContentLoaded", function () {
            const subStatusSelect = document.getElementById("calling_sub_status");
            if (subStatusSelect) {
                subStatusSelect.addEventListener("change", handleSubStatusChange);
            }
        });

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
    // common fields
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
    $user_qualification = $_POST['user_qualification'];
    $residental_address = $_POST['residental_address'];
    $customer_type = $_POST['customer_type'];

    // $calling_type_loan = $_POST['calling_type_loan'];
    // $calling_bank = $_POST['calling_bank'];
    // $calling_status = $_POST['calling_status'];
    // $calling_sub_status = $_POST['calling_sub_status'];
    $database_notes = $_POST['database_notes'];
    $office_address = $_POST['office_address1'];
    $branch_address = $_POST['branch_address1'];
    // $factory_address = $_POST['factory_address'];
    // echo $office_address;

    // salaried 
    $sal_company_type = $_POST['sal_company_type'];
    $sal_birth_date = $_POST['sal_birth_date'];
    $sal_grossSalary = $_POST['sal_grossSalary'];
    $gross_sal_amount = $_POST['gross_sal_amount'];
    $sal_netSalary = $_POST['sal_netSalary'];
    $net_sal_amount = $_POST['net_sal_amount'];
    $sal_designation_name = $_POST['sal_designation_name'];
    $sal_official_email = $_POST['sal_official_email'];
    $sal_salary_payment_type = $_POST['sal_salary_payment_type'];
    $sal_present_experience = $_POST['sal_present_experience'];
    $sal_total_experience = $_POST['sal_total_experience'];

    // senp
    $senp_industry_name = $_POST['senp_industry_name'];
    $senp_business_name = $_POST['senp_business_name'];
    $senp_company_type = $_POST['senp_company_type'];
    $senp_nature_business = $_POST['senp_nature_business'];
    $senp_incorporaton_date = $_POST['senp_incorporaton_date'];
    $senp_vintage_year = $_POST['senp_vintage_year'];
    $senp_factory_address = $_POST['senp_factory_address'];
    $senp_factory_pincode = $_POST['senp_factory_pincode'];
    $senp_gst_number = $_POST['senp_gst_number'];
    $senp_company_pan_number = $_POST['senp_company_pan_number'];
    $senp_website = $_POST['senp_website'];
    $senp_rating_type = $_POST['senp_rating_type'];
    $senp_rating_name = $_POST['senp_rating_name'];
    $senp_branches = $_POST['senp_branches'];
    $senp_employees = $_POST['senp_employees'];

    // SEP
    $sep_type_professional = $_POST['sep_type_professional'];
    $doctor_qualification = $_POST['doctor_qualification'];
    $doctor_year_pass = $_POST['doctor_year_pass'];
    $doctor_specialisation = $_POST['doctor_specialisation'];
    $doctor_university = $_POST['doctor_university'];

    $firm_name = $_POST['firm_name'];
    $ca_year_pass = $_POST['ca_year_pass'];
    $ca_number = $_POST['ca_number'];

    // nri
    $nri_country = $_POST['nri_country'];
    $nri_gross_salary = $_POST['nri_gross_salary'];

    // educational
    $educational_institute = $_POST['educational_institute'];
    $educational_students = $_POST['educational_students'];
    $edu_company_type = $_POST['edu_company_type'];
    $educational_strength = $_POST['educational_strength'];

    $created_at = date('Y-m-d H:i:s');

    if (empty($mobile_number) || empty($customer_type) || empty($lead_name) || empty($database_notes)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All Mandatory Fields are required",
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

    // File Upload Handling
    $target_dir = "../uploads/dataBase/";

    $visiting_card = "default.png"; // Default file
    if (!empty($_FILES["visiting_card"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["visiting_card"]["name"]); // Generate unique filename
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = array("jpg", "jpeg", "png", "pdf");

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["visiting_card"]["tmp_name"], $target_file)) {
                $visiting_card = $file_name; // Save only the file name
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

    $sql = "INSERT INTO `tbl_database`(`mobile_number`,`lead_name`,`email_id`,`company_name`,`alternative_mobile`,`state`,`location`,`sub_location`,`pin_code`,`source`,`visiting_card`,`user_qualification`,`residental_address`,`customer_type`,`sal_company_type`, `sal_birth_date`, `sal_grossSalary`, `gross_sal_amount`, `sal_netSalary`, `net_sal_amount`, `sal_designation_name`, `sal_official_email`, `sal_salary_payment_type`, `sal_present_experience`, `sal_total_experience`, `senp_industry_name`, `senp_business_name`, `senp_company_type`, `senp_nature_business`, `senp_incorporaton_date`, `senp_vintage_year`, `senp_factory_address`, `senp_factory_pincode`, `senp_gst_number`, `senp_company_pan_number`, `senp_website`, `senp_rating_type`, `senp_rating_name`, `senp_branches`, `senp_employees`,`sep_type_professional`, `doctor_qualification`, `doctor_year_pass`, `doctor_specialisation`, `doctor_university`,`firm_name`, `ca_year_pass`, `ca_number`,`nri_country`,`nri_gross_salary`,`educational_institute`,`educational_students`,`edu_company_type`, `educational_strength`, `database_notes`,`office_address`,`branch_address`,`createdBy`,`created_at`)  VALUES ('$mobile_number','$lead_name','$email_id','$company_name','$alternative_mobile','$state','$location','$sub_location','$pin_code','$source','$visiting_card','$user_qualification','$residental_address','$customer_type','$sal_company_type','$sal_birth_date','$sal_grossSalary','$gross_sal_amount','$sal_netSalary','$net_sal_amount','$sal_designation_name','$sal_official_email','$sal_salary_payment_type','$sal_present_experience','$sal_total_experience','$senp_industry_name','$senp_business_name','$senp_company_type','$senp_nature_business','$senp_incorporaton_date','$senp_vintage_year','$senp_factory_address','$senp_factory_pincode','$senp_gst_number','$senp_company_pan_number','$senp_website','$senp_rating_type','$senp_rating_name','$senp_branches','$senp_employees','$sep_type_professional','$doctor_qualification','$doctor_year_pass','$doctor_specialisation','$doctor_university','$firm_name','$ca_year_pass','$ca_number','$nri_country','$nri_gross_salary','$educational_institute','$educational_students','$edu_company_type','$educational_strength','$database_notes','$office_address','$branch_address','$loggedInUser','$created_at')";

    if (mysqli_query($conn, $sql)) {
        $last_id = mysqli_insert_id($conn);

        // senp table
        if (!empty($_POST['financial_year'])) {
            foreach ($_POST['financial_year'] as $key => $financial_year) {
                $assessment_year = mysqli_real_escape_string($conn, $_POST['assessment_year'][$key]);
                $turnover = mysqli_real_escape_string($conn, $_POST['turnover'][$key]);
                $depreciation = mysqli_real_escape_string($conn, $_POST['depreciation'][$key]);
                $pbt = mysqli_real_escape_string($conn, $_POST['pbt'][$key]);
                $pat = mysqli_real_escape_string($conn, $_POST['pat'][$key]);

                $senp_fy_sql = "INSERT INTO `tbl_senp_fy_details`(`database_id`, `financial_year`, `assessment_year`, `turnover`, `depreciation`, `pbt`, `pat`, `created_at`) 
                             VALUES ('$last_id', '$financial_year', '$assessment_year', '$turnover', '$depreciation', '$pbt', '$pat', '$created_at')";

                            //  echo $senp_fy_sql;
                            //  exit();
                
                mysqli_query($conn, $senp_fy_sql);
            }
        }

        // bank account table
        if (!empty($_POST['b_bank_name'])) {
            foreach ($_POST['b_bank_name'] as $key => $b_bank_name) {
                $b_account_type = mysqli_real_escape_string($conn, $_POST['b_account_type'][$key]);
                $b_account_no = mysqli_real_escape_string($conn, $_POST['b_account_no'][$key]);
                $b_branch_name = mysqli_real_escape_string($conn, $_POST['b_branch_name'][$key]);
                $b_ifsc_code = mysqli_real_escape_string($conn, $_POST['b_ifsc_code'][$key]);     

                $bank_acc_sql = "INSERT INTO `tbl_database_bank_account_details`(`database_id`, `b_bank_name`, `b_account_type`, `b_account_no`, `b_branch_name`, `b_ifsc_code`, `created_at`) 
                             VALUES ('$last_id', '$b_bank_name', '$b_account_type', '$b_account_no', '$b_branch_name', '$b_ifsc_code','$created_at')";

                mysqli_query($conn, $bank_acc_sql);
            }
        }

        // relation with bank
        if (!empty($_POST['r_bank_name'])) {
            foreach ($_POST['r_bank_name'] as $key => $r_bank_name) {
                $r_loan_type = mysqli_real_escape_string($conn, $_POST['r_loan_type'][$key]);
                $r_loan_amount = mysqli_real_escape_string($conn, $_POST['r_loan_amount'][$key]);
                $r_roi = mysqli_real_escape_string($conn, $_POST['r_roi'][$key]);
                $r_tenure = mysqli_real_escape_string($conn, $_POST['r_tenure'][$key]);  
                $r_emi = mysqli_real_escape_string($conn, $_POST['r_emi'][$key]);     
                $first_emi_date = mysqli_real_escape_string($conn, $_POST['first_emi_date'][$key]);     
                $last_emi_date = mysqli_real_escape_string($conn, $_POST['last_emi_date'][$key]);     
                $loan_account_name = mysqli_real_escape_string($conn, $_POST['loan_account_name'][$key]);     


                $bank_rel_sql = "INSERT INTO `tbl_database_relation_bank`(`database_id`, `r_bank_name`, `r_loan_type`, `r_loan_amount`, `r_roi`, `r_tenure`,`r_emi`,`first_emi_date`,`last_emi_date`,`loan_account_name`, `created_at`) 
                             VALUES ('$last_id', '$r_bank_name', '$r_loan_type', '$r_loan_amount', '$r_roi', '$r_tenure','$r_emi','$first_emi_date','$last_emi_date','$loan_account_name','$created_at')";

                mysqli_query($conn, $bank_rel_sql);
            }
        } 

        // vehicle table
        if (!empty($_POST['vehicle_number'])) {
            foreach ($_POST['vehicle_number'] as $key => $vehicle_number) {
                $vehicle_make = mysqli_real_escape_string($conn, $_POST['vehicle_make'][$key]);
                $vehical_modal = mysqli_real_escape_string($conn, $_POST['vehical_modal'][$key]);
                $manufacture_year = mysqli_real_escape_string($conn, $_POST['manufacture_year'][$key]);
                $engine_number = mysqli_real_escape_string($conn, $_POST['engine_number'][$key]);  
                $chases_number = mysqli_real_escape_string($conn, $_POST['chases_number'][$key]);     
              
                $vehicle_sql = "INSERT INTO `tbl_database_vehicle_details`(`database_id`, `vehicle_number`, `vehicle_make`, `vehical_modal`, `manufacture_year`, `engine_number`,`chases_number`,`created_at`) 
                             VALUES ('$last_id', '$vehicle_number', '$vehicle_make', '$vehical_modal', '$manufacture_year', '$engine_number','$chases_number','$created_at')";

                mysqli_query($conn, $vehicle_sql);
            }
        } 

        // property details
        if (!empty($_POST['p_property_type'])) {
            foreach ($_POST['p_property_type'] as $key => $p_property_type) {
                $p_area = mysqli_real_escape_string($conn, $_POST['p_area'][$key]);
                $p_lands = mysqli_real_escape_string($conn, $_POST['p_lands'][$key]);
                $p_sft = mysqli_real_escape_string($conn, $_POST['p_sft'][$key]);
                $p_market_value = mysqli_real_escape_string($conn, $_POST['p_market_value'][$key]);  
              
              
                $property_sql = "INSERT INTO `tbl_database_property_details`(`database_id`, `p_property_type`, `p_area`, `p_lands`, `p_sft`, `p_market_value`,`created_at`) 
                             VALUES ('$last_id', '$p_property_type', '$p_area', '$p_lands', '$p_sft', '$p_market_value','$created_at')";

                mysqli_query($conn, $property_sql);
            }
        } 

        // credit card details
        if (!empty($_POST['c_bank_name'])) {
            foreach ($_POST['c_bank_name'] as $key => $c_bank_name) {
                $c_limit = mysqli_real_escape_string($conn, $_POST['c_limit'][$key]);
              
                $credit_sql = "INSERT INTO `tbl_database_credit_card_details`(`database_id`, `c_bank_name`, `c_limit`, `created_at`) 
                             VALUES ('$last_id', '$c_bank_name', '$c_limit','$created_at')";

                mysqli_query($conn, $credit_sql);
            }
        } 

        // calling status details
        if (!empty($_POST['calling_type_loan'])) {
            foreach ($_POST['calling_type_loan'] as $key => $calling_type_loan) {
                $calling_bank = mysqli_real_escape_string($conn, $_POST['calling_bank'][$key]);
                $calling_status = mysqli_real_escape_string($conn, $_POST['calling_status'][$key]);
                $calling_sub_status = mysqli_real_escape_string($conn, $_POST['calling_sub_status'][$key]);
                $followup_datetime = mysqli_real_escape_string($conn, $_POST['followup_datetime'][$key]);

                $calling_sql = "INSERT INTO `tbl_database_calling_status`(`database_id`, `calling_type_loan`, `calling_bank`,`calling_status`,`calling_sub_status`,`calling_date_time`, `created_at`) 
                             VALUES ('$last_id', '$calling_type_loan', '$calling_bank','$calling_status','$calling_sub_status','$followup_datetime','$created_at')";

                             echo $calling_sql;
                            //  exit();

                mysqli_query($conn, $calling_sql);
            }
        } 

        echo '<script>
            iziToast.success({
                title: "Success",
                message: "DataBase Added Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="database"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="database"; }, 1000);
        </script>';
    }
}
?>