<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="grossSalary";</script>';
    exit();
}

$salary_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_senp_grosssalary WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $salary_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Gross Salary deleted successfully!");
        window.location.href="grossSalary";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Gross Salary.");
        window.location.href="grossSalary";
    </script>';
}

$stmt->close();
$conn->close();
?>
