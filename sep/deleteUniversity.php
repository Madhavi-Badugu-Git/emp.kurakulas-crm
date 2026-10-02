<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="dr_university";</script>';
    exit();
}

$university_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_sep_doctor_university WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $university_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Doctor University deleted successfully!");
        window.location.href="dr_university";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Doctor University.");
        window.location.href="dr_university";
    </script>';
}

$stmt->close();
$conn->close();
?>
