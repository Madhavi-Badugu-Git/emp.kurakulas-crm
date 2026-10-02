<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="subCustomer";</script>';
    exit();
}

$customer_type_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_sub_customer WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_type_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Sub Customer deleted successfully!");
        window.location.href="subCustomer";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Sub Customer.");
        window.location.href="subCustomer";
    </script>';
}

$stmt->close();
$conn->close();
?>
