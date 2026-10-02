<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="type_business";</script>';
    exit();
}

$business_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_senp_business_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $business_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Industry deleted successfully!");
        window.location.href="type_business";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Industry.");
        window.location.href="type_business";
    </script>';
}

$stmt->close();
$conn->close();
?>
