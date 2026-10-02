<?php

include('../includes/dbConfig.php'); // Include DB connection



// Function to get State Name by ID

// function getStateName($conn, $state) {

//     // echo "SELECT state_name FROM tbl_states WHERE id = '$state'";

//     $query = mysqli_query($conn, "SELECT state_name FROM tbl_state WHERE id = '$state'");

//     $row = mysqli_fetch_assoc($query);

//     return $row['state_name'] ?? 'Unknown'; // Return state name or 'Unknown' if not found

// }



// Function to get City Name by ID

function getCityName($conn, $city) {

    $query = mysqli_query($conn, "SELECT city_name FROM tbl_city WHERE id = '$city'");

    $row = mysqli_fetch_assoc($query);

    return $row['city_name'] ?? 'Unknown'; // Return city name or 'Unknown' if not found

}



// department

function getDepartmentName($conn, $department_id) {

    $query = mysqli_query($conn, "SELECT department_name FROM tbl_department WHERE id = '$department_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['department_name'] ?? 'Unknown'; // Return state name or 'Unknown' if not found

}



// state

function getStateName($conn, $state_id) {

    $query = mysqli_query($conn, "SELECT state_name FROM tbl_state WHERE id = '$state_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['state_name'] ?? 'Unknown'; 

}



// designation for user

function getDesignationName($conn, $designation_id) {

    $query = mysqli_query($conn, "SELECT designation_name FROM tbl_designation WHERE id = '$designation_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['designation_name'] ?? 'Unknown'; // Return state name or 'Unknown' if not found

}



// bank for user

function getBankName($conn, $bank_name) {

    $query = mysqli_query($conn, "SELECT bank_name FROM tbl_bank WHERE id = '$bank_name'");

    $row = mysqli_fetch_assoc($query);

    return $row['bank_name'] ?? 'Unknown'; // Return state name or 'Unknown' if not found

}



// user name for user reporting

function getUserName($conn, $reportingTo) {

    $query = mysqli_query($conn, "SELECT firstName FROM tbl_user WHERE id = '$reportingTo'");

    $row = mysqli_fetch_assoc($query);

    return $row['firstName'] ?? 'Unknown'; // Return state name or 'Unknown' if not found

}



// location

function getLocationName($conn, $work_location) {

    $query = mysqli_query($conn, "SELECT location FROM tbl_location WHERE id = '$work_location'");

    $row = mysqli_fetch_assoc($query);

    return $row['location'] ?? 'Unknown'; 

}

// vendor bank

function getVendorBank($conn, $vendor_bank) {

    $query = mysqli_query($conn, "SELECT vendor_bank_name FROM tbl_vendor_bank WHERE id = '$vendor_bank'");

    $row = mysqli_fetch_assoc($query);

    return $row['vendor_bank_name'] ?? 'Unknown'; 

}



// dsa name

function getDsaName($conn, $bsa_name) {

    $query = mysqli_query($conn, "SELECT bsa_name FROM tbl_bsa_name WHERE id = '$bsa_name'");

    $row = mysqli_fetch_assoc($query);

    return $row['bsa_name'] ?? 'Unknown'; 

}



// loan type

function getLoanType($conn, $loan_type) {

    $query = mysqli_query($conn, "SELECT loan_type FROM tbl_loan_type WHERE id = '$loan_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['loan_type'] ?? 'Unknown'; 

}



// branch state

function getBranchState($conn, $branch_state_id) {

    $query = mysqli_query($conn, "SELECT branch_state_name FROM tbl_branch_state WHERE id = '$branch_state_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['branch_state_name'] ?? 'Unknown'; 

}



// branch location

function getBranchLocation($conn, $location) {

    $query = mysqli_query($conn, "SELECT branch_location FROM tbl_branch_location WHERE id = '$location'");

    $row = mysqli_fetch_assoc($query);

    return $row['branch_location'] ?? 'Unknown'; 

}



// customer type

function getCustomerType($conn, $customer_type) {

    $query = mysqli_query($conn, "SELECT customer_type FROM tbl_customer_type WHERE id = '$customer_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['customer_type'] ?? 'Unknown'; 

}



// sub location

function getSubLocation($conn, $sub_location_id) {

    $query = mysqli_query($conn, "SELECT sub_location FROM tbl_sub_location WHERE id = '$sub_location_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['sub_location'] ?? 'Unknown'; 

}



// industry type

function getIndustryType($conn, $industry_type) {

    $query = mysqli_query($conn, "SELECT industry_name FROM tbl_industry_type WHERE id = '$industry_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['industry_name'] ?? 'Unknown'; 

}

// pin code

function getPinCode($conn, $pin_code) {

    $query = mysqli_query($conn, "SELECT pincode FROM tbl_pincode WHERE id = '$pin_code'");

    $row = mysqli_fetch_assoc($query);

    return $row['pincode'] ?? 'Unknown'; 

}



// business type

function getBusinessType($conn, $business_type) {

    $query = mysqli_query($conn, "SELECT business_name FROM tbl_business_type WHERE id = '$business_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['business_name'] ?? 'Unknown'; 

}



// portfolio bank name

function getPortfolioBank($conn, $bank_name ) {

    $query = mysqli_query($conn, "SELECT bank_name FROM tbl_portfolio_bank WHERE id = '$bank_name'");

    $row = mysqli_fetch_assoc($query);

    return $row['bank_name'] ?? 'Unknown'; 

}



// roi

function getRoiDetails($conn, $roi ) {

    $query = mysqli_query($conn, "SELECT roi_name FROM tbl_roi WHERE id = '$roi'");

    $row = mysqli_fetch_assoc($query);

    return $row['roi_name'] ?? 'Unknown'; 

}



// tenure

function getTenureDetails($conn, $tenure ) {

    $query = mysqli_query($conn, "SELECT tenure_name FROM tbl_tenure WHERE id = '$tenure'");

    $row = mysqli_fetch_assoc($query);

    return $row['tenure_name'] ?? 'Unknown'; 

}



// financial year

function getFinancialYear($conn, $financial_year_id ) {

    $query = mysqli_query($conn, "SELECT financial_year FROM tbl_financial_year WHERE id = '$financial_year_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['financial_year'] ?? 'Unknown'; 

}



// App Status

function getAppStatus($conn, $app_status_id ) {

    $query = mysqli_query($conn, "SELECT app_status FROM tbl_app_status WHERE id = '$app_status_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['app_status'] ?? 'Unknown'; 

}



// file Status

function getFileStatus($conn, $file_status_id ) {

    $query = mysqli_query($conn, "SELECT file_status FROM tbl_file_status WHERE id = '$file_status_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['file_status'] ?? 'Unknown'; 

}



// rating type

function getRatingType($conn, $rating_type_id ) {

    $query = mysqli_query($conn, "SELECT rating_type FROM tbl_type_rating WHERE id = '$rating_type_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['rating_type'] ?? 'Unknown'; 

}



// vehical make

function getVehicalMake($conn, $vehical_make ) {

    $query = mysqli_query($conn, "SELECT vehical_make FROM tbl_vehical_make WHERE id = '$vehical_make'");

    $row = mysqli_fetch_assoc($query);

    return $row['vehical_make'] ?? 'Unknown'; 

}



// manufacture year

function getManufactureYear($conn, $manufacture_year ) {

    $query = mysqli_query($conn, "SELECT manufacture_year FROM tbl_manufacture_year WHERE id = '$manufacture_year'");

    $row = mysqli_fetch_assoc($query);

    return $row['manufacture_year'] ?? 'Unknown'; 

}



// insurance company name

function getInsCompanyName($conn, $ins_company_name ) {

    $query = mysqli_query($conn, "SELECT company_name FROM tbl_ins_company_name WHERE id = '$ins_company_name'");

    $row = mysqli_fetch_assoc($query);

    return $row['company_name'] ?? 'Unknown'; 

}

// vehical modal 

function getVehicalModal($conn, $vehical_modal ) {

    $query = mysqli_query($conn, "SELECT vehical_modal FROM tbl_vehical_modal WHERE id = '$vehical_modal'");

    $row = mysqli_fetch_assoc($query);

    return $row['vehical_modal'] ?? 'Unknown'; 

}

// calling status

function getCallingStatus($conn, $calling_status_id ) {

    $query = mysqli_query($conn, "SELECT calling_status FROM tbl_calling_status WHERE id = '$calling_status_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['calling_status'] ?? 'Unknown'; 

}



// calling bank

function getCallingBank($conn, $calling_bank_id ) {

    $query = mysqli_query($conn, "SELECT calling_bank FROM tbl_calling_bank WHERE id = '$calling_bank_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['calling_bank'] ?? 'Unknown'; 

}



// company name

function getCompanyName($conn, $name ) {

    $query = mysqli_query($conn, "SELECT company_name FROM tbl_company_name WHERE id = '$name'");

    $row = mysqli_fetch_assoc($query);

    return $row['company_name'] ?? 'Unknown'; 

}



// Banker Designation

function getBankerDesignation($conn, $banker_designation ) {

    $query = mysqli_query($conn, "SELECT designation_name FROM tbl_banker_designation WHERE id = '$banker_designation'");

    $row = mysqli_fetch_assoc($query);

    return $row['designation_name'] ?? 'Unknown'; 

}

// type of customer name

function getCustomerName($conn, $customer_id ) {

    $query = mysqli_query($conn, "SELECT customer_name FROM tbl_type_of_customer WHERE id = '$customer_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['customer_name'] ?? 'Unknown'; 

}

// category

function getPayoutCategory($conn, $category_name ) {

    $query = mysqli_query($conn, "SELECT category_name FROM tbl_payout_category WHERE id = '$category_name'");

    $row = mysqli_fetch_assoc($query);

    return $row['category_name'] ?? 'Unknown'; 

}

// industry type

function getSENPIndustryType($conn, $industry_id ) {

    $query = mysqli_query($conn, "SELECT industry_name FROM tbl_senp_industry_type WHERE id = '$industry_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['industry_name'] ?? 'Unknown'; 

}

// source

function getSourceName($conn, $source ) {

    $query = mysqli_query($conn, "SELECT source FROM tbl_data_source WHERE id = '$source'");

    $row = mysqli_fetch_assoc($query);

    return $row['source'] ?? 'Unknown'; 

}



// calling type loan

function getCallingTypeLoan($conn, $calling_type_loan ) {

    $query = mysqli_query($conn, "SELECT calling_type_loan FROM tbl_calling_type_loan WHERE id = '$calling_type_loan'");

    $row = mysqli_fetch_assoc($query);

    return $row['calling_type_loan'] ?? 'Unknown'; 

}



// calling sub status

function getCallingSubStatus($conn, $calling_sub_status ) {

    $query = mysqli_query($conn, "SELECT calling_sub_status FROM tbl_calling_sub_status WHERE id = '$calling_sub_status'");

    $row = mysqli_fetch_assoc($query);

    return $row['calling_sub_status'] ?? 'Unknown'; 

}

// salaried company name

function getSalariedCompany($conn, $sal_company_type ) {

    $query = mysqli_query($conn, "SELECT company_type FROM tbl_company_type WHERE id = '$sal_company_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['company_type'] ?? 'Unknown'; 

}



// salaried gross salary

function getSalariedGrossSalary($conn, $sal_grossSalary ) {

    $query = mysqli_query($conn, "SELECT grossSalary FROM tbl_senp_grosssalary WHERE id = '$sal_grossSalary'");

    $row = mysqli_fetch_assoc($query);

    return $row['grossSalary'] ?? 'Unknown'; 

}



// salaried net salary

function getSalariedNetSalary($conn, $sal_netSalary ) {

    $query = mysqli_query($conn, "SELECT netSalary FROM tbl_senp_netsalary WHERE id = '$sal_netSalary'");

    $row = mysqli_fetch_assoc($query);

    return $row['netSalary'] ?? 'Unknown'; 

}



// salaried designation name

function getSalariedDesignation($conn, $sal_designation_name ) {

    $query = mysqli_query($conn, "SELECT designation_name FROM tbl_salaried_designation WHERE id = '$sal_designation_name'");

    $row = mysqli_fetch_assoc($query);

    return $row['designation_name'] ?? 'Unknown'; 

}



// salaried payment type

function getSalariedPaymentType($conn, $sal_salary_payment_type ) {

    $query = mysqli_query($conn, "SELECT salary_payment_type FROM tbl_salary_payment_type WHERE id = '$sal_salary_payment_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['salary_payment_type'] ?? 'Unknown'; 

}

// salaried present experience

function getSalariedPresentExp($conn, $sal_present_experience ) {

    $query = mysqli_query($conn, "SELECT present_experience FROM tbl_present_experience WHERE id = '$sal_present_experience'");

    $row = mysqli_fetch_assoc($query);

    return $row['present_experience'] ?? 'Unknown'; 

}



// salaried total experience

function getSalariedTotalExp($conn, $sal_total_experience ) {

    $query = mysqli_query($conn, "SELECT total_experience FROM tbl_total_experience WHERE id = '$sal_total_experience'");

    $row = mysqli_fetch_assoc($query);

    return $row['total_experience'] ?? 'Unknown'; 

}



// senp business name

function getSenpBusinessName($conn, $senp_business_name ) {

    $query = mysqli_query($conn, "SELECT business_name FROM tbl_senp_business_type WHERE id = '$senp_business_name'");

    $row = mysqli_fetch_assoc($query);

    return $row['business_name'] ?? 'Unknown'; 

}



// senp company name

function getSenpCompanysName($conn, $senp_company_type ) {

    $query = mysqli_query($conn, "SELECT company_type FROM tbl_company_type WHERE id = '$senp_company_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['company_type'] ?? 'Unknown'; 

}



// senp vintage year

function getSenpVintageYear($conn, $senp_vintage_year ) {

    $query = mysqli_query($conn, "SELECT vintage_year FROM tbl_vintage_year WHERE id = '$senp_vintage_year'");

    $row = mysqli_fetch_assoc($query);

    return $row['vintage_year'] ?? 'Unknown'; 

}



// senp rating type

function getSenpRatingType($conn, $senp_rating_type ) {

    $query = mysqli_query($conn, "SELECT rating_type FROM tbl_type_rating WHERE id = '$senp_rating_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['rating_type'] ?? 'Unknown'; 

}



// senp rating 

function getSenpRating($conn, $senp_rating_name ) {

    $query = mysqli_query($conn, "SELECT rating_name FROM tbl_rating WHERE id = '$senp_rating_name'");

    $row = mysqli_fetch_assoc($query);

    return $row['rating_name'] ?? 'Unknown'; 

}



// senp branches

function getSenpBranches($conn, $senp_branches ) {

    $query = mysqli_query($conn, "SELECT branches FROM tbl_senp_branches WHERE id = '$senp_branches'");

    $row = mysqli_fetch_assoc($query);

    return $row['branches'] ?? 'Unknown'; 

}



// senp employees

function getSenpEmployees($conn, $senp_employees ) {

    $query = mysqli_query($conn, "SELECT employees FROM tbl_senp_employees WHERE id = '$senp_employees'");

    $row = mysqli_fetch_assoc($query);

    return $row['employees'] ?? 'Unknown'; 

}



// bank account type

function getBankAccountType($conn, $b_account_type ) {

    $query = mysqli_query($conn, "SELECT account_type FROM tbl_bank_account_type WHERE id = '$b_account_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['account_type'] ?? 'Unknown'; 

}



// database property type

function getPropertyType($conn, $p_property_type ) {

    $query = mysqli_query($conn, "SELECT property_type FROM tbl_bank_property_type WHERE id = '$p_property_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['property_type'] ?? 'Unknown'; 

}



// database doctor qualification

function getDoctorQualification($conn, $doctor_qualification ) {

    $query = mysqli_query($conn, "SELECT qualification FROM tbl_sep_doctor_qualification WHERE id = '$doctor_qualification'");

    $row = mysqli_fetch_assoc($query);

    return $row['qualification'] ?? 'Unknown'; 

}



// database doctor year of pass

function getDoctorYearOfPass($conn, $doctor_year_pass ) {

    $query = mysqli_query($conn, "SELECT year_pass FROM tbl_sep_doctor_yearpass WHERE id = '$doctor_year_pass'");

    $row = mysqli_fetch_assoc($query);

    return $row['year_pass'] ?? 'Unknown'; 

}



// database doctor specialisation

function getDoctorSpecialisation($conn, $doctor_specialisation ) {

    $query = mysqli_query($conn, "SELECT specialisation FROM tbl_sep_doctor_specialisation WHERE id = '$doctor_specialisation'");

    $row = mysqli_fetch_assoc($query);

    return $row['specialisation'] ?? 'Unknown'; 

}



// database doctor university

function getDoctorUniversity($conn, $doctor_university ) {

    $query = mysqli_query($conn, "SELECT university FROM tbl_sep_doctor_university WHERE id = '$doctor_university'");

    $row = mysqli_fetch_assoc($query);

    return $row['university'] ?? 'Unknown'; 

}



// database ca year of pass

function getCaYearOfPass($conn, $ca_year_pass ) {

    $query = mysqli_query($conn, "SELECT year_pass FROM tbl_sep_ca_yearpass WHERE id = '$ca_year_pass'");

    $row = mysqli_fetch_assoc($query);

    return $row['year_pass'] ?? 'Unknown'; 

}

//database credit card bank

function getCreditCardBank($conn, $c_bank_name ) {

    $query = mysqli_query($conn, "SELECT bank_name FROM tbl_credit_card_bank WHERE id = '$c_bank_name'");

    $row = mysqli_fetch_assoc($query);

    return $row['bank_name'] ?? 'Unknown'; 

}

// database nri country

function getNriCountry($conn, $nri_country ) {

    $query = mysqli_query($conn, "SELECT country FROM tbl_nri_country WHERE id = '$nri_country'");

    $row = mysqli_fetch_assoc($query);

    return $row['country'] ?? 'Unknown'; 

}

// database educational institute

function getEduInstitute($conn, $educational_institute ) {

    $query = mysqli_query($conn, "SELECT institute FROM tbl_educational_institute WHERE id = '$educational_institute'");

    $row = mysqli_fetch_assoc($query);

    return $row['institute'] ?? 'Unknown'; 

}



// database educational year of pass

function getEduYearOfPass($conn, $educational_students ) {

    $query = mysqli_query($conn, "SELECT no_students FROM tbl_educational_no_students WHERE id = '$educational_students'");

    $row = mysqli_fetch_assoc($query);

    return $row['no_students'] ?? 'Unknown'; 

}



// database educational company type

function getEduCompanyType($conn, $edu_company_type ) {

    $query = mysqli_query($conn, "SELECT company_type FROM tbl_company_type WHERE id = '$edu_company_type'");

    $row = mysqli_fetch_assoc($query);

    return $row['company_type'] ?? 'Unknown'; 

}



// assessment year

function getAssessmentYear($conn, $assessment_year ) {

    $query = mysqli_query($conn, "SELECT assessment_year FROM tbl_assessment_year WHERE id = '$assessment_year'");

    $row = mysqli_fetch_assoc($query);

    return $row['assessment_year'] ?? 'Unknown'; 

}



// partner type

function getPartnerType($conn, $partnerType) {

    $query = mysqli_query($conn, "SELECT partner_type FROM tbl_partner_type WHERE id = '$partnerType'");

    $row = mysqli_fetch_assoc($query);

    return $row['partner_type'] ?? 'Unknown'; 

}



// Appointment Status

function getAppointmentStatus($conn, $appt_status_id) {

    $query = mysqli_query($conn, "SELECT appt_status FROM tbl_appointment_status WHERE id = '$appt_status_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['appt_status'] ?? 'Unknown'; 

}



// partner calling status

function getPartnerCallingStatus($conn, $calling_status_id) {

    $query = mysqli_query($conn, "SELECT calling_status FROM tbl_partner_calling_status WHERE id = '$calling_status_id'");

    $row = mysqli_fetch_assoc($query);

    return $row['calling_status'] ?? 'Unknown'; 

}



// partner calling sub status

function getPartnerCallingSubStatus($conn, $calling_sub_status) {

    $query = mysqli_query($conn, "SELECT calling_sub_status FROM tbl_partner_calling_sub_status WHERE id = '$calling_sub_status'");

    $row = mysqli_fetch_assoc($query);

    return $row['calling_sub_status'] ?? 'Unknown'; 

}





// Appointment bank

function getAppointmentBank($conn, $appt_bank) {

    $query = mysqli_query($conn, "SELECT bank_name FROM tbl_appointment_bank WHERE id = '$appt_bank'");

    $row = mysqli_fetch_assoc($query);

    return $row['bank_name'] ?? 'Unknown'; 

}



// Appointment product

function getAppointmentProduct($conn, $appt_product) {

    $query = mysqli_query($conn, "SELECT product_name FROM tbl_appointment_product WHERE id = '$appt_product'");

    $row = mysqli_fetch_assoc($query);

    return $row['product_name'] ?? 'Unknown'; 

}





// Appointment sub status

function getAppointmentSubStatus($conn, $appt_sub_status) {

    $query = mysqli_query($conn, "SELECT appt_sub_status FROM tbl_appointment_sub_status WHERE id = '$appt_sub_status'");

    $row = mysqli_fetch_assoc($query);

    return $row['appt_sub_status'] ?? 'Unknown'; 

}



// user full name

function getUserFullName($conn, $createdBy) {

    $query = mysqli_query($conn, "SELECT firstName, lastName FROM tbl_user WHERE username = '$createdBy'");

    $row = mysqli_fetch_assoc($query);

    

    return isset($row['firstName'], $row['lastName']) 

        ? $row['firstName'] . ' ' . $row['lastName'] 

        : 'Unknown';

}


// payout type
function getPayoutType($conn, $payout_name) {
    $query = mysqli_query($conn, "SELECT payout_name FROM tbl_payout_type WHERE id = '$payout_name'");
    $row = mysqli_fetch_assoc($query);
    return $row['payout_name'] ?? 'Unknown'; 
}

// common function for all 
function fetchColumnValue($conn, $table, $searchColumn, $searchValue, $returnColumn) {
    $query = "SELECT `$returnColumn` FROM `$table` WHERE `$searchColumn` = '$searchValue' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Query Error: " . mysqli_error($conn);
    }

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row[$returnColumn] ?? 'Unknown';
    }

    return 'Unknown';
}

function getCreatedByName($conn, $username) {
    // Check tbl_user
    $sql = "SELECT firstName, lastName, username FROM tbl_user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $u = $res->fetch_assoc();
        return $u['firstName'] . " " . $u['lastName'] ;
    }

    // Check tbl_sdsa_users
    $sql = "SELECT first_name, last_name, username FROM tbl_sdsa_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $u = $res->fetch_assoc();
        return $u['first_name'] . " " . $u['last_name'] ;
    }

    // Check tbl_partner_users
    $sql = "SELECT first_name, last_name, username FROM tbl_partner_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $u = $res->fetch_assoc();
        return $u['first_name'] . " " . $u['last_name'];
    }

    // Check tbl_cbo_users
    $sql = "SELECT first_name, last_name, username FROM tbl_cbo_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $u = $res->fetch_assoc();
        return $u['first_name'] . " " . $u['last_name'];
    }

     // Check tbl_rbh_users
    $sql = "SELECT first_name, last_name, username FROM tbl_rbh_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $u = $res->fetch_assoc();
        return $u['first_name'] . " " . $u['last_name'];
    }

     // Check tbl_bh_users
    $sql = "SELECT first_name, last_name, username FROM tbl_bh_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $u = $res->fetch_assoc();
        return $u['first_name'] . " " . $u['last_name'];
    }

     // Check tbl_sdsa_emp_users
    $sql = "SELECT first_name, last_name, username FROM tbl_sdsa_emp_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $u = $res->fetch_assoc();
        return $u['first_name'] . " " . $u['last_name'];
    }

    // If not found in any table
    return $username;
}




function displayFile($file) {

    if (!$file) return "No File Uploaded";



    $filePath = "../uploads/users-document/" . $file; // Adjust based on your directory

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    

    // Check if it's an image

    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($ext, $imageExtensions)) {

        return "<a href='$filePath' target='_blank'>View Image</a>";

    }



    // Check if it's a PDF

    if ($ext === 'pdf') {

        return "<a href='$filePath' target='_blank'>View PDF</a>";

    }



    // For other document types (DOC, DOCX, etc.)

    return "<a href='$filePath' target='_blank'>Download File</a>";

}


// Compact Pagination Function
function renderPagination($currentPage, $totalPages, $queryParams) {
    $visibleRange = 3; // Pages around the current page
    $edgePages = 5; // Pages to show at the start and end

    echo '<nav aria-label="Page navigation">';
    echo '<ul class="pagination justify-content-center">';

    // Previous Button
    if ($currentPage > 1) {
        $queryParams['page'] = $currentPage - 1;
        echo "<li class='page-item'><a class='page-link' href='?" . http_build_query($queryParams) . "'>&laquo; Prev</a></li>";
    }

    // Show first few pages
    for ($i = 1; $i <= $edgePages; $i++) {
        $activeClass = ($i == $currentPage) ? 'active' : '';
        $queryParams['page'] = $i;
        echo "<li class='page-item $activeClass'><a class='page-link' href='?" . http_build_query($queryParams) . "'>$i</a></li>";
    }

    // Add ellipsis if needed
    if ($currentPage > $edgePages + $visibleRange + 1) {
        echo "<li class='page-item disabled'><span class='page-link'>---</span></li>";
    }

    // Show pages around the current page
    $startPage = max($edgePages + 1, $currentPage - $visibleRange);
    $endPage = min($totalPages - $edgePages, $currentPage + $visibleRange);
    for ($i = $startPage; $i <= $endPage; $i++) {
        $activeClass = ($i == $currentPage) ? 'active' : '';
        $queryParams['page'] = $i;
        echo "<li class='page-item $activeClass'><a class='page-link' href='?" . http_build_query($queryParams) . "'>$i</a></li>";
    }

    // Add ellipsis if needed
    if ($currentPage < $totalPages - $edgePages - $visibleRange) {
        echo "<li class='page-item disabled'><span class='page-link'>---</span></li>";
    }

    // Show last few pages
    for ($i = max($totalPages - $edgePages + 1, $endPage + 1); $i <= $totalPages; $i++) {
        $activeClass = ($i == $currentPage) ? 'active' : '';
        $queryParams['page'] = $i;
        echo "<li class='page-item $activeClass'><a class='page-link' href='?" . http_build_query($queryParams) . "'>$i</a></li>";
    }

    // Next Button
    if ($currentPage < $totalPages) {
        $queryParams['page'] = $currentPage + 1;
        echo "<li class='page-item'><a class='page-link' href='?" . http_build_query($queryParams) . "'>Next &raquo;</a></li>";
    }

    echo '</ul>';
    echo '</nav>';
}




?>

