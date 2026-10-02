<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="dr_specialisation";</script>';
    exit();
}

$specialisation_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_sep_doctor_specialisation WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $specialisation_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Doctor Specialisation deleted successfully!");
        window.location.href="dr_specialisation";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Doctor Specialisation.");
        window.location.href="dr_specialisation";
    </script>';
}

$stmt->close();
$conn->close();
?>
