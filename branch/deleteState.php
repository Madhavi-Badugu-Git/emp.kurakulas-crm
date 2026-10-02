<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="addState";</script>';
    exit();
}

$state_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_branch_state WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $state_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Branch State deleted successfully!");
        window.location.href="addState";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Branch State.");
        window.location.href="addState";
    </script>';
}

$stmt->close();
$conn->close();
?>
