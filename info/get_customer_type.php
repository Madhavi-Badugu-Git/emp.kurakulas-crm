<?php
include('../includes/dbConfig.php');

if (isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $query = "SELECT id, customer_type FROM tbl_customer_type WHERE id = ? ORDER BY customer_type ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Customer Type</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['customer_type'].'</option>';
    }
}
?>