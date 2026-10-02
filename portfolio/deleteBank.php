<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="addBank";</script>';
    exit();
}

$portfolio_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_portfolio_bank WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $portfolio_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Portfolio Bank deleted successfully!");
        window.location.href="addBank";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete portfolio Bank.");
        window.location.href="addBank";
    </script>';
}

$stmt->close();
$conn->close();
?>
