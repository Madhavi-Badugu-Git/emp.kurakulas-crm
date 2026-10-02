<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="list";</script>';
    exit();
}

$company_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_company_info WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $company_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Company Info deleted successfully!");
        window.location.href="list";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Company Type.");
        window.location.href="list";
    </script>';
}

$stmt->close();
$conn->close();
?>
