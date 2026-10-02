<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="country";</script>';
    exit();
}

$country_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_nri_country WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $country_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Country deleted successfully!");
        window.location.href="country";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Country.");
        window.location.href="country";
    </script>';
}

$stmt->close();
$conn->close();
?>
