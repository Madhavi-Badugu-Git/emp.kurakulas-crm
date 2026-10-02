<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="total_exp";</script>';
    exit();
}

$exp_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_total_experience WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $exp_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Total Experience deleted successfully!");
        window.location.href="total_exp";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Total Experience.");
        window.location.href="total_exp";
    </script>';
}

$stmt->close();
$conn->close();
?>
