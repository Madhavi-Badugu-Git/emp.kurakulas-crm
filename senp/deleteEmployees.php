<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="employees";</script>';
    exit();
}

$emp_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_senp_employees WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $emp_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Employees deleted successfully!");
        window.location.href="employees";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Employees.");
        window.location.href="employees";
    </script>';
}

$stmt->close();
$conn->close();
?>
