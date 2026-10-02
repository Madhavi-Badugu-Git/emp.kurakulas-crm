<?php
session_start();
include('../includes/dbConfig.php');

// Validate ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>
        alert("Invalid request!");
        window.location.href = "add";
    </script>';
    exit;
}

$invoice_id = (int) $_GET['id'];

// Prepare delete query (PRIMARY KEY)
$stmt = $conn->prepare("DELETE FROM tbl_invoice WHERE id = ?");
if (!$stmt) {
    echo '<script>
        alert("SQL error. Unable to prepare delete query.");
        window.location.href = "add";
    </script>';
    exit;
}

$stmt->bind_param("i", $invoice_id);
$stmt->execute();

// ✅ CHECK affected rows (MOST IMPORTANT)
if ($stmt->affected_rows > 0) {
    echo '<script>
        alert("Invoice deleted successfully!");
        window.location.href = "add";
    </script>';
    exit;
} else {
    echo '<script>
        alert("Record not Found.");
        window.location.href = "add";
    </script>';
    exit;
}

$stmt->close();
$conn->close();
?>
