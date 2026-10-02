<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="branches";</script>';
    exit();
}

$branch_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_senp_branches WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $branch_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Branches deleted successfully!");
        window.location.href="branches";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Branches.");
        window.location.href="branches";
    </script>';
}

$stmt->close();
$conn->close();
?>
