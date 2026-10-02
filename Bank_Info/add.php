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
                                        <h5 class="mb-0">Add Bank Info</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="company_name"> Company
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <!-- <input type="text" class="form-control" name="customer_type"
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

                                                    <label class="form-label" for="bank_name"> Bank Name
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <!-- <input type="text" class="form-control" name="bank_name"
                                                            id="bank_name" placeholder="Bank Name" /> -->
                                                        <select id="bank_name" name="bank_name" class="form-select">
                                                            <option value="">Select Bank</option>
                                                            <?php
                                                                $query = "SELECT id, bank_name FROM tbl_bank ORDER BY bank_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['bank_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="Account_number"> Account Number
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="Account_number"
                                                            id="Account_number" placeholder="Account Number" />
                                                    </div>

                                                </div>
                                                <div class="col-md-6">

                                                    <label class="form-label" for="ifsc_code"> IFSC Code
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="ifsc_code"
                                                            id="ifsc_code" placeholder="IFSC Code" />
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="row mt-3">
                                                <div class="col-md-6">

                                                    <label class="form-label" for="branch_name"> Branch Name
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="branch_name"
                                                            id="branch_name" placeholder="Branch Name" />
                                                    </div>

                                                </div>
                                                <div class="col-md-6">

                                                    <label class="form-label" for="micr_code"> MICR code
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="micr_code"
                                                            id="micr_code" placeholder="MICR code" />
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">

                                                    <label class="form-label" for="bank_Address"> Bank Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-map"></i></span>
                                                        <!-- <input type="text" class="form-control" name="bank_Address"
                                                            id="bank_Address" placeholder="Bank Address" /> -->
                                                        <textarea name="bank_Address" id="bank_Address"
                                                            class="form-control" placeholder="Bank Address"></textarea>
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
     $bank_name = $_POST['bank_name'];
     $Account_number = $_POST['Account_number'];
     $branch_name = $_POST['branch_name'];
     $bank_Address = $_POST['bank_Address'];
     $ifsc_code = $_POST['ifsc_code'];
     $micr_code = $_POST['micr_code'];

     
     $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($company_name) || empty($bank_name)) {
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
    $sql = "INSERT INTO `tbl_bank_info`(`customer_type`,`created_at`) 
            VALUES ('$customer_type','$created_at')";

            $sql = "INSERT INTO `tbl_bank_info`(`company_name`, `bank_name`, `account_number`, `branch_name`, `bank_address`, `IFSC_Code`, `MICR_Code`,`created_at`) VALUES ('$company_name','$bank_name','$Account_number','$branch_name','$bank_Address','$ifsc_code','$micr_code','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "bank info Added Successfully",
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