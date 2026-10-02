<?php session_start(); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 
// $loggedInUser = $_SESSION['loggedInUser'];
// echo $loggedInUser;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>
        alert("Invalid Request!");
        window.location.href = "list";
    </script>';
    exit();
}
$user_id = $_GET['id'];

$sql = mysqli_query($conn, "SELECT * FROM tbl_portfolio WHERE id='$user_id' AND status='1'");
if(mysqli_num_rows($sql)>0){
    while($row = mysqli_fetch_assoc($sql)){
        $customer_name = $row['customer_name'];
        $company_name = $row['company_name'];
        $Phone_number = $row['Phone_number'];
        $alternative_Phone_number = $row['alternative_Phone_number'];
        $email_id = $row['email_id'];
        
        $state = $row['state'];
        $location = $row['location'];
        $sub_location = $row['sub_location'];
        $pin_code = $row['pin_code'];
        $customer_type = $row['customer_type'];
        $industry_type = $row['industry_type'];
        $business_type = $row['business_type'];
        $birth_date = $row['birth_date'];
        $address = $row['address'];
        $createdBy = $row['createdBy'];

    }
}

?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
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
                                            data-bs-target="#navs-pills-justified-loan"
                                            aria-controls="navs-pills-justified-loan" aria-selected="false"><span
                                                class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-building bx-sm me-1_5 align-text-bottom"></i>
                                               Bank & Loan</span><i class="bx bx-building bx-sm d-sm-none"></i></button>
                                    </li>
                                    <li class="nav-item mb-1 mb-sm-0">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-justified-documents"
                                            aria-controls="navs-pills-justified-documents" aria-selected="false"><span
                                                class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-file bx-sm me-1_5 align-text-bottom"></i>
                                                Documents</span><i class="bx bx-file bx-sm d-sm-none"></i></button>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">
                                    <!-- Personal Details -->
                                    <div class="tab-pane fade show active" id="navs-pills-justified-home"
                                        role="tabpanel">
                                       

                                        <div class="row">
                                            <h5>Portfolio Details</h5>
                                            <div class="col-md-6">
                                                <table class="table table-borderless w-auto m-0">
                                                    <tbody>
                                                        <tr>
                                                           
                                                            <td class="fw-bold">Customer Name :</td>
                                                            <td> <?= $customer_name; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Company Name :</td>
                                                            <td> <?= $company_name; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Phone No :</td>
                                                            <td> <?= $Phone_number; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Alternative Phone No :</td>
                                                            <td> <?= $alternative_Phone_number; ?></td>
                                                        </tr>
                                                      
                                                        <tr>
                                                            <td class="fw-bold"> Email Id:</td>
                                                            <td> <?= $email_id; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"> Date Of Birth:</td>
                                                            <td> <?= $birth_date; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"> State :</td>
                                                            <td> <?= getStateName($conn, $state); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold"> Location :</td>
                                                            <td> <?= getLocationName($conn, $location); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Sub Location :</td>
                                                            <td> <?= getSubLocation($conn, $sub_location); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">PIN Code :</td>
                                                            <td> <?= getPinCode($conn, $pin_code); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Type Of Customer :</td>
                                                            <td> <?= getCustomerType($conn, $customer_type); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Type Of Industry :</td>
                                                            <td> <?= getIndustryType($conn, $industry_type); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Type Of Business :</td>
                                                            <td> <?= getBusinessType($conn, $business_type); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Created By :</td>
                                                            <td> <?= $createdBy; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"> Address:</td>
                                                            <td> <?= $address; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <!-- Personal Details -->

                                    <!-- bank & loan Details -->
                                    <div class="tab-pane fade" id="navs-pills-justified-loan" role="tabpanel">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <h5 class="m-0">Bank & Loan Details</h5>
                                                </tr>
                                                <tr>
                                                    <th>Bank Name</th>
                                                    <th>Loan Type</th>
                                                    <th>Loan Amount</th>
                                                   <th>ROI</th>
                                                   <th>Tenure (Months)</th>
                                                   <th>EMI</th>
                                                   <th>First EMI Date</th>
                                                   <th>Last EMI Date</th>
                                                   <th>Loan Account Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $sql_loan = mysqli_query($conn, "SELECT * FROM `tbl_portfolio_loan_details` WHERE portfolio_id='$user_id'");

                                                if (mysqli_num_rows($sql_loan) > 0) {
                                                    while ($row_loan = mysqli_fetch_assoc($sql_loan)) {
                                                        // $bank_name  = $row_loan['bank_name'];
                                                        // $loan_type  = $row_loan['loan_type'];
                                                        ?>
                                                <tr>
                                                    <td><?= getPortfolioBank($conn, $row_loan['bank_name'] ); ?></td>
                                                    <td><?= getLoanType($conn, $row_loan['loan_type'] ); ?></td>
                                                    <td><?= $row_loan['loan_amount']; ?></td>
                                                    <td><?= getRoiDetails($conn, $row_loan['roi'] ); ?></td>
                                                    <td><?= getTenureDetails($conn, $row_loan['tenure'] ); ?></td>
                                                    <td><?= $row_loan['emi']; ?></td>
                                                    <td><?= $row_loan['first_emi_date']; ?></td>
                                                    <td><?= $row_loan['last_emi_date']; ?></td>
                                                    <td><?= $row_loan['loan_account_name']; ?></td>

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
                                    <!-- bank &loan Details -->

                                    <!-- document Details  -->
                                    <div class="tab-pane fade" id="navs-pills-justified-documents" role="tabpanel">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <h5 class="m-0">Document Details</h5>
                                                </tr>
                                                <tr>
                                                    <th>Document Name</th>
                                                    <th>Uploaded File</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $sql_document = mysqli_query($conn, "SELECT * FROM `tbl_portfolio_documents_details` WHERE portfolio_id='$user_id'");

                                                if (mysqli_num_rows($sql_document) > 0) {
                                                    while ($row_document = mysqli_fetch_assoc($sql_document)) {
                                                        ?>
                                                <tr>
                                                    <td><?= $row_document['document_name']; ?></td>
                                                    <td><a href="../uploads/portfolio/<?= $row_document['upload_file']; ?>" target="_blank">View File -></a></td>
                                                   
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
                                    <!-- document Details  -->
                                   
                                   <!-- social icons -->
                                    <div class="row">
                                        <div class=" d-flex justify-content-end align-items-center">
                                            <a href="https://wa.me/<?= $Phone_number; ?>" target="_blank">
                                                <img src="../assets/img/icons/brands/whats-app.png" alt="Whats App">
                                            </a>
                                            <a href="#" style="margin-left:15px;">
                                                <img src="../assets/img/icons/brands/gmail.png" alt=" Email">
                                            </a>
                                            <a href="#" style="margin-left:15px;">
                                                <img src="../assets/img/icons/brands/sms.png" alt="SMS">
                                            </a>
                                        </div>
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
        // Prevent form submission on enter key
        $("#designationForm").submit(function(event) {
            event.preventDefault();
        });

        // Function to load designations dynamically
        $('#department').change(function() {
            var department_id = $(this).val();
            $.ajax({
                url: '../info/get_designation_1.php',
                type: 'POST',
                data: {
                    department_id: department_id
                },
                success: function(response) {
                    $('#designation').html(response);
                }
            });
        });

        $('#work_state').change(function() {
            var state_id = $(this).val();
            $.ajax({
                url: '../info/get-location_1.php',
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function(response) {
                    $('#work_location').html(response);
                }
            });
        });
    });

    
    </script>
</body>

</html>

