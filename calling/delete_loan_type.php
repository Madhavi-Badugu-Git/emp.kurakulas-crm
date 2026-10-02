<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="addSubStatus";</script>';
    exit();
}

$status_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_calling_type_loan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $status_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Calling Loan Type deleted successfully!");
        window.location.href="type_loan";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Calling Loan Type.");
        window.location.href="type_loan";
    </script>';
}

$stmt->close();
$conn->close();
?>
