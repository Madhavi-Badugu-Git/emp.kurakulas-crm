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
$partner_id = $_GET['id'];

$sql = mysqli_query($conn, "SELECT * FROM tbl_partner WHERE id='$partner_id' AND status='1'");
if(mysqli_num_rows($sql)>0){
    while($row = mysqli_fetch_assoc($sql)){
        $full_name = $row['full_name'];
        $alias_name = $row['alias_name'];
        $Phone_number = $row['Phone_number'];
        $email_id = $row['email_id'];
        $aadhar_number = $row['aadhar_number'];
        $pan_number = $row['pan_number'];
        $state = $row['state'];
        $location = $row['location'];
        $partnerType = $row['partnerType'];
        $password = $row['password'];
        $address = $row['address'];

        $acc_holder_name = $row['acc_holder_name'];
        $account_number = $row['account_number'];
        $bank_name = $row['bank_name']; 
        $ifsc_code = $row['ifsc_code'];
        $branch_name = $row['branch_name'];

        $aadhar_document = $row['aadhar_document'];
        $pan_document = $row['pan_document'];
        $passbook_document = $row['passbook_document'];
        $aadhar_backside_document = $row['aadhar_backside_document'];
        $avatar = $row['avatar'];
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
                                            data-bs-target="#navs-pills-justified-calling_status"
                                            aria-controls="navs-pills-justified-calling_status"
                                            aria-selected="false"><span class="d-none d-sm-block"><i
                                                    class="tf-icons bx bx-phone bx-sm me-1_5 align-text-bottom"></i>
                                               Calling Status</span><i
                                                class="bx bx-phone bx-sm d-sm-none"></i></button>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">
                                    <!-- Personal Details -->
                                    <div class="tab-pane fade show active" id="navs-pills-justified-home"
                                        role="tabpanel">
                                        <h5>Partner Details</h5>
                                        <div class="" style="padding-bottom:10px!important;">
                                            <div class="d-flex align-items-start align-items-sm-center">
                                                <img src="../uploads/partners/<?= $avatar; ?>" alt="user-avatar"
                                                    class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless w-auto m-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-bold">Full Name :</td>
                                                            <td> <?= $full_name; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Alias Name :</td>
                                                            <td> <?= $alias_name; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"> Mobile No:</td>
                                                            <td> <?= $Phone_number; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold"> Email Id:</td>
                                                            <td> <?= $email_id; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Password :</td>
                                                            <td> <?= $password; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Created By :</td>
                                                            <td> <?= $createdBy; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Aadhar Card No :</td>
                                                            <td> <?= $aadhar_number; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Pan Card No :</td>
                                                            <td> <?= $pan_number; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">State :</td>
                                                            <td> <?= getBranchState($conn, $state); ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Location :</td>
                                                            <td> <?= getBranchLocation($conn, $location); ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Partner Type :</td>
                                                            <td> <?= getPartnerType($conn, $partnerType); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Address :</td>
                                                            <td> <?= $address; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless w-auto m-0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size:17px;">Bank Details :</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Account Holder Name :</td>
                                                            <td> <?= $acc_holder_name; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Account Number :</td>
                                                            <td> <?= $account_number; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold"> Bank Name:</td>
                                                            <td> <?= getBankName($conn, $bank_name); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold"> IFSC Code:</td>
                                                            <td> <?= $ifsc_code; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Branch Name :</td>
                                                            <td> <?= $branch_name; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td style="font-size:17px;">Documents :</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Pan Card :</td>
                                                            <td><a href="../uploads/partners/<?= $pan_document; ?>" target="_blank">View Document</a></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">Passbook Card :</td>
                                                            <td><a href="../uploads/partners/<?= $passbook_document; ?>" target="_blank">View Document</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Aadhar Card Front:</td>
                                                            <td><a href="../uploads/partners/<?= $aadhar_document; ?>" target="_blank">View Document</a></td>
                                                        </tr>
                                                        <?php 
                                                        if($aadhar_backside_document != null){ 
                                                        ?>
                                                        <tr>
                                                            <td class="fw-bold">Aadhar Card Back :</td>
                                                            <td><a href="../uploads/partners/<?= $aadhar_backside_document; ?>" target="_blank">View Document</a></td>
                                                        </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        
                                    </div>
                                    <!-- Personal Details -->
                                    
                                     <!-- calling status -->
                                     <div class="tab-pane fade" id="navs-pills-justified-calling_status" role="tabpanel">
                                        <div class="text-end">
                                            <a href="calling_status?id=<?= $partner_id ?>" class="btn btn-primary btn btn-sm">
                                                <i class="bx bx-plus"></i> Add </a>
                                        </div>
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <h5 class="m-0">Calling Status Details</h5>
                                                </tr>
                                                <tr>
                                                    <th>Calling Status</th>
                                                    <th>Calling Sub Status</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $sql_calling = mysqli_query($conn, "SELECT * FROM `tbl_partner_calling_status_details` WHERE partner_id='$partner_id'");

                                                if (mysqli_num_rows($sql_calling) > 0) {
                                                    while ($row_calling = mysqli_fetch_assoc($sql_calling)) {
                                                        $id = $row_calling['id'];
                                                        ?>
                                                <tr>
                                                    <td><?= getPartnerCallingStatus($conn, $row_calling['calling_status']); ?></td>
                                                    <td><?= getPartnerCallingSubStatus($conn, $row_calling['calling_sub_status']); ?>
                                                    </td>
                                                    <td><?= $row_calling['notes']; ?>
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
                                    <!-- calling status -->
                                    
                                     <!-- social media links -->
                                    <div class="row">
                                        <div class=" d-flex justify-content-end align-items-center">
                                            <a href="https://wa.me/<?= $Phone_number; ?>" target="_blank" >
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

</body>

</html>