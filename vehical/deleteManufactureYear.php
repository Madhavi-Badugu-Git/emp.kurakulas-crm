<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="ManufactureYear";</script>';
    exit();
}

$vehical_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_manufacture_year WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vehical_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Vehical Manufacture Year deleted successfully!");
        window.location.href="ManufactureYear";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Vehical Manufacture Year.");
        window.location.href="ManufactureYear";
    </script>';
}

$stmt->close();
$conn->close();
?>
