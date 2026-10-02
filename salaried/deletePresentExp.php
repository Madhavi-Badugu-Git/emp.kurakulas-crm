<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="present_exp";</script>';
    exit();
}

$exp_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_present_experience WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $exp_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Net Salary deleted successfully!");
        window.location.href="present_exp";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Net Salary.");
        window.location.href="present_exp";
    </script>';
}

$stmt->close();
$conn->close();
?>
