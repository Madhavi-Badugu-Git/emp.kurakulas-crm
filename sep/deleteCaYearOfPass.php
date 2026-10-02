<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="ca_yearOfPass";</script>';
    exit();
}

$year_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_sep_ca_yearpass WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $year_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Doctor Year Of Pass deleted successfully!");
        window.location.href="ca_yearOfPass";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Doctor Year Of Pass.");
        window.location.href="ca_yearOfPass";
    </script>';
}

$stmt->close();
$conn->close();
?>
