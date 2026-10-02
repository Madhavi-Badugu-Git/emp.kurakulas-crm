<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

if (isset($_POST["Import"])) {
    $filename = $_FILES["file"]["tmp_name"];

    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");
        $rowCount = 0;

        $firstRow = true; // ✅ define properly

        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {

            // ✅ Skip first row (header)
            if ($firstRow) {
                $firstRow = false;
                continue;
            }

            // ✅ Skip empty rows
            if (empty(trim($emapData[1]))) {
                continue;
            }

            // ✅ Skip header if appears again inside file
            if (strtolower(trim($emapData[1])) == 'mobile number') {
                continue;
            }

            // ✅ Ensure enough columns
            if (count($emapData) < 36) {
                continue;
            }

            // 🔹 Assign values safely
            $mobile_number = mysqli_real_escape_string($conn, $emapData[1]);
            $lead_name = mysqli_real_escape_string($conn, $emapData[2]);
            $email_id = mysqli_real_escape_string($conn, $emapData[3]);
            $company_name = mysqli_real_escape_string($conn, $emapData[4]);
            $alternative_mobile = mysqli_real_escape_string($conn, $emapData[5]);

            $state = mysqli_real_escape_string($conn, $emapData[6]);
            $location = mysqli_real_escape_string($conn, $emapData[7]);
            $sub_location = mysqli_real_escape_string($conn, $emapData[8]);
            $pin_code = mysqli_real_escape_string($conn, $emapData[9]);
            $source = mysqli_real_escape_string($conn, $emapData[10]);

            $user_qualification = mysqli_real_escape_string($conn, $emapData[11]);
            $residental_address = mysqli_real_escape_string($conn, $emapData[12]);
            $customer_type = mysqli_real_escape_string($conn, $emapData[13]);

            $senp_industry_name = mysqli_real_escape_string($conn, $emapData[14]);
            $senp_business_name = mysqli_real_escape_string($conn, $emapData[15]);
            $senp_company_type = mysqli_real_escape_string($conn, $emapData[16]);
            $senp_nature_business = mysqli_real_escape_string($conn, $emapData[17]);
            $senp_incorporaton_date = mysqli_real_escape_string($conn, $emapData[18]);
            $senp_vintage_year = mysqli_real_escape_string($conn, $emapData[19]);

            $senp_factory_address = mysqli_real_escape_string($conn, $emapData[20]);
            $senp_factory_pincode = mysqli_real_escape_string($conn, $emapData[21]);
            $senp_gst_number = mysqli_real_escape_string($conn, $emapData[22]);
            $senp_company_pan_number = mysqli_real_escape_string($conn, $emapData[23]);
            $senp_website = mysqli_real_escape_string($conn, $emapData[24]);

            $senp_rating_type = mysqli_real_escape_string($conn, $emapData[25]);
            $senp_rating_name = mysqli_real_escape_string($conn, $emapData[26]);
            $senp_branches = mysqli_real_escape_string($conn, $emapData[27]);
            $senp_employees = mysqli_real_escape_string($conn, $emapData[28]);

            $office_address = mysqli_real_escape_string($conn, $emapData[29]);
            $branch_address = mysqli_real_escape_string($conn, $emapData[30]);

            $calling_type_loan = mysqli_real_escape_string($conn, $emapData[31]);
            $calling_bank = mysqli_real_escape_string($conn, $emapData[32]);
            $calling_status = mysqli_real_escape_string($conn, $emapData[33]);
            $calling_sub_status = mysqli_real_escape_string($conn, $emapData[34]);

            $database_notes = mysqli_real_escape_string($conn, $emapData[35]);

            $createdBy = $_SESSION['username'] ?? 'admin';

            // ✅ INSERT
            $sql = "INSERT INTO tbl_database_import_test_data (
                mobile_number, lead_name, email_id, company_name, alternative_mobile,
                state, location, sub_location, pin_code, source,
                user_qualification, residental_address, customer_type,
                senp_industry_name, senp_business_name, senp_company_type,
                senp_nature_business, senp_incorporaton_date, senp_vintage_year,
                senp_factory_address, senp_factory_pincode, senp_gst_number,
                senp_company_pan_number, senp_website,
                senp_rating_type, senp_rating_name, senp_branches, senp_employees,
                office_address, branch_address,
                calling_type_loan, calling_bank, calling_status, calling_sub_status,
                database_notes, createdBy, created_at
            ) VALUES (
                '$mobile_number','$lead_name','$email_id','$company_name','$alternative_mobile',
                '$state','$location','$sub_location','$pin_code','$source',
                '$user_qualification','$residental_address','$customer_type',
                '$senp_industry_name','$senp_business_name','$senp_company_type',
                '$senp_nature_business','$senp_incorporaton_date','$senp_vintage_year',
                '$senp_factory_address','$senp_factory_pincode','$senp_gst_number',
                '$senp_company_pan_number','$senp_website',
                '$senp_rating_type','$senp_rating_name','$senp_branches','$senp_employees',
                '$office_address','$branch_address',
                '$calling_type_loan','$calling_bank','$calling_status','$calling_sub_status',
                '$database_notes','$createdBy',NOW()
            )";

            if (!mysqli_query($conn, $sql)) {
                echo "Error: " . mysqli_error($conn);
                exit;
            }

            $rowCount++;
        }

        fclose($file);
        
        echo "<script type=\"text/javascript\">
                alert(\"CSV File has been successfully imported. Total Rows Imported: $rowCount\");
                window.location = \"index.php\";
              </script>";
    } else {
        echo "<script type=\"text/javascript\">
                alert(\"Invalid File: Please Upload a CSV File.\");
                window.location = \"index.php\";
              </script>";
    }
}

// Close the connection
mysqli_close($conn);
?>
