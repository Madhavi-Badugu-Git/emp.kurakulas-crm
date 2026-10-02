<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="category";</script>';
    exit();
}

$category_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_payout_category WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $category_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Category deleted successfully!");
        window.location.href="category";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Category.");
        window.location.href="category";
    </script>';
}

$stmt->close();
$conn->close();
?>
