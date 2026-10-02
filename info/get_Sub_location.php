<?php
include('../includes/dbConfig.php');

if (isset($_POST['location_id'])) {
    $location_id = $_POST['location_id'];
    $query = "SELECT id, sub_location FROM tbl_sub_location WHERE location_id = ? ORDER BY sub_location ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $location_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Location</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['sub_location'].'</option>';
    }
}
?>