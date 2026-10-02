<?php
session_start();
include('../includes/dbConfig.php');

if (isset($_POST["Import"])) {
    $filename = $_FILES["file"]["tmp_name"];

    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");
        $rowCount = 0;

        // Skip the header row
        fgetcsv($file, 10000, ",");

        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
            // Check if the row has exactly 5 columns to avoid undefined index errors
            if (count($emapData) < 5) {
                continue;
            }

            // Prevent SQL injection
            $lead_name = mysqli_real_escape_string($conn, trim($emapData[0]));  // Name
            $mobile_number = mysqli_real_escape_string($conn, trim($emapData[1]));  // Mobile Number
            $company_name = mysqli_real_escape_string($conn, trim($emapData[2]));  // Company Name
            $createdBy = mysqli_real_escape_string($conn, trim($emapData[3]));  // CREATED BY
            $customer_type = mysqli_real_escape_string($conn, trim($emapData[4]));  // Type of Customer

            // Insert data into the database
            $sql = "INSERT INTO `tbl_database` 
                    (`mobile_number`, `lead_name`, `company_name`, `customer_type`, `createdBy`, `created_at`) 
                    VALUES ('$mobile_number', '$lead_name', '$company_name', '$customer_type', '$createdBy', NOW())";
            
            if (!mysqli_query($conn, $sql)) {
                echo "<script type=\"text/javascript\">
                        alert(\"Error Importing File: " . mysqli_error($conn) . "\");
                        window.location = \"index.php\";
                      </script>";
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
