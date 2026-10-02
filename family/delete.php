<?php
session_start();
include('../includes/dbConfig.php');


// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="add";</script>';
    exit();
}

$relation_id = $_GET['id'];

// Delete query
$query = "DELETE FROM tbl_family_relation WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $relation_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Relation deleted successfully!");
        window.location.href="relation";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Relation.");
        window.location.href="relation";
    </script>';
}

$stmt->close();
$conn->close();
?>
