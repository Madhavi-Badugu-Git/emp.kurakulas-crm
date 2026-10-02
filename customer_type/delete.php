<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="add";</script>';
    exit();
}

$customer_type_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_customer_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_type_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Customer Type deleted successfully!");
        window.location.href="add";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Customer Type.");
        window.location.href="add";
    </script>';
}

$stmt->close();
$conn->close();
?>
