<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// echo $loggedInUser;

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$get_id = $_GET['id'];

// echo $get_id;
// exit();

$query = "SELECT * FROM tbl_database WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $get_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Calling Status not found!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$database = $result->fetch_assoc();
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

                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="get_id" value="<?= $database['id']; ?>">

                                            <div class="row mt-5">
                                                <h5> Calling Status Details</h5>
                                                <div class="col-md-12">
                                                    <div class="row mt-5">
                                                        <!-- <h5> Calling Status</h5> -->
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="calling_type_loan"> Calling
                                                                Type
                                                                Loan</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-file"></i></span>
                                                                <select id="calling_type_loan" name="calling_type_loan"
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
                                                                        class="bx bx-building"></i></span>
                                                                <select id="calling_bank" name="calling_bank"
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
                                                                <select id="calling_status" name="calling_status"
                                                                    class="form-select"
                                                                    onchange="getSubStatus(this.value)">
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
                                                            <label class="form-label" for="calling_sub_status"> Calling
                                                                Sub
                                                                Status</label><span style="color:red;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-phone"></i></span>
                                                                <select id="calling_sub_status"
                                                                    name="calling_sub_status" class="form-select">
                                                                    <option value="">Select Calling Sub Status</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Datetime Field (Initially Hidden) -->
                                                    <div class="row mt-3" id="datetimeContainer" style="display: none;">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="followup_datetime">Calling
                                                                Date & Time</label><span style="color:red;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-calendar"></i></span>
                                                                <input type="datetime-local" id="followup_datetime"
                                                                    name="followup_datetime" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Datetime Field (Initially Hidden) -->

                                                    
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <input type="submit" name="submit_form" class="btn btn-primary mt-10"
                                                    value="Save">
                                            </div>
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
                        document.getElementById("calling_sub_status").addEventListener("change",
                            handleSubStatusChange);
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
    document.addEventListener("DOMContentLoaded", function() {
        const subStatusSelect = document.getElementById("calling_sub_status");
        if (subStatusSelect) {
            subStatusSelect.addEventListener("change", handleSubStatusChange);
        }
    });

    // appt sub status
    function getAppointmentStatus(subStatusId) {
        if (subStatusId) {
            fetch("../info/get_appointment_status", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "appt_status_id=" + subStatusId
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("appt_sub_status").innerHTML = data;
                });
        } else {
            document.getElementById("appt_sub_status").innerHTML =
                '<option value="">Select Appointment Sub Status</option>';
        }
    }
    </script>
</body>

</html>

