<?php session_start(); 

include('../includes/dbConfig.php');

include('../includes/validation.php'); 

include('../includes/functions.php'); 

// $loggedInUser = $_SESSION['loggedInUser'];

// echo $loggedInUser;



if (!isset($_GET['id']) || empty($_GET['id'])) {

    echo '<script>

        alert("Invalid Request!");

        window.location.href = "appointment";

    </script>';

    exit();

}

$get_id = $_GET['id'];



$sql = mysqli_query($conn, "SELECT * FROM tbl_appointment WHERE id='$get_id' AND status='1'");

if(mysqli_num_rows($sql)>0){

    while($row = mysqli_fetch_assoc($sql)){

        // common fields

        $database_id = $row['database_id'];

        $mobile_number = $row['mobile_number'];

        $lead_name = $row['lead_name'];

        $email_id = $row['email_id'];

        $company_name = $row['company_name'];

        $alternative_mobile = $row['alternative_mobile'];

        $state = $row['state'];

        $location = $row['location'];

        $sub_location = $row['sub_location'];

        $pin_code = $row['pin_code'];

        $source = $row['source'];

        $visiting_card = $row['visiting_card'];

        $user_qualification = $row['user_qualification'];

        $customer_type = $row['customer_type'];



        $calling_type_loan = $row['calling_type_loan'];

        $calling_bank = $row['calling_bank'];

        $calling_status = $row['calling_status'];

        $calling_sub_status = $row['calling_sub_status'];

        $database_notes = $row['database_notes'];



        // salaried

        $sal_company_type = $row['sal_company_type'];

        $sal_birth_date = $row['sal_birth_date'];

        $sal_grossSalary = $row['sal_grossSalary'];

        $gross_sal_amount = $row['gross_sal_amount'];

        $sal_netSalary = $row['sal_netSalary'];

        $net_sal_amount = $row['net_sal_amount'];

        $sal_designation_name = $row['sal_designation_name'];

        $sal_official_email = $row['sal_official_email'];

        $sal_salary_payment_type = $row['sal_salary_payment_type'];

        $sal_present_experience = $row['sal_present_experience'];

        $sal_total_experience = $row['sal_total_experience'];



        // senp

        $senp_industry_name = $row['senp_industry_name'];

        $senp_business_name = $row['senp_business_name'];

        $senp_company_type = $row['senp_company_type'];

        $senp_nature_business = $row['senp_nature_business'];

        $senp_incorporaton_date = $row['senp_incorporaton_date'];

        $senp_vintage_year = $row['senp_vintage_year'];

        $senp_factory_address = $row['senp_factory_address'];

        $senp_factory_pincode = $row['senp_factory_pincode'];

        $senp_gst_number = $row['senp_gst_number'];

        $senp_company_pan_number = $row['senp_company_pan_number'];

        $senp_website = $row['senp_website'];

        $senp_rating_type = $row['senp_rating_type'];

        $senp_rating_name = $row['senp_rating_name'];

        $senp_branches = $row['senp_branches'];

        $senp_employees = $row['senp_employees'];



        // sep

        $sep_type_professional = $row['sep_type_professional'];

        $doctor_qualification = $row['doctor_qualification'];

        $doctor_year_pass = $row['doctor_year_pass'];

        $doctor_specialisation = $row['doctor_specialisation'];

        $doctor_university = $row['doctor_university'];

        $firm_name = $row['firm_name'];

        $ca_year_pass = $row['ca_year_pass'];

        $ca_number = $row['ca_number'];



        // nri

        $nri_country = $row['nri_country'];

        $nri_gross_salary = $row['nri_gross_salary'];



        // educational

        $educational_institute = $row['educational_institute'];

        $educational_students = $row['educational_students'];

        $edu_company_type = $row['edu_company_type'];

        $educational_strength = $row['educational_strength'];



        $residental_address = $row['residental_address'];

        $office_address = $row['office_address'];

        $branch_address = $row['branch_address'];



    }

}







?>

<!DOCTYPE html>



<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"

    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">



<?php include('../includes/header.php'); ?>

<style>

.table tr td {

    padding: 5px 0px !important;

}

