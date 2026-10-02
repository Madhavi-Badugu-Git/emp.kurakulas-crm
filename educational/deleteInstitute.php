<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="institute";</script>';
    exit();
}

$institute_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_educational_institute WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $institute_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Institute deleted successfully!");
        window.location.href="institute";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Institute.");
        window.location.href="institute";
    </script>';
}

$stmt->close();
$conn->close();
?>
