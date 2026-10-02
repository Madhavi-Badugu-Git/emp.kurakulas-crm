<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="no_students";</script>';
    exit();
}

$student_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_educational_no_students WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Number Of Students deleted successfully!");
        window.location.href="no_students";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Number Of Students.");
        window.location.href="no_students";
    </script>';
}

$stmt->close();
$conn->close();
?>
