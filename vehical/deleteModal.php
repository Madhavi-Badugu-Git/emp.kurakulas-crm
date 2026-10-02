<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="modal";</script>';
    exit();
}

$vehical_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_vehical_modal WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vehical_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Vehical Modal deleted successfully!");
        window.location.href="modal";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Vehical Modal.");
        window.location.href="modal";
    </script>';
}

$stmt->close();
$conn->close();
?>
