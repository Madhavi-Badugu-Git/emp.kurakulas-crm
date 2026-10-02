<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="sub_status";</script>';
    exit();
}

$status_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_partner_calling_sub_status WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $status_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Calling Sub Status deleted successfully!");
        window.location.href="sub_status";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Calling Status.");
        window.location.href="sub_status";
    </script>';
}

$stmt->close();
$conn->close();
?>
