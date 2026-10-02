<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="type_industry";</script>';
    exit();
}

$industry_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_senp_industry_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $industry_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Industry deleted successfully!");
        window.location.href="type_industry";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Industry.");
        window.location.href="type_industry";
    </script>';
}

$stmt->close();
$conn->close();
?>
