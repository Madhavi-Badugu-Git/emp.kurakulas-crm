<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="dr_qualification";</script>';
    exit();
}

$qualification_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_sep_doctor_qualification WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $qualification_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Doctor Qualification deleted successfully!");
        window.location.href="dr_qualification";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Doctor Qualification.");
        window.location.href="dr_qualification";
    </script>';
}

$stmt->close();
$conn->close();
?>
