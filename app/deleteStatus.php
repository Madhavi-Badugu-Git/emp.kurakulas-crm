<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="addStatus";</script>';
    exit();
}

$status_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_app_status WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $status_id);

if ($stmt->execute()) {
    echo '<script>
        alert("App Status deleted successfully!");
        window.location.href="addStatus";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete App Status.");
        window.location.href="addStatus";
    </script>';
}

$stmt->close();
$conn->close();
?>
