<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="type";</script>';
    exit();
}

$payout_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_payout_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payout_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Payout Type deleted successfully!");
        window.location.href="type";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Payout Type.");
        window.location.href="type";
    </script>';
}

$stmt->close();
$conn->close();
?>
