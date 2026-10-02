<?php
session_start();
include('../includes/dbConfig.php');
// Include the PhpSpreadsheet library

    // File upload and validation
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        $fileType = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);
        if (in_array($fileType, ['xls', 'xlsx', 'csv'])) {
            $filePath = $_FILES['excel_file']['tmp_name'];
            $spreadsheet = IOFactory::load($filePath);
            $data = $spreadsheet->getActiveSheet()->toArray();

            // Skip header row and insert data
            $inserted = 0;
            foreach (array_slice($data, 1) as $row) {
                $mobile_number = $conn->real_escape_string(trim($row[0]));
                $lead_name = $conn->real_escape_string(trim($row[1]));
                $company_name = $conn->real_escape_string(trim($row[2]));
                $customer_type = $conn->real_escape_string(trim($row[3]));
                $status = $conn->real_escape_string(trim($row[4]));

                // Insert row into the database
                $sql = "INSERT INTO tbl_database_excel_import (mobile_number, lead_name, company_name, customer_type, status, created_at) 
                        VALUES ('$mobile_number', '$lead_name', '$company_name', '$customer_type', '$status', NOW())";
                
                if ($conn->query($sql)) {
                    $inserted++;
                }
            }

            echo "Successfully imported $inserted rows.";
        } else {
            echo "Invalid file type. Please upload an Excel file (xls, xlsx, or csv).";
        }
    } else {
        echo "No file selected or file upload error.";
    }

    $conn->close();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Excel Import</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <label>Select Excel File:</label>
        <input type="file" name="excel_file" accept=".xls,.xlsx,.csv" required>
        <button type="submit">Upload and Import</button>
    </form>
</body>
</html>
