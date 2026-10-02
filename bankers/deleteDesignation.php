<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="addDesignation";</script>';
    exit();
}

$designation_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_banker_designation WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $designation_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Banker Designation deleted successfully!");
        window.location.href="addDesignation";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Banker Designation.");
        window.location.href="addDesignation";
    </script>';
}

$stmt->close();
$conn->close();
?>
