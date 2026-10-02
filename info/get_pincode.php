<?php
include('../includes/dbConfig.php');

if (isset($_POST['sub_location_id'])) {
    $sub_location_id = $_POST['sub_location_id'];
    $query = "SELECT id, pincode  FROM tbl_pincode WHERE sub_location_id = ? ORDER BY pincode ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sub_location_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select PIN Code</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['pincode'].'</option>';
    }
}
?>