<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="product";</script>';
    exit();
}

$product_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_appointment_product WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Appointment Product deleted successfully!");
        window.location.href="product";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Appointment Product.");
        window.location.href="product";
    </script>';
}

$stmt->close();
$conn->close();
?>
