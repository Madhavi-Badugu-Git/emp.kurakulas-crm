<?php
session_start();
include('../includes/dbConfig.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="list";</script>';
    exit();
}

$config_id = (int)$_GET['id'];

// soft delete, same convention as the rest of the app (status flag)
$query = "UPDATE tbl_statutory_config SET status = 0 WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $config_id);

if ($stmt->execute()) {
    echo '<script>
        alert("Statutory Config deleted successfully!");
        window.location.href="list";
    </script>';
} else {
    echo '<script>
        alert("Error! Unable to delete Statutory Config.");
        window.location.href="list";
    </script>';
}

$stmt->close();
$conn->close();
?>
