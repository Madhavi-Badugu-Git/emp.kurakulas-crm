<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="list";</script>';
    exit();
}

$user_id = $_GET['id'];
// echo $user_id;
// exit();

// Delete query
$query = "DELETE FROM tbl_user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo '<script>
        alert("User deleted successfully!");
        window.location.href="list";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete User.");
        window.location.href="list";
    </script>';
}

$stmt->close();
$conn->close();
?>
