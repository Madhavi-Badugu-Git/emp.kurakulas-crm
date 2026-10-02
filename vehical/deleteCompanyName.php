<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="companyName";</script>';
    exit();
}

$vehical_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_ins_company_name WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vehical_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Insurance Company Name deleted successfully!");
        window.location.href="companyName";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Insurance Company Name.");
        window.location.href="companyName";
    </script>';
}

$stmt->close();
$conn->close();
?>
