<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="addType";</script>';
    exit();
}

$rating_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_type_rating WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rating_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Type of Rating deleted successfully!");
        window.location.href="addType";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Type of Rating.");
        window.location.href="addType";
    </script>';
}

$stmt->close();
$conn->close();
?>
