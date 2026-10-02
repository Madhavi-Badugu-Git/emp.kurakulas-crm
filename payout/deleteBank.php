<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="bank";</script>';
    exit();
}

$payout_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_bank_payout WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payout_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Bank Payout deleted successfully!");
        window.location.href="bank";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Bank Payout.");
        window.location.href="bank";
    </script>';
}

$stmt->close();
$conn->close();
?>
