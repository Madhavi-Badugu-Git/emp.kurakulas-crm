<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

if (isset($_POST['download_excel'])) {

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=database_data.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = fopen('php://output', 'w');

    // ✅ FIXED HEADER (no duplicate)
    fputcsv($output, [
        'S.No', 'Mobile Number', 'Lead Name', 'Email', 'Company Name',
        'Alternative No', 'State', 'Location', 'Sub Location', 'Pincode',
        'Source', 'Qualification', 'Residential Address', 'Customer Type',
        'Senp Industry Name', 'Senp Business Name', 'Senp Company Type',
        'Senp Nature Business', 'Senp Incorporation Date', 'Senp Vintage Year',
        'Senp Factory Address', 'Senp Factory Pincode', 'Senp GST Number',
        'Senp Company PAN Number', 'Senp Website',
        'Senp Rating Type', 'Senp Rating Name',
        'Senp Branches', 'Senp Employees',
        'Office Address', 'Branch Address',
        'Calling Type Loan', 'Calling Bank',
        'Calling Status', 'Calling Sub Status',
        'Database Notes'
    ]);

    $query = "SELECT * FROM tbl_database WHERE customer_type = '35' AND location = '35'";
    $result = mysqli_query($conn, $query);

    // ✅ START FROM 1 ALWAYS
    $i = 1;

    while ($row = mysqli_fetch_assoc($result)) {

        fputcsv($output, [
            $i++, // ✅ increment here (clean way)

            $row['mobile_number'],
            $row['lead_name'],
            $row['email_id'],
            $row['company_name'],
            $row['alternative_mobile'],
            $row['state'],
            $row['location'],
            $row['sub_location'],
            $row['pin_code'],
            $row['source'],
            $row['user_qualification'],
            $row['residental_address'],
            $row['customer_type'],
            $row['senp_industry_name'],
            $row['senp_business_name'],
            $row['senp_company_type'],
            $row['senp_nature_business'],
            $row['senp_incorporaton_date'],
            $row['senp_vintage_year'],
            $row['senp_factory_address'],
            $row['senp_factory_pincode'],
            $row['senp_gst_number'],
            $row['senp_company_pan_number'],
            $row['senp_website'],
            $row['senp_rating_type'],
            $row['senp_rating_name'], 
            $row['senp_branches'], 
            $row['senp_employees'],
            $row['office_address'], 
            $row['branch_address'],
            $row['calling_type_loan'],
            $row['calling_bank'],
            $row['calling_status'],
            $row['calling_sub_status'],
            $row['database_notes']
        ]);
    }

    fclose($output);
    exit();
}
?>