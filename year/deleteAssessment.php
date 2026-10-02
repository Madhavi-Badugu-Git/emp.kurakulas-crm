<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="addAssessment";</script>';
    exit();
}

$assessment_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_assessment_year WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $assessment_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Assessment Year deleted successfully!");
        window.location.href="addAssessment";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Assessment Year.");
        window.location.href="addAssessment";
    </script>';
}

$stmt->close();
$conn->close();
?>