</style>



<body>



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

                        <div class="col-xl-12">

                            <!-- <h6 class="text-muted">Filled Pills</h6> -->

                            <div class="nav-align-top mb-6">

                                <ul class="nav nav-pills mb-4 nav-fill" role="tablist">

                                    <li class="nav-item mb-1 mb-sm-0">

                                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"

                                            data-bs-target="#navs-pills-justified-home"

                                            aria-controls="navs-pills-justified-home" aria-selected="true"><span

                                                class="d-none d-sm-block"><i

                                                    class="tf-icons bx bx-user bx-sm me-1_5 align-text-bottom"></i>

                                                Profile

                                                <i class="bx bx-user bx-sm d-sm-none"></i></button>

                                    </li>

                                    <li class="nav-item mb-1 mb-sm-0">

                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"

                                            data-bs-target="#navs-pills-justified-bank_account"

                                            aria-controls="navs-pills-justified-bank_account"

                                            aria-selected="false"><span class="d-none d-sm-block"><i

                                                    class="tf-icons bx bx-building bx-sm me-1_5 align-text-bottom"></i>

                                                Bank Account</span><i

                                                class="bx bx-building bx-sm d-sm-none"></i></button>

                                    </li>

                                    <li class="nav-item mb-1 mb-sm-0">

                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"

                                            data-bs-target="#navs-pills-justified-rel_bank"

                                            aria-controls="navs-pills-justified-rel_bank" aria-selected="false"><span

                                                class="d-none d-sm-block"><i

                                                    class="tf-icons bx bx-link-alt bx-sm me-1_5 align-text-bottom"></i>

                                                Relation With Bank</span><i

                                                class="bx bx-link-alt bx-sm d-sm-none"></i></button>

                                    </li>

                                    <li class="nav-item mb-1 mb-sm-0">

                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"

                                            data-bs-target="#navs-pills-justified-experience"

                                            aria-controls="navs-pills-justified-experience" aria-selected="false"><span

                                                class="d-none d-sm-block"><i

                                                    class="tf-icons bx bx-car bx-sm me-1_5 align-text-bottom"></i>

                                                Vehicle</span><i class="bx bx-car bx-sm d-sm-none"></i></button>

                                    </li>

                                    <li class="nav-item mb-1 mb-sm-0">

                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"

                                            data-bs-target="#navs-pills-justified-perperty"

                                            aria-controls="navs-pills-justified-perperty" aria-selected="true"><span

                                                class="d-none d-sm-block"><i

                                                    class="tf-icons bx bx-home bx-sm me-1_5 align-text-bottom"></i>

                                                Property

                                                <i class="bx bx-home bx-sm d-sm-none"></i></button>

                                    </li>

                                    <li class="nav-item mb-1 mb-sm-0">

                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"

                                            data-bs-target="#navs-pills-justified-credit_card"

                                            aria-controls="navs-pills-justified-credit_card" aria-selected="true"><span

                                                class="d-none d-sm-block"><i

                                                    class="tf-icons bx bx-wallet bx-sm me-1_5 align-text-bottom"></i>

                                                Credit Card

                                                <i class="bx bx-wallet bx-sm d-sm-none"></i></button>

                                    </li>

                                    <li class="nav-item mb-1 mb-sm-0">

                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"

                                            data-bs-target="#navs-pills-justified-calling_status"

                                            aria-controls="navs-pills-justified-calling_status"

                                            aria-selected="true"><span class="d-none d-sm-block"><i

                                                    class="tf-icons bx bx-calendar-check bx-sm me-1_5 align-text-bottom"></i>

                                                    Appointment Status

                                                <i class="bx bx-calendar-check bx-sm d-sm-none"></i></button>

                                    </li>

                                </ul>

                                <div class="tab-content">

                                    <!-- Personal Details -->

                                    <div class="tab-pane fade show active" id="navs-pills-justified-home"

                                        role="tabpanel">

                                        <!-- <?php 

                                            if ($loggedInUserRank == 'superAdmin'){

                                            ?> -->

                                        <div class="text-end">

                                            <a href="edit?id=<?= $get_id ?>" class="btn btn-primary btn btn-sm">Edit

                                                Profile</a>

                                        </div>

                                        <!-- <?php } ?> -->



                                        <div class="row">

                                            <h4>Appointment  Details</h4>

                                            <div class="col-md-6">

                                                <table class="table table-borderless w-auto m-0">

                                                    <tbody>

                                                        <tr>

                                                            <td class="fw-bold">Mobile No :</td>

                                                            <td> <?= $mobile_number; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Lead Name :</td>

                                                            <td> <?= $lead_name; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Email Id :</td>

                                                            <td> <?= $email_id; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Company Name :</td>

                                                            <td> <?= $company_name; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Alternative No:</td>

                                                            <td> <?= $alternative_mobile; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">State:</td>

                                                            <td> <?= getStateName($conn, $state); ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Location :</td>

                                                            <td> <?= getLocationName($conn, $location); ?></td>

                                                        </tr>



                                                        <tr>

                                                            <td class="fw-bold">Sub Location :</td>

                                                            <td> <?= getSubLocation($conn, $sub_location); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Pincode :</td>

                                                            <td> <?= getPinCode($conn, $pin_code); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Source :</td>

                                                            <td> <?= getSourceName($conn, $source); ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Visiting Card :</td>

                                                            <td> <a href="../uploads/dataBase/<?= $visiting_card; ?>"

                                                                    target="_blank"> View Card</a></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Qualification :</td>

                                                            <td> <?= $user_qualification; ?></td>

                                                        </tr>

                                                        

                                                        <tr>

                                                            <td class="fw-bold">Notes :</td>

                                                            <td> <?= $database_notes; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Residential Address :</td>

                                                            <td> <?= $residental_address; ?></td>

                                                        </tr>



                                                    </tbody>

                                                </table>

                                            </div>

                                            <div class="col-md-6">

                                                <table class="table table-borderless w-auto m-0">



                                                    <tbody>

                                                        <tr>

                                                            <td class="fw-bold">Customer Type : </td>

                                                            <td> <?= getCustomerType($conn, $customer_type); ?></td>

                                                        </tr>



                                                        <?php 

                                                       if($customer_type == '39'){

                                                        ?>

                                                        <tr>

                                                            <td class="fw-bold">Office Address :</td>

                                                            <td> <?= $office_address; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Company Name : </td>

                                                            <td> <?= getSalariedCompany($conn, $sal_company_type); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Birth Date : </td>

                                                            <td> <?= $sal_birth_date; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Gross Salary : </td>

                                                            <td> <?= getSalariedGrossSalary($conn, $sal_grossSalary); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Gross Salary Amount : </td>

                                                            <td> <?= $gross_sal_amount; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Net Salary : </td>

                                                            <td> <?= getSalariedNetSalary($conn, $sal_netSalary); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Net Salary Amount : </td>

                                                            <td> <?= $net_sal_amount; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Designation Name : </td>

                                                            <td> <?= getSalariedDesignation($conn, $sal_designation_name); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Official Email : </td>

                                                            <td> <?= $sal_official_email; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Payment Type : </td>

                                                            <td> <?= getSalariedPaymentType($conn, $sal_salary_payment_type); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Present Experience : </td>

                                                            <td> <?= getSalariedPresentExp($conn, $sal_present_experience); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Total Experience : </td>

                                                            <td> <?= getSalariedTotalExp($conn, $sal_total_experience); ?>

                                                            </td>

                                                        </tr>

                                                        <?php

                                                       } else if($customer_type == '35'){

                                                        ?>

                                                        <tr>

                                                            <td class="fw-bold">Office Address :</td>

                                                            <td> <?= $office_address; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Branch Address :</td>

                                                            <td> <?= $branch_address; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Industry Name : </td>

                                                            <td> <?= getSENPIndustryType($conn, $senp_industry_name); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Business Name : </td>

                                                            <td> <?= getSenpBusinessName($conn, $senp_business_name); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Company Name : </td>

                                                            <td> <?= getSenpCompanysName($conn, $senp_company_type); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Nature Business : </td>

                                                            <td> <?= $senp_nature_business; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Incorporation Date : </td>

                                                            <td> <?= $senp_incorporaton_date; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Vintage Year : </td>

                                                            <td> <?= getSenpVintageYear($conn, $senp_vintage_year); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Factory Address : </td>

                                                            <td> <?= $senp_factory_address; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Factory Pincode : </td>

                                                            <td> <?= $senp_factory_pincode; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">GST Number : </td>

                                                            <td> <?= $senp_gst_number; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Company PAN Number : </td>

                                                            <td> <?= $senp_company_pan_number; ?></td>

                                                        </tr>



                                                        <tr>

                                                            <td class="fw-bold">Website : </td>

                                                            <td> <a href="<?= $senp_website; ?>"

                                                                    target="_blank"><?= $senp_website; ?></a></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Rating Type : </td>

                                                            <td> <?= getSenpRatingType($conn, $senp_rating_type); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Rating : </td>

                                                            <td> <?= getSenpRating($conn, $senp_rating_name); ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Branches : </td>

                                                            <td> <?= getSenpBranches($conn, $senp_branches); ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Employees : </td>

                                                            <td> <?= getSenpEmployees($conn, $senp_employees); ?></td>

                                                        </tr>

                                                        <?php

                                                       } else if($customer_type == '36') {

                                                        ?>

                                                        <tr>

                                                            <td class="fw-bold">Office Address :</td>

                                                            <td> <?= $office_address; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Branch Address :</td>

                                                            <td> <?= $branch_address; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Type Of Professional : </td>

                                                            <td> <?= $sep_type_professional; ?></td>

                                                        </tr>

                                                        <?php

                                                        if($sep_type_professional == 'doctor-prof'){

                                                            ?>

                                                        <tr>

                                                            <td class="fw-bold">Qualification : </td>

                                                            <td> <?= getDoctorQualification($conn, $doctor_qualification); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Year Of Pass : </td>

                                                            <td> <?= getDoctorYearOfPass($conn, $doctor_year_pass); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Specialisation : </td>

                                                            <td> <?= getDoctorSpecialisation($conn, $doctor_specialisation); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">University : </td>

                                                            <td> <?= getDoctorUniversity($conn, $doctor_university); ?>

                                                            </td>

                                                        </tr>

                                                        <?php



                                                        } else if($sep_type_professional == 'ca-prof'){

                                                            ?>

                                                        <tr>

                                                            <td class="fw-bold">Firm Name : </td>

                                                            <td> <?= $firm_name; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Year Of Pass : </td>

                                                            <td> <?= getCaYearOfPass($conn, $ca_year_pass); ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">CA Number : </td>

                                                            <td> <?= $ca_number; ?></td>

                                                        </tr>

                                                        <?php

                                                        }

                                                        ?>

                                                        <?php

                                                       } else if($customer_type == '37'){

                                                        ?>

                                                        <tr>

                                                            <td class="fw-bold">Country : </td>

                                                            <td> <?= getNriCountry($conn, $nri_country); ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Gross Salary : </td>

                                                            <td> <?= $nri_gross_salary; ?></td>

                                                        </tr>

                                                        <?php

                                                       } else if($customer_type == '38'){

                                                        ?>

                                                        <tr>

                                                            <td class="fw-bold">Office Address :</td>

                                                            <td> <?= $office_address; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Branch Address :</td>

                                                            <td> <?= $branch_address; ?></td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Institute : </td>

                                                            <td> <?= getEduInstitute($conn, $educational_institute); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Number Of Students : </td>

                                                            <td> <?= getEduYearOfPass($conn, $educational_students); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Company Type : </td>

                                                            <td> <?= getEduCompanyType($conn, $edu_company_type); ?>

                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td class="fw-bold">Strength : </td>

                                                            <td> <?= $educational_strength; ?></td>

                                                        </tr>



                                                        <?php

                                                       }

                                                       ?>



                                                    </tbody>

                                                </table>

                                            </div>



                                            <!-- senp dynamic table -->

                                            <?php 

                                             if($customer_type == '35'){

                                                ?>

                                            <h4>SENP Details</h4>

                                            <!--<div class="text-end">-->

                                            <!--    <a href="add_fy?id=<?= $get_id ?>" class="btn btn-primary btn btn-sm">-->

                                            <!--        <i class="bx bx-plus"></i> Add </a>-->

                                            <!--</div>-->

                                            <div class="row table-responsive">



                                                <table class="table table-bordered text-center">

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

                                                            <th>Edit</th>

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                        <?php 

                                                        $sql_fy = mysqli_query($conn, "SELECT * FROM `tbl_senp_fy_details` WHERE database_id='$database_id'");



                                                        if (mysqli_num_rows($sql_fy) > 0) {

                                                            while ($row_fy = mysqli_fetch_assoc($sql_fy)) {

                                                                // $id = $row_fy['id'];

                                                                ?>

                                                                <tr>

                                                                    <td><?= getFinancialYear($conn, $row_fy['financial_year']); ?>

                                                                    </td>

                                                                    <td><?= getAssessmentYear($conn, $row_fy['assessment_year']); ?>

                                                                    </td>

                                                                    <td><?= $row_fy['turnover']; ?></td>

                                                                    <td><?= $row_fy['depreciation']; ?></td>

                                                                    <td><?= $row_fy['pbt']; ?></td>

                                                                    <td><?= $row_fy['pat']; ?></td>

                                                                    <td>

                                                                    <a href="edit_db_senp?id=<?= $row_fy['id']; ?>&get_id=<?= $get_id; ?>">

                                                                        <i class="bx bx-edit"></i>

                                                                    </a>

                                                                    </td>

                                                                </tr>

                                                                <?php

                                                            }

                                                        } else {

                                                            echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";

                                                        }

                                                        ?>

                                                    </tbody>

                                                </table>

                                            </div>

                                            <?php

                                             }

                                             ?>

                                        </div>

                                    </div>

                                    <!-- Personal Details -->



                                    <!-- bank account Details -->

                                    <div class="tab-pane fade" id="navs-pills-justified-bank_account" role="tabpanel">

                                        <div class="text-end">

                                           <a href="bank_account?id=<?= $database_id ?>" class="btn btn-primary btn btn-sm">

                                               <i class="bx bx-plus"></i> Add </a>

                                        </div>

                                        <table class="table table-bordered text-center">

                                            <thead>

                                                <tr>

                                                    <h5 class="m-0">Bank Account Details</h5>

                                                </tr>

                                                <tr>

                                                    <th>Bank Name</th>

                                                    <th>Account Type</th>

                                                    <th>Account Number</th>

                                                    <th>Branch Name</th>

                                                    <th>IFSC Code</th>

                                                    <th>Edit</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php 

                                                $sql_bank = mysqli_query($conn, "SELECT * FROM `tbl_database_bank_account_details` WHERE database_id='$database_id'");



                                                if (mysqli_num_rows($sql_bank) > 0) {

                                                    while ($row_bank = mysqli_fetch_assoc($sql_bank)) {

                                                        $id = $row_bank['id'];

                                                        ?>

                                                <tr>

                                                    <td><?= getBankName($conn, $row_bank['b_bank_name']); ?></td>

                                                    <td><?= getBankAccountType($conn, $row_bank['b_account_type']); ?>

                                                    </td>

                                                    <td><?= $row_bank['b_account_no']; ?></td>

                                                    <td><?= $row_bank['b_branch_name']; ?></td>

                                                    <td><?= $row_bank['b_ifsc_code']; ?></td>

                                                    <td>

                                                    <a href="edit_db_bank?id=<?= $row_bank['id']; ?>&get_id=<?= $get_id; ?>">

                                                        <i class="bx bx-edit"></i>

                                                    </a>

                                                    </td>

                                                </tr>

                                                <?php

                                                    }

                                                } else {

                                                    echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";

                                                }

                                                ?>

                                            </tbody>

                                        </table>

                                    </div>

                                    <!-- bank account Details -->



                                    <!-- Relation with Bank Details  -->

                                    <div class="tab-pane fade" id="navs-pills-justified-rel_bank" role="tabpanel">

                                        <div class="text-end">

                                           <a href="bank_relationship?id=<?= $database_id ?>" class="btn btn-primary btn btn-sm"> <i class="bx bx-plus"></i> Add </a>

                                        </div>

                                        <table class="table table-bordered text-center">

                                            <thead>

                                                <tr>

                                                    <h5 class="m-0">Relation With Bank Details</h5>

                                                </tr>

                                                <tr>

                                                    <th>Bank Name</th>

                                                    <th>Loan Type</th>

                                                    <th>Loan Amount</th>

                                                    <th>ROI</th>

                                                    <th>Tenure</th>

                                                    <th>EMI</th>

                                                    <th>First EMI Date</th>

                                                    <th>Last EMI Date</th>

                                                    <th>Account Number</th>

                                                    <th>Edit</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php 

                                                $sql_rel_bank = mysqli_query($conn, "SELECT * FROM `tbl_database_relation_bank` WHERE database_id='$database_id'");



                                                if (mysqli_num_rows($sql_rel_bank) > 0) {

                                                    while ($row_rel = mysqli_fetch_assoc($sql_rel_bank)) {

                                                        ?>

                                                <tr>

                                                    <td><?= getPortfolioBank($conn, $row_rel['r_bank_name']); ?></td>

                                                    <td><?= getLoanType($conn, $row_rel['r_loan_type']); ?></td>

                                                    <td><?= $row_rel['r_loan_amount']; ?></td>

                                                    <td><?= getRoiDetails($conn, $row_rel['r_roi']); ?></td>

                                                    <td><?= getTenureDetails($conn, $row_rel['r_tenure']); ?></td>

                                                    <td><?= $row_rel['r_emi']; ?></td>

                                                    <td><?= date('d-m-Y', strtotime($row_rel['first_emi_date'])); ?>

                                                    </td>

                                                    <td><?= date('d-m-Y', strtotime($row_rel['last_emi_date'])); ?></td>

                                                    <td><?= $row_rel['loan_account_name']; ?></td>

                                                    <td>

                                                    <a href="edit_db_rel_bn?id=<?= $row_rel['id']; ?>&get_id=<?= $get_id; ?>">

                                                        <i class="bx bx-edit"></i>

                                                    </a>

                                                    </td>

                                                </tr>

                                                <?php

                                                    }

                                                } else {

                                                    echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";

                                                }

                                                ?>

                                            </tbody>

                                        </table>

                                    </div>

                                    <!-- Relation with Bank Details  -->



                                    <!-- vehicle Details  -->

                                    <div class="tab-pane fade" id="navs-pills-justified-experience" role="tabpanel">

                                        <div class="text-end">

                                           <a href="add_vehicle?id=<?= $database_id ?>" class="btn btn-primary btn btn-sm">   <i class="bx bx-plus"></i> Add </a>

                                        </div>

                                        <table class="table table-bordered text-center">

                                            <thead>

                                                <tr>

                                                    <h5 class="m-0">Vehicle Details</h5>

                                                </tr>

                                                <tr>

                                                    <th rowspan="2">Number</th>

                                                    <th rowspan="2">Make</th>

                                                    <th rowspan="2">Modal</th>

                                                    <th rowspan="2">MAN Year</th>

                                                    <th rowspan="2">Engine Number</th>

                                                    <th rowspan="2"> Chases Number</th>

                                                    <th>Edit</th>

                                                </tr>



                                            </thead>

                                            <tbody>

                                                <?php 

                                                $sql_vehicle = mysqli_query($conn, "SELECT * FROM `tbl_database_vehicle_details` WHERE database_id='$database_id'");



                                                if (mysqli_num_rows($sql_vehicle) > 0) {

                                                    while ($row_vehicle = mysqli_fetch_assoc($sql_vehicle)) {

                                                        ?>

                                                <tr>

                                                    <td><?=$row_vehicle['vehicle_number']; ?></td>

                                                    <td><?= getVehicalMake($conn, $row_vehicle['vehicle_make']); ?></td>

                                                    <td><?= getVehicalModal($conn, $row_vehicle['vehical_modal']); ?>

                                                    </td>

                                                    <td><?= getManufactureYear($conn, $row_vehicle['manufacture_year']); ?>

                                                    </td>

                                                    <td><?= $row_vehicle['engine_number']; ?></td>

                                                    <td><?= $row_vehicle['chases_number']; ?>

                                                    </td>

                                                    <td>

                                                    <a href="edit_db_veh?id=<?= $row_vehicle['id']; ?>&get_id=<?= $get_id; ?>">

                                                        <i class="bx bx-edit"></i>

                                                    </a>

                                                    </td>

                                                </tr>

                                                <?php

                                                    }

                                                } else {

                                                    echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";

                                                }

                                                ?>

                                            </tbody>

                                        </table>

                                    </div>

                                    <!-- vehicle Details  -->



                                    <!-- property Details -->

                                    <div class="tab-pane fade" id="navs-pills-justified-perperty" role="tabpanel">

                                        <div class="text-end">

                                           <a href="add_property?id=<?= $database_id ?>" class="btn btn-primary btn btn-sm">       <i class="bx bx-plus"></i> Add </a>

                                        </div>

                                        <table class="table table-bordered text-center">

                                            <thead>

                                                <tr>

                                                    <h5 class="m-0">Property Details</h5>

                                                </tr>

                                                <tr>

                                                    <th rowspan="2">Property Type</th>

                                                    <th rowspan="2">Area</th>

                                                    <th rowspan="2">Lands</th>

                                                    <th rowspan="2">SFT</th>

                                                    <th rowspan="2">Market Value</th>

                                                    <th>Edit</th>

                                                </tr>



                                            </thead>

                                            <tbody>

                                                <?php 

                                                $sql_vehicle = mysqli_query($conn, "SELECT * FROM `tbl_database_property_details` WHERE database_id='$database_id'");



                                                if (mysqli_num_rows($sql_vehicle) > 0) {

                                                    while ($row_vehicle = mysqli_fetch_assoc($sql_vehicle)) {

                                                        ?>

                                                <tr>

                                                    <td><?= getPropertyType($conn, $row_vehicle['p_property_type']); ?>

                                                    </td>

                                                    <td><?=$row_vehicle['p_area']; ?></td>



                                                    <td><?= $row_vehicle['p_lands']; ?></td>

                                                    <td><?= $row_vehicle['p_sft']; ?></td>

                                                    <td><?= $row_vehicle['p_market_value']; ?>

                                                    </td>

                                                    <td>

                                                    <a href="edit_db_prop?id=<?= $row_vehicle['id']; ?>&get_id=<?= $get_id; ?>">

                                                        <i class="bx bx-edit"></i>

                                                    </a>

                                                    </td>

                                                </tr>

                                                <?php

                                                    }

                                                } else {

                                                    echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";

                                                }

                                                ?>

                                            </tbody>

                                        </table>

                                    </div>

                                    <!-- property Details -->



                                    <!-- credit card -->

                                    <div class="tab-pane fade" id="navs-pills-justified-credit_card" role="tabpanel">

                                        <div class="text-end">

                                           <a href="add_credit_card?id=<?= $database_id ?>"  class="btn btn-primary btn btn-sm"> <i class="bx bx-plus"></i> Add </a>

                                        </div>

                                        <table class="table table-bordered text-center">

                                            <thead>

                                                <tr>

                                                    <h5 class="m-0">Credit Card Details</h5>

                                                </tr>

                                                <tr>

                                                    <th>Bank Name</th>

                                                    <th>Limit</th>

                                                    <th>Edit</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php 

                                                $sql_credit = mysqli_query($conn, "SELECT * FROM `tbl_database_credit_card_details` WHERE database_id='$database_id'");



                                                if (mysqli_num_rows($sql_credit) > 0) {

                                                    while ($row_credit = mysqli_fetch_assoc($sql_credit)) {

                                                        ?>

                                                <tr>

                                                    <td><?= getCreditCardBank($conn, $row_credit['c_bank_name']); ?>

                                                    </td>

                                                    <td><?=$row_credit['c_limit']; ?></td>

                                                    <!-- <td>

                                                        <a href="edit_db_cc?id=<?php echo $row_credit['id']?>"> <i class="bx bx-edit"></i></a>

                                                    </td> -->

                                                    <td>

                                                    <a href="edit_db_cc?id=<?= $row_credit['id']; ?>&get_id=<?= $get_id; ?>">

                                                        <i class="bx bx-edit"></i>

                                                    </a>

                                                    </td>

                                                </tr>

                                                <?php

                                                    }

                                                } else {

                                                    echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";

                                                }

                                                ?>

                                            </tbody>

                                        </table>

                                    </div>

                                    <!-- credit card -->



                                    <!-- Appointment status -->

                                    <div class="tab-pane fade" id="navs-pills-justified-calling_status" role="tabpanel">

                                        <div class="text-end">

                                            <a href="appointment_status?id=<?= $get_id ?>"

                                                class="btn btn-primary btn btn-sm"> <i class="bx bx-plus"></i> Add </a>

                                        </div>

                                     

                                        <table class="table table-bordered text-center">

                                            <thead>

                                                <tr>

                                                    <h5 class="m-0">Appointment Details</h5>

                                                </tr>

                                                <tr>

                                                    <th>Created Date & Time</th>

                                                    <th>Bank Name</th>

                                                    <th>Product Name</th>

                                                    <th>Appointment Status</th>

                                                    <th>Appointment Sub Status</th>

                                                    <th>Notes</th>

                                                    <th>Created By</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php 

                                        $sql_calling = mysqli_query($conn, "SELECT * FROM `tbl_appointment_calling_status` WHERE appt_id='$get_id'");



                                        if (mysqli_num_rows($sql_calling) > 0) {

                                            while ($row_calling = mysqli_fetch_assoc($sql_calling)) {

                                                $createdBy = $row_calling['createdBy'];



                                                $sql_user = "SELECT * FROM `tbl_user` WHERE status = 1 AND username = '$createdBy'";

                                                $result_user = mysqli_query($conn, $sql_user);



                                                $name = 'User not found';

                                                if ($result_user && mysqli_num_rows($result_user) > 0) {

                                                    $user_row = mysqli_fetch_assoc($result_user);

                                                    $name = $user_row['firstName'] . ' ' . $user_row['lastName'];

                                                }

                                             

                                                ?>

                                                <tr>

                                                    <td>

                                                    <?php 

                                                    if (!empty($row_calling['created_at']) && $row_calling['created_at'] != '0000-00-00 00:00:00') {

                                                        echo date("d-m-Y h:i A", strtotime($row_calling['created_at']));

                                                    } else {

                                                        echo ''; // or you can echo 'N/A' or '-'

                                                    }

                                                    ?>

                                                    </td>

                                                    <td><?= getAppointmentBank($conn, $row_calling['appt_bank']); ?>

                                                    </td>

                                                    <td><?= getAppointmentProduct($conn, $row_calling['appt_product']); ?></td>

                                              

                                                    <td><?= getAppointmentStatus($conn, $row_calling['appt_status']); ?></td>

                                                    <td><?= getAppointmentSubStatus($conn, $row_calling['appt_sub_status']); ?></td>

                                                  

                                                    <td><?= $row_calling['notes']; ?></td>

                                                    <td><?= $name; ?></td>



                                                </tr>

                                                <?php

                                            }

                                        } else {

                                            echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";

                                        }

                                        ?>

                                            </tbody>

                                        </table>

                                              

                                    </div>



                                    <!-- social media links -->

                                    <div class="row">

                                        <div class=" d-flex justify-content-end align-items-center">

                                            <a href="https://wa.me/<?= $mobile_number; ?>" target="_blank" >

                                            <img src="../assets/img/icons/brands/whats-app.png" alt="Whats App">

                                            </a>

                                            <a href="#" style="margin-left:15px;">

                                            <img src="../assets/img/icons/brands/gmail.png" alt=" Email" >

                                            </a>

                                            <a href="#" style="margin-left:15px;">

                                            <img src="../assets/img/icons/brands/sms.png" alt="SMS">

                                            </a>

                                        </div>

                                    </div>

                                    <!-- social media links -->

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

                    <button type="button" class="btn btn-success action-btn-2 addRow-2">Add</button>

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

    </script>



</body>



</html>