<?php
if (isset($_POST['submit_form'])) {
    $get_id = $_POST['get_id'];
    $calling_type_loan = $_POST['calling_type_loan'];
    $calling_bank = $_POST['calling_bank'];
    $calling_status = $_POST['calling_status'];
    $calling_sub_status = $_POST['calling_sub_status'];
    $followup_datetime = $_POST['followup_datetime'];
    $created_at = date('Y-m-d H:i:s');

    // Insert into tbl_database_calling_status
    $calling_sql = "INSERT INTO `tbl_database_calling_status` 
                    (`database_id`, `calling_type_loan`, `calling_bank`, `calling_status`, `calling_sub_status`, `calling_date_time`, `createdBy`, `created_at`) 
                    VALUES ('$get_id', '$calling_type_loan', '$calling_bank', '$calling_status', '$calling_sub_status', '$followup_datetime', '$loggedInUser', '$created_at')";

    if ($conn->query($calling_sql) === TRUE) {
        
        // Only move data if calling_sub_status is "Appointment Fixed"
        if ($calling_sub_status === '7') {
            $moveQuery = "INSERT INTO tbl_appointment (
                database_id, mobile_number, lead_name, email_id, company_name, alternative_mobile,
                state, location, sub_location, pin_code, source, visiting_card, user_qualification,
                residental_address, customer_type, sal_company_type, sal_birth_date,
                sal_grossSalary, gross_sal_amount, sal_netSalary, net_sal_amount,
                sal_designation_name, sal_official_email, sal_salary_payment_type,
                sal_present_experience, sal_total_experience, senp_industry_name,
                senp_business_name, senp_company_type, senp_nature_business,
                senp_incorporaton_date, senp_vintage_year, senp_factory_address,
                senp_factory_pincode, senp_gst_number, senp_company_pan_number, senp_website,
                senp_rating_type, senp_rating_name, senp_branches, senp_employees,
                sep_type_professional, doctor_qualification, doctor_year_pass,
                doctor_specialisation, doctor_university, firm_name, ca_year_pass, ca_number,
                nri_country, nri_gross_salary, educational_institute, educational_students,
                edu_company_type, educational_strength, calling_type_loan, calling_bank,
                calling_status, calling_sub_status, database_notes, office_address,
                branch_address, status, createdBy, movedBy, created_at, updated_at, moved_at
            )
            SELECT 
                '".mysqli_real_escape_string($conn, $get_id)."', mobile_number, lead_name, email_id, company_name, alternative_mobile,
                state, location, sub_location, pin_code, source, visiting_card, user_qualification,
                residental_address, customer_type, sal_company_type, sal_birth_date,
                sal_grossSalary, gross_sal_amount, sal_netSalary, net_sal_amount,
                sal_designation_name, sal_official_email, sal_salary_payment_type,
                sal_present_experience, sal_total_experience, senp_industry_name,
                senp_business_name, senp_company_type, senp_nature_business,
                senp_incorporaton_date, senp_vintage_year, senp_factory_address,
                senp_factory_pincode, senp_gst_number, senp_company_pan_number, senp_website,
                senp_rating_type, senp_rating_name, senp_branches, senp_employees,
                sep_type_professional, doctor_qualification, doctor_year_pass,
                doctor_specialisation, doctor_university, firm_name, ca_year_pass, ca_number,
                nri_country, nri_gross_salary, educational_institute, educational_students,
                edu_company_type, educational_strength, calling_type_loan, calling_bank,
                calling_status, calling_sub_status, database_notes, office_address,
                branch_address, status, createdBy, '".mysqli_real_escape_string($conn, $loggedInUser)."', created_at, updated_at, '".mysqli_real_escape_string($conn, $created_at)."'
            FROM tbl_database
            WHERE id = $get_id";

            if ($conn->query($moveQuery) === TRUE) {
                
                // Get the last inserted ID
                $lastInsertedId = mysqli_insert_id($conn);

                // Generate the Unique ID (APT1, APT2, etc.)
                $prefix = "APT";
                $countQuery = "SELECT COUNT(*) AS total FROM tbl_appointment WHERE unique_id LIKE '$prefix%'";
                $countResult = mysqli_query($conn, $countQuery);

                if ($countResult && mysqli_num_rows($countResult) > 0) {
                    $countRow = mysqli_fetch_assoc($countResult);
                    $nextCount = $countRow['total'] + 1;
                    $unique_no = $prefix . $nextCount;

                              $insertQuery = "UPDATE `tbl_appointment` SET `unique_id`='$unique_no' WHERE `id`='$lastInsertedId'";

                    if (mysqli_query($conn, $insertQuery)) {
                        echo '<script>
                            iziToast.success({
                                title: "Success",
                                message: "Data successfully moved to Appointment.",
                                position: "topRight"
                            });
                        </script>';
                    } else {
                        echo '<script>
                            iziToast.error({
                                title: "Error",
                                message: "Error inserting data: ' . mysqli_error($conn) . '",
                                position: "topRight"
                            });
                        </script>';
                    }
                } else {
                    echo '<script>
                        iziToast.error({
                            title: "Error",
                            message: "Error fetching count: ' . mysqli_error($conn) . '",
                            position: "topRight"
                        });
                    </script>';
            }
            } else {
                echo '<script>
                    iziToast.error({
                        title: "Error",
                        message: "Error moving data to Appointment: ' . $conn->error . '",
                        position: "topRight"
                    });
                </script>';
            }
        } else {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Calling Status Added Successfully",
                    position: "topRight"
                });
                setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
            </script>';
        }

    } else {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Failed to add calling status: ' . $conn->error . '",
                position: "topRight"
            });
        </script>';
    }
}
?>
