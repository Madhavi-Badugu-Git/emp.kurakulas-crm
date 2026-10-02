<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="salary_payment_type";</script>';
    exit();
}

$salary_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_salary_payment_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $salary_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Salary Payment Type deleted successfully!");
        window.location.href="salary_payment_type";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Salary Payment Type.");
        window.location.href="salary_payment_type";
    </script>';
}

$stmt->close();
$conn->close();
?>
