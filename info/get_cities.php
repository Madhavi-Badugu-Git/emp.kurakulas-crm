<?php
include('../includes/dbConfig.php');

if (isset($_POST['state_id'])) {
    $state_id = $_POST['state_id'];
    $query = "SELECT id, city_name FROM tbl_city WHERE state_id = ? ORDER BY city_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $state_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select City</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['city_name'].'</option>';
    }
}
?>
