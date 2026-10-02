<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

if (isset($_POST['download_excel'])) {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=agent_data.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Open output stream
    $output = fopen('php://output', 'w');

    // CSV Header
    fputcsv($output, [
        'S.No', 'Full Name', 'Company Name', 'Phone', 'Alternative Phone Number',
        'Email', 'Partner Type', 'State', 'Location', 'Address'
    ]);

    // Fetch data
    $query = "SELECT * FROM tbl_agent_data WHERE location = '10'";
    $result = mysqli_query($conn, $query);

    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $i,
            $row['full_name'],
            $row['company_name'],
            $row['Phone_number'],
            $row['alternative_Phone_number'],
            $row['email_id'],
            fetchColumnValue($conn, 'tbl_partner_type', 'id', $row['partnerType'], 'partner_type'),
            fetchColumnValue($conn, 'tbl_branch_state', 'id', $row['state'], 'branch_state_name'),
            fetchColumnValue($conn, 'tbl_branch_location', 'id', $row['location'], 'branch_location'),
            $row['address']
        ]);
        $i++;
    }

    fclose($output);
    exit();
}
?>
