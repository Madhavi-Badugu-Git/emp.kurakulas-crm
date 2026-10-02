<?php
include('../includes/dbConfig.php'); // Include DB connection

header("Content-Type: text/plain"); // Ensure plain text output

if (isset($_POST['Phone_number']) && isset($_POST['loggedInUser'])) {
    $phone = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $loggedInUser = mysqli_real_escape_string($conn, $_POST['loggedInUser']);

    // Debugging
    error_log("Checking phone: " . $phone . " for user: " . $loggedInUser);

    // Check if the phone number exists for the same createdBy
    $query = "SELECT id FROM tbl_connectors WHERE Phone_number = '$phone' AND createdBy = '$loggedInUser' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        echo "exists"; // Phone + User exists
    } else {
        echo "available"; // Phone is unique for this user
    }
} else {
    echo "error"; // Debugging response
}
?>
