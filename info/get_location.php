<?php
include('../includes/dbConfig.php');

if (isset($_POST['state_id'])) {
    $state_id = $_POST['state_id'];
    $query = "SELECT id, location FROM tbl_location WHERE state_id = ? ORDER BY location ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $state_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Location</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['location'].'</option>';
    }
}
?>