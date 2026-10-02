<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="addBank";</script>';
    exit();
}

$status_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_calling_bank WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $status_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Calling Bank deleted successfully!");
        window.location.href="addBank";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Calling Bank.");
        window.location.href="addBank";
    </script>';
}

$stmt->close();
$conn->close();
?>
