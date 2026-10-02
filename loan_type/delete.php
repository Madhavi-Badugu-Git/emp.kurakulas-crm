<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="add";</script>';
    exit();
}

$loan_type_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_loan_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $loan_type_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Loan Type deleted successfully!");
        window.location.href="add";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Loan Type.");
        window.location.href="add";
    </script>';
}

$stmt->close();
$conn->close();
?>
