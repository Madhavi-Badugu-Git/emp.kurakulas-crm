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
$query = "DELETE FROM tbl_file_status WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $status_id);

if ($stmt->execute()) {
    echo '<script>
        alert("File Status deleted successfully!");
        window.location.href="addStatus";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete File Status.");
        window.location.href="addStatus";
    </script>';
}

$stmt->close();
$conn->close();
?>
