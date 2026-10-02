<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="add";</script>';
    exit();
}

$partner_id = $_GET['id'];


$query = "SELECT * FROM tbl_partner WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Partner not found!"); window.location.href="list";</script>';
    exit();
}

$partner = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Partner</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="partner_id" value="<?= $partner['id']; ?>">

                                            <div class="row mt-0">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="full_name">Full
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="full_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="full_name"
                                                            id="full_name" placeholder="Full Name"
                                                            value="<?= $partner['full_name']; ?>" aria-label="Full Name"
                                                            aria-describedby="full_name2" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="alias_name">Alias
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="alias_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="alias_name"
                                                            id="alias_name" placeholder="Alias Name"
                                                            value="<?= $partner['alias_name']; ?>"
                                                            aria-label="Alias Name" aria-describedby="alias_name2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number"> Phone
                                                        No</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="Phone_number2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="Phone_number" name="Phone_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941" readonly
                                                            value="<?= $partner['Phone_number']; ?>"
                                                            aria-label="658 799 8941" aria-describedby="Phone_number2"
                                                            maxlength="10" pattern="[0-9]{10}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="email_id"> Email</label>
                                                    <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="email" name="email_id" id="email_id"
                                                            class="form-control" placeholder="User Email"
                                                            value="<?= $partner['email_id']; ?>"
                                                            aria-label="Partner Email" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="aadhar_number"> Aadhar Card
                                                        No</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="aadhar_number2" class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <input type="text" id="aadhar_number" name="aadhar_number"
                                                            class="form-control phone-mask" placeholder="658 799 8941"
                                                            value="<?= $partner['aadhar_number']; ?>"
                                                            aria-label="658 799 8941" aria-describedby="aadhar_number2"
                                                            maxlength="12" pattern="[0-9]{12}"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="pan_number"> Pan Card No</label>
                                                    <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-id-card"></i></span>
                                                        <input type="text" name="pan_number" id="pan_number"
                                                            class="form-control" placeholder="Pan Card No"
                                                            value="<?= $partner['pan_number']; ?>"
                                                            aria-label="Pan Card No" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="state">Branch State</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-map-alt"></i></span>
                                                                <select id="state" name="state" class="form-select"
                                                            onchange="loadBranchLocation(this.value)">
                                                            <option value="">Select Branch State</option>
                                                            <?php
                                                            $query = "SELECT id, branch_state_name FROM tbl_branch_state ORDER BY branch_state_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($state = $result->fetch_assoc()) {
                                                                $selected = ($state['id'] == $partner['state']) ? 'selected' : '';
                                                                echo '<option value="'.$state['id'].'" '.$selected.'>'.$state['branch_state_name'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="location">Branch Location</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-map"></i></span>
                                                        <select id="location" name="location" class="form-select">
                                                            <option value="">Select Branch Location</option>
                                                            <?php
                                                        $query = "SELECT id, branch_location FROM tbl_branch_location WHERE branch_state_id = '".$partner['state']."' ORDER BY branch_location ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $partner['location']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['branch_location'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="partnerType">Type
                                                        Of Partner</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <select id="partnerType" name="partnerType" class="form-select">
                                                            <option value="">Select Partner Type</option>
                                                            <?php
                                                        $query = "SELECT id, partner_type FROM tbl_partner_type WHERE status = 1 ORDER BY partner_type ASC;";
                                                        $result = $conn->query($query);
                                                        
                                                        while ($row = $result->fetch_assoc()) {
                                                          
                                                            $selected = ($row['id'] == $partner['partnerType']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['partner_type'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="password">Passward
                                                    </label> <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="password2" class="input-group-text"><i
                                                                class="bx bx-lock-alt"></i>

                                                        </span>
                                                        <input type="password" class="form-control" name="password"
                                                            id="password" placeholder="Password"
                                                            value="<?= $partner['password']; ?>" aria-label="Password"
                                                            aria-describedby="password2" onclick="togglePassword()" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="partner_file" class="form-label">Partner Image
                                                    </label>
                                                    <input class="form-control" type="file" id="partner_file"
                                                        name="partner_file" accept="image/*">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="birth_date">Date of
                                                        Birth</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="text" class="form-control" name="birth_date"
                                                            id="birth_date" value="<?= $partner['birth_date']; ?>" placeholder="DD/MM/YYYY"
                                                            aria-describedby="birth_date2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="password">Address
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"> <i class="bx bx-map"></i>
                                                        </span>
                                                        <textarea name="partner_address" id="partner_address"
                                                            class="form-control" rows="2"
                                                            placeholder="Address"><?= $partner['address']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="mt-4">Bank Details</h5>
                                            <div class="row mt-0">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="acc_holder_name">Account Holder Name
                                                    </label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="acc_holder_name2" class="input-group-text"><i
                                                                class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="acc_holder_name"
                                                            id="acc_holder_name" placeholder="Account Holder Name"
                                                            value="<?= $partner['acc_holder_name']; ?>"
                                                            aria-label="Account Holder Name"
                                                            aria-describedby="acc_holder_name2" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="account_number">Account
                                                        Number</label> <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="account_number2" class="input-group-text"><i
                                                                class="bx bx-credit-card"></i></span>
                                                        <input type="text" class="form-control" name="account_number"
                                                            id="account_number" placeholder="Account Number"
                                                            value="<?= $partner['account_number']; ?>"
                                                            aria-label="Account Number"
                                                            aria-describedby="account_number2" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="bank_name">Bank Name
                                                    </label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="acc_holder_name2" class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="bank_name" name="bank_name" class="form-select">
                                                            <option value="">Select Bank</option>
                                                            <?php
                                                        $query = "SELECT id, bank_name FROM tbl_bank WHERE status = 1 ORDER BY bank_name ASC;";
                                                        $result = $conn->query($query);
                                                        
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $partner['bank_name']) ? 'selected' : ''; // Corrected condition
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['bank_name'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label" for="ifsc_code">IFSC Code
                                                    </label> <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="ifsc_code2" class="input-group-text"><i
                                                                class="bx bx-hash"></i></span>
                                                        <input type="text" class="form-control" name="ifsc_code"
                                                            id="ifsc_code" placeholder="IFSC Code"
                                                            value="<?= $partner['ifsc_code']; ?>" aria-label="IFSC Code"
                                                            aria-describedby="ifsc_code2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="branch_name">Branch Name
                                                    </label> <span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span id="branch_name2" class="input-group-text"><i
                                                                class="bx bx-map"></i></span>
                                                        <input type="text" class="form-control" name="branch_name"
                                                            id="branch_name" placeholder="Branch Name"
                                                            value="<?= $partner['branch_name']; ?>"
                                                            aria-label="Branch Name" aria-describedby="branch_name2" />
                                                    </div>
                                                </div>
                                            </div>
                                            <h5 class="mt-4">Upload Documents</h5>
                                            <div class="row mt-0">

                                                <div class="col-md-6">
                                                    <label for="pan_file" class="form-label">Pan Card
                                                    </label> <span style="color:red;"> *</span>
                                                    <input class="form-control" type="file" id="pan_file"
                                                        name="pan_file" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="passbook_file" class="form-label">Bank Passbook
                                                    </label> <span style="color:red;"> *</span>
                                                    <input class="form-control" type="file" id="passbook_file"
                                                        name="passbook_file" accept="image/*,application/pdf">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="aadhar_file" class="form-label">Aadhar Card Front
                                                    </label> <span style="color:red;"> *</span>
                                                    <input class="form-control" type="file" id="aadhar_file"
                                                        name="aadhar_file" accept="image/*,application/pdf">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="aadhar_back_file" class="form-label">Aadhar Card Back
                                                    </label> <span style="font-size:12px;"> (Optional)</span>
                                                    <input class="form-control" type="file" id="aadhar_back_file"
                                                        name="aadhar_back_file" accept="image/*,application/pdf">
                                                </div>
                                            </div>

                                            <input type="submit" name="update_partner" class="btn btn-primary mt-4"
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
        function loadBranchLocation(stateId) {
            if (stateId) {
                fetch("../info/get_branch_location", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "branch_state_id=" + stateId
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("location").innerHTML = data;
                    });
            } else {
                document.getElementById("location").innerHTML = '<option value="">Select Branch Location</option>';
            }
        }

        // password
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleIcon = document.getElementById("toggleIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.replace("bx-show", "bx-hide");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.replace("bx-hide", "bx-show");
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
if (isset($_POST['update_partner'])) {
    $partner_id = $_POST['partner_id'];

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $alias_name = mysqli_real_escape_string($conn, $_POST['alias_name']);
    $Phone_number = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $email_id = mysqli_real_escape_string($conn, $_POST['email_id']);
    $aadhar_number = mysqli_real_escape_string($conn, $_POST['aadhar_number']);
    $pan_number = mysqli_real_escape_string($conn, $_POST['pan_number']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $partnerType = mysqli_real_escape_string($conn, $_POST['partnerType']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $partner_address = mysqli_real_escape_string($conn, $_POST['partner_address']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $acc_holder_name = mysqli_real_escape_string($conn, $_POST['acc_holder_name']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $ifsc_code = mysqli_real_escape_string($conn, $_POST['ifsc_code']);
    $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name']);
    $created_at = date('Y-m-d H:i:s');

    // Fetch existing file names from DB
    $existing_files = mysqli_fetch_assoc(mysqli_query($conn, "SELECT aadhar_document, pan_document, passbook_document, aadhar_backside_document, avatar FROM tbl_partner WHERE id='$partner_id'"));

    // Upload directory and allowed file types
    $path = '../uploads/partners/';
    $valid_extensions = ['jpeg', 'jpg', 'png', 'pdf'];

    // Define file input names and corresponding DB columns
    $file_inputs = [
        'aadhar_file' => 'aadhar_document',
        'pan_file' => 'pan_document',
        'passbook_file' => 'passbook_document',
        'aadhar_back_file' => 'aadhar_backside_document',
        'partner_file' => 'avatar'
    ];

    foreach ($file_inputs as $input_name => $db_column) {
        if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
            $filename = $_FILES[$input_name]['name'];
            $tmp = $_FILES[$input_name]['tmp_name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $valid_extensions)) {
                $final_image = date("dHis") . '-' . strtolower(str_replace(' ', '-', $filename));
                if (move_uploaded_file($tmp, $path . $final_image)) {
                    $$db_column = $final_image; // Assign new file name
                }
            } else {
                echo "<script>
                    iziToast.error({
                        title: 'Error',
                        message: 'Invalid file format for " . ucfirst(str_replace('_', ' ', $input_name)) . ". Only JPG, JPEG, PNG, and PDF allowed.',
                        position: 'topRight'
                    });
                </script>";
                exit;
            }
        } else {
            $$db_column = $existing_files[$db_column]; // Keep old file if no new file uploaded
        }
    }

    // Validate required fields
    if (!empty($Phone_number) && !empty($full_name) && !empty($email_id) && !empty($partnerType) && !empty($password) && !empty($acc_holder_name) && !empty($account_number) && !empty($bank_name) && !empty($ifsc_code) && !empty($branch_name)) {

        // Update query
        $sql = "UPDATE tbl_partner SET 
                full_name='$full_name',
                alias_name='$alias_name',
                Phone_number='$Phone_number',
                email_id='$email_id',
                aadhar_number='$aadhar_number',
                pan_number='$pan_number',
                state='$state',
                location='$location',
                partnerType='$partnerType',
                password='$password',
                address='$partner_address',
                birth_date='$birth_date',
                acc_holder_name='$acc_holder_name',
                account_number='$account_number',
                bank_name='$bank_name',
                ifsc_code='$ifsc_code',
                branch_name='$branch_name',
                aadhar_document='$aadhar_document',
                pan_document='$pan_document',
                passbook_document='$passbook_document',
                aadhar_backside_document='$aadhar_backside_document',
                avatar='$avatar',
                updated_at='$created_at'
                WHERE id='$partner_id'";

                // echo $sql;
                // exit();

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                iziToast.success({
                    title: 'Success',
                    message: 'Partner details updated successfully!',
                    position: 'topRight'
                });
                setTimeout(() => { window.location.href = 'list'; }, 1000);
            </script>";
        } else {
            echo "<script>
                iziToast.error({
                    title: 'Error',
                    message: 'Failed to update partner details. Please try again.',
                    position: 'topRight'
                });
            </script>";
        }
    } else {
        echo "<script>
            iziToast.warning({
                title: 'Warning',
                message: 'Please fill in all required fields.',
                position: 'topRight'
            });
        </script>";
    }
}
?>