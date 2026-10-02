<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="account_type";</script>';
    exit();
}

$acc_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_bank_account_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $acc_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Account Type deleted successfully!");
        window.location.href="account_type";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Account Type.");
        window.location.href="account_type";
    </script>';
}

$stmt->close();
$conn->close();
?>
