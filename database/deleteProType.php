<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="property_type";</script>';
    exit();
}

$pro_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_bank_property_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pro_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Property Type deleted successfully!");
        window.location.href="property_type";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Property Type.");
        window.location.href="property_type";
    </script>';
}

$stmt->close();
$conn->close();
?>
