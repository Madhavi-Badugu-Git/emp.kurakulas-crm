<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="add";</script>';
    exit();
}

$subLocation_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_sub_location WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $subLocation_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Sub Location deleted successfully!");
        window.location.href="add";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Sub Location.");
        window.location.href="add";
    </script>';
}

$stmt->close();
$conn->close();
?>
