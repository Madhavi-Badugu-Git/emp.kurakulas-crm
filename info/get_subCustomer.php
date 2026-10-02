<?php
include('../includes/dbConfig.php');

if (isset($_POST['sub_customer_id'])) {
    $sub_customer_id = $_POST['sub_customer_id'];
    $query = "SELECT id, subcustomer_name FROM tbl_sub_customer WHERE customer_id = ? ORDER BY subcustomer_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sub_customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Sub Customer</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['subcustomer_name'].'</option>';
    }
}
?>