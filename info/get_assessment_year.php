<?php
include('../includes/dbConfig.php');

if (isset($_POST['financial_year_id'])) {
    $financial_year_id = $_POST['financial_year_id'];
    $query = "SELECT id, assessment_year FROM tbl_assessment_year WHERE financial_year_id = ? ORDER BY assessment_year ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $financial_year_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Assessment Year</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['assessment_year'].'</option>';
    }
}
?>