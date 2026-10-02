<?php
include('../includes/dbConfig.php');

if (isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $query = "SELECT id, industry_name FROM tbl_industry_type WHERE customer_id = ? ORDER BY industry_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Industry Type</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['industry_name'].'</option>';
    }
}
?>