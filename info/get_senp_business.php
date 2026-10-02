<?php
include('../includes/dbConfig.php');

if (isset($_POST['industry_id'])) {
    $industry_id = $_POST['industry_id'];
    $query = "SELECT id, business_name FROM tbl_senp_business_type WHERE industry_id = ? ORDER BY business_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $industry_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Type Of Business</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['business_name'].'</option>';
    }
}
?>