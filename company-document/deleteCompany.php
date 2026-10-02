<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="addCompany";</script>';
    exit();
}

$comp_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_company_name WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $comp_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Company Name Deleted successfully!");
        window.location.href="addCompany";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Company Name .");
        window.location.href="addCompany";
    </script>';
}

$stmt->close();
$conn->close();
?>
