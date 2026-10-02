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
                                        <h5 class="mb-0">Add Company Info</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="department_name"> Company
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <!-- <input type="text" class="form-control" name="customer_type"ss
                                                            id="customer_type" placeholder="Customer Type" /> -->
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

                                                    <label class="form-label" for="cin_number"> CIN Number
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="cin_number"
                                                            id="cin_number" placeholder="CIN Number" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="pan_number"> PAN Number
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="pan_number"
                                                            id="pan_number" placeholder="PAN Number" />
                                                    </div>

                                                </div>
                                                <div class="col-md-6">

                                                    <label class="form-label" for="tan_number"> TAN Number
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="tan_number"
                                                            id="tan_number" placeholder="TAN Number" />
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="row mt-3">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="esi_number"> ESI Number
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="esi_number"
                                                            id="esi_number" placeholder="ESI Number" />
                                                    </div>

                                                </div>
                                                <div class="col-md-6">

                                                    <label class="form-label" for="pf_number"> PF Number
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="pf_number"
                                                            id="pf_number" placeholder="PF Number" />
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="pt_number"> PT Number
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="pt_number"
                                                            id="pt_number" placeholder="PT Number" />
                                                    </div>

                                                </div>
                                                <div class="col-md-6">

                                                    <label class="form-label" for="gsttg_number"> GST.TG Number
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="gsttg_number"
                                                            id="gsttg_number" placeholder="GST_TG Number" />
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="gstap_number"> GST.AP Number
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="gstap_number"
                                                            id="gstap_number" placeholder="GST_AP Number" />
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- ESI Username -->
                                                <div class="col-md-6">
                                                    <label class="form-label" for="user_name">Username</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="user_name"
                                                            id="user_name" placeholder="Username" />
                                                    </div>
                                                </div>

                                                <!-- ESI Phone No -->
                                                <div class="col-md-6">
                                                    <label class="form-label" for="phone_number">Phone No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" class="form-control" name="phone_number"
                                                            id="phone_number" placeholder="Phone No" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <!-- ESI Email ID -->
                                                <div class="col-md-6">
                                                    <label class="form-label" for="email_id">Email ID</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="email" class="form-control" name="email_id"
                                                            id="email_id" placeholder="Email ID" />
                                                    </div>
                                                </div>

                                                <!-- ESI Authorized Person Name -->
                                                <div class="col-md-6">
                                                    <label class="form-label" for="authorized_person_name">Authorized
                                                        Person Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control"
                                                            name="authorized_person_name" id="authorized_person_name"
                                                            placeholder="Authorized Person Name" />
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="submit" name="form_submit" class="btn btn-primary mt-3">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            
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
</body>

</html>
<?php

if (isset($_POST['form_submit'])) {
    $company_name = $_POST['company_name'];
    $cin_number	 = $_POST['cin_number'];
    $pan_number = $_POST['pan_number'];
    $esi_number = $_POST['esi_number'];
    $pt_number = $_POST['pt_number'];
    $pf_number = $_POST['pf_number'];
    $tan_number = $_POST['tan_number'];
    $gsttg_number = $_POST['gsttg_number'];
    $gstap_number = $_POST['gstap_number'];
    $user_name = $_POST['user_name'];
    $phone_number = $_POST['phone_number'];
    $email_id = $_POST['email_id'];
    $authorized_person_name = $_POST['authorized_person_name'];


     $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($company_name) ) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Customer Type field is required",
                position: "topRight",
            });
            
        </script>';
        exit();
    } else{

$sql = "INSERT INTO `tbl_company_info`(`company_name`, `cin_number`, `pan_number`, `esi_number`, `pt_number`, `gstap_number`, `tan_number`, `pf_number`, `gsttg_number`, `user_name`, `phone_number`, `email_id`, `authorized_person_name`, `created_at`) VALUES ('$company_name','$cin_number','$pan_number','$esi_number','$pt_number','$gstap_number','$tan_number','$pf_number','$gsttg_number','$user_name','$phone_number','$email_id','$authorized_person_name','$created_at')";


    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "company info Added Successfully",
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