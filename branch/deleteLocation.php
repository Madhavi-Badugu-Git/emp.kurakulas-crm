<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="addLocation";</script>';
    exit();
}

$location_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_branch_location WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $location_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Branch Location deleted successfully!");
        window.location.href="addLocation";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Branch Location.");
        window.location.href="addLocation";
    </script>';
}

$stmt->close();
$conn->close();
?>
