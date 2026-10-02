<?php
session_start();
include('../includes/dbConfig.php');

// Check if `id` is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid request!"); window.location.href="list";</script>';
    exit();
}

$insurance_id = $_GET['id'];

// Begin transaction
$conn->begin_transaction();

try {
    // Delete from tbl_vehical_documents
    $query1 = "DELETE FROM tbl_vehical_documents WHERE vehical_id = ?";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("i", $insurance_id);
    $stmt1->execute();
    $stmt1->close();

    // Delete from tbl_vehical_insurance_details
    $query2 = "DELETE FROM tbl_vehical_insurance_details WHERE vehical_id = ?";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("i", $insurance_id);
    $stmt2->execute();
    $stmt2->close();

    // Delete from tbl_vehical_insurance
    $query3 = "DELETE FROM tbl_vehical_insurance WHERE id = ?";
    $stmt3 = $conn->prepare($query3);
    $stmt3->bind_param("i", $insurance_id);
    $stmt3->execute();
    $stmt3->close();

    // Commit transaction
    $conn->commit();

    echo '<script>
        alert("Vehicle Insurance deleted successfully!");
        window.location.href="list";
    </script>';
} catch (Exception $e) {
    // Rollback if any query fails
    $conn->rollback();
    echo '<script>
        alert("Error! Unable to delete Vehicle Insurance.");
        window.location.href="list";
    </script>';
}

$conn->close();
?>
