<?php
include('../includes/dbConfig.php'); // Include DB connection

header("Content-Type: text/plain"); // Ensure plain text output

if (isset($_POST['Phone_number'])) {
    $phone = mysqli_real_escape_string($conn, $_POST['Phone_number']);

    // Debugging log
    error_log("Checking phone: " . $phone);

    // Query to check if the phone number exists
    $query = "SELECT id FROM tbl_partner WHERE Phone_number = '$phone' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query failed: " . mysqli_error($conn));
        echo "error"; // Return error if query fails
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        echo "exists"; // Phone number already exists
    } else {
        echo "available"; // Phone number is unique
    }
} else {
    echo "error"; // If no phone number is provided
}
?>
