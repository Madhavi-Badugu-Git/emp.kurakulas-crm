<?php
session_start();
include('../includes/dbConfig.php');

// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="appointment";</script>';
    exit();
}

$get_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_appointment WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $get_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Appointment deleted successfully!");
        window.location.href="appointment";
        
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Appointment.");
        window.location.href="appointment";
    </script>';
}

$stmt->close();
$conn->close();
?>
