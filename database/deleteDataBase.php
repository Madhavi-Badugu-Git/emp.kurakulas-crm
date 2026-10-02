<?php
session_start();
include('../includes/dbConfig.php');

// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="list";</script>';
    exit();
}

$database_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_database WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $database_id);

if ($stmt->execute()) {
    echo '<script>
        alert("DataBase deleted successfully!");
        window.location.href="database";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete DataBase.");
        window.location.href="database";
    </script>';
}

$stmt->close();
$conn->close();
?>
