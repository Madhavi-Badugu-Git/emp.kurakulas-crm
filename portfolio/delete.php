<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="list";</script>';
    exit();
}

$portfolio_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_portfolio WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $portfolio_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Portfolio deleted successfully!");
        window.location.href="list";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Portfolio.");
        window.location.href="list";
    </script>';
}

$stmt->close();
$conn->close();
?>
