<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="bank";</script>';
    exit();
}

$bank_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_file_bank WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bank_id);

if ($stmt->execute()) {
    echo '<script>
        alert("File Bank deleted successfully!");
        window.location.href="bank";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete File Bank.");
        window.location.href="bank";
    </script>';
}

$stmt->close();
$conn->close();
?>
